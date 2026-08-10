<?php

namespace App\Http\Controllers\Admin;

use App\Exports\PropertyEntriesExport;
use App\Http\Controllers\Controller;
use App\Models\PropertyEntry;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class PropertyEntryReportController extends Controller
{
    private const PHOTO_SLOTS = [
        0 => 'Front / exterior',
        1 => 'Interior — full floor',
        2 => 'Roof / height shot',
        3 => 'Dock doors close-up',
        4 => 'Office / cabin',
        5 => 'Fire system',
        6 => 'Approach road',
        7 => 'Fire NOC document',
    ];

    // ── Report Page ───────────────────────────────────────────────────────────

    public function index(Request $request): View
    {
        // Unfiltered summary counts — always reflect the full dataset
        $summary = [
            'total'     => PropertyEntry::count(),
            'submitted' => PropertyEntry::where('status', 'submitted')->count(),
            'verified'  => PropertyEntry::where('status', 'verified')->count(),
            'recheck'   => PropertyEntry::where('status', 'recheck')->count(),
            'rejected'  => PropertyEntry::where('status', 'rejected')->count(),
        ];

        // Filtered, paginated entries
        $entries = $this->buildQuery($request)->latest()->paginate(20)->appends($request->query());

        // All supply heads for top-level filter, with the zones they cover
        $supplyHeads = User::where('role', 'supply_head')
            ->with('zones:id')
            ->orderBy('name')->get(['id', 'name']);

        $allOfficers = User::where('role', 'field_officer')->orderBy('name')->get(['id', 'name', 'zone_id']);

        // A supply head's officers are the ones working in the zones they
        // cover — there is no direct supply head link on the user any more.
        $officersBySupplyHead = $supplyHeads->mapWithKeys(function ($sh) use ($allOfficers) {
            $zoneIds = $sh->zones->pluck('id')->all();
            return [$sh->id => $allOfficers->whereIn('zone_id', $zoneIds)->values()];
        });

        // Field officers: if a supply head is selected, scope to that head's officers only
        $officers = $request->filled('supply_head_id')
            ? ($officersBySupplyHead[(int) $request->supply_head_id] ?? collect())
            : $allOfficers;

        $zones = \App\Models\Zone::ordered()->get(['id', 'name']);

        $statuses      = ['draft', 'submitted', 'verified', 'recheck', 'rejected'];
        $facilityTypes = PropertyEntry::whereNotNull('facility_type')
            ->distinct()->orderBy('facility_type')->pluck('facility_type');
        $cities        = PropertyEntry::whereNotNull('nearest_city')
            ->distinct()->orderBy('nearest_city')->pluck('nearest_city');

        return view('admin.property-entry-report.index', compact(
            'summary', 'entries', 'supplyHeads', 'officers', 'officersBySupplyHead',
            'zones', 'statuses', 'facilityTypes', 'cities'
        ));
    }

    // ── Admin Show (read-only, no role guard) ─────────────────────────────────

    public function show(PropertyEntry $entry): View
    {
        $entry->load(['photos', 'fieldOfficer', 'supplyHead', 'reviewer', 'adminActioner', 'logs.user']);
        $slots = self::PHOTO_SLOTS;

        return view('admin.property-entry-report.show', compact('entry', 'slots'));
    }

    // ── Excel Export ──────────────────────────────────────────────────────────

    public function export(Request $request): BinaryFileResponse
    {
        $entries = $this->buildQuery($request)
            ->with(['fieldOfficer', 'supplyHead'])
            ->latest()
            ->get();

        $filename = 'property-entry-report-' . now()->format('Y-m-d-His') . '.xlsx';

        return Excel::download(new PropertyEntriesExport($entries), $filename);
    }

    // ── Shared Filter Logic ───────────────────────────────────────────────────

    private function buildQuery(Request $request)
    {
        $query = PropertyEntry::with(['fieldOfficer', 'supplyHead', 'zone']);

        if ($request->filled('supply_head_id')) {
            $query->where('supply_head_id', $request->supply_head_id);
        }

        if ($request->filled('officer_id')) {
            $query->where('field_officer_id', $request->officer_id);
        }

        if ($request->filled('zone_id')) {
            $query->where('zone_id', $request->zone_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('facility_type')) {
            $query->where('facility_type', $request->facility_type);
        }

        if ($request->filled('city')) {
            $query->where('nearest_city', $request->city);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        return $query;
    }

    // ── Admin Approve ─────────────────────────────────────────────────────────

    public function adminApprove(Request $request, PropertyEntry $entry)
    {
        if ($entry->status !== 'verified') {
            return response()->json([
                'success' => false,
                'message' => 'Only supply-head verified entries can be approved by admin.',
            ], 422);
        }

        $entry->admin_status      = 'approved';
        $entry->admin_note        = $request->input('note');
        $entry->admin_actioned_at = now();
        $entry->admin_actioned_by = $request->user()->id;
        $entry->save();

        // Log the action
        $entry->logs()->create([
            'user_id' => $request->user()->id,
            'action'  => 'admin_approved',
            'note'    => $request->input('note') ?? 'Admin approved.',
        ]);

        return response()->json([
            'success'      => true,
            'admin_status' => 'approved',
            'actioned_by'  => $request->user()->name,
            'actioned_at'  => $entry->admin_actioned_at->format('d M Y, g:i A'),
            'message'      => 'Entry approved. You can now control website visibility.',
        ]);
    }

    // ── Admin Reject ──────────────────────────────────────────────────────────

    public function adminReject(Request $request, PropertyEntry $entry)
    {
        if ($entry->status !== 'verified') {
            return response()->json([
                'success' => false,
                'message' => 'Only supply-head verified entries can be rejected by admin.',
            ], 422);
        }

        $request->validate(['note' => 'required|string|max:1000']);

        $entry->admin_status      = 'rejected';
        $entry->admin_note        = $request->input('note');
        $entry->admin_actioned_at = now();
        $entry->admin_actioned_by = $request->user()->id;
        // If previously shown on website, hide it
        $entry->show_on_website   = false;
        $entry->save();

        // Log the action
        $entry->logs()->create([
            'user_id' => $request->user()->id,
            'action'  => 'admin_rejected',
            'note'    => $request->input('note'),
        ]);

        return response()->json([
            'success'      => true,
            'admin_status' => 'rejected',
            'actioned_by'  => $request->user()->name,
            'actioned_at'  => $entry->admin_actioned_at->format('d M Y, g:i A'),
            'message'      => 'Entry rejected by admin.',
        ]);
    }

    // ── Toggle Website Visibility ─────────────────────────────────────────────

    public function toggleWebsite(PropertyEntry $entry)
    {
        // Only allow for admin-approved entries
        if ($entry->admin_status !== 'approved') {
            return response()->json([
                'success' => false,
                'message' => 'Only admin-approved entries can be shown on the website.',
            ], 403);
        }

        $entry->show_on_website = !$entry->show_on_website;
        $entry->save();

        return response()->json([
            'success'         => true,
            'show_on_website' => $entry->show_on_website,
            'message'         => $entry->show_on_website
                ? 'Property entry is now visible on the website.'
                : 'Property entry is now hidden from the website.',
        ]);
    }

}

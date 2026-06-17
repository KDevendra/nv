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

        // All supply heads for top-level filter — include their field officers for JS dropdown
        $supplyHeads = User::where('role', 'supply_head')
            ->with(['fieldOfficers' => fn($q) => $q->orderBy('name')->select('id', 'name', 'supply_head_id')])
            ->orderBy('name')->get(['id', 'name']);

        // Field officers: if a supply head is selected, scope to that head's officers only
        $officers = $request->filled('supply_head_id')
            ? User::where('role', 'field_officer')
                  ->where('supply_head_id', $request->supply_head_id)
                  ->orderBy('name')->get(['id', 'name'])
            : User::where('role', 'field_officer')->orderBy('name')->get(['id', 'name']);

        $statuses      = ['draft', 'submitted', 'verified', 'recheck', 'rejected'];
        $facilityTypes = PropertyEntry::whereNotNull('facility_type')
            ->distinct()->orderBy('facility_type')->pluck('facility_type');
        $cities        = PropertyEntry::whereNotNull('nearest_city')
            ->distinct()->orderBy('nearest_city')->pluck('nearest_city');

        return view('admin.property-entry-report.index', compact(
            'summary', 'entries', 'supplyHeads', 'officers', 'statuses', 'facilityTypes', 'cities'
        ));
    }

    // ── Admin Show (read-only, no role guard) ─────────────────────────────────

    public function show(PropertyEntry $entry): View
    {
        $entry->load(['photos', 'fieldOfficer', 'supplyHead', 'reviewer', 'logs.user']);
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
        $query = PropertyEntry::with(['fieldOfficer', 'supplyHead']);

        if ($request->filled('supply_head_id')) {
            $query->where('supply_head_id', $request->supply_head_id);
        }

        if ($request->filled('officer_id')) {
            $query->where('field_officer_id', $request->officer_id);
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

}

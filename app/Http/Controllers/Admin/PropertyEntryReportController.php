<?php

namespace App\Http\Controllers\Admin;

use App\Exports\PropertyEntriesExport;
use App\Http\Controllers\Controller;
use App\Models\PropertyEntry;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
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
        $adminStatuses = PropertyEntry::whereNotNull('admin_status')
            ->distinct()->orderBy('admin_status')->pluck('admin_status');
        $facilityTypes = PropertyEntry::whereNotNull('facility_type')
            ->distinct()->orderBy('facility_type')->pluck('facility_type');
        $cities        = PropertyEntry::whereNotNull('nearest_city')
            ->distinct()->orderBy('nearest_city')->pluck('nearest_city');

        $analytics = $this->buildAnalytics($request);

        return view('admin.property-entry-report.index', compact(
            'summary', 'entries', 'supplyHeads', 'officers', 'officersBySupplyHead',
            'zones', 'statuses', 'adminStatuses', 'facilityTypes', 'cities', 'analytics'
        ));
    }

    // ── Analytics (cached aggregate queries — never pulls rows into PHP) ─────

    /**
     * All aggregate numbers behind the charts/leaderboards. Each block is its
     * own short-lived cache entry (5 min) keyed by the active filter set, so
     * the same filtered view doesn't re-hit the DB on every request but a
     * different filter combo still gets a fresh query.
     */
    private function buildAnalytics(Request $request): array
    {
        return [
            'by_property_type'  => $this->analyticsByPropertyType($request),
            'submissions_daily'   => $this->analyticsSubmissionsDaily($request),
            'submissions_monthly' => $this->analyticsSubmissionsMonthly($request),
            'by_city'            => $this->analyticsTopBreakdown($request, 'nearest_city', 'city'),
            'by_officer'         => $this->analyticsTopBreakdown($request, 'field_officer_id', 'officer'),
            'draft_vs_submitted' => $this->analyticsDraftVsSubmitted($request),
        ];
    }

    /**
     * Cache key incorporates every filter buildQuery() understands plus a
     * distinguishing suffix per aggregate, so two different charts (or two
     * different filter combos) never collide on the same cached value.
     */
    private function filterCacheKey(Request $request, string $suffix): string
    {
        $filters = $request->only([
            'search', 'supply_head_id', 'officer_id', 'zone_id', 'status', 'admin_status',
            'facility_type', 'property_type', 'field_verified', 'city',
            'date_from', 'date_to',
        ]);
        ksort($filters);

        return 'property_entry_report:' . $suffix . ':' . md5(json_encode($filters));
    }

    /**
     * Counts per property_type, in config('property_types') display order
     * (types with zero matching entries are still included at 0 so the
     * chart's category set never shifts between filter combos), respecting
     * every active filter except property_type itself — clicking one
     * segment should narrow to that type without losing e.g. a status or
     * city filter already applied, and clicking a different segment should
     * still be a meaningful choice rather than a no-op.
     */
    private function analyticsByPropertyType(Request $request): array
    {
        return Cache::remember($this->filterCacheKey($request, 'by_type'), 300, function () use ($request) {
            $counts = $this->buildQuery($request, ['property_type'])
                ->selectRaw('property_type, COUNT(*) as total')
                ->groupBy('property_type')
                ->pluck('total', 'property_type');

            $types = config('property_types.types', []);
            $out = [];
            foreach ($types as $key => $meta) {
                $out[] = [
                    'key'   => $key,
                    'label' => $meta['label'] ?? $key,
                    'count' => (int) ($counts[$key] ?? 0),
                ];
            }

            // Rows that predate the property_type backfill have a null type.
            // PHP coerces a null array key to '' on both write and read, so
            // a single lookup already covers both — summing $counts[''] and
            // $counts->get(null) here would double-count the same bucket.
            $unclassified = (int) ($counts[''] ?? 0);
            if ($unclassified > 0) {
                $out[] = ['key' => null, 'label' => 'Unclassified', 'count' => $unclassified];
            }

            return $out;
        });
    }

    /**
     * Daily submitted_at counts for the last 90 days — one query covers the
     * 7d/30d/90d toggle client-side (the "all-time" toggle uses the separate
     * monthly aggregate below instead of extending this range indefinitely).
     */
    private function analyticsSubmissionsDaily(Request $request): array
    {
        return Cache::remember($this->filterCacheKey($request, 'daily'), 300, function () use ($request) {
            $since = now()->subDays(89)->startOfDay();

            $rows = $this->buildQuery($request, ['date_from', 'date_to'])
                ->whereNotNull('submitted_at')
                ->where('submitted_at', '>=', $since)
                ->selectRaw('DATE(submitted_at) as day, COUNT(*) as total')
                ->groupBy('day')
                ->pluck('total', 'day');

            $out = [];
            for ($d = $since->copy(); $d->lte(now()); $d->addDay()) {
                $key = $d->format('Y-m-d');
                $out[] = ['date' => $key, 'count' => (int) ($rows[$key] ?? 0)];
            }

            return $out;
        });
    }

    /**
     * Monthly submitted_at counts since the earliest submission — backs the
     * "all-time" toggle without ever materialising one row per entry.
     */
    private function analyticsSubmissionsMonthly(Request $request): array
    {
        return Cache::remember($this->filterCacheKey($request, 'monthly'), 300, function () use ($request) {
            $rows = $this->buildQuery($request, ['date_from', 'date_to'])
                ->setEagerLoads([])
                ->whereNotNull('submitted_at')
                ->selectRaw("DATE_FORMAT(submitted_at, '%Y-%m') as month, COUNT(*) as total")
                ->groupBy('month')
                ->orderBy('month')
                ->get();

            return $rows->map(fn ($r) => ['month' => $r->month, 'count' => (int) $r->total])->all();
        });
    }

    /**
     * Top-5 + "and N more" for a city / field-officer leaderboard, respecting
     * every currently active filter (including property_type/status/etc.) so
     * the leaderboard always matches whatever the table below is showing.
     */
    private function analyticsTopBreakdown(Request $request, string $column, string $suffix): array
    {
        return Cache::remember($this->filterCacheKey($request, 'top_' . $suffix), 300, function () use ($request, $column, $suffix) {
            $query = $this->buildQuery($request)->setEagerLoads([])->whereNotNull($column);

            if ($suffix === 'officer') {
                $rows = $query->selectRaw("{$column} as key_id, COUNT(*) as total")
                    ->groupBy($column)
                    ->orderByDesc('total')
                    ->get();

                $officerNames = User::whereIn('id', $rows->pluck('key_id'))->pluck('name', 'id');
                $rows = $rows->map(fn ($r) => [
                    'label' => $officerNames[$r->key_id] ?? "Officer #{$r->key_id}",
                    'count' => (int) $r->total,
                ]);
            } else {
                $rows = $query->selectRaw("{$column} as label, COUNT(*) as total")
                    ->groupBy($column)
                    ->orderByDesc('total')
                    ->get()
                    ->map(fn ($r) => ['label' => $r->label, 'count' => (int) $r->total]);
            }

            return [
                'top'         => $rows->take(5)->values()->all(),
                'all'         => $rows->values()->all(),
                'total_count' => $rows->count(),
            ];
        });
    }

    /**
     * Draft vs. submitted+ counts — the data-quality signal called out in
     * the report brief (most entries currently sit in draft and never reach
     * a reviewable state). Respects active filters like the other blocks.
     */
    private function analyticsDraftVsSubmitted(Request $request): array
    {
        return Cache::remember($this->filterCacheKey($request, 'draft_ratio'), 300, function () use ($request) {
            $draft = $this->buildQuery($request)->where('status', 'draft')->count();
            $beyondDraft = $this->buildQuery($request)->where('status', '!=', 'draft')->count();
            $total = $draft + $beyondDraft;

            return [
                'draft'          => $draft,
                'beyond_draft'   => $beyondDraft,
                'draft_percent'  => $total > 0 ? round($draft / $total * 100, 1) : 0.0,
            ];
        });
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

    /**
     * @param string[] $excluding Filter keys to skip even if present in the
     *                            request — used by the analytics blocks so a
     *                            chart can respect every filter except the
     *                            one dimension it's itself breaking down by
     *                            (e.g. the property-type chart still honours
     *                            status/city/etc. but not property_type,
     *                            otherwise every bar but one would be zero
     *                            whenever a type filter is already active).
     */
    private function buildQuery(Request $request, array $excluding = [])
    {
        $query = PropertyEntry::with(['fieldOfficer', 'supplyHead', 'zone']);

        if (!in_array('search', $excluding) && $request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('code', 'like', "%{$search}%")
                  ->orWhere('property_name', 'like', "%{$search}%")
                  ->orWhere('owner_contact_name', 'like', "%{$search}%")
                  ->orWhere('owner_contact_phone', 'like', "%{$search}%")
                  ->orWhere('submitter_full_name', 'like', "%{$search}%")
                  ->orWhere('locality_broad_area', 'like', "%{$search}%")
                  ->orWhere('name_full_address', 'like', "%{$search}%")
                  ->orWhere('nearest_city', 'like', "%{$search}%")
                  ->orWhere('city', 'like', "%{$search}%");
            });
        }

        if (!in_array('supply_head_id', $excluding) && $request->filled('supply_head_id')) {
            $query->where('supply_head_id', $request->supply_head_id);
        }

        if (!in_array('officer_id', $excluding) && $request->filled('officer_id')) {
            $query->where('field_officer_id', $request->officer_id);
        }

        if (!in_array('zone_id', $excluding) && $request->filled('zone_id')) {
            $query->where('zone_id', $request->zone_id);
        }

        if (!in_array('status', $excluding) && $request->filled('status')) {
            $query->where('status', $request->status);
        }

        if (!in_array('admin_status', $excluding) && $request->filled('admin_status')) {
            $adminStatus = $request->admin_status;
            if ($adminStatus === 'approved') {
                $query->where('admin_status', 'approved');
            } elseif ($adminStatus === 'pending' || $adminStatus === 'not_approved') {
                $query->where(function ($q) {
                    $q->whereNull('admin_status')
                      ->orWhere('admin_status', 'pending')
                      ->orWhere('admin_status', '!=', 'approved');
                });
            } elseif ($adminStatus === 'rejected') {
                $query->where('admin_status', 'rejected');
            } else {
                $query->where('admin_status', $adminStatus);
            }
        }

        if (!in_array('property_type', $excluding) && $request->filled('property_type')) {
            $query->where('property_type', $request->property_type);
        }

        if (!in_array('facility_type', $excluding) && $request->filled('facility_type')) {
            $query->where('facility_type', $request->facility_type);
        }

        if (!in_array('city', $excluding) && $request->filled('city')) {
            $query->where('nearest_city', $request->city);
        }

        if (!in_array('date_from', $excluding) && $request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if (!in_array('date_to', $excluding) && $request->filled('date_to')) {
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

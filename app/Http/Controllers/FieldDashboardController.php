<?php

namespace App\Http\Controllers;

use App\Models\PropertyEntry;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class FieldDashboardController extends Controller
{
    public function index(): View|RedirectResponse
    {
        $user   = auth()->user();
        $userId = auth()->id();

        // Redirect field officers directly to their properties page
        if ($user->role === 'field_officer') {
            return redirect()->route('field.properties.index');
        }

        $counters = [];
        $recentEntries = collect();
        $officerStats = collect();

        if ($user->role === 'supply_head') {
            // Field officers are reached through the zones this supply head
            // covers; owners are added when can_approve_owner_listings is on.
            $zoneIds = $user->zoneIds();
            $fieldOfficerIds = User::where('role', 'field_officer')->whereIn('zone_id', $zoneIds)->pluck('id');
            $ownerIds = $user->can_approve_owner_listings
                ? User::where('role', 'owner')->pluck('id')
                : collect();
            $assigneeIds = $fieldOfficerIds->concat($ownerIds);

            $counters = [
                'total'      => PropertyEntry::whereIn('field_officer_id', $assigneeIds)->where('status', '!=', 'draft')->count(),
                'pending'    => PropertyEntry::whereIn('field_officer_id', $assigneeIds)->where('status', 'submitted')->count(),
                'verified'   => PropertyEntry::whereIn('field_officer_id', $assigneeIds)->where('status', 'verified')->count(),
                'rejected'   => PropertyEntry::whereIn('field_officer_id', $assigneeIds)->where('status', 'rejected')->count(),
                'recheck'    => PropertyEntry::whereIn('field_officer_id', $assigneeIds)->where('status', 'recheck')->count(),
                'not_opened' => PropertyEntry::whereIn('field_officer_id', $assigneeIds)->where('status', '!=', 'draft')->whereNull('supply_head_viewed_at')->count(),
            ];

            // Recent unviewed submitted entries for notification bar (last 10)
            $recentEntries = PropertyEntry::with('fieldOfficer')
                ->whereIn('field_officer_id', $assigneeIds)
                ->where('status', 'submitted')
                ->whereNull('supply_head_viewed_at')
                ->latest('submitted_at')
                ->limit(10)
                ->get();

            // Chart data for officer & owner submissions — no draft
            $officerStats = User::where(function($q) use ($zoneIds, $user) {
                $q->where('role', 'field_officer')->whereIn('zone_id', $zoneIds);
                if ($user->can_approve_owner_listings) {
                    $q->orWhere('role', 'owner');
                }
            })
            ->with(['propertyEntries' => function ($q) {
                $q->select('field_officer_id', 'status', 'supply_head_viewed_at')
                  ->whereIn('status', ['submitted', 'verified', 'rejected', 'recheck']);
            }])
            ->get()
            ->filter(function ($assignee) {
                // If owner, only include if they have submitted property entries
                return $assignee->role !== 'owner' || $assignee->propertyEntries->isNotEmpty();
            })
            ->map(function ($officer) {
                $entries    = $officer->propertyEntries->groupBy('status');
                $notOpened  = $officer->propertyEntries->whereNull('supply_head_viewed_at')->count();
                return [
                    'id'         => $officer->id,
                    'name'       => $officer->name . ($officer->role === 'owner' ? ' (Owner)' : ''),
                    'total'      => $officer->propertyEntries->count(),
                    'submitted'  => $entries->get('submitted',  collect())->count(),
                    'verified'   => $entries->get('verified',   collect())->count(),
                    'rejected'   => $entries->get('rejected',   collect())->count(),
                    'recheck'    => $entries->get('recheck',    collect())->count(),
                    'not_opened' => $notOpened,
                ];
            })
            ->values();
        }

        return view('field.dashboard', compact('user', 'counters', 'recentEntries', 'officerStats'));
    }
}

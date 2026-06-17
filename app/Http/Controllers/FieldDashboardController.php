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
            // Get field officers under this supply head
            $fieldOfficerIds = User::where('supply_head_id', $userId)->pluck('id');
            
            $counters = [
                'total'    => PropertyEntry::whereIn('field_officer_id', $fieldOfficerIds)->count(),
                'pending'  => PropertyEntry::whereIn('field_officer_id', $fieldOfficerIds)->where('status', 'submitted')->count(),
                'verified' => PropertyEntry::whereIn('field_officer_id', $fieldOfficerIds)->where('status', 'verified')->count(),
                'rejected' => PropertyEntry::whereIn('field_officer_id', $fieldOfficerIds)->where('status', 'rejected')->count(),
                'recheck'  => PropertyEntry::whereIn('field_officer_id', $fieldOfficerIds)->where('status', 'recheck')->count(),
            ];

            // Chart data for officer submissions
            $officerStats = User::where('supply_head_id', $userId)
                ->with(['propertyEntries' => function($q) {
                    $q->select('field_officer_id', 'status');
                }])
                ->get()
                ->map(function($officer) {
                    $entries = $officer->propertyEntries->groupBy('status');
                    return [
                        'name' => $officer->name,
                        'total' => $officer->propertyEntries->count(),
                        'draft' => $entries->get('draft', collect())->count(),
                        'submitted' => $entries->get('submitted', collect())->count(),
                        'verified' => $entries->get('verified', collect())->count(),
                        'rejected' => $entries->get('rejected', collect())->count(),
                        'recheck' => $entries->get('recheck', collect())->count(),
                    ];
                });
        }

        return view('field.dashboard', compact('user', 'counters', 'recentEntries', 'officerStats'));
    }
}

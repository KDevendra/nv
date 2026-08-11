<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use App\Models\Property;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SalesExecutiveLeadController extends Controller
{
    /**
     * Display a listing of assigned SE leads for auth user's division.
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        $query = Lead::where('division', $user->division)
            ->where('assigned_se_id', $user->id)
            ->whereIn('stage', Lead::SE_STAGES)
            ->with(['property' => function ($q) {
                $q->select('id', 'title', 'slug', 'price', 'carpet_area', 'built_up_area', 'plot_area', 'city_id', 'location_id', 'property_type_id');
            }, 'property.city:id,name', 'property.location:id,name', 'property.propertyType:id,name']);

        if ($request->filled('stage')) {
            $query->where('stage', $request->stage);
        }

        if ($request->filled('side_state')) {
            $query->where('side_state', $request->side_state);
        }

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('name', 'LIKE', "%{$s}%")
                  ->orWhere('phone', 'LIKE', "%{$s}%")
                  ->orWhere('email', 'LIKE', "%{$s}%");
            });
        }

        $stats = [
            'total'        => (clone $query)->count(),
            'active'       => (clone $query)->whereNull('side_state')->count(),
            'on_hold'      => (clone $query)->where('side_state', 'inquiry_hold')->count(),
            'sla_breached' => (clone $query)->whereNull('first_contacted_at')->count(),
        ];

        $leads = $query->orderBy('updated_at', 'desc')->paginate(15);

        return view('se.leads.index', compact('leads', 'stats'));
    }

    /**
     * Display the specified SE lead (info-gated property details).
     */
    public function show(Lead $lead)
    {
        $user = Auth::user();

        if ($lead->division !== $user->division || $lead->assigned_se_id !== $user->id) {
            abort(403, 'Unauthorized access to this lead.');
        }

        $lead->load(['stageHistories.changedBy']);
        $propertySnapshot = Lead::publicPropertySnapshot($lead->property);

        // Fetch properties from property_entries table (info-gated for options sharing)
        $propertyEntries = \App\Models\PropertyEntry::query()
            ->select('id', 'code', 'property_name', 'facility_type', 'nearest_city', 'village_town_district', 'built_up_area', 'plot_area', 'expected_rent', 'expected_sale_price')
            ->limit(50)
            ->get()
            ->map(function ($pe) {
                $code = $pe->code ?: ('PE-' . $pe->id);
                $title = $pe->property_name ?: "Property #{$code}";
                $city = $pe->nearest_city ?: $pe->village_town_district ?: 'Raipur';
                $areaNum = $pe->built_up_area ?: $pe->plot_area;
                $area = $areaNum ? number_format($areaNum) . ' sqft' : 'N/A';
                $price = $pe->expected_rent ? ("₹" . number_format($pe->expected_rent) . "/mo") : ($pe->expected_sale_price ? ("₹" . number_format($pe->expected_sale_price)) : 'On Request');
                return [
                    'id'            => $code,
                    'code'          => $code,
                    'title'         => $title,
                    'facility_type' => $pe->facility_type ?: 'Warehouse/Commercial',
                    'city'          => $city,
                    'area'          => $area,
                    'price'         => $price,
                    'source'        => 'property_entries',
                ];
            });

        $standardProperties = Property::where('is_active', true)
            ->select('id', 'title', 'slug', 'price', 'carpet_area', 'built_up_area', 'city_id', 'location_id', 'property_type_id')
            ->with(['city:id,name', 'location:id,name', 'propertyType:id,name'])
            ->limit(30)
            ->get()
            ->map(function ($p) {
                $snapshot = Lead::publicPropertySnapshot($p);
                $code = 'PROP-' . $p->id;
                return [
                    'id'            => $code,
                    'code'          => $code,
                    'title'         => $snapshot['title'],
                    'facility_type' => $snapshot['property_type'] ?? 'Property',
                    'city'          => $snapshot['city'] ?? 'Raipur',
                    'area'          => ($snapshot['built_up_area'] ?? $snapshot['carpet_area'] ?? null) ? number_format($snapshot['built_up_area'] ?? $snapshot['carpet_area']) . ' sqft' : 'N/A',
                    'price'         => '₹' . number_format($snapshot['price']),
                    'source'        => 'properties',
                ];
            });

        $availableOptions = $propertyEntries->concat($standardProperties);

        $history = $lead->stageHistories;

        return view('se.leads.show', compact('lead', 'propertySnapshot', 'availableOptions', 'history'));
    }

    /**
     * Update lead stage, contact log, qualification notes, options shared, or handover note.
     */
    public function update(Request $request, Lead $lead)
    {
        $user = Auth::user();

        if ($lead->division !== $user->division || $lead->assigned_se_id !== $user->id) {
            abort(403, 'Unauthorized action.');
        }

        // Side-state action handling
        if ($request->has('action')) {
            return $this->handleSideStateAction($request, $lead);
        }

        // Stage transition handling
        if ($request->filled('stage')) {
            $newStage = $request->stage;

            // Block transition if currently on hold
            if ($lead->side_state === 'inquiry_hold') {
                return response()->json([
                    'success' => false,
                    'message' => 'Lead is on inquiry hold. Resume lead before advancing stage.'
                ], 422);
            }

            // Stage 5 Handover Gate enforcement
            if ($newStage === 'escalated_to_cc') {
                $handoverNote = trim((string) $request->input('handover_note', $lead->handover_note));
                if (empty($handoverNote)) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Handover note is required before escalating lead to Chief Coordinator.'
                    ], 422);
                }
                $lead->handover_note = $handoverNote;
                $lead->handover_completed_at = now();
                $lead->save();
            }

            try {
                $lead->transitionTo($newStage, $user);
            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage()
                ], 422);
            }
        }

        // Update qualification or contact outcome fields
        if ($request->filled('qualification_notes')) {
            $lead->qualification_notes = $request->qualification_notes;
        }

        if ($request->filled('contact_outcome')) {
            $now = now();
            $lead->contact_outcome = $request->contact_outcome;
            $lead->contact_attempts = $lead->contact_attempts + 1;
            $lead->last_contacted_at = $now;
            if (!$lead->first_contacted_at) {
                $lead->first_contacted_at = $now;
            }
        }

        if ($request->has('options_shared_property_ids')) {
            $lead->options_shared_property_ids = (array) $request->options_shared_property_ids;
        }

        $lead->save();

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Lead updated successfully.',
                'lead'    => $lead->fresh(['stageHistories'])
            ]);
        }

        return back()->with('success', 'Lead updated successfully.');
    }

    private function handleSideStateAction(Request $request, Lead $lead)
    {
        $action = $request->action;
        $user = Auth::user();

        try {
            match ($action) {
                'put_on_hold' => $lead->putOnHold(
                    reason: (string) $request->input('hold_reason'),
                    expectedResumeDate: $request->input('hold_expected_resume_date')
                ),
                'resume_from_hold' => $lead->resumeFromHold(),
                'defer' => $lead->deferFollowUp(
                    date: (string) $request->input('follow_up_date')
                ),
                'mark_lost' => $lead->markLost(
                    reason: (string) $request->input('lost_reason'),
                    otherText: $request->input('lost_reason_other')
                ),
                default => throw new \InvalidArgumentException("Invalid side-state action '{$action}'."),
            };
        } catch (\Exception $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => $e->getMessage()], 422);
            }
            return back()->with('error', $e->getMessage());
        }

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Lead side-state updated.', 'lead' => $lead->fresh()]);
        }

        return back()->with('success', 'Lead side-state updated.');
    }

    public function logContact(Request $request, Lead $lead)
    {
        $now = now();
        $lead->contact_attempts = $lead->contact_attempts + 1;
        $lead->last_contacted_at = $now;
        if (!$lead->first_contacted_at) {
            $lead->first_contacted_at = $now;
        }
        $notes = $request->input('notes');
        if ($notes) {
            $lead->contact_outcome = $notes;
        }
        if ($lead->stage === 'new_lead') {
            $lead->transitionTo('contacted', Auth::user());
        } else {
            $lead->save();
        }

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Contact attempt logged.', 'lead' => $lead->fresh()]);
        }
        return back()->with('success', 'Contact attempt logged.');
    }

    public function qualify(Request $request, Lead $lead)
    {
        $lead->qualification_notes = $request->input('qualification_notes', $lead->qualification_notes);
        
        if ($lead->stage === 'contacted' || $lead->stage === 'new_lead' || $request->boolean('advance_stage')) {
            $lead->transitionTo('qualified', Auth::user());
        } else {
            $lead->save();
        }

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Lead marked as Qualified.', 'lead' => $lead->fresh()]);
        }
        return back()->with('success', 'Lead marked as Qualified.');
    }

    public function shareOptions(Request $request, Lead $lead)
    {
        $propertyIds = array_filter((array) $request->input('property_ids', []));
        
        if (count($propertyIds) < 2 || count($propertyIds) > 4) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Please select between 2 and 4 property options to share.'], 422);
            }
            return back()->with('error', 'Please select between 2 and 4 property options to share.');
        }

        $lead->options_shared_property_ids = array_values($propertyIds);
        if ($lead->stage === 'qualified') {
            $lead->transitionTo('options_shared', Auth::user());
        } else {
            $lead->save();
        }

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Property options shared successfully.', 'lead' => $lead->fresh()]);
        }
        return back()->with('success', 'Property options shared successfully.');
    }

    public function confirmInterest(Request $request, Lead $lead)
    {
        if ($lead->stage === 'options_shared') {
            $lead->transitionTo('interest_confirmed', Auth::user());
        }

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Client interest confirmed.', 'lead' => $lead->fresh()]);
        }
        return back()->with('success', 'Client interest confirmed.');
    }

    public function handover(Request $request, Lead $lead)
    {
        $request->validate([
            'handover_note' => 'required|string|min:20',
        ]);

        $lead->handover_note = $request->handover_note;
        $lead->handover_completed_at = now();

        if ($lead->stage === 'interest_confirmed') {
            $lead->transitionTo('escalated_to_cc', Auth::user());
        } else {
            $lead->stage = 'escalated_to_cc';
            $lead->save();
        }

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Lead escalated to Chief Coordinator.', 'lead' => $lead->fresh()]);
        }
        return back()->with('success', 'Lead escalated to Chief Coordinator.');
    }

    public function hold(Request $request, Lead $lead)
    {
        $reason = $request->input('reason') ?: 'On Hold';
        $holdUntil = $request->input('hold_until') ?: now()->addDays(7)->toDateString();
        $lead->putOnHold($reason, $holdUntil);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Lead placed on hold.', 'lead' => $lead->fresh()]);
        }
        return back()->with('success', 'Lead placed on hold.');
    }

    public function resume(Request $request, Lead $lead)
    {
        $lead->resumeFromHold();

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Lead resumed.', 'lead' => $lead->fresh()]);
        }
        return back()->with('success', 'Lead resumed.');
    }

    public function defer(Request $request, Lead $lead)
    {
        $date = $request->input('defer_until') ?: $request->input('follow_up_date') ?: now()->addDays(1)->toDateString();
        $lead->deferFollowUp($date);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Lead follow-up deferred.', 'lead' => $lead->fresh()]);
        }
        return back()->with('success', 'Lead follow-up deferred.');
    }

    public function markLost(Request $request, Lead $lead)
    {
        $request->validate([
            'reason' => 'required|string',
        ]);

        $lead->markLost($request->reason);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Lead marked as lost.', 'lead' => $lead->fresh()]);
        }
        return back()->with('success', 'Lead marked as lost.');
    }
}

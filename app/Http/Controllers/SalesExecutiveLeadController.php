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

        // Fetch properties for options sharing (info-gated)
        $availableOptions = Property::where('is_active', true)
            ->select('id', 'title', 'slug', 'price', 'carpet_area', 'built_up_area', 'city_id', 'location_id', 'property_type_id')
            ->with(['city:id,name', 'location:id,name', 'propertyType:id,name'])
            ->limit(30)
            ->get()
            ->map(fn ($p) => Lead::publicPropertySnapshot($p));

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
            $lead->contact_outcome = $request->contact_outcome;
            $lead->contact_attempts = $lead->contact_attempts + 1;
            if (!$lead->first_contacted_at) {
                $lead->first_contacted_at = now();
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
}

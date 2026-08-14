<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ChiefCoordinatorLeadController extends Controller
{
    /**
     * Display a listing of assigned CC leads for auth user's division.
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        $query = Lead::where('division', $user->division)
            ->where('assigned_cc_id', $user->id)
            ->with(['property' => function ($q) {
                $q->select('id', 'title', 'slug', 'price', 'carpet_area', 'built_up_area', 'plot_area', 'city_id', 'location_id', 'property_type_id');
            }, 'property.city:id,name', 'property.location:id,name', 'property.propertyType:id,name', 'assignedSE:id,name', 'zone:id,name,slug']);

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
            'total'               => (clone $query)->count(),
            'active'              => (clone $query)->whereNull('side_state')->count(),
            'feasibility_pending' => (clone $query)->whereNotNull('feasibility_raised_at')->whereNull('feasibility_responded_at')->count(),
            'sla_breached'        => (clone $query)->whereNotNull('feasibility_raised_at')->whereNull('feasibility_responded_at')->where('feasibility_raised_at', '<=', now()->subHours(24))->count(),
            'load_current'        => $user->activeCCLeadCount(),
            'load_cap'            => Lead::CC_MAX_ACTIVE_LEADS,
        ];

        $leads = $query->orderBy('updated_at', 'desc')->paginate(15);
        $supplyHeads = User::getSupplyHeadsByDivision($user->division);

        return view('cc.leads.index', compact('leads', 'supplyHeads', 'stats'));
    }

    /**
     * Display the specified CC lead (info-gated property snapshot).
     */
    public function show(Lead $lead)
    {
        $user = Auth::user();

        if ($lead->division !== $user->division || $lead->assigned_cc_id !== $user->id) {
            abort(403, 'Unauthorized access to this lead.');
        }

        $lead->load(['stageHistories.changedBy', 'assignedSE']);
        $propertySnapshot = Lead::publicPropertySnapshot($lead->property);
        $supplyHeads = User::getSupplyHeadsByDivision($user->division);
        $history = $lead->stageHistories;

        return view('cc.leads.show', compact('lead', 'propertySnapshot', 'supplyHeads', 'history'));
    }

    /**
     * Update CC lead stage, feasibility request, site visit link, or deal close.
     */
    public function update(Request $request, Lead $lead)
    {
        $user = Auth::user();

        if ($lead->division !== $user->division || $lead->assigned_cc_id !== $user->id) {
            abort(403, 'Unauthorized action.');
        }

        // Side-state action handling
        if ($request->has('action')) {
            return $this->handleSideStateAction($request, $lead);
        }

        // Raise Feasibility Relay to Supply Head (Stage 7)
        if ($request->has('raise_feasibility')) {
            if ($lead->side_state === 'inquiry_hold') {
                return response()->json(['success' => false, 'message' => 'Lead is on hold. Cannot raise feasibility.'], 422);
            }

            $shId = $request->input('feasibility_sh_id');
            $sh = User::where('id', $shId)->where('role', 'supply_head')->where('division', $user->division)->first();

            if (!$sh) {
                return response()->json(['success' => false, 'message' => 'Invalid Supply Head selected for this division.'], 422);
            }

            $lead->update([
                'feasibility_sh_id'     => $sh->id,
                'feasibility_raised_at' => now(),
                'feasibility_status'    => 'pending',
                'feasibility_notes'     => $request->input('feasibility_notes'),
            ]);

            if ($lead->canTransitionTo('inventory_check_done')) {
                $lead->transitionTo('inventory_check_done', $user);
            }

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => true, 'message' => 'Feasibility check raised to Supply Head.', 'lead' => $lead->fresh()]);
            }
            return back()->with('success', 'Feasibility check raised to Supply Head.');
        }

        // Trigger Site-Visit Expiring Link (Stage 8)
        if ($request->has('trigger_visit_link')) {
            $token = $lead->generateVisitLinkToken();
            $linkUrl = route('leads.visit_link', ['token' => $token]);

            Log::info("SMS SENT to {$lead->phone}: Click link to view site visit address (valid 24h): {$linkUrl}");

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Site visit link sent to visitor via SMS (valid for 24h).',
                    'visit_link_sent_at' => $lead->visit_link_sent_at,
                    'visit_link_expires_at' => $lead->visit_link_expires_at
                ]);
            }
            return back()->with('success', 'Site visit link sent via SMS.');
        }

        // Log Site Visit Feedback (Stage 9)
        if ($request->filled('site_visit_feedback')) {
            $lead->site_visit_feedback = $request->site_visit_feedback;
            if ($request->filled('site_visit_date')) {
                $lead->site_visit_date = $request->site_visit_date;
            }
            $lead->save();
        }

        // Stage Transition
        if ($request->filled('stage')) {
            try {
                $lead->transitionTo($request->stage, $user);
            } catch (\Exception $e) {
                return response()->json(['success' => false, 'message' => $e->getMessage()], 422);
            }
        }

        // Close Deal (Stage 11)
        if ($request->has('close_deal')) {
            $lead->deal_closed_at = now();
            if ($request->filled('commission_amount')) {
                $lead->commission_amount = $request->commission_amount;
            }
            $lead->owner_notified_at = now();
            $lead->reminder_6mo_at = now()->addMonths(6);

            if ($lead->canTransitionTo('deal_closed')) {
                $lead->transitionTo('deal_closed', $user);
            } else {
                $lead->save();
            }
        }

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Lead updated successfully.', 'lead' => $lead->fresh(['stageHistories'])]);
        }

        return back()->with('success', 'Lead updated successfully.');
    }

    private function handleSideStateAction(Request $request, Lead $lead)
    {
        try {
            match ($request->action) {
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
                default => throw new \InvalidArgumentException("Invalid side-state action."),
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

    public function requestFeasibility(Request $request, Lead $lead)
    {
        $request->merge(['raise_feasibility' => 1]);
        return $this->update($request, $lead);
    }

    public function requestInventoryCheck(Request $request, Lead $lead)
    {
        return $this->requestFeasibility($request, $lead);
    }

    public function generateSiteVisitLink(Request $request, Lead $lead)
    {
        $token = $lead->generateVisitLinkToken();
        $linkUrl = route('leads.visit_link', ['token' => $token]);

        if ($lead->stage === 'inventory_check_done') {
            $lead->transitionTo('site_visit_scheduled', Auth::user());
        }

        Log::info("SMS SENT to {$lead->phone}: Click link to view site visit address (valid 24h): {$linkUrl}");

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Site visit link generated.',
                'url'     => $linkUrl,
                'expires_at' => $lead->visit_link_expires_at?->format('d M Y, H:i')
            ]);
        }
        return back()->with('success', 'Site visit link generated.');
    }

    public function sendVisitLink(Request $request, Lead $lead)
    {
        return $this->generateSiteVisitLink($request, $lead);
    }

    public function siteVisitFeedback(Request $request, Lead $lead)
    {
        $feedback = $request->input('feedback') ?: $request->input('site_visit_feedback');
        $request->merge(['site_visit_feedback' => $feedback]);
        $request->validate([
            'site_visit_feedback' => 'required|string',
        ]);

        $lead->site_visit_feedback = $feedback;
        if ($request->filled('site_visit_date')) {
            $lead->site_visit_date = $request->site_visit_date;
        }
        if ($lead->canTransitionTo('site_visit_completed')) {
            $lead->transitionTo('site_visit_completed', Auth::user());
        } else {
            $lead->save();
        }

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Site visit feedback recorded.', 'lead' => $lead->fresh()]);
        }
        return back()->with('success', 'Site visit feedback recorded.');
    }

    public function recordVisitFeedback(Request $request, Lead $lead)
    {
        return $this->siteVisitFeedback($request, $lead);
    }

    public function negotiate(Request $request, Lead $lead)
    {
        if ($request->filled('negotiation_notes')) {
            $lead->negotiation_notes = $request->negotiation_notes;
        }
        if ($request->boolean('advance_stage') || $lead->stage === 'site_visit_completed') {
            $lead->transitionTo('negotiation', Auth::user());
        } else {
            $lead->save();
        }

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Negotiation notes saved.', 'lead' => $lead->fresh()]);
        }
        return back()->with('success', 'Negotiation notes saved.');
    }

    public function closeDeal(Request $request, Lead $lead)
    {
        $lead->deal_closed_at = now();
        if ($request->filled('commission_amount')) {
            $lead->commission_amount = $request->commission_amount;
        }
        $lead->owner_notified_at = now();
        $lead->reminder_6mo_at = now()->addMonths(6);

        if ($lead->canTransitionTo('deal_closed')) {
            $lead->transitionTo('deal_closed', Auth::user());
        } else {
            $lead->save();
        }

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Deal closed successfully.', 'lead' => $lead->fresh()]);
        }
        return back()->with('success', 'Deal closed successfully.');
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

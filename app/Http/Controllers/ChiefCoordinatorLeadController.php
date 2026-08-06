<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use App\Models\Property;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/**
 * Panel 2 — Chief Coordinator
 *
 * Routes prefix : /cc/leads
 * Middleware    : auth (role check enforced in each method)
 *
 * Stages owned  : escalated_to_cc → feasibility_check → options_shared
 *               → site_visit_scheduled → site_visit_done → negotiation → deal_closed
 *
 * Actions       : raise feasibility to SH, generate site-visit link,
 *                 log site-visit feedback, negotiate, close deal
 */
class ChiefCoordinatorLeadController extends Controller
{
    // ──────────────────────────────────────────────────────────────────────
    // Gate helpers
    // ──────────────────────────────────────────────────────────────────────

    private function authorise(): User
    {
        /** @var User $user */
        $user = Auth::user();

        if (!$user || $user->role !== 'chief_coordinator') {
            abort(403, 'Access restricted to Chief Coordinators.');
        }

        return $user;
    }

    private function authoriseLead(Lead $lead, User $cc): void
    {
        if ((int) $lead->assigned_cc_id !== $cc->id) {
            abort(403, 'This lead is not assigned to you.');
        }

        if (!in_array($lead->stage, Lead::CC_STAGES, true)) {
            abort(403, 'This lead is not in the CC pipeline.');
        }
    }

    // ──────────────────────────────────────────────────────────────────────
    // Index
    // ──────────────────────────────────────────────────────────────────────

    /**
     * GET /cc/leads
     */
    public function index(Request $request)
    {
        $cc    = $this->authorise();
        $query = Lead::forCC($cc->id)
            ->whereIn('stage', Lead::CC_STAGES)
            ->with(['property', 'assignedSE', 'feasibilitySH'])
            ->latest();

        if ($stage = $request->query('stage')) {
            $query->where('stage', $stage);
        }
        if ($sideState = $request->query('side_state')) {
            $query->where('side_state', $sideState);
        } else {
            $query->where(function ($q) {
                $q->whereNull('side_state')->orWhere('side_state', '!=', 'lost');
            });
        }

        $leads = $query->paginate(25)->withQueryString();

        $stats = [
            'total'              => Lead::forCC($cc->id)->whereIn('stage', Lead::CC_STAGES)->count(),
            'active'             => Lead::forCC($cc->id)->whereIn('stage', Lead::CC_STAGES)->whereNull('side_state')->count(),
            'feasibility_pending'=> Lead::forCC($cc->id)->where('stage', 'feasibility_check')
                                        ->where('feasibility_status', 'pending')->count(),
            'sla_breached'       => Lead::forCC($cc->id)->where('sla_feasibility_breached', true)->count(),
            'load_cap'           => Lead::CC_MAX_ACTIVE_LEADS,
            'load_current'       => $cc->activeCCLeadCount(),
        ];

        if ($request->ajax()) {
            return response()->json([
                'html'  => view('cc.leads._table', compact('leads'))->render(),
                'links' => $leads->links()->toHtml(),
            ]);
        }

        return view('cc.leads.index', compact('leads', 'stats', 'cc'));
    }

    // ──────────────────────────────────────────────────────────────────────
    // Show
    // ──────────────────────────────────────────────────────────────────────

    /**
     * GET /cc/leads/{lead}
     */
    public function show(Lead $lead)
    {
        $cc = $this->authorise();
        $this->authoriseLead($lead, $cc);

        $lead->load(['property', 'assignedSE', 'feasibilitySH', 'stageHistories.changedBy']);

        $propertySnapshot = $lead->publicPropertySnapshot();
        $supplyHeads      = User::getSupplyHeadsByDivision($lead->division);
        $history          = $lead->stageHistories;

        return view('cc.leads.show', compact('lead', 'cc', 'propertySnapshot', 'supplyHeads', 'history'));
    }

    // ──────────────────────────────────────────────────────────────────────
    // Raise feasibility request to SH
    // ──────────────────────────────────────────────────────────────────────

    /**
     * POST /cc/leads/{lead}/request-feasibility
     */
    public function requestFeasibility(Request $request, Lead $lead)
    {
        $cc = $this->authorise();
        $this->authoriseLead($lead, $cc);

        if ($lead->stage !== 'escalated_to_cc') {
            $msg = 'Feasibility can only be raised from escalated_to_cc stage.';
            return $request->ajax()
                ? response()->json(['success' => false, 'message' => $msg], 422)
                : back()->withErrors($msg);
        }

        $request->validate([
            'feasibility_sh_id' => 'required|exists:users,id',
            'notes'             => 'nullable|string|max:2000',
        ]);

        // Verify the chosen SH belongs to the same division
        $sh = User::where('id', $request->feasibility_sh_id)
            ->where('role', 'supply_head')
            ->where('division', $lead->division)
            ->firstOrFail();

        DB::transaction(function () use ($lead, $request, $sh, $cc) {
            $lead->update([
                'feasibility_sh_id'          => $sh->id,
                'feasibility_status'         => 'pending',
                'feasibility_requested_at'   => now(),
                'feasibility_notes'          => $request->notes,
                // Set SH SLA: 24 clock hours
                'sla_feasibility_due_at'     => now()->addHours(24),
                'sla_feasibility_breached'   => false,
            ]);

            $lead->transitionTo('feasibility_check', "Feasibility raised to SH #{$sh->id}.", $cc);
        });

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => "Feasibility request sent to {$sh->name}.",
                'stage'   => $lead->fresh()->stage,
            ]);
        }

        return back()->with('success', "Feasibility request sent to {$sh->name}.");
    }

    // ──────────────────────────────────────────────────────────────────────
    // Generate single-use site-visit token
    // ──────────────────────────────────────────────────────────────────────

    /**
     * POST /cc/leads/{lead}/generate-site-visit-link
     */
    public function generateSiteVisitLink(Request $request, Lead $lead)
    {
        $cc = $this->authorise();
        $this->authoriseLead($lead, $cc);

        if (!in_array($lead->stage, ['options_shared', 'site_visit_scheduled'], true)) {
            $msg = 'Site-visit link can only be generated after options are shared.';
            return $request->ajax()
                ? response()->json(['success' => false, 'message' => $msg], 422)
                : back()->withErrors($msg);
        }

        $token = $lead->generateSiteVisitToken();
        $url   = route('site-visit.show', ['token' => $token]);

        if ($lead->stage === 'options_shared') {
            $lead->transitionTo('site_visit_scheduled', 'Site-visit link generated.', $cc);
        }

        if ($request->ajax()) {
            return response()->json([
                'success'    => true,
                'message'    => 'Single-use site-visit link generated (valid 24 h).',
                'url'        => $url,
                'expires_at' => $lead->fresh()->site_visit_token_expires_at->toIso8601String(),
            ]);
        }

        return back()->with('success', 'Site-visit link generated.')->with('site_visit_url', $url);
    }

    // ──────────────────────────────────────────────────────────────────────
    // Log site-visit feedback
    // ──────────────────────────────────────────────────────────────────────

    /**
     * POST /cc/leads/{lead}/site-visit-feedback
     */
    public function siteVisitFeedback(Request $request, Lead $lead)
    {
        $cc = $this->authorise();
        $this->authoriseLead($lead, $cc);

        if ($lead->stage !== 'site_visit_scheduled') {
            $msg = 'Lead must be at site_visit_scheduled to log feedback.';
            return $request->ajax()
                ? response()->json(['success' => false, 'message' => $msg], 422)
                : back()->withErrors($msg);
        }

        $request->validate([
            'feedback' => 'required|string|min:10|max:5000',
        ]);

        DB::transaction(function () use ($lead, $request, $cc) {
            $lead->update(['site_visit_feedback' => $request->feedback]);
            $lead->transitionTo('site_visit_done', 'Site-visit feedback logged.', $cc);
        });

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Site-visit feedback saved.', 'stage' => $lead->fresh()->stage]);
        }

        return back()->with('success', 'Site-visit feedback saved.');
    }

    // ──────────────────────────────────────────────────────────────────────
    // Negotiation notes
    // ──────────────────────────────────────────────────────────────────────

    /**
     * POST /cc/leads/{lead}/negotiate
     */
    public function negotiate(Request $request, Lead $lead)
    {
        $cc = $this->authorise();
        $this->authoriseLead($lead, $cc);

        $request->validate([
            'negotiation_notes' => 'required|string|max:5000',
            'advance_stage'     => 'nullable|boolean',
        ]);

        DB::transaction(function () use ($lead, $request, $cc) {
            $lead->negotiation_notes = $request->negotiation_notes;
            $lead->save();

            if ($request->boolean('advance_stage') && $lead->canTransitionTo('negotiation')) {
                $lead->transitionTo('negotiation', 'Entered negotiation.', $cc);
            }
        });

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Negotiation notes saved.', 'stage' => $lead->fresh()->stage]);
        }

        return back()->with('success', 'Negotiation notes saved.');
    }

    // ──────────────────────────────────────────────────────────────────────
    // Close deal
    // ──────────────────────────────────────────────────────────────────────

    /**
     * POST /cc/leads/{lead}/close-deal
     */
    public function closeDeal(Request $request, Lead $lead)
    {
        $cc = $this->authorise();
        $this->authoriseLead($lead, $cc);

        if ($lead->stage !== 'negotiation') {
            $msg = 'Lead must be at negotiation stage to close.';
            return $request->ajax()
                ? response()->json(['success' => false, 'message' => $msg], 422)
                : back()->withErrors($msg);
        }

        $request->validate([
            'deal_value' => 'required|numeric|min:1',
            'deal_notes' => 'nullable|string|max:2000',
        ]);

        DB::transaction(function () use ($lead, $request, $cc) {
            $lead->deal_value  = $request->deal_value;
            $lead->deal_notes  = $request->deal_notes;
            $lead->save();
            $lead->transitionTo('deal_closed', "Deal closed at ₹{$request->deal_value}.", $cc);
        });

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Deal closed successfully.', 'stage' => 'deal_closed']);
        }

        return redirect()->route('cc.leads.index')->with('success', 'Deal closed successfully.');
    }

    // ──────────────────────────────────────────────────────────────────────
    // Side-state actions (mirrors SE panel)
    // ──────────────────────────────────────────────────────────────────────

    /** POST /cc/leads/{lead}/hold */
    public function hold(Request $request, Lead $lead)
    {
        $cc = $this->authorise();
        $this->authoriseLead($lead, $cc);
        $request->validate(['reason' => 'nullable|string|max:500', 'hold_until' => 'nullable|date|after:today']);
        $until = $request->filled('hold_until') ? new \DateTime($request->hold_until) : null;
        $lead->putOnHold($request->reason, $until, $cc);

        return $request->ajax()
            ? response()->json(['success' => true, 'message' => 'Lead placed on hold.'])
            : back()->with('success', 'Lead placed on hold.');
    }

    /** POST /cc/leads/{lead}/resume */
    public function resume(Request $request, Lead $lead)
    {
        $cc = $this->authorise();
        $this->authoriseLead($lead, $cc);
        $lead->resumeFromHold($cc);

        return $request->ajax()
            ? response()->json(['success' => true, 'message' => 'Lead resumed.'])
            : back()->with('success', 'Lead resumed from hold.');
    }

    /** POST /cc/leads/{lead}/defer */
    public function defer(Request $request, Lead $lead)
    {
        $cc = $this->authorise();
        $this->authoriseLead($lead, $cc);
        $request->validate(['defer_until' => 'required|date|after:now', 'reason' => 'nullable|string|max:500']);
        $lead->deferFollowUp(new \DateTime($request->defer_until), $request->reason, $cc);

        return $request->ajax()
            ? response()->json(['success' => true, 'message' => 'Follow-up deferred.'])
            : back()->with('success', 'Follow-up deferred.');
    }

    /** POST /cc/leads/{lead}/lost */
    public function markLost(Request $request, Lead $lead)
    {
        $cc = $this->authorise();
        $this->authoriseLead($lead, $cc);
        $request->validate(['reason' => 'required|string|max:500']);
        $lead->markLost($request->reason, $cc);

        return $request->ajax()
            ? response()->json(['success' => true, 'message' => 'Lead marked as lost.'])
            : redirect()->route('cc.leads.index')->with('success', 'Lead marked as lost.');
    }
}

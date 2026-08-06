<?php

namespace App\Http\Controllers;

use App\Helpers\WorkingHours;
use App\Models\Lead;
use App\Models\Property;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/**
 * Panel 1 — Sales Executive
 *
 * Routes prefix : /se/leads
 * Middleware    : auth (role check enforced in each method)
 *
 * Stages owned  : new_lead → contacted → interest_confirmed
 * Actions       : log contact, qualify, share property options, submit handover note → escalate to CC
 */
class SalesExecutiveLeadController extends Controller
{
    // ──────────────────────────────────────────────────────────────────────
    // Middleware / gate helper
    // ──────────────────────────────────────────────────────────────────────

    private function authorise(): User
    {
        /** @var User $user */
        $user = Auth::user();

        if (!$user || $user->role !== 'sales_executive') {
            abort(403, 'Access restricted to Sales Executives.');
        }

        return $user;
    }

    private function authoriseLead(Lead $lead, User $se): void
    {
        if ((int) $lead->assigned_se_id !== $se->id) {
            abort(403, 'This lead is not assigned to you.');
        }

        if (!in_array($lead->stage, Lead::SE_STAGES, true)) {
            abort(403, 'This lead has already been escalated to the CC panel.');
        }
    }

    // ──────────────────────────────────────────────────────────────────────
    // Index — dashboard
    // ──────────────────────────────────────────────────────────────────────

    /**
     * GET /se/leads
     */
    public function index(Request $request)
    {
        $se     = $this->authorise();
        $query  = Lead::forSE($se->id)
            ->whereIn('stage', Lead::SE_STAGES)
            ->with(['property', 'assignedCC'])
            ->latest();

        // Filters
        if ($stage = $request->query('stage')) {
            $query->where('stage', $stage);
        }
        if ($sideState = $request->query('side_state')) {
            $query->where('side_state', $sideState);
        } else {
            // Default: show active + held (exclude lost)
            $query->where(function ($q) {
                $q->whereNull('side_state')->orWhere('side_state', '!=', 'lost');
            });
        }

        $leads = $query->paginate(25)->withQueryString();

        $stats = [
            'total'    => Lead::forSE($se->id)->whereIn('stage', Lead::SE_STAGES)->count(),
            'active'   => Lead::forSE($se->id)->whereIn('stage', Lead::SE_STAGES)->whereNull('side_state')->count(),
            'on_hold'  => Lead::forSE($se->id)->where('side_state', 'on_hold')->count(),
            'sla_breached' => Lead::forSE($se->id)->where('sla_contact_breached', true)->count(),
        ];

        if ($request->ajax()) {
            return response()->json([
                'html'  => view('se.leads._table', compact('leads'))->render(),
                'links' => $leads->links()->toHtml(),
            ]);
        }

        return view('se.leads.index', compact('leads', 'stats', 'se'));
    }

    // ──────────────────────────────────────────────────────────────────────
    // Show — detail panel
    // ──────────────────────────────────────────────────────────────────────

    /**
     * GET /se/leads/{lead}
     */
    public function show(Lead $lead)
    {
        $se = $this->authorise();
        $this->authoriseLead($lead, $se);

        $lead->load(['property', 'stageHistories.changedBy', 'assignedCC']);
        $propertySnapshot = $lead->publicPropertySnapshot();
        $history          = $lead->stageHistories;

        return view('se.leads.show', compact('lead', 'se', 'propertySnapshot', 'history'));
    }

    // ──────────────────────────────────────────────────────────────────────
    // Log a contact attempt
    // ──────────────────────────────────────────────────────────────────────

    /**
     * POST /se/leads/{lead}/log-contact
     */
    public function logContact(Request $request, Lead $lead)
    {
        $se = $this->authorise();
        $this->authoriseLead($lead, $se);

        $request->validate([
            'notes' => 'nullable|string|max:2000',
        ]);

        DB::transaction(function () use ($lead, $request, $se) {
            $attempts = $lead->contact_attempts + 1;
            $lead->contact_attempts  = $attempts;
            $lead->last_contacted_at = now();

            if ($request->filled('notes')) {
                $existing = $lead->qualification_notes ?? '';
                $lead->qualification_notes = $existing
                    ? $existing . "\n[" . now()->toDateTimeString() . '] ' . $request->notes
                    : '[' . now()->toDateTimeString() . '] ' . $request->notes;
            }

            // Auto-advance stage on first contact
            if ($lead->stage === 'new_lead' && $attempts === 1) {
                $lead->save();
                $lead->transitionTo('contacted', 'First contact logged.', $se);
            } else {
                $lead->save();
            }
        });

        if ($request->ajax()) {
            return response()->json([
                'success'           => true,
                'message'           => 'Contact attempt logged.',
                'contact_attempts'  => $lead->fresh()->contact_attempts,
                'stage'             => $lead->fresh()->stage,
            ]);
        }

        return back()->with('success', 'Contact attempt logged.');
    }

    // ──────────────────────────────────────────────────────────────────────
    // Save qualification notes
    // ──────────────────────────────────────────────────────────────────────

    /**
     * POST /se/leads/{lead}/qualify
     */
    public function qualify(Request $request, Lead $lead)
    {
        $se = $this->authorise();
        $this->authoriseLead($lead, $se);

        $request->validate([
            'qualification_notes' => 'required|string|max:5000',
            'advance_stage'       => 'nullable|boolean',
        ]);

        DB::transaction(function () use ($lead, $request, $se) {
            $lead->qualification_notes = $request->qualification_notes;
            $lead->save();

            if ($request->boolean('advance_stage') && $lead->canTransitionTo('interest_confirmed')) {
                $lead->transitionTo('interest_confirmed', 'Interest confirmed after qualification.', $se);
            }
        });

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Qualification saved.', 'stage' => $lead->fresh()->stage]);
        }

        return back()->with('success', 'Qualification saved.');
    }

    // ──────────────────────────────────────────────────────────────────────
    // Share property options
    // ──────────────────────────────────────────────────────────────────────

    /**
     * POST /se/leads/{lead}/share-options
     * Body: property_ids[] (array of property IDs visible to SE's division)
     */
    public function shareOptions(Request $request, Lead $lead)
    {
        $se = $this->authorise();
        $this->authoriseLead($lead, $se);

        $request->validate([
            'property_ids'   => 'required|array|min:1|max:10',
            'property_ids.*' => 'integer|exists:properties,id',
        ]);

        // Build info-gated snapshots
        $properties = Property::whereIn('id', $request->property_ids)
            ->where('is_active', true)
            ->get();

        $snapshots = $properties->mapWithKeys(function ($p) use ($lead) {
            return [$p->id => $lead->publicPropertySnapshot($p)];
        });

        $lead->update([
            'options_shared_property_ids' => array_values($request->property_ids),
            'options_shared_at'           => now(),
        ]);

        if ($request->ajax()) {
            return response()->json([
                'success'   => true,
                'message'   => count($properties) . ' option(s) shared with lead.',
                'snapshots' => $snapshots,
            ]);
        }

        return back()->with('success', count($properties) . ' option(s) shared.');
    }

    // ──────────────────────────────────────────────────────────────────────
    // Submit handover note & escalate to CC
    // ──────────────────────────────────────────────────────────────────────

    /**
     * POST /se/leads/{lead}/handover
     */
    public function handover(Request $request, Lead $lead)
    {
        $se = $this->authorise();
        $this->authoriseLead($lead, $se);

        if ($lead->stage !== 'interest_confirmed') {
            $msg = 'Lead must be at interest_confirmed stage before handover.';
            return $request->ajax()
                ? response()->json(['success' => false, 'message' => $msg], 422)
                : back()->withErrors($msg);
        }

        $request->validate([
            'handover_note' => 'required|string|min:20|max:5000',
        ]);

        DB::transaction(function () use ($lead, $request, $se) {
            $lead->handover_note          = $request->handover_note;
            $lead->handover_completed_at  = now();
            $lead->save();

            // Attempt CC auto-assignment
            $lead->assignBestCC();

            // Transition stage — hard gate passes because handover fields are now set
            $lead->transitionTo('escalated_to_cc', 'Handover to CC: ' . $request->handover_note, $se);
        });

        if ($request->ajax()) {
            return response()->json([
                'success'      => true,
                'message'      => 'Lead escalated to Chief Coordinator.',
                'assigned_cc'  => $lead->fresh()->assignedCC?->name ?? 'Holding queue',
            ]);
        }

        return redirect()->route('se.leads.index')->with('success', 'Lead escalated to Chief Coordinator.');
    }

    // ──────────────────────────────────────────────────────────────────────
    // Side-state actions
    // ──────────────────────────────────────────────────────────────────────

    /**
     * POST /se/leads/{lead}/hold
     */
    public function hold(Request $request, Lead $lead)
    {
        $se = $this->authorise();
        $this->authoriseLead($lead, $se);

        $request->validate([
            'reason'     => 'nullable|string|max:500',
            'hold_until' => 'nullable|date|after:today',
        ]);

        $until = $request->filled('hold_until') ? new \DateTime($request->hold_until) : null;
        $lead->putOnHold($request->reason, $until, $se);

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Lead placed on hold.']);
        }
        return back()->with('success', 'Lead placed on hold.');
    }

    /**
     * POST /se/leads/{lead}/resume
     */
    public function resume(Request $request, Lead $lead)
    {
        $se = $this->authorise();
        $this->authoriseLead($lead, $se);
        $lead->resumeFromHold($se);

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Lead resumed.']);
        }
        return back()->with('success', 'Lead resumed from hold.');
    }

    /**
     * POST /se/leads/{lead}/defer
     */
    public function defer(Request $request, Lead $lead)
    {
        $se = $this->authorise();
        $this->authoriseLead($lead, $se);

        $request->validate([
            'defer_until' => 'required|date|after:now',
            'reason'      => 'nullable|string|max:500',
        ]);

        $lead->deferFollowUp(new \DateTime($request->defer_until), $request->reason, $se);

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Follow-up deferred.']);
        }
        return back()->with('success', 'Follow-up deferred.');
    }

    /**
     * POST /se/leads/{lead}/lost
     */
    public function markLost(Request $request, Lead $lead)
    {
        $se = $this->authorise();
        $this->authoriseLead($lead, $se);

        $request->validate(['reason' => 'required|string|max:500']);
        $lead->markLost($request->reason, $se);

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Lead marked as lost.']);
        }
        return redirect()->route('se.leads.index')->with('success', 'Lead marked as lost.');
    }
}

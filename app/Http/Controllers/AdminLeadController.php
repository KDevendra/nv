<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/**
 * Admin lead management — cross-division view, holding queue assignment,
 * stage overrides, and division review queue.
 *
 * Routes prefix : /admin/leads
 * Middleware    : auth + permission (existing admin permission middleware)
 */
class AdminLeadController extends Controller
{
    // ──────────────────────────────────────────────────────────────────────
    // Gate helper
    // ──────────────────────────────────────────────────────────────────────

    private function authorise(): User
    {
        /** @var User $user */
        $user = Auth::user();

        if (!$user || !in_array($user->role, ['super_admin', 'admin'], true)) {
            abort(403, 'Admin access required.');
        }

        return $user;
    }

    // ──────────────────────────────────────────────────────────────────────
    // Index — cross-division list
    // ──────────────────────────────────────────────────────────────────────

    /**
     * GET /admin/leads
     */
    public function index(Request $request)
    {
        $this->authorise();

        $query = Lead::with(['assignedSE', 'assignedCC', 'feasibilitySH', 'property'])
            ->withTrashed();

        // Filters
        if ($division = $request->query('division')) {
            $query->where('division', $division);
        }
        if ($stage = $request->query('stage')) {
            $query->where('stage', $stage);
        }
        if ($sideState = $request->query('side_state')) {
            $query->where('side_state', $sideState);
        }
        if ($request->boolean('needs_review')) {
            $query->needsReview();
        }
        if ($request->boolean('holding_queue')) {
            $query->holdingQueue();
        }
        if ($search = $request->query('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $leads = $query->latest()->paginate(30)->withQueryString();

        $stats = [
            'total'         => Lead::count(),
            'active'        => Lead::whereNull('side_state')->count(),
            'holding_queue' => Lead::holdingQueue()->count(),
            'needs_review'  => Lead::needsReview()->count(),
            'deal_closed'   => Lead::where('stage', 'deal_closed')->count(),
            'lost'          => Lead::where('side_state', 'lost')->count(),
        ];

        // Data for assignment dropdowns
        $salesExecs    = User::where('role', 'sales_executive')->where('is_active', true)->get(['id', 'name', 'division']);
        $chiefCoords   = User::where('role', 'chief_coordinator')->where('is_active', true)->get(['id', 'name', 'division']);

        if ($request->ajax()) {
            return response()->json([
                'html'  => view('admin.leads._table', compact('leads'))->render(),
                'links' => $leads->links()->toHtml(),
            ]);
        }

        return view('admin.leads.index', compact('leads', 'stats', 'salesExecs', 'chiefCoords'));
    }

    // ──────────────────────────────────────────────────────────────────────
    // Show — detail with full history
    // ──────────────────────────────────────────────────────────────────────

    /**
     * GET /admin/leads/{lead}
     */
    public function show(Lead $lead)
    {
        $this->authorise();

        $lead->load([
            'property',
            'assignedSE',
            'assignedCC',
            'feasibilitySH',
            'stageHistories.changedBy',
        ]);

        $salesExecs  = User::where('role', 'sales_executive')->where('division', $lead->division)->where('is_active', true)->get();
        $chiefCoords = User::where('role', 'chief_coordinator')->where('division', $lead->division)->where('is_active', true)->get();
        $supplyHeads = User::where('role', 'supply_head')->where('division', $lead->division)->where('is_active', true)->get();
        $history     = $lead->stageHistories;

        return view('admin.leads.show', compact('lead', 'salesExecs', 'chiefCoords', 'supplyHeads', 'history'));
    }

    // ──────────────────────────────────────────────────────────────────────
    // Assign from holding queue
    // ──────────────────────────────────────────────────────────────────────

    /**
     * POST /admin/leads/{lead}/assign-cc
     */
    public function assignCC(Request $request, Lead $lead)
    {
        $admin = $this->authorise();

        $request->validate([
            'cc_id' => 'required|exists:users,id',
        ]);

        $cc = User::where('id', $request->cc_id)
            ->where('role', 'chief_coordinator')
            ->where('division', $lead->division)
            ->firstOrFail();

        if ($cc->activeCCLeadCount() >= Lead::CC_MAX_ACTIVE_LEADS) {
            $msg = "CC {$cc->name} is already at the {$cc->activeCCLeadCount()}-lead cap.";
            return $request->ajax()
                ? response()->json(['success' => false, 'message' => $msg], 422)
                : back()->withErrors($msg);
        }

        $lead->update([
            'assigned_cc_id'        => $cc->id,
            'cc_load_at_assignment' => $cc->activeCCLeadCount(),
            'cc_assigned_at'        => now(),
        ]);

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => "Lead assigned to CC {$cc->name}."]);
        }

        return back()->with('success', "Lead assigned to CC {$cc->name}.");
    }

    /**
     * POST /admin/leads/{lead}/assign-se
     */
    public function assignSE(Request $request, Lead $lead)
    {
        $this->authorise();

        $request->validate(['se_id' => 'required|exists:users,id']);

        $se = User::where('id', $request->se_id)
            ->where('role', 'sales_executive')
            ->where('division', $lead->division)
            ->firstOrFail();

        $lead->update([
            'assigned_se_id' => $se->id,
            'se_assigned_at' => now(),
        ]);

        // Set SE contact SLA (4 working hours)
        $due = \App\Helpers\WorkingHours::addWorkingHours(null, 4);
        $lead->update(['sla_contact_due_at' => $due, 'sla_contact_breached' => false]);

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => "Lead assigned to SE {$se->name}."]);
        }

        return back()->with('success', "Lead assigned to SE {$se->name}.");
    }

    // ──────────────────────────────────────────────────────────────────────
    // Stage override (admin only)
    // ──────────────────────────────────────────────────────────────────────

    /**
     * POST /admin/leads/{lead}/override-stage
     */
    public function overrideStage(Request $request, Lead $lead)
    {
        $admin = $this->authorise();

        $request->validate([
            'stage'  => 'required|in:' . implode(',', Lead::STAGES),
            'reason' => 'required|string|max:1000',
        ]);

        $fromStage  = $lead->stage;
        $lead->stage = $request->stage;
        $lead->save();

        \App\Models\LeadStageHistory::create([
            'lead_id'            => $lead->id,
            'from_stage'         => $fromStage,
            'to_stage'           => $request->stage,
            'note'               => '[ADMIN OVERRIDE] ' . $request->reason,
            'changed_by_user_id' => $admin->id,
        ]);

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Stage overridden by admin.', 'stage' => $request->stage]);
        }

        return back()->with('success', 'Stage overridden.');
    }

    // ──────────────────────────────────────────────────────────────────────
    // Division review — resolve flagged leads
    // ──────────────────────────────────────────────────────────────────────

    /**
     * POST /admin/leads/{lead}/resolve-division
     */
    public function resolveDivision(Request $request, Lead $lead)
    {
        $this->authorise();

        $request->validate([
            'division' => 'required|in:warehousing,residential,commercial',
        ]);

        // Unique index check: ensure (phone, new_division) doesn't already exist
        $conflict = Lead::where('phone', $lead->phone)
            ->where('division', $request->division)
            ->where('id', '!=', $lead->id)
            ->withTrashed()
            ->exists();

        if ($conflict) {
            $msg = "A lead with this phone already exists in the '{$request->division}' division.";
            return $request->ajax()
                ? response()->json(['success' => false, 'message' => $msg], 422)
                : back()->withErrors($msg);
        }

        $lead->update([
            'division'              => $request->division,
            'needs_division_review' => false,
        ]);

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Division resolved.']);
        }

        return back()->with('success', 'Division resolved.');
    }

    // ──────────────────────────────────────────────────────────────────────
    // Side-state override
    // ──────────────────────────────────────────────────────────────────────

    /** POST /admin/leads/{lead}/hold */
    public function hold(Request $request, Lead $lead)
    {
        $admin = $this->authorise();
        $request->validate(['reason' => 'nullable|string|max:500', 'hold_until' => 'nullable|date|after:today']);
        $until = $request->filled('hold_until') ? new \DateTime($request->hold_until) : null;
        $lead->putOnHold($request->reason, $until, $admin);

        return $request->ajax()
            ? response()->json(['success' => true, 'message' => 'Lead placed on hold.'])
            : back()->with('success', 'Lead placed on hold.');
    }

    /** POST /admin/leads/{lead}/resume */
    public function resume(Request $request, Lead $lead)
    {
        $admin = $this->authorise();
        $lead->resumeFromHold($admin);

        return $request->ajax()
            ? response()->json(['success' => true, 'message' => 'Lead resumed.'])
            : back()->with('success', 'Lead resumed.');
    }

    /** POST /admin/leads/{lead}/lost */
    public function markLost(Request $request, Lead $lead)
    {
        $admin = $this->authorise();
        $request->validate(['reason' => 'required|string|max:500']);
        $lead->markLost($request->reason, $admin);

        return $request->ajax()
            ? response()->json(['success' => true, 'message' => 'Lead marked as lost.'])
            : back()->with('success', 'Lead marked as lost.');
    }

    /** DELETE /admin/leads/{lead} */
    public function destroy(Request $request, Lead $lead)
    {
        $this->authorise();
        $lead->delete(); // soft delete

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Lead soft-deleted.']);
        }

        return redirect()->route('admin.leads.index')->with('success', 'Lead deleted.');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Panel 3 — Supply Head (relay / feasibility response)
 *
 * Routes prefix : /sh/leads
 * Middleware    : auth (role check enforced in each method)
 *
 * The SH sees only feasibility requests for their own division.
 * They respond with feasible / not_feasible / conditional + notes.
 * SH does NOT advance pipeline stages directly — CC does that on receipt.
 */
class SupplyHeadLeadController extends Controller
{
    // ──────────────────────────────────────────────────────────────────────
    // Gate helpers
    // ──────────────────────────────────────────────────────────────────────

    private function authorise(): User
    {
        /** @var User $user */
        $user = Auth::user();

        if (!$user || $user->role !== 'supply_head') {
            abort(403, 'Access restricted to Supply Heads.');
        }

        return $user;
    }

    private function authoriseLead(Lead $lead, User $sh): void
    {
        if ((int) $lead->feasibility_sh_id !== $sh->id) {
            abort(403, 'This feasibility request is not assigned to you.');
        }
    }

    // ──────────────────────────────────────────────────────────────────────
    // Index — feasibility queue
    // ──────────────────────────────────────────────────────────────────────

    /**
     * GET /sh/leads
     */
    public function index(Request $request)
    {
        $sh    = $this->authorise();
        $query = Lead::where('feasibility_sh_id', $sh->id)
            ->where('division', $sh->division)
            ->with(['assignedCC', 'assignedSE'])
            ->latest('feasibility_requested_at');

        // Filter by feasibility status
        $statusFilter = $request->query('status', 'pending');
        if ($statusFilter !== 'all') {
            $query->where('feasibility_status', $statusFilter);
        }

        $leads = $query->paginate(20)->withQueryString();

        $stats = [
            'pending'       => Lead::where('feasibility_sh_id', $sh->id)->where('feasibility_status', 'pending')->count(),
            'responded'     => Lead::where('feasibility_sh_id', $sh->id)->whereNotIn('feasibility_status', ['pending', null])->count(),
            'sla_breached'  => Lead::where('feasibility_sh_id', $sh->id)->where('sla_feasibility_breached', true)->count(),
        ];

        if ($request->ajax()) {
            return response()->json([
                'html'  => view('sh.leads._table', compact('leads'))->render(),
                'links' => $leads->links()->toHtml(),
            ]);
        }

        return view('sh.leads.index', compact('leads', 'stats', 'sh'));
    }

    // ──────────────────────────────────────────────────────────────────────
    // Show — feasibility detail
    // ──────────────────────────────────────────────────────────────────────

    /**
     * GET /sh/leads/{lead}
     */
    public function show(Lead $lead)
    {
        $sh = $this->authorise();
        $this->authoriseLead($lead, $sh);

        $lead->load(['assignedCC', 'assignedSE', 'stageHistories.changedBy']);

        // SH gets a property snapshot — info-gated (no owner/GPS)
        $propertySnapshot = $lead->publicPropertySnapshot();
        $history          = $lead->stageHistories;

        return view('sh.leads.show', compact('lead', 'sh', 'propertySnapshot', 'history'));
    }

    // ──────────────────────────────────────────────────────────────────────
    // Submit feasibility response
    // ──────────────────────────────────────────────────────────────────────

    /**
     * POST /sh/leads/{lead}/respond
     */
    public function respond(Request $request, Lead $lead)
    {
        $sh = $this->authorise();
        $this->authoriseLead($lead, $sh);

        if ($lead->feasibility_status !== 'pending') {
            $msg = 'This feasibility request has already been responded to.';
            return $request->ajax()
                ? response()->json(['success' => false, 'message' => $msg], 422)
                : back()->withErrors($msg);
        }

        $request->validate([
            'feasibility_status' => 'required|in:feasible,not_feasible,conditional',
            'feasibility_notes'  => 'required|string|min:10|max:5000',
        ]);

        $lead->update([
            'feasibility_status'          => $request->feasibility_status,
            'feasibility_notes'           => $request->feasibility_notes,
            'feasibility_responded_at'    => now(),
            // SLA is resolved once response arrives — clear breach flag
            'sla_feasibility_breached'    => false,
        ]);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Feasibility response submitted.',
                'status'  => $request->feasibility_status,
            ]);
        }

        return redirect()->route('sh.leads.index')->with('success', 'Feasibility response submitted.');
    }
}

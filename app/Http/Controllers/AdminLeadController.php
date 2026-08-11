<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use App\Models\User;
use Illuminate\Http\Request;

class AdminLeadController extends Controller
{
    /**
     * Cross-division listing of all leads, holding queue, and overrides.
     */
    public function index(Request $request)
    {
        $query = Lead::with([
            'property:id,title,slug,price,city_id,location_id',
            'property.city:id,name',
            'property.location:id,name',
            'assignedSE:id,name,division',
            'assignedCC:id,name,division',
            'feasibilitySH:id,name,division'
        ]);

        // Filter by division
        if ($request->filled('division')) {
            $query->where('division', $request->division);
        }

        // Filter by stage
        if ($request->filled('stage')) {
            $query->where('stage', $request->stage);
        }

        // Filter holding queue (unassigned CCs on escalation)
        if ($request->boolean('holding_queue')) {
            $query->holdingQueue();
        }

        // Search by name/phone/email
        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('name', 'LIKE', "%{$s}%")
                  ->orWhere('phone', 'LIKE', "%{$s}%")
                  ->orWhere('email', 'LIKE', "%{$s}%");
            });
        }

        $stats = [
            'total'         => Lead::count(),
            'active'        => Lead::whereNull('side_state')->count(),
            'holding_queue' => Lead::holdingQueue()->count(),
            'needs_review'  => 0,
            'deal_closed'   => Lead::where('stage', 'deal_closed')->count(),
            'lost'          => Lead::where('side_state', 'lost')->count(),
        ];

        $leads = $query->orderBy('updated_at', 'desc')->paginate(20);

        // Fetch SEs and CCs grouped by division for manual reassignment dropdowns
        $salesExecutives = User::where('role', 'sales_executive')->where('is_active', true)->get();
        $chiefCoordinators = User::where('role', 'chief_coordinator')->where('is_active', true)->get();

        $holdingQueueCount = Lead::holdingQueue()->count();

        return view('admin.leads.index', compact('leads', 'salesExecutives', 'chiefCoordinators', 'holdingQueueCount', 'stats'));
    }

    /**
     * Show detailed lead information (Admin sees full details including property owner info).
     */
    public function show(Lead $lead)
    {
        $lead->load(['property', 'property.user', 'assignedSE', 'assignedCC', 'feasibilitySH', 'stageHistories.changedBy']);
        
        $salesExecs = User::where('role', 'sales_executive')->where('division', $lead->division)->where('is_active', true)->get();
        $chiefCoords = User::where('role', 'chief_coordinator')->where('division', $lead->division)->where('is_active', true)->get();
        $history = $lead->stageHistories;

        return view('admin.leads.show', compact('lead', 'salesExecs', 'chiefCoords', 'history'));
    }

    /**
     * Admin override reassign SE/CC or force stage change.
     */
    public function update(Request $request, Lead $lead)
    {
        if ($request->filled('assigned_se_id')) {
            $se = User::where('id', $request->assigned_se_id)->where('role', 'sales_executive')->first();
            if ($se) {
                $lead->assigned_se_id = $se->id;
            }
        }

        if ($request->filled('assigned_cc_id')) {
            $cc = User::where('id', $request->assigned_cc_id)->where('role', 'chief_coordinator')->first();
            if ($cc) {
                $lead->assigned_cc_id = $cc->id;
                $lead->cc_load_at_assignment = $cc->active_cc_lead_count;
            }
        }

        if ($request->filled('override_stage') && $request->override_stage !== $lead->stage) {
            $newStage = $request->override_stage;
            if (in_array($newStage, Lead::STAGES, true)) {
                $fromStage = $lead->stage;
                $lead->stage = $newStage;
                
                \App\Models\LeadStageHistory::create([
                    'lead_id'            => $lead->id,
                    'from_stage'         => $fromStage,
                    'to_stage'           => $newStage,
                    'changed_by_user_id' => auth()->id(),
                    'note'               => 'Admin Stage Override: ' . ($request->override_reason ?? 'Manual Admin adjustment'),
                ]);
            }
        }

        $lead->save();

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Lead updated by Admin successfully.',
                'lead'    => $lead->fresh(['assignedSE', 'assignedCC'])
            ]);
        }

        return back()->with('success', 'Lead updated by Admin successfully.');
    }

    /**
     * Admin assign / reassign Chief Coordinator.
     */
    public function assignCC(Request $request, Lead $lead)
    {
        $ccId = $request->input('cc_id') ?? $request->input('assigned_cc_id');
        
        $request->merge(['cc_id' => $ccId]);
        $request->validate([
            'cc_id' => 'required|exists:users,id',
        ]);

        $cc = User::where('id', $ccId)->where('role', 'chief_coordinator')->firstOrFail();

        $lead->assigned_cc_id = $cc->id;
        $lead->cc_load_at_assignment = $cc->activeCCLeadCount();
        
        // If lead stage is prior to escalated_to_cc, advance stage to escalated_to_cc
        if (Lead::stageIndex($lead->stage) < Lead::stageIndex('escalated_to_cc')) {
            $lead->stage = 'escalated_to_cc';
            if (empty($lead->handover_note)) {
                $lead->handover_note = 'Assigned to Chief Coordinator by Admin.';
                $lead->handover_completed_at = now();
            }
        }
        
        $lead->save();

        \App\Models\LeadStageHistory::create([
            'lead_id'            => $lead->id,
            'from_stage'         => $lead->stage,
            'to_stage'           => $lead->stage,
            'changed_by_user_id' => auth()->id(),
            'note'               => "Admin assigned Chief Coordinator: {$cc->name}",
        ]);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => "Chief Coordinator {$cc->name} assigned successfully.",
                'lead'    => $lead->fresh(['assignedCC'])
            ]);
        }

        return back()->with('success', "Chief Coordinator {$cc->name} assigned successfully.");
    }

    /**
     * Admin assign / reassign Sales Executive.
     */
    public function assignSE(Request $request, Lead $lead)
    {
        $seId = $request->input('se_id') ?? $request->input('assigned_se_id');
        
        $request->merge(['se_id' => $seId]);
        $request->validate([
            'se_id' => 'required|exists:users,id',
        ]);

        $se = User::where('id', $seId)->where('role', 'sales_executive')->firstOrFail();

        $lead->assigned_se_id = $se->id;
        $lead->save();

        \App\Models\LeadStageHistory::create([
            'lead_id'            => $lead->id,
            'from_stage'         => $lead->stage,
            'to_stage'           => $lead->stage,
            'changed_by_user_id' => auth()->id(),
            'note'               => "Admin assigned Sales Executive: {$se->name}",
        ]);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => "Sales Executive {$se->name} assigned successfully.",
                'lead'    => $lead->fresh(['assignedSE'])
            ]);
        }

        return back()->with('success', "Sales Executive {$se->name} assigned successfully.");
    }

    /**
     * Admin force stage override.
     */
    public function overrideStage(Request $request, Lead $lead)
    {
        $newStage = $request->input('stage') ?? $request->input('override_stage');
        $reason = $request->input('reason') ?? $request->input('override_reason') ?? 'Manual Admin Stage Override';

        if (!in_array($newStage, Lead::STAGES, true)) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Invalid stage.'], 422);
            }
            return back()->with('error', 'Invalid stage specified.');
        }

        $fromStage = $lead->stage;
        $lead->stage = $newStage;
        $lead->save();

        \App\Models\LeadStageHistory::create([
            'lead_id'            => $lead->id,
            'from_stage'         => $fromStage,
            'to_stage'           => $newStage,
            'changed_by_user_id' => auth()->id(),
            'note'               => "Admin Stage Override: {$reason}",
        ]);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => "Lead stage changed to '{$newStage}' successfully.",
                'lead'    => $lead->fresh()
            ]);
        }

        return back()->with('success', "Lead stage changed to '{$newStage}' successfully.");
    }

    /**
     * Admin change / resolve lead division.
     */
    public function resolveDivision(Request $request, Lead $lead)
    {
        $request->validate([
            'division' => 'required|in:warehousing,residential,commercial',
        ]);

        $oldDivision = $lead->division;
        $newDivision = $request->division;

        if ($oldDivision !== $newDivision) {
            $lead->division = $newDivision;

            // If assigned SE division doesn't match new division, unassign SE
            if ($lead->assignedSE && $lead->assignedSE->division !== $newDivision) {
                $lead->assigned_se_id = null;
            }

            // If assigned CC division doesn't match new division, unassign CC
            if ($lead->assignedCC && $lead->assignedCC->division !== $newDivision) {
                $lead->assigned_cc_id = null;
                $lead->cc_load_at_assignment = null;
            }

            $lead->save();

            \App\Models\LeadStageHistory::create([
                'lead_id'            => $lead->id,
                'from_stage'         => $lead->stage,
                'to_stage'           => $lead->stage,
                'changed_by_user_id' => auth()->id(),
                'note'               => "Admin changed division from '" . ucfirst((string)$oldDivision) . "' to '" . ucfirst($newDivision) . "'",
            ]);
        }

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => "Lead division changed to '" . ucfirst($newDivision) . "' successfully.",
                'lead'    => $lead->fresh(['assignedSE', 'assignedCC'])
            ]);
        }

        return back()->with('success', "Lead division changed to '" . ucfirst($newDivision) . "' successfully.");
    }

    /**
     * Admin put lead on hold.
     */
    public function hold(Request $request, Lead $lead)
    {
        $request->validate([
            'reason' => 'required|string|max:500',
        ]);

        $lead->putOnHold($request->reason, $request->expected_resume_date);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Lead placed on hold.',
                'lead'    => $lead->fresh()
            ]);
        }

        return back()->with('success', 'Lead placed on hold.');
    }

    /**
     * Admin resume lead from hold.
     */
    public function resume(Request $request, Lead $lead)
    {
        $lead->resumeFromHold();

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Lead resumed from hold.',
                'lead'    => $lead->fresh()
            ]);
        }

        return back()->with('success', 'Lead resumed from hold.');
    }

    /**
     * Admin mark lead as lost.
     */
    public function markLost(Request $request, Lead $lead)
    {
        $request->validate([
            'reason' => 'required|string',
        ]);

        $lead->markLost($request->reason, $request->reason_other);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Lead marked as lost.',
                'lead'    => $lead->fresh()
            ]);
        }

        return back()->with('success', 'Lead marked as lost.');
    }

    /**
     * Admin soft delete lead.
     */
    public function destroy(Request $request, Lead $lead)
    {
        $lead->delete();

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Lead deleted successfully.'
            ]);
        }

        return redirect()->route('admin.leads.index')->with('success', 'Lead deleted successfully.');
    }
}

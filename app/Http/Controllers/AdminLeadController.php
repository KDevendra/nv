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
}

<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SupplyHeadLeadController extends Controller
{
    /**
     * Display listing of feasibility check requests assigned to this SH for their division.
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        // Division scoping query filter
        $query = Lead::where('division', $user->division)
            ->where('feasibility_sh_id', $user->id)
            ->whereNotNull('feasibility_raised_at')
            ->with(['property' => function ($q) {
                $q->select('id', 'title', 'slug', 'price', 'carpet_area', 'built_up_area', 'address', 'latitude', 'longitude', 'user_id', 'city_id', 'location_id')
                  ->with('user:id,name,phone,email');
            }, 'assignedCC:id,name']);

        $stats = [
            'pending'      => (clone $query)->whereNull('feasibility_responded_at')->count(),
            'responded'    => (clone $query)->whereNotNull('feasibility_responded_at')->count(),
            'sla_breached' => (clone $query)->whereNull('feasibility_responded_at')->where('feasibility_raised_at', '<=', now()->subHours(24))->count(),
        ];

        $pendingCount = $stats['pending'];
        $leads = $query->orderBy('feasibility_raised_at', 'desc')->paginate(15);

        return view('sh.leads.index', compact('leads', 'pendingCount', 'stats'));
    }

    /**
     * Display single feasibility check request for response.
     */
    public function show(Lead $lead)
    {
        $user = Auth::user();

        if ($lead->division !== $user->division || $lead->feasibility_sh_id !== $user->id) {
            abort(403, 'Unauthorized access to this feasibility request.');
        }

        $lead->load(['stageHistories.changedBy', 'assignedCC']);
        $propertySnapshot = Lead::publicPropertySnapshot($lead->property);
        $history = $lead->stageHistories;

        return view('sh.leads.show', compact('lead', 'propertySnapshot', 'history'));
    }

    /**
     * Respond to a feasibility check request (relay response only).
     */
    public function respond(Request $request, Lead $lead)
    {
        $user = Auth::user();

        if ($lead->division !== $user->division || $lead->feasibility_sh_id !== $user->id) {
            abort(403, 'Unauthorized access to this feasibility request.');
        }

        $request->validate([
            'feasibility_status' => 'required|in:feasible,not_feasible,conditional',
            'feasibility_notes'  => 'required|string|max:2000',
        ]);

        $lead->update([
            'feasibility_status'       => $request->feasibility_status,
            'feasibility_notes'        => $request->feasibility_notes,
            'feasibility_responded_at' => now(),
        ]);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Feasibility check response recorded successfully.',
                'lead'    => $lead->fresh()
            ]);
        }

        return back()->with('success', 'Feasibility check response recorded successfully.');
    }
}

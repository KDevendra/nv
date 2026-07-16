<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\PropertyInquiry;
use Illuminate\Http\Request;

class UserDashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }

    /**
     * Display user dashboard
     */
    public function index()
    {
        $user = auth()->user();
        
        // Get user's inquiries statistics
        $totalInquiries = PropertyInquiry::where('user_id', $user->id)->count();
        $pendingInquiries = PropertyInquiry::where('user_id', $user->id)
            ->where('status', 'pending')
            ->count();
        $contactedInquiries = PropertyInquiry::where('user_id', $user->id)
            ->where('status', 'contacted')
            ->count();
        
        // Get recent inquiries
        $recentInquiries = PropertyInquiry::where('user_id', $user->id)
            ->with(['property', 'propertyEntry'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
        
        return view('user.dashboard', compact(
            'totalInquiries',
            'pendingInquiries',
            'contactedInquiries',
            'recentInquiries'
        ));
    }

    /**
     * Display user's inquiries list
     */
    public function inquiries(Request $request)
    {
        $user = auth()->user();
        
        $query = PropertyInquiry::where('user_id', $user->id)
            ->with(['property', 'propertyEntry']);
        
        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        // Filter by inquiry type
        if ($request->filled('type')) {
            $query->where('inquiry_type', $request->type);
        }
        
        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('message', 'like', "%{$search}%");
            });
        }
        
        $inquiries = $query->orderBy('created_at', 'desc')->paginate(15);
        
        return view('user.inquiries.index', compact('inquiries'));
    }

    /**
     * Display single inquiry details
     */
    public function showInquiry(PropertyInquiry $inquiry)
    {
        // Ensure user can only view their own inquiries
        if ($inquiry->user_id !== auth()->id()) {
            abort(403, 'Unauthorized access');
        }
        
        $inquiry->load(['property', 'propertyEntry']);
        
        return view('user.inquiries.show', compact('inquiry'));
    }

    /**
     * Display user profile
     */
    public function profile()
    {
        $user = auth()->user();
        return view('user.profile', compact('user'));
    }

    /**
     * Update user profile
     */
    public function updateProfile(Request $request)
    {
        $user = auth()->user();
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
        ]);
        
        $user->update($validated);
        
        return back()->with('success', 'Profile updated successfully!');
    }
}

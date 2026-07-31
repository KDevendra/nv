<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\PropertyInquiry;
use App\Models\PropertyWishlist;
use App\Models\Property;
use App\Models\PropertyEntry;
use Illuminate\Http\Request;

class UserDashboardController extends Controller
{

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
        
        // Get wishlist count
        $wishlistCount = PropertyWishlist::where('user_id', $user->id)->count();
        
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
            'wishlistCount',
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

    /**
     * Display user's wishlist
     */
    public function wishlist()
    {
        $user = auth()->user();
        
        $wishlists = PropertyWishlist::where('user_id', $user->id)
            ->with([
                'property.mainImage',
                'property.city',
                'property.location',
                'property.bhk',
                'property.projectStatus',
                'propertyEntry.photos',
            ])
            ->orderBy('created_at', 'desc')
            ->paginate(12);
        
        return view('user.wishlist', compact('wishlists'));
    }

    /**
     * Toggle wishlist item (add or remove)
     */
    public function toggleWishlist(Request $request)
    {
        $validated = $request->validate([
            'property_id' => 'nullable|exists:properties,id',
            'property_entry_code' => 'nullable|string|max:50',
        ]);
        
        $user = auth()->user();
        
        // Check if item exists in wishlist (including soft-deleted)
        $wishlist = PropertyWishlist::withTrashed()
            ->where('user_id', $user->id)
            ->where(function($query) use ($validated) {
                if (!empty($validated['property_id'])) {
                    $query->where('property_id', $validated['property_id']);
                }
                if (!empty($validated['property_entry_code'])) {
                    $query->where('property_entry_code', $validated['property_entry_code']);
                }
            })
            ->first();
        
        if ($wishlist) {
            if ($wishlist->trashed()) {
                // Restore soft deleted wishlist item
                $wishlist->restore();
                
                return response()->json([
                    'success' => true,
                    'added' => true,
                    'action' => 'added',
                    'message' => 'Added to wishlist'
                ]);
            } else {
                // Soft delete wishlist item
                $wishlist->delete();
                
                return response()->json([
                    'success' => true,
                    'added' => false,
                    'action' => 'removed',
                    'message' => 'Removed from wishlist'
                ]);
            }
        } else {
            // Add to wishlist
            PropertyWishlist::create([
                'user_id' => $user->id,
                'property_id' => $validated['property_id'] ?? null,
                'property_entry_code' => $validated['property_entry_code'] ?? null,
            ]);
            
            return response()->json([
                'success' => true,
                'added' => true,
                'action' => 'added',
                'message' => 'Added to wishlist'
            ]);
        }
    }
}

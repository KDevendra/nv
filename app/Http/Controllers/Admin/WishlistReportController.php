<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PropertyWishlist;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class WishlistReportController extends Controller
{
    public function index(Request $request): View
    {
        // Unfiltered summary counts (including soft-deleted history)
        $summary = [
            'total_saved'              => PropertyWishlist::withTrashed()->count(),
            'active_saved'             => PropertyWishlist::count(),
            'removed_saved'            => PropertyWishlist::onlyTrashed()->count(),
            'unique_users'             => PropertyWishlist::withTrashed()->distinct('user_id')->count('user_id'),
            'regular_properties_count' => PropertyWishlist::whereNotNull('property_id')->count(),
            'property_entries_count'   => PropertyWishlist::whereNotNull('property_entry_code')->count(),
        ];

        // Top Most Saved Property Entries
        $topSavedEntries = PropertyWishlist::withTrashed()
            ->whereNotNull('property_entry_code')
            ->select('property_entry_code', DB::raw('count(*) as count'))
            ->groupBy('property_entry_code')
            ->orderByDesc('count')
            ->limit(5)
            ->with(['propertyEntry'])
            ->get();

        // Top Most Saved Regular Properties
        $topSavedProperties = PropertyWishlist::withTrashed()
            ->whereNotNull('property_id')
            ->select('property_id', DB::raw('count(*) as count'))
            ->groupBy('property_id')
            ->orderByDesc('count')
            ->limit(5)
            ->with(['property.city', 'property.location'])
            ->get();

        // Query builder for Wishlist items
        $query = PropertyWishlist::with([
            'user',
            'property.city',
            'property.location',
            'property.mainImage',
            'propertyEntry.photos',
        ]);

        // Trashed / Soft deleted status filter - default to ALL items (withTrashed)
        if ($request->filled('status')) {
            if ($request->status === 'active') {
                // only active items
            } elseif ($request->status === 'removed') {
                $query->onlyTrashed();
            } else {
                $query->withTrashed();
            }
        } else {
            $query->withTrashed();
        }

        // Search Filter
        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->whereHas('user', function ($u) use ($search) {
                    $u->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%")
                      ->orWhere('phone', 'like', "%{$search}%");
                })
                ->orWhereHas('property', function ($p) use ($search) {
                    $p->where('title', 'like', "%{$search}%");
                })
                ->orWhereHas('propertyEntry', function ($pe) use ($search) {
                    $pe->where('code', 'like', "%{$search}%")
                       ->orWhere('property_name', 'like', "%{$search}%")
                       ->orWhere('nearest_city', 'like', "%{$search}%");
                })
                ->orWhere('property_entry_code', 'like', "%{$search}%");
            });
        }

        // Item Type Filter
        if ($request->filled('type')) {
            if ($request->type === 'property') {
                $query->whereNotNull('property_id');
            } elseif ($request->type === 'entry') {
                $query->whereNotNull('property_entry_code');
            }
        }

        // Date Range Filter
        if ($request->filled('from_date')) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }
        if ($request->filled('to_date')) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }

        $query->latest();

        $wishlists = $query->paginate(20)->appends($request->query());

        return view('admin.wishlist-report.index', compact(
            'summary',
            'wishlists',
            'topSavedEntries',
            'topSavedProperties'
        ));
    }
}

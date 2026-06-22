<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Area;
use App\Models\Region;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Str;

class AreaController extends Controller
{
    /**
     * Display a listing of areas.
     */
    public function index(Request $request): View
    {
        $query = Area::with('region')->orderBy('sort_order', 'asc');

        // Filter by region
        if ($request->filled('region_id')) {
            $query->where('region_id', $request->region_id);
        }

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhereHas('region', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  });
            });
        }

        $areas = $query->paginate(15);
        $regions = Region::active()->ordered()->get();

        return view('admin.areas.index', compact('areas', 'regions'));
    }

    /**
     * Show the form for creating a new area.
     */
    public function create(): View
    {
        $regions = Region::active()->ordered()->get();
        return view('admin.areas.create', compact('regions'));
    }

    /**
     * Store a newly created area in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'region_id' => 'required|exists:regions,id',
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:areas,slug',
            'description' => 'nullable|string',
            'status' => 'boolean',
            'sort_order' => 'required|integer|min:0',
        ]);

        $data = $request->all();
        
        // Auto-generate slug if not provided
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($request->name);
        }
        
        $data['status'] = $request->has('status');

        Area::create($data);

        return redirect()->route('admin.areas.index')
            ->with('success', 'Area created successfully.');
    }

    /**
     * Display the specified area.
     */
    public function show(Area $area): View
    {
        $area->load('region');
        $area->loadCount('users');
        return view('admin.areas.show', compact('area'));
    }

    /**
     * Show the form for editing the specified area.
     */
    public function edit(Area $area): View
    {
        $regions = Region::active()->ordered()->get();
        return view('admin.areas.edit', compact('area', 'regions'));
    }

    /**
     * Update the specified area in storage.
     */
    public function update(Request $request, Area $area): RedirectResponse
    {
        $request->validate([
            'region_id' => 'required|exists:regions,id',
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:areas,slug,' . $area->id,
            'description' => 'nullable|string',
            'status' => 'boolean',
            'sort_order' => 'required|integer|min:0',
        ]);

        $data = $request->all();
        
        // Auto-generate slug if not provided
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($request->name);
        }
        
        $data['status'] = $request->has('status');

        $area->update($data);

        return redirect()->route('admin.areas.index')
            ->with('success', 'Area updated successfully.');
    }

    /**
     * Remove the specified area from storage.
     */
    public function destroy(Area $area): RedirectResponse
    {
        // Check if area has users
        if ($area->users()->exists()) {
            return redirect()->route('admin.areas.index')
                ->with('error', 'Cannot delete area that has users assigned. Please reassign users first.');
        }

        $area->delete();

        return redirect()->route('admin.areas.index')
            ->with('success', 'Area deleted successfully.');
    }

    /**
     * Toggle area status.
     */
    public function toggleStatus(Area $area)
    {
        $area->update(['status' => !$area->status]);

        return response()->json([
            'success' => true,
            'status' => $area->status,
            'message' => 'Area status updated successfully.'
        ]);
    }

    /**
     * Get areas by region (AJAX endpoint).
     */
    public function getByRegion(Request $request)
    {
        $areas = Area::where('region_id', $request->region_id)
                     ->active()
                     ->ordered()
                     ->get(['id', 'name']);

        return response()->json($areas);
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Region;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Str;

class RegionController extends Controller
{
    /**
     * Display a listing of regions.
     */
    public function index(): View
    {
        $regions = Region::withCount('areas')->orderBy('sort_order', 'asc')->paginate(15);
        return view('admin.regions.index', compact('regions'));
    }

    /**
     * Show the form for creating a new region.
     */
    public function create(): View
    {
        return view('admin.regions.create');
    }

    /**
     * Store a newly created region in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:regions,name',
            'slug' => 'nullable|string|max:255|unique:regions,slug',
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

        Region::create($data);

        return redirect()->route('admin.regions.index')
            ->with('success', 'Region created successfully.');
    }

    /**
     * Display the specified region.
     */
    public function show(Region $region): View
    {
        $region->loadCount('areas', 'users');
        return view('admin.regions.show', compact('region'));
    }

    /**
     * Show the form for editing the specified region.
     */
    public function edit(Region $region): View
    {
        return view('admin.regions.edit', compact('region'));
    }

    /**
     * Update the specified region in storage.
     */
    public function update(Request $request, Region $region): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:regions,name,' . $region->id,
            'slug' => 'nullable|string|max:255|unique:regions,slug,' . $region->id,
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

        $region->update($data);

        return redirect()->route('admin.regions.index')
            ->with('success', 'Region updated successfully.');
    }

    /**
     * Remove the specified region from storage.
     */
    public function destroy(Region $region): RedirectResponse
    {
        // Check if region has areas or users
        if ($region->areas()->exists()) {
            return redirect()->route('admin.regions.index')
                ->with('error', 'Cannot delete region that has areas assigned. Please delete or reassign areas first.');
        }

        if ($region->users()->exists()) {
            return redirect()->route('admin.regions.index')
                ->with('error', 'Cannot delete region that has users assigned. Please reassign users first.');
        }

        $region->delete();

        return redirect()->route('admin.regions.index')
            ->with('success', 'Region deleted successfully.');
    }

    /**
     * Toggle region status.
     */
    public function toggleStatus(Region $region)
    {
        $region->update(['status' => !$region->status]);

        return response()->json([
            'success' => true,
            'status' => $region->status,
            'message' => 'Region status updated successfully.'
        ]);
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Zone;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Str;

class ZoneController extends Controller
{
    /**
     * Display a listing of zones.
     */
    public function index(): View
    {
        $zones = Zone::withCount(['fieldOfficers', 'supplyHeads', 'propertyEntries'])
            ->orderBy('sort_order', 'asc')
            ->paginate(15);

        return view('admin.zones.index', compact('zones'));
    }

    /**
     * Show the form for creating a new zone.
     */
    public function create(): View
    {
        return view('admin.zones.create');
    }

    /**
     * Store a newly created zone in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:zones,name',
            'slug' => 'nullable|string|max:255|unique:zones,slug',
            'description' => 'nullable|string',
            'status' => 'boolean',
            'sort_order' => 'required|integer|min:0',
        ]);

        $data = $request->only(['name', 'slug', 'description', 'sort_order']);

        // Auto-generate slug if not provided
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($request->name);
        }

        $data['status'] = $request->has('status');

        Zone::create($data);

        return redirect()->route('admin.zones.index')
            ->with('success', 'Zone created successfully.');
    }

    /**
     * Show the form for editing the specified zone.
     */
    public function edit(Zone $zone): View
    {
        return view('admin.zones.edit', compact('zone'));
    }

    /**
     * Update the specified zone in storage.
     */
    public function update(Request $request, Zone $zone): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:zones,name,' . $zone->id,
            'slug' => 'nullable|string|max:255|unique:zones,slug,' . $zone->id,
            'description' => 'nullable|string',
            'status' => 'boolean',
            'sort_order' => 'required|integer|min:0',
        ]);

        $data = $request->only(['name', 'slug', 'description', 'sort_order']);

        // Auto-generate slug if not provided
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($request->name);
        }

        $data['status'] = $request->has('status');

        $zone->update($data);

        return redirect()->route('admin.zones.index')
            ->with('success', 'Zone updated successfully.');
    }

    /**
     * Remove the specified zone from storage.
     */
    public function destroy(Zone $zone): RedirectResponse
    {
        if ($zone->users()->exists() || $zone->supplyHeads()->exists()) {
            return redirect()->route('admin.zones.index')
                ->with('error', 'Cannot delete zone that has users assigned. Please reassign those users first.');
        }

        if ($zone->propertyEntries()->exists()) {
            return redirect()->route('admin.zones.index')
                ->with('error', 'Cannot delete zone that already has property entries.');
        }

        $zone->delete();

        return redirect()->route('admin.zones.index')
            ->with('success', 'Zone deleted successfully.');
    }

    /**
     * Toggle zone status.
     */
    public function toggleStatus(Zone $zone)
    {
        $zone->update(['status' => !$zone->status]);

        return response()->json([
            'success' => true,
            'status' => $zone->status,
            'message' => 'Zone status updated successfully.'
        ]);
    }
}

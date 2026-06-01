<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Helpers\ImageHelper;
use App\Models\City;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CityController extends Controller
{
    public function index()
    {
        $cities = City::orderBy('sort_order', 'asc')->paginate(10);
        return view('admin.cities.index', compact('cities'));
    }

    public function create()
    {
        return view('admin.cities.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'image_alt' => 'nullable|string|max:255',
            'property_count' => 'required|integer|min:0',
            'link' => 'nullable|string|max:255',
            'status' => 'boolean',
            'sort_order' => 'required|integer|min:0',
        ]);

        $data = $request->all();
        $data['slug'] = Str::slug($request->name);
        $data['status'] = $request->has('status');

        if ($request->hasFile('image')) {
            $data['image'] = ImageHelper::storeWebp(
                $request->file('image'),
                $request->name,
                0,
                'city',
                'cities'
            );
        }

        City::create($data);

        return redirect()->route('admin.cities.index')
            ->with('success', 'City created successfully.');
    }

    public function show(City $city)
    {
        return view('admin.cities.show', compact('city'));
    }

    public function edit(City $city)
    {
        return view('admin.cities.edit', compact('city'));
    }

    public function update(Request $request, City $city)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'image_alt' => 'nullable|string|max:255',
            'property_count' => 'required|integer|min:0',
            'link' => 'nullable|string|max:255',
            'status' => 'boolean',
            'sort_order' => 'required|integer|min:0',
        ]);

        $data = $request->all();
        $data['slug'] = Str::slug($request->name);
        $data['status'] = $request->has('status');

        if ($request->hasFile('image')) {
            // Delete old image
            if ($city->image && Str::startsWith($city->image, 'uploads/')) {
                $oldPath = public_path($city->image);
                if (file_exists($oldPath)) {
                    @unlink($oldPath);
                }
            }

            $data['image'] = ImageHelper::storeWebp(
                $request->file('image'),
                $request->name,
                $city->id,
                'city',
                'cities'
            );
        }

        $city->update($data);

        return redirect()->route('admin.cities.index')
            ->with('success', 'City updated successfully.');
    }

    public function destroy(City $city)
    {
        if ($city->image && Str::startsWith($city->image, 'uploads/')) {
            $path = public_path($city->image);
            if (file_exists($path)) {
                @unlink($path);
            }
        }

        $city->delete();

        return redirect()->route('admin.cities.index')
            ->with('success', 'City deleted successfully.');
    }

    public function toggleStatus(City $city)
    {
        $city->update(['status' => !$city->status]);

        return response()->json([
            'success' => true,
            'status' => $city->status,
            'message' => 'City status updated successfully.'
        ]);
    }
}

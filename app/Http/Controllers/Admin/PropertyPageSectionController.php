<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Helpers\ImageHelper;
use App\Models\PropertyPageSection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PropertyPageSectionController extends Controller
{
    public function index()
    {
        $sections = PropertyPageSection::orderBy('order', 'asc')->get();
        
        // Create default sections if they don't exist
        $this->ensureDefaultSections();
        
        return view('admin.property-page-sections.index', compact('sections'));
    }

    public function create()
    {
        // Redirect to index since we only edit existing sections
        return redirect()->route('admin.property-page-sections.index')
            ->with('info', 'Please edit one of the existing sections.');
    }

    public function store(Request $request)
    {
        // Redirect to index since we only edit existing sections
        return redirect()->route('admin.property-page-sections.index')
            ->with('info', 'Please edit one of the existing sections.');
    }

    private function ensureDefaultSections()
    {
        $defaultSections = [
            [
                'section_key' => 'carousel_section',
                'title' => 'Homes Designed for Comfort, Space & Lifestyle',
                'subtitle' => 'Residential Living',
                'description' => 'Every residence listed above is selected with a clear focus on location, design quality, and everyday livability. Whether you are looking for a modern apartment or a spacious independent home, the right options are curated to match different lifestyle needs.',
                'button_text' => 'Get Expert Advice',
                'button_link' => '#enquiry',
                'secondary_button_text' => 'View More Projects',
                'secondary_button_link' => '#projects',
                'is_active' => true,
                'order' => 1,
            ],
            [
                'section_key' => 'perspective_section',
                'title' => 'Choose the Right Home with Clear, Practical Insights',
                'subtitle' => 'Residential Properties',
                'description' => 'Explore residential projects with a simple view of location, connectivity, amenities, safety, and long-term comfort. Use these quick pointers to compare options faster and shortlist the best fit for your family.',
                'button_text' => 'Get Project List',
                'button_link' => '#enquiry',
                'secondary_button_text' => 'Request a Call',
                'secondary_button_link' => '#contact',
                'features' => [
                    '<strong>Connectivity:</strong> Main roads, expressways, office hubs & public transport access.',
                    '<strong>Project Amenities:</strong> Clubhouse, gym, kids play, green spaces, parking & power backup.',
                    '<strong>Floor Plan Fit:</strong> Layout flow, ventilation, balcony use, storage & usable carpet area.',
                ],
                'is_active' => true,
                'order' => 2,
            ],
        ];

        foreach ($defaultSections as $section) {
            PropertyPageSection::firstOrCreate(
                ['section_key' => $section['section_key']],
                $section
            );
        }
    }

    public function edit(PropertyPageSection $propertyPageSection)
    {
        return view('admin.property-page-sections.edit', compact('propertyPageSection'));
    }

    public function update(Request $request, PropertyPageSection $propertyPageSection)
    {
        $validated = $request->validate([
            'title'                  => 'nullable|string|max:255',
            'subtitle'               => 'nullable|string|max:255',
            'kicker'                 => 'nullable|string|max:255',
            'description'            => 'nullable|string',
            'button_text'            => 'nullable|string|max:255',
            'button_link'            => 'nullable|string|max:255',
            'secondary_button_text'  => 'nullable|string|max:255',
            'secondary_button_link'  => 'nullable|string|max:255',
            'images.*'               => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'features'               => 'nullable|array',
            'features.*'             => 'nullable|string',
            'badges'                 => 'nullable|array',
            'badges.*'               => 'nullable|string',
            'is_active'              => 'boolean',
            'order'                  => 'nullable|integer',
        ]);

        // Append new uploads into the dedicated images table
        if ($request->hasFile('images')) {
            $currentCount = $propertyPageSection->sectionImages()->count();
            foreach ($request->file('images') as $index => $image) {
                $path = ImageHelper::storeWebp(
                    $image,
                    $propertyPageSection->section_key,
                    $propertyPageSection->id,
                    'img-' . ($currentCount + $index + 1) . '-' . time(),
                    'property-page-sections'
                );
                $propertyPageSection->sectionImages()->create([
                    'image_path' => $path,
                    'alt_tag'    => null,
                    'sort_order' => $currentCount + $index,
                ]);
            }
        }

        $validated['features']  = array_values(array_filter($request->input('features', [])));
        $validated['badges']    = array_values(array_filter($request->input('badges', [])));
        $validated['is_active'] = $request->boolean('is_active');
        unset($validated['images']);

        $propertyPageSection->update($validated);

        return redirect()->route('admin.property-page-sections.index')
            ->with('success', 'Property page section updated successfully.');
    }

    public function destroy(PropertyPageSection $propertyPageSection)
    {
        return redirect()->route('admin.property-page-sections.index')
            ->with('error', 'Sections cannot be deleted. You can deactivate them instead.');
    }

    public function toggleStatus(PropertyPageSection $propertyPageSection)
    {
        $propertyPageSection->update([
            'is_active' => !$propertyPageSection->is_active
        ]);

        return back()->with('success', 'Status updated successfully.');
    }

    public function deleteImage(Request $request, PropertyPageSection $propertyPageSection)
    {
        // Support deletion by image ID (new) or by index (legacy)
        $imageId = $request->input('image_id');
        $imageIndex = $request->input('image_index');

        if ($imageId) {
            $image = $propertyPageSection->sectionImages()->find($imageId);
            if ($image) {
                $filePath = public_path($image->image_path);
                if (file_exists($filePath)) {
                    @unlink($filePath);
                }
                $image->delete();
            }
        } elseif ($imageIndex !== null) {
            $image = $propertyPageSection->sectionImages()->skip((int) $imageIndex)->first();
            if ($image) {
                $filePath = public_path($image->image_path);
                if (file_exists($filePath)) {
                    @unlink($filePath);
                }
                $image->delete();
            }
        }

        return back()->with('success', 'Image deleted successfully.');
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Helpers\ImageHelper;
use App\Models\PropertyType;
use App\Models\ServiceType;
use App\Models\PropertyPageSection;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class PropertyTypeController extends Controller
{
    public function index(): View
    {
        $propertyTypes = PropertyType::withCount(['serviceTypes', 'bhks'])->orderBy('sort_order', 'asc')->paginate(10);
        return view('admin.property-types.index', compact('propertyTypes'));
    }

    public function create(): View
    {
        $serviceTypes = ServiceType::active()->ordered()->get();
        $bhks = \App\Models\Bhk::active()->ordered()->get();
        return view('admin.property-types.create', compact('serviceTypes', 'bhks'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            // 'category' => 'required|in:residential,commercial',
            'description' => 'nullable|string',
            'icon' => 'nullable|string|max:255',
            'status' => 'boolean',
            'show_in_header' => 'boolean',
            'sort_order' => 'integer|min:0',
            'service_types' => 'nullable|array',
            'service_types.*' => 'exists:service_types,id',
            'bhks' => 'nullable|array',
            'bhks.*' => 'exists:bhks,id',
            
            // Carousel section fields
            'carousel_title' => 'nullable|string|max:255',
            'carousel_subtitle' => 'nullable|string|max:255',
            'carousel_description' => 'nullable|string',
            'carousel_button_text' => 'nullable|string|max:100',
            'carousel_button_link' => 'nullable|string|max:255',
            'carousel_secondary_button_text' => 'nullable|string|max:100',
            'carousel_secondary_button_link' => 'nullable|string|max:255',
            'carousel_images.*' => 'nullable|image|mimes:jpeg,jpg,png,webp|max:2048',
            
            // Perspective section fields
            'perspective_title' => 'nullable|string|max:255',
            'perspective_subtitle' => 'nullable|string|max:255',
            'perspective_description' => 'nullable|string',
            'perspective_button_text' => 'nullable|string|max:100',
            'perspective_button_link' => 'nullable|string|max:255',
            'perspective_secondary_button_text' => 'nullable|string|max:100',
            'perspective_secondary_button_link' => 'nullable|string|max:255',
            'perspective_images.*' => 'nullable|image|mimes:jpeg,jpg,png,webp|max:2048',
            'perspective_features' => 'nullable|array',
            
            // Intro section fields
            'intro_kicker' => 'nullable|string|max:255',
            'intro_title' => 'nullable|string|max:255',
            'intro_description' => 'nullable|string',
            'intro_badges' => 'nullable|array',
        ]);

        // Auto-generate slug from name
        $validated['slug'] = Str::slug($validated['name']);

        $propertyType = PropertyType::create($validated);

        // Sync service types
        if ($request->has('service_types')) {
            $propertyType->serviceTypes()->sync($request->service_types);
        }

        // Sync BHKs
        if ($request->has('bhks')) {
            $propertyType->bhks()->sync($request->bhks);
        }

        // Handle carousel section
        $this->handleSection($propertyType, $request, 'carousel');
        
        // Handle perspective section
        $this->handleSection($propertyType, $request, 'perspective');
        
        // Handle intro section
        $this->handleIntroSection($propertyType, $request);

        return redirect()->route('admin.property-types.index')
            ->with('success', 'Property Type created successfully.');
    }

    public function show(PropertyType $propertyType): View
    {
        $propertyType->load([
            'serviceTypes',
            'introSection',
            'carouselSection.sectionImages',
            'perspectiveSection.sectionImages',
        ]);
        return view('admin.property-types.show', compact('propertyType'));
    }

    public function edit(PropertyType $propertyType): View
    {
        $serviceTypes = ServiceType::active()->ordered()->get();
        $bhks = \App\Models\Bhk::active()->ordered()->get();
        $propertyType->load([
            'serviceTypes',
            'bhks',
            'carouselSection.sectionImages',
            'perspectiveSection.sectionImages',
            'introSection',
        ]);
        return view('admin.property-types.edit', compact('propertyType', 'serviceTypes', 'bhks'));
    }

    public function update(Request $request, PropertyType $propertyType): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            // 'category' => 'required|in:residential,commercial',
            'description' => 'nullable|string',
            'icon' => 'nullable|string|max:255',
            'status' => 'boolean',
            'show_in_header' => 'boolean',
            'sort_order' => 'integer|min:0',
            'service_types' => 'nullable|array',
            'service_types.*' => 'exists:service_types,id',
            'bhks' => 'nullable|array',
            'bhks.*' => 'exists:bhks,id',
            
            // Carousel section fields
            'carousel_title' => 'nullable|string|max:255',
            'carousel_subtitle' => 'nullable|string|max:255',
            'carousel_description' => 'nullable|string',
            'carousel_button_text' => 'nullable|string|max:100',
            'carousel_button_link' => 'nullable|string|max:255',
            'carousel_secondary_button_text' => 'nullable|string|max:100',
            'carousel_secondary_button_link' => 'nullable|string|max:255',
            'carousel_images.*' => 'nullable|image|mimes:jpeg,jpg,png,webp|max:2048',
            'carousel_existing_images' => 'nullable|array',
            
            // Perspective section fields
            'perspective_title' => 'nullable|string|max:255',
            'perspective_subtitle' => 'nullable|string|max:255',
            'perspective_description' => 'nullable|string',
            'perspective_button_text' => 'nullable|string|max:100',
            'perspective_button_link' => 'nullable|string|max:255',
            'perspective_secondary_button_text' => 'nullable|string|max:100',
            'perspective_secondary_button_link' => 'nullable|string|max:255',
            'perspective_images.*' => 'nullable|image|mimes:jpeg,jpg,png,webp|max:2048',
            'perspective_existing_images' => 'nullable|array',
            'perspective_features' => 'nullable|array',
            
            // Intro section fields
            'intro_kicker' => 'nullable|string|max:255',
            'intro_title' => 'nullable|string|max:255',
            'intro_description' => 'nullable|string',
            'intro_badges' => 'nullable|array',
        ]);

        // Auto-generate slug from name
        $validated['slug'] = Str::slug($validated['name']);

        $propertyType->update($validated);

        // Sync service types
        $propertyType->serviceTypes()->sync($request->service_types ?? []);

        // Sync BHKs
        $propertyType->bhks()->sync($request->bhks ?? []);

        // Handle carousel section
        $this->handleSection($propertyType, $request, 'carousel', true);
        
        // Handle perspective section
        $this->handleSection($propertyType, $request, 'perspective', true);
        
        // Handle intro section
        $this->handleIntroSection($propertyType, $request, true);

        return redirect()->route('admin.property-types.index')
            ->with('success', 'Property Type updated successfully.');
    }

    public function destroy(PropertyType $propertyType): RedirectResponse
    {
        $propertyType->serviceTypes()->detach();
        $propertyType->bhks()->detach();
        $propertyType->delete();

        return redirect()->route('admin.property-types.index')
            ->with('success', 'Property Type deleted successfully.');
    }

    public function toggleStatus(PropertyType $propertyType): RedirectResponse
    {
        $propertyType->update(['status' => !$propertyType->status]);

        $status = $propertyType->status ? 'activated' : 'deactivated';
        return redirect()->back()
            ->with('success', "Property Type {$status} successfully.");
    }

    /**
     * Handle section creation/update with image uploads
     */
    private function handleSection(PropertyType $propertyType, Request $request, string $sectionType, bool $isUpdate = false): void
    {
        $prefix    = $sectionType;
        $sectionKey = $sectionType . '_section';

        Log::channel('uploads')->info("=== handleSection [{$sectionKey}] ===", [
            'property_type_id' => $propertyType->id,
            'is_update'        => $isUpdate,
            'has_files'        => $request->hasFile("{$prefix}_images"),
            'file_count'       => $request->hasFile("{$prefix}_images") ? count($request->file("{$prefix}_images")) : 0,
            'existing_images'  => $request->input("{$prefix}_existing_images", []),
            'remove_images'    => $request->input("{$prefix}_remove_images", []),
        ]);

        $hasAnyData = $request->filled("{$prefix}_title")
            || $request->filled("{$prefix}_subtitle")
            || $request->filled("{$prefix}_description")
            || $request->filled("{$prefix}_button_text")
            || $request->filled("{$prefix}_button_link")
            || $request->filled("{$prefix}_secondary_button_text")
            || $request->filled("{$prefix}_secondary_button_link")
            || $request->hasFile("{$prefix}_images");

        if (!$isUpdate && !$hasAnyData) {
            Log::channel('uploads')->info("Skipping {$sectionKey} — no data on create.");
            return;
        }

        $section = $propertyType->propertyPageSections()
            ->where('section_key', $sectionKey)
            ->first();

        // ── Resolve which existing images to keep (with their alt tags) ──────
        $keptImages = []; // [['path' => ..., 'alt' => ...], ...]

        $existingImages = $request->input("{$prefix}_existing_images", []);
        $existingAlts   = $request->input("{$prefix}_existing_images_alt", []);
        $removeImages   = $request->input("{$prefix}_remove_images", []);

        if ($isUpdate && $section) {
            foreach ($section->sectionImages as $img) {
                $storedPath = $img->image_path;

                if (in_array($storedPath, $removeImages)) {
                    // Delete the physical file
                    $filePath = public_path($storedPath);
                    if (file_exists($filePath)) {
                        @unlink($filePath);
                    }
                    // Will be deleted from DB below (not in keptImages)
                } elseif (in_array($storedPath, $existingImages)) {
                    $altIndex = array_search($storedPath, $existingImages);
                    $keptImages[] = [
                        'path' => $storedPath,
                        'alt'  => $existingAlts[$altIndex] ?? $img->alt_tag ?? '',
                    ];
                }
            }
        } elseif ($isUpdate) {
            // Section row doesn't exist yet but hidden inputs may carry paths
            foreach ($existingImages as $idx => $path) {
                $keptImages[] = [
                    'path' => $path,
                    'alt'  => $existingAlts[$idx] ?? '',
                ];
            }
        }

        // ── Upload new images ───────────────────────────────────────────────
        $newImages = [];
        if ($request->hasFile("{$prefix}_images")) {
            $newAlts = $request->input("{$prefix}_images_alt", []);
            foreach ($request->file("{$prefix}_images") as $index => $image) {
                $path = ImageHelper::storeWebp(
                    $image,
                    $propertyType->name,
                    $propertyType->id,
                    "{$prefix}-" . (count($keptImages) + $index + 1) . '-' . time(),
                    'property-page-sections'
                );
                $newImages[] = [
                    'path' => $path,
                    'alt'  => $newAlts[$index] ?? '',
                ];
            }
        }

        // ── Persist section (text fields) ───────────────────────────────────
        $sectionData = [
            'title'                  => $request->input("{$prefix}_title"),
            'subtitle'               => $request->input("{$prefix}_subtitle"),
            'description'            => $request->input("{$prefix}_description"),
            'button_text'            => $request->input("{$prefix}_button_text"),
            'button_link'            => $request->input("{$prefix}_button_link"),
            'secondary_button_text'  => $request->input("{$prefix}_secondary_button_text"),
            'secondary_button_link'  => $request->input("{$prefix}_secondary_button_link"),
            'features'               => $request->input("{$prefix}_features", []),
            'is_active'              => true,
            'order'                  => $sectionType === 'carousel' ? 1 : 2,
        ];

        try {
            $saved = PropertyPageSection::updateOrCreate(
                [
                    'property_type_id' => $propertyType->id,
                    'section_key'      => $sectionKey,
                ],
                $sectionData
            );

            // ── Sync images into the dedicated table ────────────────────────
            // Remove all current image rows, then re-insert in correct order.
            $saved->sectionImages()->delete();

            $allImages = array_merge($keptImages, $newImages);
            $sortOrder = 0;
            foreach ($allImages as $img) {
                $saved->sectionImages()->create([
                    'image_path' => $img['path'],
                    'alt_tag'    => $img['alt'],
                    'sort_order' => $sortOrder++,
                ]);
            }

            Log::channel('uploads')->info("Section SAVED [{$sectionKey}]", [
                'section_id'           => $saved->id,
                'was_recently_created' => $saved->wasRecentlyCreated,
                'images_count'         => count($allImages),
            ]);
        } catch (\Throwable $e) {
            Log::channel('uploads')->error("Failed to SAVE section [{$sectionKey}]", [
                'message' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Handle intro section creation/update
     */
    private function handleIntroSection(PropertyType $propertyType, Request $request, bool $isUpdate = false): void
    {
        $hasAnyData = $request->filled('intro_kicker')
            || $request->filled('intro_title')
            || $request->filled('intro_description')
            || $request->has('intro_badges');

        if (!$isUpdate && !$hasAnyData) {
            return;
        }

        // Find existing section (both on create and update)
        $section = $propertyType->propertyPageSections()
            ->where('section_key', 'intro_section')
            ->first();

        $sectionData = [
            'property_type_id' => $propertyType->id,
            'section_key'      => 'intro_section',
            'kicker'           => $request->input('intro_kicker'),
            'title'            => $request->input('intro_title'),
            'description'      => $request->input('intro_description'),
            'badges'           => array_filter($request->input('intro_badges', [])),
            'is_active'        => true,
            'order'            => 0,
        ];

        if ($section) {
            $section->update($sectionData);
        } else {
            PropertyPageSection::create($sectionData);
        }
    }
}

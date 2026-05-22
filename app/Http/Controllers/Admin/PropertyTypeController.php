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
        $propertyType->load(['serviceTypes', 'introSection', 'carouselSection', 'perspectiveSection']);
        return view('admin.property-types.show', compact('propertyType'));
    }

    public function edit(PropertyType $propertyType): View
    {
        $serviceTypes = ServiceType::active()->ordered()->get();
        $bhks = \App\Models\Bhk::active()->ordered()->get();
        $propertyType->load(['serviceTypes', 'bhks', 'carouselSection', 'perspectiveSection', 'introSection']);
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

        // Always process on update so text-only edits are saved.
        // On create, skip if absolutely nothing was provided.
        $hasAnyData = $request->filled("{$prefix}_title")
            || $request->filled("{$prefix}_subtitle")
            || $request->filled("{$prefix}_description")
            || $request->filled("{$prefix}_button_text")
            || $request->filled("{$prefix}_button_link")
            || $request->filled("{$prefix}_secondary_button_text")
            || $request->filled("{$prefix}_secondary_button_link")
            || $request->hasFile("{$prefix}_images");

        if (!$isUpdate && !$hasAnyData) {
            return;
        }

        // Find existing section (both on create and update, to avoid duplicate-key errors)
        $section = $propertyType->propertyPageSections()
            ->where('section_key', $sectionKey)
            ->first();

        // ── Image handling ──────────────────────────────────────────────────
        $images = [];

        if ($isUpdate && $section && $section->images) {
            // Start from the images already stored in the DB
            $existingImages = $request->input("{$prefix}_existing_images", $section->images);
            $removeImages   = $request->input("{$prefix}_remove_images", []);

            foreach ($section->images as $storedImage) {
                if (in_array($storedImage, $removeImages)) {
                    // User explicitly ticked "remove" — delete from disk
                    $filePath = public_path($storedImage);
                    if (file_exists($filePath)) {
                        unlink($filePath);
                    }
                } elseif (in_array($storedImage, $existingImages)) {
                    // Keep it
                    $images[] = $storedImage;
                }
            }
        } elseif (!$isUpdate) {
            // Fresh create — no existing images to worry about
            $images = [];
        } else {
            // Update but section had no images yet — preserve whatever hidden inputs sent
            $images = $request->input("{$prefix}_existing_images", []);
        }

        // Upload new images
        if ($request->hasFile("{$prefix}_images")) {
            foreach ($request->file("{$prefix}_images") as $index => $image) {
                $path = ImageHelper::storeWebp(
                    $image,
                    $propertyType->name,
                    $propertyType->id,
                    "{$prefix}-" . ($index + 1),
                    'property-page-sections'
                );
                $images[] = $path;
            }
        }

        // ── Persist ─────────────────────────────────────────────────────────
        $sectionData = [
            'property_type_id'       => $propertyType->id,
            'section_key'            => $sectionKey,
            'title'                  => $request->input("{$prefix}_title"),
            'subtitle'               => $request->input("{$prefix}_subtitle"),
            'description'            => $request->input("{$prefix}_description"),
            'button_text'            => $request->input("{$prefix}_button_text"),
            'button_link'            => $request->input("{$prefix}_button_link"),
            'secondary_button_text'  => $request->input("{$prefix}_secondary_button_text"),
            'secondary_button_link'  => $request->input("{$prefix}_secondary_button_link"),
            'images'                 => $images,
            'features'               => $request->input("{$prefix}_features", []),
            'is_active'              => true,
            'order'                  => $sectionType === 'carousel' ? 1 : 2,
        ];

        if ($section) {
            $section->update($sectionData);
        } else {
            PropertyPageSection::create($sectionData);
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

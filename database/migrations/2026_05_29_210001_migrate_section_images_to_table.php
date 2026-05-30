<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $sections = DB::table('property_page_sections')->get();

        foreach ($sections as $section) {
            if (empty($section->images)) {
                continue;
            }

            $images = json_decode($section->images, true);

            if (!is_array($images)) {
                continue;
            }

            $sortOrder = 0;
            foreach ($images as $image) {
                // Handle both old (string) and new ({path, alt}) formats
                if (is_array($image)) {
                    $path = $image['path'] ?? null;
                    $alt  = $image['alt'] ?? null;
                } else {
                    $path = $image;
                    $alt  = null;
                }

                if (empty($path)) {
                    continue;
                }

                // Normalize old paths that lack the "uploads/" prefix
                // Old format: "property-page-sections/xxx.jpg"
                // New format: "uploads/property-page-sections/xxx.webp"
                if (!str_starts_with($path, 'uploads/') && str_starts_with($path, 'property-page-sections/')) {
                    // Keep as-is; the accessor/asset() resolves from public root.
                    // Old files may live in public/property-page-sections/ directly.
                }

                DB::table('property_page_section_images')->insert([
                    'property_page_section_id' => $section->id,
                    'image_path'               => $path,
                    'alt_tag'                  => $alt,
                    'sort_order'               => $sortOrder++,
                    'created_at'               => now(),
                    'updated_at'               => now(),
                ]);
            }
        }
    }

    public function down(): void
    {
        DB::table('property_page_section_images')->truncate();
    }
};

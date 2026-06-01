<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $sections = DB::table('property_page_sections')->get();

        foreach ($sections as $section) {
            // Skip if already has images in the new table
            $existingCount = DB::table('property_page_section_images')
                ->where('property_page_section_id', $section->id)
                ->count();

            if ($existingCount > 0) {
                continue;
            }

            // Check JSON column
            if (empty($section->images)) {
                continue;
            }

            $images = json_decode($section->images, true);
            if (!is_array($images) || empty($images)) {
                continue;
            }

            $sortOrder = 0;
            foreach ($images as $image) {
                if (is_array($image)) {
                    $path = $image['path'] ?? null;
                    $alt = $image['alt'] ?? null;
                } else {
                    $path = $image;
                    $alt = null;
                }

                if (empty($path)) {
                    continue;
                }

                DB::table('property_page_section_images')->insert([
                    'property_page_section_id' => $section->id,
                    'image_path' => $path,
                    'alt_tag' => $alt,
                    'sort_order' => $sortOrder++,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }

    public function down(): void
    {
        // No rollback needed - data stays
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('property_page_sections', function (Blueprint $table) {
            // Check what indexes exist
            $indexes = collect(DB::select("SHOW INDEX FROM property_page_sections"));
            $sectionKeyIndex = $indexes->where('Key_name', 'property_page_sections_section_key_unique')->first();
            $compositeIndex = $indexes->where('Key_name', 'property_type_section_unique')->first();
            
            if ($sectionKeyIndex) {
                // Drop the old unique constraint on section_key
                $table->dropUnique(['section_key']);
            }
            
            // Only add composite unique constraint if it doesn't exist
            if (!$compositeIndex) {
                $table->unique(['property_type_id', 'section_key'], 'property_type_section_unique');
            }
        });
    }

    public function down(): void
    {
        Schema::table('property_page_sections', function (Blueprint $table) {
            // Check what indexes exist
            $indexes = collect(DB::select("SHOW INDEX FROM property_page_sections"));
            $compositeIndex = $indexes->where('Key_name', 'property_type_section_unique')->first();
            
            if ($compositeIndex) {
                // Drop the composite unique constraint
                $table->dropUnique('property_type_section_unique');
            }
            
            // Restore the old unique constraint on section_key
            $table->unique('section_key');
        });
    }
};

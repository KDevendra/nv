<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('property_entries', function (Blueprint $table) {
            $table->float('canopy_length_front')->nullable()->after('canopy_width_front');
            $table->float('canopy_length_left')->nullable()->after('canopy_width_left');
            $table->float('canopy_length_right')->nullable()->after('canopy_width_right');
            $table->float('canopy_length_back')->nullable()->after('canopy_width_back');
            // Also add has_dock_leveller flag
            $table->boolean('has_dock_leveller')->nullable()->after('dock_leveller_back');
        });
    }

    public function down(): void
    {
        Schema::table('property_entries', function (Blueprint $table) {
            $table->dropColumn([
                'canopy_length_front','canopy_length_left',
                'canopy_length_right','canopy_length_back',
                'has_dock_leveller',
            ]);
        });
    }
};

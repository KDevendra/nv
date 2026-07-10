<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('property_entries', function (Blueprint $table) {
            // Single unit applies to all four area fields:
            // plot_area, built_up_area, carpet_area, available_area
            $table->enum('area_unit', ['sq_ft', 'sq_mt', 'sq_yd'])
                  ->default('sq_ft')
                  ->after('available_area');
        });
    }

    public function down(): void
    {
        Schema::table('property_entries', function (Blueprint $table) {
            $table->dropColumn('area_unit');
        });
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('property_entries', function (Blueprint $table) {
            if (!Schema::hasColumn('property_entries', 'zone_id')) {
                // Zone the entry belongs to — copied from the submitting
                // field officer, and used to route the entry to the supply
                // heads assigned to that zone.
                $col = $table->foreignId('zone_id')->nullable();
                if (Schema::hasColumn('property_entries', 'supply_head_id')) {
                    $col->after('supply_head_id');
                }
                $col->constrained('zones')->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('property_entries', function (Blueprint $table) {
            if (Schema::hasColumn('property_entries', 'zone_id')) {
                $table->dropForeign(['zone_id']);
                $table->dropColumn('zone_id');
            }
        });
    }
};

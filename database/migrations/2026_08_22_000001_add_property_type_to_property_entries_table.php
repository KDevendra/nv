<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasColumn('property_entries', 'property_type')) {
            Schema::table('property_entries', function (Blueprint $table) {
                $table->string('property_type')->nullable()->after('facility_type')->index();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('property_entries', 'property_type')) {
            Schema::table('property_entries', function (Blueprint $table) {
                $table->dropIndex(['property_type']);
                $table->dropColumn('property_type');
            });
        }
    }
};

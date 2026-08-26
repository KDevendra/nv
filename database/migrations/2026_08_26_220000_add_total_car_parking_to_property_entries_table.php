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
        if (Schema::hasTable('property_entries')) {
            Schema::table('property_entries', function (Blueprint $table) {
                if (!Schema::hasColumn('property_entries', 'total_car_parking')) {
                    $table->integer('total_car_parking')->nullable();
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('property_entries')) {
            Schema::table('property_entries', function (Blueprint $table) {
                if (Schema::hasColumn('property_entries', 'total_car_parking')) {
                    $table->dropColumn('total_car_parking');
                }
            });
        }
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'region_id')) {
                $table->foreignId('region_id')->nullable()->after('supply_head_id')->constrained('regions')->onDelete('set null');
            }
            if (!Schema::hasColumn('users', 'area_id')) {
                $table->foreignId('area_id')->nullable()->after('region_id')->constrained('areas')->onDelete('set null');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'area_id')) {
                $table->dropForeign(['area_id']);
                $table->dropColumn('area_id');
            }
            if (Schema::hasColumn('users', 'region_id')) {
                $table->dropForeign(['region_id']);
                $table->dropColumn('region_id');
            }
        });
    }
};

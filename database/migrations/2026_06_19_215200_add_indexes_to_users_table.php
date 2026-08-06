<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Collect existing index names so we skip ones already present
        $existingIndexes = collect(
            DB::select("SHOW INDEX FROM users")
        )->pluck('Key_name')->toArray();

        Schema::table('users', function (Blueprint $table) use ($existingIndexes) {
            if (!in_array('users_role_index', $existingIndexes)) {
                $table->index('role');
            }
            if (!in_array('users_is_active_index', $existingIndexes)) {
                $table->index('is_active');
            }
            if (!in_array('users_region_id_index', $existingIndexes)) {
                $table->index('region_id');
            }
            if (!in_array('users_area_id_index', $existingIndexes)) {
                $table->index('area_id');
            }
            if (!in_array('users_created_at_index', $existingIndexes)) {
                $table->index('created_at');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            foreach (['role','is_active','region_id','area_id','created_at'] as $col) {
                try { $table->dropIndex([$col]); } catch (\Throwable) {}
            }
        });
    }
};

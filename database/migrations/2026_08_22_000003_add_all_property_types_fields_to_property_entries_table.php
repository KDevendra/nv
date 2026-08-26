<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        try {
            DB::statement('SET SESSION innodb_strict_mode = 0;');
        } catch (\Throwable $e) {
            // Ignore if database user lacks SYSTEM_VARIABLES_ADMIN / SESSION_VARIABLES_ADMIN privilege on live DB
        }

        Schema::table('property_entries', function (Blueprint $table) {
            if (!Schema::hasColumn('property_entries', 'custom_fields')) {
                $table->longText('custom_fields')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('property_entries', function (Blueprint $table) {
            if (Schema::hasColumn('property_entries', 'custom_fields')) {
                $table->dropColumn('custom_fields');
            }
        });
    }
};

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
        if (!Schema::hasColumn('property_entries', 'deleted_at')) {
            Schema::table('property_entries', function (Blueprint $table) {
                $table->softDeletes();
            });
        }

        if (!Schema::hasColumn('property_wishlists', 'deleted_at')) {
            Schema::table('property_wishlists', function (Blueprint $table) {
                $table->softDeletes();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('property_entries', 'deleted_at')) {
            Schema::table('property_entries', function (Blueprint $table) {
                $table->dropSoftDeletes();
            });
        }

        if (Schema::hasColumn('property_wishlists', 'deleted_at')) {
            Schema::table('property_wishlists', function (Blueprint $table) {
                $table->dropSoftDeletes();
            });
        }
    }
};

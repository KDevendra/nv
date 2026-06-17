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
        Schema::table('property_entries', function (Blueprint $table) {
            if (!Schema::hasColumn('property_entries', 'verified_at')) {
                $table->timestamp('verified_at')->nullable()->after('reviewed_at');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('property_entries', function (Blueprint $table) {
            $table->dropColumn('verified_at');
        });
    }
};

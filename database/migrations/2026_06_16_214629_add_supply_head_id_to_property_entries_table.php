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
            // Add missing columns that weren't in the original table
            if (!Schema::hasColumn('property_entries', 'supply_head_id')) {
                $table->foreignId('supply_head_id')->nullable()->after('field_officer_id')->constrained('users')->onDelete('set null');
            }
            if (!Schema::hasColumn('property_entries', 'supply_head_note')) {
                $table->text('supply_head_note')->nullable()->after('rejection_reason');
            }
            if (!Schema::hasColumn('property_entries', 'reviewed_at')) {
                $table->timestamp('reviewed_at')->nullable()->after('verified_at');
            }
            if (!Schema::hasColumn('property_entries', 'reviewed_by')) {
                $table->foreignId('reviewed_by')->nullable()->after('reviewed_at')->constrained('users')->onDelete('set null');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('property_entries', function (Blueprint $table) {
            $table->dropForeign(['reviewed_by']);
            $table->dropColumn(['supply_head_id', 'supply_head_note', 'reviewed_at', 'reviewed_by']);
        });
    }
};

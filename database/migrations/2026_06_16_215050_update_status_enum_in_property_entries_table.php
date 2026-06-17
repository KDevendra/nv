<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // First, update any existing records that might have invalid status values
        DB::table('property_entries')
            ->where('status', 'not_verified')
            ->update(['status' => 'draft']);

        // Then update the enum to include all the new status values
        DB::statement("ALTER TABLE property_entries MODIFY COLUMN status ENUM('draft', 'submitted', 'verified', 'rejected', 'recheck') DEFAULT 'draft'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert to original enum (if needed)
        DB::statement("ALTER TABLE property_entries MODIFY COLUMN status ENUM('not_verified', 'submitted', 'verified', 'rejected', 'recheck') DEFAULT 'not_verified'");
    }
};

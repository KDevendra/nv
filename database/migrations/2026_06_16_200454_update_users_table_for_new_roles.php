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
        Schema::table('users', function (Blueprint $table) {
            // Update the role enum to include new roles and remove staff
            $table->dropColumn('role');
        });
        
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['super_admin', 'admin', 'supply_head', 'field_officer'])->default('field_officer')->after('email');
            $table->unsignedBigInteger('supply_head_id')->nullable()->after('role');
            
            // Add foreign key constraint
            $table->foreign('supply_head_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['supply_head_id']);
            $table->dropColumn(['supply_head_id']);
            $table->dropColumn('role');
        });
        
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['super_admin', 'admin', 'staff'])->default('staff')->after('email');
        });
    }
};

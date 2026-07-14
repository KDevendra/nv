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
        Schema::table('property_inquiries', function (Blueprint $table) {
            $table->string('property_entry_code', 50)->nullable()->after('property_id');
            $table->index('property_entry_code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('property_inquiries', function (Blueprint $table) {
            $table->dropIndex(['property_entry_code']);
            $table->dropColumn('property_entry_code');
        });
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('property_entries', function (Blueprint $table) {
            // Timestamp set when supply head first opens/views the entry
            $table->timestamp('supply_head_viewed_at')->nullable()->after('allow_resubmit');
        });
    }

    public function down(): void
    {
        Schema::table('property_entries', function (Blueprint $table) {
            $table->dropColumn('supply_head_viewed_at');
        });
    }
};

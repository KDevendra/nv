<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('property_entries', function (Blueprint $table) {
            // NULL  = not rejected (field irrelevant)
            // false = rejected, re-edit NOT allowed
            // true  = rejected, supply head has allowed re-edit
            $table->boolean('allow_resubmit')->nullable()->default(null)->after('supply_head_note');
        });
    }

    public function down(): void
    {
        Schema::table('property_entries', function (Blueprint $table) {
            $table->dropColumn('allow_resubmit');
        });
    }
};

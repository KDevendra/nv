<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Add show_on_website flag to property_entries table.
     * Only verified entries can be marked to show on website.
     */
    public function up(): void
    {
        Schema::table('property_entries', function (Blueprint $table) {
            $table->boolean('show_on_website')->default(false)->after('status');
            
            $table->index('show_on_website');
        });
    }

    public function down(): void
    {
        Schema::table('property_entries', function (Blueprint $table) {
            $table->dropIndex(['show_on_website']);
            $table->dropColumn('show_on_website');
        });
    }
};

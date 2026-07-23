<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('property_entries', function (Blueprint $table) {
            $table->string('form_submited_location')->nullable()->after('remarks');
        });
    }

    public function down(): void
    {
        Schema::table('property_entries', function (Blueprint $table) {
            $table->dropColumn('form_submited_location');
        });
    }
};

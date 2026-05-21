<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('about_page_sections', function (Blueprint $table) {
            $table->string('values_who_we_are_image')->nullable()->after('values_who_we_are');
            $table->string('values_mission_image')->nullable()->after('values_mission');
            $table->string('values_vision_image')->nullable()->after('values_vision');
            $table->string('values_teamwork_image')->nullable()->after('values_teamwork');
        });
    }

    public function down(): void
    {
        Schema::table('about_page_sections', function (Blueprint $table) {
            $table->dropColumn([
                'values_who_we_are_image',
                'values_mission_image',
                'values_vision_image',
                'values_teamwork_image',
            ]);
        });
    }
};

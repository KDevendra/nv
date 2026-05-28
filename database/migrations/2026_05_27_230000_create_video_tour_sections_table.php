<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('video_tour_sections', function (Blueprint $table) {
            $table->id();
            $table->string('badge_text')->default('Take a video tour');
            $table->string('title');
            $table->string('button_text')->nullable();
            $table->string('button_link')->nullable();
            $table->string('youtube_url')->nullable();
            $table->string('video_path')->nullable();
            $table->string('thumbnail')->nullable();
            $table->string('thumbnail_alt')->nullable();
            $table->string('phone_number')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('video_tour_sections');
    }
};

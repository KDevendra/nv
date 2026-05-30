<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('property_page_section_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('property_page_section_id')
                ->constrained('property_page_sections')
                ->cascadeOnDelete();
            $table->string('image_path');
            $table->string('alt_tag')->nullable();
            $table->integer('sort_order')->default(0);
            $table->timestamps();

            $table->index('property_page_section_id');
            $table->index('sort_order');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('property_page_section_images');
    }
};

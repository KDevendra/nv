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
        Schema::create('advisory_page_sections', function (Blueprint $table) {
            $table->id();

            // Page Title
            $table->string('page_title')->nullable();

            // Hero Section
            $table->string('hero_eyebrow')->nullable();
            $table->string('hero_title')->nullable();
            $table->text('hero_description')->nullable();
            $table->string('hero_note')->nullable();
            $table->string('hero_btn1_text')->nullable();
            $table->string('hero_btn1_link')->nullable();
            $table->string('hero_btn2_text')->nullable();
            $table->string('hero_btn2_link')->nullable();

            // Services Section Header
            $table->string('services_eyebrow')->nullable();
            $table->string('services_title')->nullable();
            $table->text('services_description')->nullable();

            // Track 1 (ZENDO Select)
            $table->string('track1_title')->nullable();
            $table->string('track1_tagline')->nullable();
            $table->text('track1_description')->nullable();
            $table->json('track1_benefits')->nullable();

            // Track 2 (ZENDO Upgrade)
            $table->string('track2_title')->nullable();
            $table->string('track2_tagline')->nullable();
            $table->text('track2_description')->nullable();
            $table->json('track2_benefits')->nullable();

            // Why Choose Section
            $table->string('why_eyebrow')->nullable();
            $table->string('why_title')->nullable();
            $table->json('why_items')->nullable();

            // Final CTA Section
            $table->string('cta_eyebrow')->nullable();
            $table->string('cta_title')->nullable();
            $table->string('cta_phone_text')->nullable();
            $table->string('cta_phone_link')->nullable();
            $table->string('cta_note')->nullable();
            $table->string('cta_btn1_text')->nullable();
            $table->string('cta_btn1_link')->nullable();
            $table->string('cta_btn2_text')->nullable();
            $table->string('cta_btn2_link')->nullable();

            // Footnote
            $table->text('footnote_text')->nullable();

            // Status
            $table->boolean('is_active')->default(true);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('advisory_page_sections');
    }
};

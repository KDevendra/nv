<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('property_entry_field_reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('property_entry_id')->constrained()->onDelete('cascade');
            $table->foreignId('reviewed_by')->constrained('users')->onDelete('cascade');
            $table->string('field_name'); // e.g., 'facility_type', 'nearest_city'
            $table->string('field_label'); // e.g., 'Facility Type', 'Nearest City'
            $table->text('field_value')->nullable(); // The actual value of the field
            $table->boolean('is_correct')->nullable(); // true = correct, false = incorrect, null = not reviewed yet
            $table->text('remark')->nullable(); // Required when is_correct = false
            $table->timestamps();
            
            // Unique constraint: one review per field per entry
            $table->unique(['property_entry_id', 'field_name']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('property_entry_field_reviews');
    }
};

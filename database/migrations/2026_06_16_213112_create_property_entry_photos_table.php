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
        Schema::create('property_entry_photos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('property_entry_id')->constrained()->onDelete('cascade');
            $table->string('slot_label'); // "Front Elevation", "Rear View", etc.
            $table->string('file_path'); // "property_entries/photos/xyz.jpg"
            $table->timestamps();
            
            $table->index('property_entry_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('property_entry_photos');
    }
};

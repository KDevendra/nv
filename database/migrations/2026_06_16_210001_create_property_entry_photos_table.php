<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('property_entry_photos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('property_entry_id')->constrained('property_entries')->cascadeOnDelete();
            $table->string('slot_label');
            $table->string('file_path');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('property_entry_photos');
    }
};

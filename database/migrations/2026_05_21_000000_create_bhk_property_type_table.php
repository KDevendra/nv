<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bhk_property_type', function (Blueprint $table) {
            $table->foreignId('bhk_id')->constrained()->cascadeOnDelete();
            $table->foreignId('property_type_id')->constrained()->cascadeOnDelete();
            $table->primary(['bhk_id', 'property_type_id']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bhk_property_type');
    }
};

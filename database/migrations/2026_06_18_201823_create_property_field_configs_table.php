<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('property_field_configs', function (Blueprint $table) {
            $table->id();
            $table->string('field_key')->unique();
            $table->boolean('keep_field')->default(true);
            $table->boolean('mandatory_field')->default(true);
            $table->boolean('show_on_website')->default(false);
            $table->boolean('show_after_verification')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('property_field_configs');
    }
};

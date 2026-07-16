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
        Schema::create('property_wishlists', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('property_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('property_entry_code')->nullable();
            $table->timestamps();

            // Composite unique constraint to prevent duplicate entries (with custom short name)
            $table->unique(['user_id', 'property_id', 'property_entry_code'], 'wishlist_user_prop_unique');
            
            // Indexes for faster queries
            $table->index('user_id');
            $table->index('property_id');
            $table->index('property_entry_code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('property_wishlists');
    }
};

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
        Schema::create('property_entry_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('property_entry_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('action'); // 'created', 'updated', 'submitted', 'verified', 'rejected', 'recheck'
            $table->string('old_status')->nullable();
            $table->string('new_status')->nullable();
            $table->text('note')->nullable(); // For rejection/recheck notes
            $table->json('changes')->nullable(); // JSON of changed fields
            $table->timestamps();
            
            $table->index('property_entry_id');
            $table->index('user_id');
            $table->index('action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('property_entry_logs');
    }
};

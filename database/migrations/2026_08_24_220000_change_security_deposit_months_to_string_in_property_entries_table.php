<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('property_entries', function (Blueprint $table) {
            $table->string('security_deposit_months', 255)->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('property_entries', function (Blueprint $table) {
            $table->decimal('security_deposit_months', 8, 2)->nullable()->change();
        });
    }
};

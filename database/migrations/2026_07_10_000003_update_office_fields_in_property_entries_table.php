<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('property_entries', function (Blueprint $table) {
            // has_offices: null=not answered, true=yes, false=no
            $table->boolean('has_offices')->nullable()->after('no_of_offices');
            // Change office_sizes from varchar to text to store JSON array
            $table->text('office_sizes')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('property_entries', function (Blueprint $table) {
            $table->dropColumn('has_offices');
            $table->string('office_sizes', 255)->nullable()->change();
        });
    }
};

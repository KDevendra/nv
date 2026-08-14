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
        if (Schema::hasTable('leads') && !Schema::hasColumn('leads', 'pincode')) {
            Schema::table('leads', function (Blueprint $table) {
                $table->string('pincode', 10)->nullable()->after('email');
            });
        }

        if (Schema::hasTable('consultations') && !Schema::hasColumn('consultations', 'pincode')) {
            Schema::table('consultations', function (Blueprint $table) {
                $table->string('pincode', 10)->nullable()->after('email');
            });
        }

        if (Schema::hasTable('inquiries') && !Schema::hasColumn('inquiries', 'pincode')) {
            Schema::table('inquiries', function (Blueprint $table) {
                $table->string('pincode', 10)->nullable()->after('email');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('leads') && Schema::hasColumn('leads', 'pincode')) {
            Schema::table('leads', function (Blueprint $table) {
                $table->dropColumn('pincode');
            });
        }

        if (Schema::hasTable('consultations') && Schema::hasColumn('consultations', 'pincode')) {
            Schema::table('consultations', function (Blueprint $table) {
                $table->dropColumn('pincode');
            });
        }

        if (Schema::hasTable('inquiries') && Schema::hasColumn('inquiries', 'pincode')) {
            Schema::table('inquiries', function (Blueprint $table) {
                $table->dropColumn('pincode');
            });
        }
    }
};

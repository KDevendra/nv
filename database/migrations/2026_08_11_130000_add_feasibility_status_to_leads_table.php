<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('leads', 'feasibility_status')) {
            Schema::table('leads', function (Blueprint $table) {
                $table->enum('feasibility_status', ['pending', 'feasible', 'not_feasible', 'conditional'])->nullable()->after('feasibility_notes');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('leads', 'feasibility_status')) {
            Schema::table('leads', function (Blueprint $table) {
                $table->dropColumn('feasibility_status');
            });
        }
    }
};

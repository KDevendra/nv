<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('leads', 'negotiation_notes')) {
            Schema::table('leads', function (Blueprint $table) {
                $table->text('negotiation_notes')->nullable()->after('site_visit_feedback');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('leads', 'negotiation_notes')) {
            Schema::table('leads', function (Blueprint $table) {
                $table->dropColumn('negotiation_notes');
            });
        }
    }
};

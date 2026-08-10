<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * The lead pipeline code (LeadPipelineService, MigrateLegacyLeads, the
     * CRM views and the admin sidebar badge) already reads and writes these
     * columns, but they were never added to the leads table.
     */
    public function up(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            if (!Schema::hasColumn('leads', 'origin_table')) {
                // Source record the lead was raised from: inquiries,
                // property_inquiries or consultations.
                $table->string('origin_table', 64)->nullable()->after('property_id');
            }
            if (!Schema::hasColumn('leads', 'needs_division_review')) {
                // Set when the division could not be inferred from the
                // originating record and an admin has to confirm it.
                $table->boolean('needs_division_review')->default(false)->after('origin_table');
            }
            if (!Schema::hasColumn('leads', 'sla_contact_due_at')) {
                $table->timestamp('sla_contact_due_at')->nullable()->after('first_contacted_at');
            }
            if (!Schema::hasColumn('leads', 'sla_contact_breached')) {
                $table->boolean('sla_contact_breached')->default(false)->after('sla_contact_due_at');
            }
            if (!Schema::hasColumn('leads', 'sla_feasibility_due_at')) {
                $table->timestamp('sla_feasibility_due_at')->nullable()->after('feasibility_responded_at');
            }
            if (!Schema::hasColumn('leads', 'sla_feasibility_breached')) {
                $table->boolean('sla_feasibility_breached')->default(false)->after('sla_feasibility_due_at');
            }
            if (!Schema::hasColumn('leads', 'deleted_at')) {
                // LeadPipelineService deduplicates with withTrashed().
                $table->softDeletes();
            }
        });

        Schema::table('leads', function (Blueprint $table) {
            $table->index('needs_division_review');
        });
    }

    public function down(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            $table->dropIndex(['needs_division_review']);

            foreach ([
                'origin_table',
                'needs_division_review',
                'sla_contact_due_at',
                'sla_contact_breached',
                'sla_feasibility_due_at',
                'sla_feasibility_breached',
                'deleted_at',
            ] as $column) {
                if (Schema::hasColumn('leads', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};

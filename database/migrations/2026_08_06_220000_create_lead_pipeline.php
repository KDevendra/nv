<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Single migration for the entire Zendo Lead Pipeline feature.
 *
 * Steps executed in order:
 *  1. Extend role ENUMs on users + role_permissions to include
 *     sales_executive and chief_coordinator.
 *  2. Add division column to users (if not already present).
 *  3. Create leads table (pipeline source of truth).
 *  4. Create lead_stage_histories table (audit trail).
 *
 * Idempotency notes:
 *  - The users.role ENUM may already include the new roles from a previous
 *    partial run; we detect the current definition and skip if already done.
 *  - role_permissions may contain legacy 'staff' rows that must be removed
 *    before widening the ENUM (MySQL strict mode would truncate otherwise).
 */
return new class extends Migration
{
    private const ROLES = [
        'super_admin', 'admin', 'user', 'supply_head', 'field_officer',
        'owner', 'channel_partner', 'sales_executive', 'chief_coordinator',
    ];

    // ──────────────────────────────────────────────────────────────────────
    // UP
    // ──────────────────────────────────────────────────────────────────────

    public function up(): void
    {
        $roleList = implode("','", self::ROLES);

        // ── Step 1a: extend users.role ENUM (skip if already done) ────────
        $usersRoleType = $this->columnType('users', 'role');
        if (!str_contains($usersRoleType, 'sales_executive')) {
            DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('{$roleList}') NOT NULL DEFAULT 'user'");
        }

        // ── Step 1b: extend role_permissions.role ENUM ────────────────────
        // First remove any rows whose role value won't exist in the new ENUM
        // (e.g. legacy 'staff' rows from early migrations).
        DB::table('role_permissions')->whereNotIn('role', self::ROLES)->delete();

        $rpRoleType = $this->columnType('role_permissions', 'role');
        if (!str_contains($rpRoleType, 'sales_executive')) {
            DB::statement("ALTER TABLE role_permissions MODIFY COLUMN role ENUM('{$roleList}') NOT NULL");
        }

        // ── Step 2: add division column to users ──────────────────────────
        if (!Schema::hasColumn('users', 'division')) {
            DB::statement("ALTER TABLE users ADD COLUMN division ENUM('warehousing','residential','commercial') NULL AFTER role");
            DB::statement("ALTER TABLE users ADD INDEX idx_users_division (division)");
        }
        // Back-fill field_officer rows (always warehousing — safe to re-run)
        DB::table('users')->where('role', 'field_officer')->whereNull('division')->update(['division' => 'warehousing']);

        // ── Step 3: create leads table ────────────────────────────────────
        if (!Schema::hasTable('leads')) {
            Schema::create('leads', function (Blueprint $table) {
                $table->id();

                // Core identity
                $table->enum('division', ['warehousing', 'residential', 'commercial']);
                $table->string('name');
                $table->string('phone', 20);
                $table->string('email')->nullable();
                $table->unsignedBigInteger('property_id')->nullable();
                $table->foreign('property_id')->references('id')->on('properties')->nullOnDelete();

                // Pipeline stage (forward-only)
                $table->enum('stage', [
                    'new_lead', 'contacted', 'interest_confirmed',
                    'escalated_to_cc', 'feasibility_check', 'options_shared',
                    'site_visit_scheduled', 'site_visit_done', 'negotiation', 'deal_closed',
                ])->default('new_lead');

                // Side-state (orthogonal to stage)
                $table->enum('side_state', ['on_hold', 'deferred', 'lost'])->nullable();
                $table->string('pre_hold_status')->nullable();

                // Hold
                $table->timestamp('hold_started_at')->nullable();
                $table->date('hold_until_date')->nullable();
                $table->timestamp('hold_ended_at')->nullable();

                // Defer
                $table->timestamp('deferred_until')->nullable();

                // Lost
                $table->string('lost_reason')->nullable();
                $table->timestamp('lost_at')->nullable();

                // Assignment
                $table->unsignedBigInteger('assigned_se_id')->nullable();
                $table->foreign('assigned_se_id')->references('id')->on('users')->nullOnDelete();
                $table->unsignedBigInteger('assigned_cc_id')->nullable();
                $table->foreign('assigned_cc_id')->references('id')->on('users')->nullOnDelete();
                $table->unsignedSmallInteger('cc_load_at_assignment')->default(0);
                $table->timestamp('se_assigned_at')->nullable();
                $table->timestamp('cc_assigned_at')->nullable();

                // SE panel fields
                $table->unsignedTinyInteger('contact_attempts')->default(0);
                $table->timestamp('last_contacted_at')->nullable();
                $table->text('qualification_notes')->nullable();
                $table->json('options_shared_property_ids')->nullable();
                $table->timestamp('options_shared_at')->nullable();
                $table->text('handover_note')->nullable();
                $table->timestamp('handover_completed_at')->nullable();

                // CC / SH fields
                $table->timestamp('feasibility_requested_at')->nullable();
                $table->unsignedBigInteger('feasibility_sh_id')->nullable();
                $table->foreign('feasibility_sh_id')->references('id')->on('users')->nullOnDelete();
                $table->enum('feasibility_status', ['pending', 'feasible', 'not_feasible', 'conditional'])->nullable();
                $table->text('feasibility_notes')->nullable();
                $table->timestamp('feasibility_responded_at')->nullable();

                // Site-visit single-use token (24 h)
                $table->string('site_visit_token', 64)->nullable()->unique();
                $table->timestamp('site_visit_token_expires_at')->nullable();
                $table->timestamp('site_visit_token_opened_at')->nullable();
                $table->timestamp('site_visit_scheduled_at')->nullable();
                $table->text('site_visit_feedback')->nullable();
                $table->timestamp('site_visit_done_at')->nullable();

                // Negotiation & deal
                $table->text('negotiation_notes')->nullable();
                $table->decimal('deal_value', 15, 2)->nullable();
                $table->text('deal_notes')->nullable();
                $table->timestamp('deal_closed_at')->nullable();

                // SLA tracking
                $table->timestamp('sla_contact_due_at')->nullable();
                $table->boolean('sla_contact_breached')->default(false);
                $table->timestamp('sla_feasibility_due_at')->nullable();
                $table->boolean('sla_feasibility_breached')->default(false);

                // Legacy traceability
                $table->string('origin_table')->nullable();
                $table->unsignedBigInteger('origin_id')->nullable();
                $table->boolean('needs_division_review')->default(false);

                $table->timestamps();
                $table->softDeletes();

                // Indexes
                $table->unique(['phone', 'division']);
                $table->index(['division', 'stage']);
                $table->index('side_state');
            });
        }

        // ── Step 4: create lead_stage_histories table ─────────────────────
        if (!Schema::hasTable('lead_stage_histories')) {
            Schema::create('lead_stage_histories', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('lead_id');
                $table->foreign('lead_id')->references('id')->on('leads')->cascadeOnDelete();
                $table->string('from_stage')->nullable();
                $table->string('to_stage');
                $table->string('from_side_state')->nullable();
                $table->string('to_side_state')->nullable();
                $table->text('note')->nullable();
                $table->unsignedBigInteger('changed_by_user_id')->nullable();
                $table->foreign('changed_by_user_id')->references('id')->on('users')->nullOnDelete();
                $table->timestamp('created_at')->useCurrent();

                $table->index('lead_id');
                $table->index('changed_by_user_id');
            });
        }
    }

    // ──────────────────────────────────────────────────────────────────────
    // DOWN
    // ──────────────────────────────────────────────────────────────────────

    public function down(): void
    {
        Schema::dropIfExists('lead_stage_histories');
        Schema::dropIfExists('leads');

        // Remove division index + column
        if (Schema::hasColumn('users', 'division')) {
            // Drop index first (ignore error if it doesn't exist)
            try {
                DB::statement("ALTER TABLE users DROP INDEX idx_users_division");
            } catch (\Throwable) {}
            Schema::table('users', fn (Blueprint $t) => $t->dropColumn('division'));
        }

        // Revert role ENUMs to previous set
        $previous = ['super_admin', 'admin', 'user', 'supply_head', 'field_officer', 'owner', 'channel_partner'];
        DB::table('users')->whereNotIn('role', $previous)->update(['role' => 'user']);
        DB::table('role_permissions')->whereNotIn('role', $previous)->delete();

        $oldList = implode("','", $previous);
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('{$oldList}') NOT NULL DEFAULT 'user'");
        DB::statement("ALTER TABLE role_permissions MODIFY COLUMN role ENUM('{$oldList}') NOT NULL");
    }

    // ──────────────────────────────────────────────────────────────────────
    // Helper
    // ──────────────────────────────────────────────────────────────────────

    /** Return the raw column type string from INFORMATION_SCHEMA. */
    private function columnType(string $table, string $column): string
    {
        $row = DB::selectOne(
            "SELECT COLUMN_TYPE FROM INFORMATION_SCHEMA.COLUMNS
             WHERE TABLE_SCHEMA = DATABASE()
               AND TABLE_NAME   = ?
               AND COLUMN_NAME  = ?",
            [$table, $column]
        );
        return $row ? strtolower($row->COLUMN_TYPE) : '';
    }
};

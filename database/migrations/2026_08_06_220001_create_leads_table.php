<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Schema::dropIfExists('lead_stage_histories');
        Schema::dropIfExists('leads');
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        Schema::create('leads', function (Blueprint $table) {
            $table->id();
            $table->enum('division', ['warehousing', 'residential', 'commercial']);
            $table->string('name');
            $table->string('phone', 20);
            $table->string('email')->nullable();
            $table->foreignId('property_id')->nullable()->constrained('properties')->nullOnDelete();

            // pipeline stage — forward-only
            $table->enum('stage', [
                'new_lead','contacted','qualified','options_shared','interest_confirmed',
                'escalated_to_cc','inventory_check_done','site_visit_scheduled',
                'site_visit_completed','negotiation','deal_closed',
            ])->default('new_lead');

            // side-states
            $table->enum('side_state', ['none','inquiry_hold','follow_up_later','lost'])->default('none');
            $table->string('pre_hold_status')->nullable();
            $table->timestamp('hold_started_at')->nullable();
            $table->date('hold_expected_resume_date')->nullable();
            $table->text('hold_reason')->nullable();
            $table->date('follow_up_date')->nullable();
            $table->string('lost_reason')->nullable();
            $table->text('lost_reason_other')->nullable();

            // ownership
            $table->foreignId('assigned_se_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('assigned_cc_id')->nullable()->constrained('users')->nullOnDelete();
            $table->unsignedTinyInteger('cc_load_at_assignment')->nullable();

            // stage 2 — contact
            $table->unsignedTinyInteger('contact_attempts')->default(0);
            $table->timestamp('first_contacted_at')->nullable();
            $table->text('contact_outcome')->nullable();

            // stage 3 — qualification
            $table->text('qualification_notes')->nullable();

            // stage 4 — options
            $table->json('options_shared_property_ids')->nullable();

            // stage 5 — handover gate
            $table->text('handover_note')->nullable();
            $table->timestamp('handover_completed_at')->nullable();

            // stage 7 — feasibility
            $table->timestamp('feasibility_raised_at')->nullable();
            $table->foreignId('feasibility_sh_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('feasibility_responded_at')->nullable();
            $table->text('feasibility_notes')->nullable();

            // stage 8 — site visit link
            $table->string('visit_link_token', 64)->nullable()->unique();
            $table->timestamp('visit_link_sent_at')->nullable();
            $table->timestamp('visit_link_expires_at')->nullable();
            $table->timestamp('visit_link_opened_at')->nullable();
            $table->date('site_visit_date')->nullable();

            // stage 9 — site visit feedback
            $table->text('site_visit_feedback')->nullable();

            // stage 11 — deal close
            $table->timestamp('deal_closed_at')->nullable();
            $table->decimal('commission_amount', 12, 2)->nullable();
            $table->timestamp('owner_notified_at')->nullable();
            $table->timestamp('reminder_6mo_at')->nullable();

            $table->softDeletes();
            $table->timestamps();

            $table->unique(['phone', 'division']);
            $table->index(['division', 'stage']);
            $table->index(['assigned_se_id']);
            $table->index(['assigned_cc_id']);
            $table->index(['side_state']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('leads');
    }
};

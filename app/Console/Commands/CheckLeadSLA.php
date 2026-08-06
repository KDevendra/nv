<?php

namespace App\Console\Commands;

use App\Helpers\WorkingHours;
use App\Models\Lead;
use Carbon\Carbon;
use Illuminate\Console\Command;

/**
 * Artisan command: app:check-lead-sla
 *
 * Runs hourly (scheduled in console.php) and flags SLA breaches:
 *
 *  1. SE contact SLA — 4 working hours from SE assignment.
 *     Applies to leads in stage 'new_lead' with contact_attempts = 0.
 *
 *  2. SH feasibility response SLA — 24 clock hours from feasibility request.
 *     Applies to leads in stage 'feasibility_check' with feasibility_status = 'pending'.
 *
 * Held leads are excluded from SLA breach detection.
 * After a lead resumes from hold, the SLA deadline is not retroactively
 * set to "breached" for the hold period.
 */
class CheckLeadSLA extends Command
{
    protected $signature   = 'app:check-lead-sla
                                {--dry-run : Report breaches without writing flags}';

    protected $description = 'Check SLA breaches for active (non-held) leads and flag them.';

    public function handle(): int
    {
        $dryRun = $this->option('dry-run');
        $now    = Carbon::now();

        $contactBreached     = 0;
        $feasibilityBreached = 0;

        // ── 1. SE contact SLA ─────────────────────────────────────────────
        // 4 working hours from se_assigned_at, first contact not yet made.
        Lead::query()
            ->where('stage', 'new_lead')
            ->where('contact_attempts', 0)
            ->whereNotNull('assigned_se_id')
            ->whereNotNull('sla_contact_due_at')
            ->where('sla_contact_breached', false)
            ->whereNull('side_state')        // exclude held/lost/deferred
            ->where('sla_contact_due_at', '<', $now)
            ->chunk(200, function ($leads) use ($dryRun, &$contactBreached) {
                foreach ($leads as $lead) {
                    $contactBreached++;
                    if (!$dryRun) {
                        $lead->update(['sla_contact_breached' => true]);
                    }
                    $this->line("  [SLA-CONTACT] Lead #{$lead->id} ({$lead->name}) breached — due {$lead->sla_contact_due_at}");
                }
            });

        // ── 2. SH feasibility response SLA ───────────────────────────────
        // 24 clock hours from feasibility_requested_at.
        Lead::query()
            ->where('stage', 'feasibility_check')
            ->where('feasibility_status', 'pending')
            ->whereNotNull('feasibility_requested_at')
            ->whereNotNull('sla_feasibility_due_at')
            ->where('sla_feasibility_breached', false)
            ->whereNull('side_state')        // exclude held/lost/deferred
            ->where('sla_feasibility_due_at', '<', $now)
            ->chunk(200, function ($leads) use ($dryRun, &$feasibilityBreached) {
                foreach ($leads as $lead) {
                    $feasibilityBreached++;
                    if (!$dryRun) {
                        $lead->update(['sla_feasibility_breached' => true]);
                    }
                    $this->line("  [SLA-FEASIBILITY] Lead #{$lead->id} ({$lead->name}) breached — due {$lead->sla_feasibility_due_at}");
                }
            });

        $this->newLine();
        $this->info("SLA check complete. Contact breaches: {$contactBreached}, Feasibility breaches: {$feasibilityBreached}" . ($dryRun ? ' [DRY RUN — nothing written]' : ''));

        return self::SUCCESS;
    }
}

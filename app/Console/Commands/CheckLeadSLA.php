<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Lead;
use App\Models\LeadStageHistory;
use App\Helpers\WorkingHours;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class CheckLeadSLA extends Command
{
    protected $signature = 'app:check-lead-sla';
    protected $description = 'Check for SLA breaches on active leads (contact SLA & feasibility SLA)';

    public function handle(): int
    {
        $activeLeads = Lead::whereNotIn('side_state', ['inquiry_hold', 'lost'])->get();

        $contactBreaches = 0;
        $feasibilityBreaches = 0;

        foreach ($activeLeads as $lead) {
            // 1. Contact SLA check (4 working hours from created_at)
            if ($lead->first_contacted_at === null) {
                $now = now();
                $workingHoursElapsed = WorkingHours::getWorkingHoursElapsed($lead->created_at, $now);

                // Calculate total working hours spent while on hold, if any
                $holdWorkingHours = $this->calculateHoldWorkingHours($lead);
                $effectiveWorkingHours = max(0, $workingHoursElapsed - $holdWorkingHours);

                if ($effectiveWorkingHours > 4.0) {
                    $contactBreaches++;
                    Log::warning("SLA Breach: Lead #{$lead->id} not contacted within 4 working hours. Effective elapsed: {$effectiveWorkingHours} hrs.");
                }
            }

            // 2. Feasibility SLA check (>24h elapsed since raised)
            if ($lead->feasibility_raised_at !== null && $lead->feasibility_responded_at === null) {
                $raisedAt = Carbon::parse($lead->feasibility_raised_at);
                $elapsedHours = $raisedAt->diffInHours(now());

                if ($elapsedHours > 24) {
                    $feasibilityBreaches++;
                    Log::warning("SLA Breach: Lead #{$lead->id} feasibility response delayed (>24h since raised). SH ID: {$lead->feasibility_sh_id}.");
                }
            }
        }

        $this->info("SLA Check completed. Contact breaches: {$contactBreaches}, Feasibility breaches: {$feasibilityBreaches}");
        return Command::SUCCESS;
    }

    /**
     * Calculate working hours spent on hold for a lead.
     */
    private function calculateHoldWorkingHours(Lead $lead): float
    {
        $holdHistories = LeadStageHistory::where('lead_id', $lead->id)
            ->where(function ($q) {
                $q->where('to_side_state', 'inquiry_hold')
                  ->orWhere('from_side_state', 'inquiry_hold');
            })
            ->orderBy('id')
            ->get();

        $totalHoldWorkingHours = 0.0;
        $holdStart = null;

        foreach ($holdHistories as $h) {
            if ($h->to_side_state === 'inquiry_hold' && !$holdStart) {
                $holdStart = Carbon::parse($h->created_at);
            } elseif ($h->from_side_state === 'inquiry_hold' && $holdStart) {
                $holdEnd = Carbon::parse($h->created_at);
                $totalHoldWorkingHours += WorkingHours::getWorkingHoursElapsed($holdStart, $holdEnd);
                $holdStart = null;
            }
        }

        return $totalHoldWorkingHours;
    }
}

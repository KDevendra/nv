<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// ── Lead pipeline scheduled commands ──────────────────────────────────────
// Check SLA breaches every hour for active (non-held) leads.
Schedule::command('app:check-lead-sla')->hourly();

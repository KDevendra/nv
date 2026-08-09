<?php

namespace App\Helpers;

use Carbon\Carbon;

class WorkingHours
{
    /**
     * Calculate working hours elapsed between two timestamps, excluding Sundays.
     * Working day defined as 9:00 AM to 6:00 PM (9 working hours/day).
     */
    public static function getWorkingHoursElapsed(Carbon $start, Carbon $end): float
    {
        if ($start->greaterThanOrEqualTo($end)) {
            return 0.0;
        }

        $current = $start->copy();
        $seconds = 0;

        while ($current->lessThan($end)) {
            // Skip Sundays
            if ($current->dayOfWeek === Carbon::SUNDAY) {
                $current->addDay()->startOfDay();
                continue;
            }

            $dayStart = $current->copy()->setTime(9, 0, 0);
            $dayEnd   = $current->copy()->setTime(18, 0, 0);

            if ($current->lessThan($dayStart)) {
                $current = $dayStart;
            }

            if ($current->greaterThanOrEqualTo($dayEnd)) {
                $current->addDay()->startOfDay();
                continue;
            }

            $nextStep = $current->copy()->addMinutes(15);
            if ($nextStep->greaterThan($end)) {
                $nextStep = $end->copy();
            }

            if ($nextStep->greaterThan($dayEnd)) {
                $nextStep = $dayEnd->copy();
            }

            $seconds += $nextStep->diffInSeconds($current);
            $current = $nextStep;
        }

        return round($seconds / 3600, 2);
    }

    /**
     * Add working hours to a starting timestamp (skipping Sundays and non-working hours).
     */
    public static function addWorkingHours(Carbon $start, int $hours): Carbon
    {
        $current = $start->copy();
        $remainingSeconds = $hours * 3600;

        while ($remainingSeconds > 0) {
            // Skip Sunday
            if ($current->dayOfWeek === Carbon::SUNDAY) {
                $current->addDay()->setTime(9, 0, 0);
                continue;
            }

            $dayStart = $current->copy()->setTime(9, 0, 0);
            $dayEnd   = $current->copy()->setTime(18, 0, 0);

            if ($current->lessThan($dayStart)) {
                $current = $dayStart;
            }

            if ($current->greaterThanOrEqualTo($dayEnd)) {
                $current->addDay()->setTime(9, 0, 0);
                continue;
            }

            $secondsAvailableToday = $dayEnd->diffInSeconds($current);

            if ($remainingSeconds <= $secondsAvailableToday) {
                $current->addSeconds($remainingSeconds);
                $remainingSeconds = 0;
            } else {
                $remainingSeconds -= $secondsAvailableToday;
                $current->addDay()->setTime(9, 0, 0);
            }
        }

        return $current;
    }
}

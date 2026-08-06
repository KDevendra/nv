<?php

namespace App\Helpers;

use Carbon\Carbon;

/**
 * Helper for computing working-hour deadlines and elapsed working time.
 *
 * Working day definition:
 *  - Monday–Saturday, 09:00–18:00 IST
 *  - Sunday is always excluded
 *
 * All inputs/outputs are Carbon instances (or null).
 */
class WorkingHours
{
    public const WORK_START_HOUR = 9;
    public const WORK_END_HOUR   = 18;
    public const TIMEZONE        = 'Asia/Kolkata';

    /**
     * Add N working hours to a given datetime.
     *
     * Example: addWorkingHours(Friday 17:00, 4) → Monday 12:00
     *
     * @param  Carbon|null  $from  Defaults to now().
     * @param  int          $hours Number of working hours to add.
     * @return Carbon
     */
    public static function addWorkingHours(?Carbon $from = null, int $hours = 4): Carbon
    {
        $cursor = ($from ?? Carbon::now(self::TIMEZONE))->copy()->setTimezone(self::TIMEZONE);

        // If starting outside working hours, jump to next working start
        $cursor = self::snapToWorkStart($cursor);

        $remaining = $hours;

        while ($remaining > 0) {
            $endOfDay = $cursor->copy()->setHour(self::WORK_END_HOUR)->setMinute(0)->setSecond(0);
            $minutesLeftToday = $cursor->diffInMinutes($endOfDay, false);

            if ($minutesLeftToday <= 0) {
                // Past end of working day — jump to next working day start
                $cursor = self::nextWorkDayStart($cursor);
                continue;
            }

            $hoursLeftToday = $minutesLeftToday / 60;

            if ($remaining <= $hoursLeftToday) {
                $cursor->addMinutes((int) round($remaining * 60));
                $remaining = 0;
            } else {
                $remaining -= $hoursLeftToday;
                $cursor = self::nextWorkDayStart($cursor);
            }
        }

        return $cursor;
    }

    /**
     * Calculate the number of working hours elapsed between two datetimes.
     *
     * @param  Carbon  $from
     * @param  Carbon  $to
     * @return float  (fractional hours)
     */
    public static function workingHoursBetween(Carbon $from, Carbon $to): float
    {
        $from   = $from->copy()->setTimezone(self::TIMEZONE);
        $to     = $to->copy()->setTimezone(self::TIMEZONE);
        $total  = 0.0;
        $cursor = self::snapToWorkStart($from);

        while ($cursor->lt($to)) {
            if (self::isSunday($cursor)) {
                $cursor = self::nextWorkDayStart($cursor);
                continue;
            }

            $endOfDay = $cursor->copy()->setHour(self::WORK_END_HOUR)->setMinute(0)->setSecond(0);
            $dayEnd   = $endOfDay->lt($to) ? $endOfDay : $to;

            if ($cursor->lt($dayEnd)) {
                $total += $cursor->diffInMinutes($dayEnd) / 60;
            }

            $cursor = self::nextWorkDayStart($cursor);
        }

        return $total;
    }

    /**
     * Check whether a given Carbon datetime is within working hours.
     */
    public static function isWithinWorkingHours(Carbon $dt): bool
    {
        $dt = $dt->copy()->setTimezone(self::TIMEZONE);
        if (self::isSunday($dt)) {
            return false;
        }
        $hour = (int) $dt->format('G');
        return $hour >= self::WORK_START_HOUR && $hour < self::WORK_END_HOUR;
    }

    // ──────────────────────────────────────────────────────────────────────
    // Private helpers
    // ──────────────────────────────────────────────────────────────────────

    private static function isSunday(Carbon $dt): bool
    {
        return $dt->dayOfWeek === Carbon::SUNDAY;
    }

    /**
     * If $cursor is before the working-day start or on a Sunday, advance it
     * to the next valid working-day start time.
     */
    private static function snapToWorkStart(Carbon $cursor): Carbon
    {
        $c = $cursor->copy();

        // Sunday → Monday
        while (self::isSunday($c)) {
            $c->addDay()->setHour(self::WORK_START_HOUR)->setMinute(0)->setSecond(0);
        }

        $dayStart = $c->copy()->setHour(self::WORK_START_HOUR)->setMinute(0)->setSecond(0);
        $dayEnd   = $c->copy()->setHour(self::WORK_END_HOUR)->setMinute(0)->setSecond(0);

        if ($c->lt($dayStart)) {
            $c = $dayStart;
        } elseif ($c->gte($dayEnd)) {
            $c = self::nextWorkDayStart($c);
        }

        return $c;
    }

    /**
     * Return the start of the next working day (skips Sundays).
     */
    private static function nextWorkDayStart(Carbon $cursor): Carbon
    {
        $c = $cursor->copy()->addDay()->setHour(self::WORK_START_HOUR)->setMinute(0)->setSecond(0);

        while (self::isSunday($c)) {
            $c->addDay();
        }

        return $c;
    }
}

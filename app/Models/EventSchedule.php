<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EventSchedule extends Model
{
    protected $fillable = [
        'event_id',
        'scheduled_at',
        'time',
        'day_of_week',
        'week_of_month',
        'next_run_at',
    ];

    protected $casts = [
        'scheduled_at'  => 'datetime',
        'week_of_month' => 'array',
        'next_run_at'   => 'datetime',
    ];

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    /**
     * Compute the next occurrence after the given time (defaults to now).
     * Returns null if there is no future occurrence.
     */
    public function computeNextRun(?Carbon $after = null): ?Carbon
    {
        $after ??= now();

        if ($this->event->type === Event::TYPE_ONCE) {
            return ($this->scheduled_at && $this->scheduled_at->gt($after))
                ? $this->scheduled_at
                : null;
        }

        return $this->nextRepeatingOccurrence($after);
    }

    /**
     * Find the next occurrence of the Nth-weekday-of-month rule after $after.
     * Searches up to 13 months ahead.
     */
    private function nextRepeatingOccurrence(Carbon $after): ?Carbon
    {
        [$hour, $minute] = explode(':', $this->time);
        $weekNums = collect($this->week_of_month)->sort()->values();
        $isoDow   = $this->day_of_week; // 1=Mon … 7=Sun

        $cursor = $after->copy()->startOfMonth();

        for ($i = 0; $i < 13; $i++) {
            foreach ($weekNums as $weekNum) {
                $date = $this->nthWeekdayOfMonth($cursor->year, $cursor->month, (int) $weekNum, $isoDow);

                if ($date === null) {
                    continue;
                }

                $occurrence = $date->setTime((int) $hour, (int) $minute, 0);

                if ($occurrence->gt($after)) {
                    return $occurrence;
                }
            }

            $cursor->addMonthNoOverflow();
        }

        return null;
    }

    /**
     * Return the Nth occurrence of the given ISO day-of-week in the given month/year.
     * Returns null if the Nth occurrence falls outside the month.
     *
     * ISO day-of-week: 1 = Monday … 7 = Sunday
     * n = 1 → first occurrence, n = 2 → second, etc.
     */
    private function nthWeekdayOfMonth(int $year, int $month, int $n, int $isoDow): ?Carbon
    {
        $first    = Carbon::create($year, $month, 1)->startOfDay();
        $firstDow = $first->dayOfWeekIso; // 1=Mon … 7=Sun

        $daysToFirst = ($isoDow - $firstDow + 7) % 7;
        $result      = $first->copy()->addDays($daysToFirst)->addWeeks($n - 1);

        return $result->month === $month ? $result : null;
    }
}

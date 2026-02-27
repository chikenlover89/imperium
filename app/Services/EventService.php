<?php

namespace App\Services;

use App\Models\Device;
use App\Models\DeviceReading;
use App\Models\Event;
use App\Models\EventLog;
use Illuminate\Support\Facades\Log;

class EventService
{
    /**
     * Check device_alert events for a device after a new reading is stored.
     */
    public function checkDeviceAlerts(Device $device, DeviceReading $reading): void
    {
        $events = Event::with('trigger')
            ->where('type', Event::TYPE_DEVICE_ALERT)
            ->where('is_active', true)
            ->where('device_id', $device->id)
            ->get();

        foreach ($events as $event) {
            if (! $event->trigger?->evaluate($reading)) {
                continue;
            }

            // Respect cooldown: skip if last fire was within cooldown_minutes
            if ($event->cooldown_minutes !== null) {
                $lastLog = $event->logs()->orderByDesc('occurred_at')->first();
                if ($lastLog && $lastLog->occurred_at->diffInMinutes(now()) < $event->cooldown_minutes) {
                    continue;
                }
            }

            $this->fire($event, [
                'device'    => $device->friendly_name,
                'field'     => $event->trigger->field,
                'value'     => $reading->{$event->trigger->field},
                'operator'  => $event->trigger->operator,
                'threshold' => $event->trigger->threshold,
            ]);
        }
    }

    /**
     * Check once and repeating scheduled events. Run every minute via scheduler.
     */
    public function checkScheduledEvents(): void
    {
        $now = now();

        // Once events: fire if scheduled_at has passed and not yet fired
        Event::with('schedule')
            ->where('type', Event::TYPE_ONCE)
            ->where('is_active', true)
            ->whereHas('schedule', fn ($q) => $q->where('scheduled_at', '<=', $now))
            ->whereDoesntHave('logs')
            ->each(function (Event $event) {
                $this->fire($event, ['scheduled_at' => $event->schedule->scheduled_at->toIso8601String()]);
                $event->update(['is_active' => false]); // one-time: disable after firing
            });

        // Repeating events: fire if next_run_at has passed
        Event::with('schedule')
            ->where('type', Event::TYPE_REPEATING)
            ->where('is_active', true)
            ->whereHas('schedule', fn ($q) => $q->where('next_run_at', '<=', $now))
            ->each(function (Event $event) {
                $this->fire($event, ['scheduled_at' => $event->schedule->next_run_at->toIso8601String()]);

                // Advance to the next occurrence
                $next = $event->schedule->computeNextRun();
                $event->schedule->update(['next_run_at' => $next]);
            });
    }

    /**
     * Record the event firing in the log.
     */
    private function fire(Event $event, array $context = []): void
    {
        EventLog::create([
            'event_id'    => $event->id,
            'occurred_at' => now(),
            'context'     => $context ?: null,
        ]);

        Log::info("[Event] Fired: {$event->name}", $context);
    }
}

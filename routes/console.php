<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

// Prune sensor readings older than 30 days every day at midnight
Schedule::command('model:prune')->daily();

// Check once/repeating scheduled events every minute
Schedule::call(fn () => app(\App\Services\EventService::class)->checkScheduledEvents())->everyMinute();

// Mark devices offline if no data received in last 31 minutes
Schedule::call(function () {
    \App\Models\Device::where('is_online', true)
        ->where('last_seen_at', '<', now()->subMinutes(31))
        ->update(['is_online' => false]);
})->everyMinute();

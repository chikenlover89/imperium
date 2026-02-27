<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

// Prune sensor readings older than 30 days every day at midnight
Schedule::command('model:prune')->daily();

// Check once/repeating scheduled events every minute
Schedule::call(fn () => app(\App\Services\EventService::class)->checkScheduledEvents())->everyMinute();

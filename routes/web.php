<?php

use App\Models\Device;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $devices = Device::with('latestReading')->get();

    $stats = [
        'total'   => $devices->count(),
        'online'  => $devices->where('is_online', true)->count(),
        'offline' => $devices->where('is_online', false)->count(),
        'avg_temp' => $devices
            ->map(fn($d) => $d->latestReading?->temperature)
            ->filter()
            ->avg(),
    ];

    return view('welcome', compact('devices', 'stats'));
});

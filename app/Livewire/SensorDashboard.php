<?php

namespace App\Livewire;

use App\Models\Device;
use Livewire\Component;

class SensorDashboard extends Component
{
    public function render()
    {
        $devices = Device::with([
            'latestReading',
            'deviceReadings' => fn($q) => $q
                ->where('created_at', '>=', now()->subHours(24))
                ->orderBy('created_at')
                ->select('device_id', 'temperature', 'humidity', 'created_at'),
        ])->get();
        $userTimezone = 'Europe/Riga';

        $updated = now();
        $updated->setTimezone(new \DateTimeZone($userTimezone));

        $stats = [
            'total'    => $devices->count(),
            'online'   => $devices->where('is_online', true)->count(),
            'offline'  => $devices->where('is_online', false)->count(),
            'avg_temp' => $devices
                ->map(fn($d) => $d->latestReading?->temperature)
                ->filter()
                ->avg(),
            'updated'  => $updated,    
        ];

        return view('livewire.sensor-dashboard', compact('devices', 'stats'));
    }
}

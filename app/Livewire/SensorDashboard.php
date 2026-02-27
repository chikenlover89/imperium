<?php

namespace App\Livewire;

use App\Models\Device;
use App\Models\EventLog;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
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

        $eventLogs = EventLog::with('event')
            ->orderByDesc('occurred_at')
            ->limit(30)
            ->get();

        $weather = Cache::remember('weather_ikskile', 900, function () {
            try {
                $response = Http::timeout(5)->get('https://api.open-meteo.com/v1/forecast', [
                    'latitude'        => 56.8319,
                    'longitude'       => 24.4983,
                    'current'         => 'temperature_2m,relative_humidity_2m,apparent_temperature,weather_code,wind_speed_10m',
                    'timezone'        => 'Europe/Riga',
                    'wind_speed_unit' => 'ms',
                ]);
                return $response->ok() ? $response->json('current') : null;
            } catch (\Exception $e) {
                return null;
            }
        });

        $wmoDescriptions = [
            0 => 'Clear sky', 1 => 'Mainly clear', 2 => 'Partly cloudy', 3 => 'Overcast',
            45 => 'Fog', 48 => 'Icy fog',
            51 => 'Light drizzle', 53 => 'Drizzle', 55 => 'Heavy drizzle',
            61 => 'Light rain', 63 => 'Rain', 65 => 'Heavy rain',
            71 => 'Light snow', 73 => 'Snow', 75 => 'Heavy snow',
            80 => 'Rain showers', 81 => 'Rain showers', 82 => 'Heavy showers',
            85 => 'Snow showers', 86 => 'Heavy snow showers',
            95 => 'Thunderstorm', 96 => 'Thunderstorm + hail', 99 => 'Thunderstorm + hail',
        ];
        $weatherDesc = $weather ? ($wmoDescriptions[$weather['weather_code'] ?? 0] ?? 'Unknown') : null;

        return view('livewire.sensor-dashboard', compact('devices', 'stats', 'eventLogs', 'weather', 'weatherDesc'));
    }

    public function resolveLog(int $logId): void
    {
        EventLog::where('id', $logId)
            ->whereNull('resolved_at')
            ->update(['resolved_at' => now()]);
    }
}

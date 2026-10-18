<?php

namespace App\Jobs;

use App\Models\Device;
use App\Models\DeviceReading;
use App\Services\EventService;
use Illuminate\Support\Facades\Log;

class ProcessMqttMessage
{
    public function __construct(
        public readonly string $topic,
        public readonly string $payload,
    ) {}

    public function handle(): void
    {
        // ── Bridge: full device list published by Zigbee2MQTT ──
        if ($this->topic === 'zigbee2mqtt/bridge/devices') {
            $this->syncDevices();
            return;
        }

        // ── Bridge: ignore logging / responses ──
        if (str_starts_with($this->topic, 'zigbee2mqtt/bridge/')) {
            return;
        }

        // ── Device reading ──
        // Topic format: zigbee2mqtt/{friendly_name}
        // Ignore set/get sub-topics
        if (str_contains($this->topic, '/set') || str_contains($this->topic, '/get')) {
            return;
        }

        $friendlyName = str_replace('zigbee2mqtt/', '', $this->topic);
        $this->storeDeviceReading($friendlyName);
    }

    // ──────────────────────────────────────────────────────────
    // Sync all devices from zigbee2mqtt/bridge/devices payload
    // ──────────────────────────────────────────────────────────
    private function syncDevices(): void
    {
        $devices = json_decode($this->payload, true);

        if (!is_array($devices)) {
            return;
        }

        foreach ($devices as $d) {
            // Skip the coordinator itself
            if (($d['type'] ?? '') === 'Coordinator') {
                continue;
            }

            $features  = collect($d['definition']['exposes'] ?? [])
                ->pluck('name')
                ->all();
            $deviceType = match(true) {
                in_array('temperature', $features) => 'th_sensor',
                in_array('power', $features)       => 'power_switch',
                in_array('water_leak', $features)  => 'water_sensor',
                default                             => 'unknown',
            };

            Device::updateOrCreate(
                ['ieee_address' => $d['ieee_address'] ?? null],
                [
                    'friendly_name' => $d['friendly_name'] ?? 'Unknown',
                    'type'          => $deviceType,
                    'model'         => $d['definition']['model'] ?? null,
                    'manufacturer'  => $d['definition']['vendor'] ?? null,
                    'description'   => $d['definition']['description'] ?? null,
                    'meta'          => $d,
                ]
            );
        }

        Log::info('Z2M: synced ' . count($devices) . ' devices from bridge/devices');
    }

    // ──────────────────────────────────────────────────────────
    // Store a device reading for a known friendly name
    // ──────────────────────────────────────────────────────────
    private function storeDeviceReading(string $friendlyName): void
    {
        $data = json_decode($this->payload, true);

        if (!is_array($data)) {
            return;
        }

        // Auto-create device if it appeared before bridge/devices sync
        $device = Device::firstOrCreate(
            ['friendly_name' => $friendlyName],
            ['type' => 'unknown', 'is_online' => true]
        );

        $device->markSeen();

        // Only store a reading if there is sensor data in the payload
        $hasSensorData = isset($data['temperature'])
            || isset($data['humidity'])
            || isset($data['battery'])
            || isset($data['linkquality'])
            || isset($data['power'])
            || isset($data['state']);

        if (!$hasSensorData) {
            return;
        }

        $reading = DeviceReading::create([
            'device_id'   => $device->id,
            'battery'     => $data['battery'] ?? null,
            'linkquality' => $data['linkquality'] ?? null,
            'raw_payload' => $data,
            'created_at'  => now(),
        ]);

        app(EventService::class)->checkDeviceAlerts($device, $reading);
    }
}

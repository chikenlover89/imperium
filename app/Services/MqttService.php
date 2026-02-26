<?php

namespace App\Services;

use App\Models\Device;
use App\Models\DeviceCommand;
use PhpMqtt\Client\Facades\MQTT;
use Illuminate\Support\Facades\Log;

class MqttService
{
    /**
     * Send a command to a device (e.g. relay toggle).
     * Creates a DeviceCommand record and publishes to MQTT.
     *
     * Example:
     *   $mqtt->sendCommand($relay, ['state' => 'ON']);
     */
    public function sendCommand(Device $device, array $payload): DeviceCommand
    {
        $command = DeviceCommand::create([
            'device_id' => $device->id,
            'payload'   => $payload,
            'status'    => 'pending',
        ]);

        try {
            $topic = "zigbee2mqtt/{$device->friendly_name}/set";
            MQTT::publish($topic, json_encode($payload));
            $command->markSent();
            Log::info("Z2M: command sent to [{$device->friendly_name}]", $payload);
        } catch (\Throwable $e) {
            $command->markFailed();
            Log::error("Z2M: failed to send command to [{$device->friendly_name}]: " . $e->getMessage());
        }

        return $command;
    }
}

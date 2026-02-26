<?php

namespace App\Console\Commands;

use App\Jobs\ProcessMqttMessage;
use Illuminate\Console\Command;
use PhpMqtt\Client\Facades\MQTT;
use PhpMqtt\Client\Exceptions\MqttClientException;

class MqttSubscribe extends Command
{
    protected $signature   = 'mqtt:subscribe';
    protected $description = 'Subscribe to Zigbee2MQTT and process incoming messages';

    public function handle(): void
    {
        while (true) {
            try {
                $this->info('Connecting to MQTT broker...');

                $mqtt = MQTT::connection();

                $mqtt->subscribe('zigbee2mqtt/#', function (string $topic, string $message) {
                    $this->line("[<comment>{$topic}</comment>] " . mb_strimwidth($message, 0, 120, '...'));
                    (new ProcessMqttMessage($topic, $message))->handle();
                }, 0);

                $this->info('Subscribed to zigbee2mqtt/#  — waiting for messages (Ctrl+C to stop)');

                $mqtt->loop(true);

            } catch (MqttClientException $e) {
                $this->warn('Connection lost: ' . $e->getMessage());
                $this->warn('Reconnecting in 5 seconds...');

                // Clean up the broken connection before retrying
                try { $mqtt->disconnect(); } catch (\Throwable) {}

                sleep(5);
            } catch (\Throwable $e) {
                $this->error('Unexpected error: ' . $e->getMessage());
                $this->warn('Reconnecting in 5 seconds...');
                sleep(5);
            }
        }
    }
}

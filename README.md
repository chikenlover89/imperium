# Imperium

A home automation dashboard for monitoring and controlling Zigbee IoT devices in real time.

## Features

- **Live sensor dashboard** — real-time readings (temperature, humidity, CO2, pressure, signal) from all paired Zigbee devices via MQTT
- **Smart plug control** — toggle power switch on/off and reset the energy counter directly from the dashboard
- **Power monitoring** — live voltage, current, power (W), and energy consumption (kWh) for smart plugs
- **Event log** — tracks device events with timestamps; unresolved events are highlighted with alert
- **Alert sound** — optional audio alert when unresolved events are present, toggleable per session
- **Weather widget** — current weather for a configured location via Open-Meteo API (temperature, humidity, wind, apparent temperature)
- **Local-only controls** — some features are restricted to local network access (Cloudflare traffic is external);

## Stack

- **Laravel 11**
- **Livewire 3**
- **Zigbee2MQTT**
- **PhpMqtt**
- **Open-Meteo**

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::rename('sensor_readings', 'device_readings');
    }

    public function down(): void
    {
        Schema::rename('device_readings', 'sensor_readings');
    }
};

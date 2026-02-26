<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sensor_readings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('device_id')->constrained()->cascadeOnDelete();
            $table->float('temperature')->nullable();
            $table->float('humidity')->nullable();
            $table->tinyInteger('battery')->nullable();
            $table->tinyInteger('linkquality')->nullable()->comment('0-255 signal quality');
            $table->integer('voltage')->nullable()->comment('mV');
            $table->json('raw_payload');
            $table->timestamp('created_at')->useCurrent();
            // No updated_at — append-only time series
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sensor_readings');
    }
};

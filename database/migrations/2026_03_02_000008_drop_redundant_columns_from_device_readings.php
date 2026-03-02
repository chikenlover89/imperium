<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('device_readings', function (Blueprint $table) {
            $table->dropColumn([
                'temperature',
                'humidity',
                'voltage',
                'current',
                'power',
                'energy',
                'plug_state',
            ]);
        });
    }

    public function down(): void
    {
        Schema::table('device_readings', function (Blueprint $table) {
            $table->float('temperature')->nullable();
            $table->float('humidity')->nullable();
            $table->integer('voltage')->nullable();
            $table->float('current')->nullable();
            $table->float('power')->nullable();
            $table->float('energy')->nullable();
            $table->string('plug_state', 10)->nullable();
        });
    }
};

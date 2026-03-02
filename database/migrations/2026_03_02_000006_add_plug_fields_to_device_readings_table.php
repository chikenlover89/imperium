<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('device_readings', function (Blueprint $table) {
            $table->float('current')->nullable()->after('voltage')->comment('Amps');
            $table->float('power')->nullable()->after('current')->comment('Watts');
            $table->float('energy')->nullable()->after('power')->comment('kWh cumulative');
            $table->string('plug_state', 10)->nullable()->after('energy')->comment('ON / OFF');
        });
    }

    public function down(): void
    {
        Schema::table('device_readings', function (Blueprint $table) {
            $table->dropColumn(['current', 'power', 'energy', 'plug_state']);
        });
    }
};

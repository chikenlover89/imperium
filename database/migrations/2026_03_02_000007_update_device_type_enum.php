<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Remap old values to new ones
        DB::table('devices')->where('type', 'sensor')->update(['type' => 'th_sensor']);
        DB::table('devices')->whereIn('type', ['relay', 'switch'])->update(['type' => 'power_switch']);

        // MySQL supports ENUM modification; SQLite uses plain TEXT so no DDL needed
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE devices MODIFY COLUMN type ENUM('th_sensor','power_switch','unknown') NOT NULL DEFAULT 'unknown'");
        } else {
            // SQLite: rewrite the column as a plain string (no real ENUM support)
            Schema::table('devices', function (Blueprint $table) {
                $table->string('type')->default('unknown')->change();
            });
        }
    }

    public function down(): void
    {
        DB::table('devices')->where('type', 'th_sensor')->update(['type' => 'sensor']);
        DB::table('devices')->where('type', 'power_switch')->update(['type' => 'relay']);

        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE devices MODIFY COLUMN type ENUM('sensor','relay','switch','unknown') NOT NULL DEFAULT 'unknown'");
        } else {
            Schema::table('devices', function (Blueprint $table) {
                $table->string('type')->default('unknown')->change();
            });
        }
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE devices MODIFY COLUMN type ENUM('th_sensor','power_switch','water_sensor','unknown') NOT NULL DEFAULT 'unknown'");
        }

        // Reclassify devices that synced as 'unknown' before this type existed,
        // but whose definition exposes a water_leak feature.
        DB::table('devices')->where('type', 'unknown')->get(['id', 'meta'])->each(function ($device) {
            $meta = json_decode($device->meta ?? '[]', true);
            $features = collect($meta['definition']['exposes'] ?? [])->pluck('name')->all();

            if (in_array('water_leak', $features)) {
                DB::table('devices')->where('id', $device->id)->update(['type' => 'water_sensor']);
            }
        });
    }

    public function down(): void
    {
        DB::table('devices')->where('type', 'water_sensor')->update(['type' => 'unknown']);

        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE devices MODIFY COLUMN type ENUM('th_sensor','power_switch','unknown') NOT NULL DEFAULT 'unknown'");
        }
    }
};

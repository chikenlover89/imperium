<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Device extends Model
{
    protected $fillable = [
        'ieee_address',
        'friendly_name',
        'type',
        'model',
        'manufacturer',
        'description',
        'is_online',
        'last_seen_at',
        'meta',
    ];

    protected $casts = [
        'is_online'    => 'boolean',
        'last_seen_at' => 'datetime',
        'meta'         => 'array',
    ];

    public function deviceReadings(): HasMany
    {
        return $this->hasMany(DeviceReading::class);
    }

    public function latestReading(): HasOne
    {
        return $this->hasOne(DeviceReading::class)->latestOfMany();
    }

    public function commands(): HasMany
    {
        return $this->hasMany(DeviceCommand::class);
    }

    /**
     * True if no reading has been received in the last 60 seconds.
     */
    public function isStale(): bool
    {
        return $this->last_seen_at === null
            || $this->last_seen_at->diffInSeconds(now()) > 60;
    }

    /**
     * Mark device online and update last_seen_at.
     */
    public function markSeen(): void
    {
        $this->update([
            'is_online'    => true,
            'last_seen_at' => now(),
        ]);
    }
}

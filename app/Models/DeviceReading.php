<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\MassPrunable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DeviceReading extends Model
{
    use MassPrunable;

    protected $table = 'device_readings';

    // Append-only — no updated_at column
    public $timestamps = false;

    protected $fillable = [
        'device_id',
        'temperature',
        'humidity',
        'battery',
        'linkquality',
        'voltage',
        'raw_payload',
    ];

    protected $casts = [
        'temperature' => 'float',
        'humidity'    => 'float',
        'battery'     => 'integer',
        'linkquality' => 'integer',
        'voltage'     => 'integer',
        'raw_payload' => 'array',
        'created_at'  => 'datetime',
    ];

    public function device(): BelongsTo
    {
        return $this->belongsTo(Device::class);
    }

    /**
     * Prune readings older than 30 days (rolling window).
     */
    public function prunable()
    {
        return static::where('created_at', '<', now()->subDays(30));
    }
}

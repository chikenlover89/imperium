<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Event extends Model
{
    const TYPE_DEVICE_ALERT = 'device_alert';
    const TYPE_ONCE         = 'once';
    const TYPE_REPEATING    = 'repeating';

    protected $fillable = [
        'name',
        'description',
        'type',
        'is_active',
        'cooldown_minutes',
        'device_id',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function device(): BelongsTo
    {
        return $this->belongsTo(Device::class);
    }

    public function trigger(): HasOne
    {
        return $this->hasOne(EventTrigger::class);
    }

    public function schedule(): HasOne
    {
        return $this->hasOne(EventSchedule::class);
    }

    public function logs(): HasMany
    {
        return $this->hasMany(EventLog::class);
    }
}

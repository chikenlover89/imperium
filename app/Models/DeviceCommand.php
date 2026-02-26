<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DeviceCommand extends Model
{
    protected $fillable = [
        'device_id',
        'payload',
        'status',
        'sent_at',
    ];

    protected $casts = [
        'payload' => 'array',
        'sent_at' => 'datetime',
    ];

    public function device(): BelongsTo
    {
        return $this->belongsTo(Device::class);
    }

    public function markSent(): void
    {
        $this->update(['status' => 'sent', 'sent_at' => now()]);
    }

    public function markFailed(): void
    {
        $this->update(['status' => 'failed']);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EventLog extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'event_id',
        'occurred_at',
        'context',
        'resolved_at',
    ];

    protected $casts = [
        'occurred_at' => 'datetime',
        'context'     => 'array',
        'resolved_at' => 'datetime',
    ];

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }
}

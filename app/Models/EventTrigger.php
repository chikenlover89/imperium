<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EventTrigger extends Model
{
    protected $fillable = [
        'event_id',
        'field',
        'operator',
        'threshold',
    ];

    protected $casts = [
        'threshold' => 'float',
    ];

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    /**
     * Evaluate the trigger condition against a device reading.
     */
    public function evaluate(DeviceReading $reading): bool
    {
        $value = $reading->{$this->field};

        if ($value === null) {
            return false;
        }

        return match ($this->operator) {
            '>'  => $value >  $this->threshold,
            '<'  => $value <  $this->threshold,
            '>=' => $value >= $this->threshold,
            '<=' => $value <= $this->threshold,
            '='  => $value == $this->threshold,
            default => false,
        };
    }
}

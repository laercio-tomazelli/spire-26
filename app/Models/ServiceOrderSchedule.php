<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ServiceOrderSchedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'service_order_id',
        'scheduled_by',
        'scheduled_date',
        'scheduled_period',
        'scheduled_time',
        'status',
        'notes',
        'confirmed_at',
        'completed_at',
        'canceled_at',
        'cancellation_reason',
    ];

    protected function casts(): array
    {
        return [
            'scheduled_date' => 'date',
            'scheduled_time' => 'datetime',
            'confirmed_at' => 'datetime',
            'completed_at' => 'datetime',
            'canceled_at' => 'datetime',
        ];
    }

    // Relationships

    public function serviceOrder(): BelongsTo
    {
        return $this->belongsTo(ServiceOrder::class);
    }

    public function scheduledByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'scheduled_by');
    }

    // Helpers

    public function isCanceled(): bool
    {
        return ! is_null($this->canceled_at);
    }

    public function isConfirmed(): bool
    {
        return ! is_null($this->confirmed_at);
    }

    public function isCompleted(): bool
    {
        return ! is_null($this->completed_at);
    }
}

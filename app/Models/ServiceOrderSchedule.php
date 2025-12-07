<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $service_order_invite_id
 * @property Carbon $scheduled_date
 * @property int|null $status_id
 * @property string|null $observations
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read User|null $scheduledByUser
 * @property-read ServiceOrder|null $serviceOrder
 *
 * @method static Builder<static>|ServiceOrderSchedule newModelQuery()
 * @method static Builder<static>|ServiceOrderSchedule newQuery()
 * @method static Builder<static>|ServiceOrderSchedule query()
 * @method static Builder<static>|ServiceOrderSchedule whereCreatedAt($value)
 * @method static Builder<static>|ServiceOrderSchedule whereId($value)
 * @method static Builder<static>|ServiceOrderSchedule whereObservations($value)
 * @method static Builder<static>|ServiceOrderSchedule whereScheduledDate($value)
 * @method static Builder<static>|ServiceOrderSchedule whereServiceOrderInviteId($value)
 * @method static Builder<static>|ServiceOrderSchedule whereStatusId($value)
 * @method static Builder<static>|ServiceOrderSchedule whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
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

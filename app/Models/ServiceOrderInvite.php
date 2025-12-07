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
 * @property int $service_order_id
 * @property int $partner_id
 * @property int|null $status_id
 * @property string|null $rejection_reason
 * @property string|null $observations
 * @property Carbon|null $responded_at
 * @property int|null $responded_by
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read User|null $invitedByUser
 * @property-read Partner $partner
 * @property-read ServiceOrder $serviceOrder
 *
 * @method static Builder<static>|ServiceOrderInvite newModelQuery()
 * @method static Builder<static>|ServiceOrderInvite newQuery()
 * @method static Builder<static>|ServiceOrderInvite query()
 * @method static Builder<static>|ServiceOrderInvite whereCreatedAt($value)
 * @method static Builder<static>|ServiceOrderInvite whereId($value)
 * @method static Builder<static>|ServiceOrderInvite whereObservations($value)
 * @method static Builder<static>|ServiceOrderInvite wherePartnerId($value)
 * @method static Builder<static>|ServiceOrderInvite whereRejectionReason($value)
 * @method static Builder<static>|ServiceOrderInvite whereRespondedAt($value)
 * @method static Builder<static>|ServiceOrderInvite whereRespondedBy($value)
 * @method static Builder<static>|ServiceOrderInvite whereServiceOrderId($value)
 * @method static Builder<static>|ServiceOrderInvite whereStatusId($value)
 * @method static Builder<static>|ServiceOrderInvite whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class ServiceOrderInvite extends Model
{
    use HasFactory;

    protected $fillable = [
        'service_order_id',
        'partner_id',
        'invited_by',
        'status',
        'invited_at',
        'responded_at',
        'response_notes',
        'expires_at',
    ];

    protected function casts(): array
    {
        return [
            'invited_at' => 'datetime',
            'responded_at' => 'datetime',
            'expires_at' => 'datetime',
        ];
    }

    // Relationships

    public function serviceOrder(): BelongsTo
    {
        return $this->belongsTo(ServiceOrder::class);
    }

    public function partner(): BelongsTo
    {
        return $this->belongsTo(Partner::class);
    }

    public function invitedByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'invited_by');
    }

    // Helpers

    public function isExpired(): bool
    {
        if (! $this->expires_at) {
            return false;
        }

        return $this->expires_at->isPast();
    }

    public function isPending(): bool
    {
        return $this->status === 'pending' && ! $this->isExpired();
    }
}

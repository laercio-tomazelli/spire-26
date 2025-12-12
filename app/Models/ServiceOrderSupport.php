<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property-read ServiceOrder|null $serviceOrder
 * @property-read User|null $user
 *
 * @method static Builder<static>|ServiceOrderSupport newModelQuery()
 * @method static Builder<static>|ServiceOrderSupport newQuery()
 * @method static Builder<static>|ServiceOrderSupport query()
 *
 * @mixin \Eloquent
 */
class ServiceOrderSupport extends Model
{
    use HasFactory;

    protected $table = 'service_order_technical_support';

    protected $fillable = [
        'service_order_id',
        'user_id',
        'channel',
        'direction',
        'subject',
        'content',
        'started_at',
        'ended_at',
        'duration_seconds',
        'result',
    ];

    protected function casts(): array
    {
        return [
            'started_at' => 'datetime',
            'ended_at' => 'datetime',
            'duration_seconds' => 'integer',
        ];
    }

    // Relationships

    public function serviceOrder(): BelongsTo
    {
        return $this->belongsTo(ServiceOrder::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

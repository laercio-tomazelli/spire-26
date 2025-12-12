<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $code
 * @property string $name
 * @property string|null $color
 * @property string|null $icon
 * @property int $display_order
 * @property bool $is_active
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection<int, ServiceOrder> $serviceOrders
 * @property-read int|null $service_orders_count
 * @property-read Collection<int, ServiceOrderSubStatus> $subStatuses
 * @property-read int|null $sub_statuses_count
 *
 * @method static Builder<static>|ServiceOrderStatus active()
 * @method static Builder<static>|ServiceOrderStatus newModelQuery()
 * @method static Builder<static>|ServiceOrderStatus newQuery()
 * @method static Builder<static>|ServiceOrderStatus query()
 * @method static Builder<static>|ServiceOrderStatus whereCode($value)
 * @method static Builder<static>|ServiceOrderStatus whereColor($value)
 * @method static Builder<static>|ServiceOrderStatus whereCreatedAt($value)
 * @method static Builder<static>|ServiceOrderStatus whereDisplayOrder($value)
 * @method static Builder<static>|ServiceOrderStatus whereIcon($value)
 * @method static Builder<static>|ServiceOrderStatus whereId($value)
 * @method static Builder<static>|ServiceOrderStatus whereIsActive($value)
 * @method static Builder<static>|ServiceOrderStatus whereName($value)
 * @method static Builder<static>|ServiceOrderStatus whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class ServiceOrderStatus extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'color',
        'icon',
        'display_order',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'display_order' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Scope: only active statuses.
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    // Relationships

    public function subStatuses(): HasMany
    {
        return $this->hasMany(ServiceOrderSubStatus::class, 'status_id');
    }

    public function serviceOrders(): HasMany
    {
        return $this->hasMany(ServiceOrder::class, 'status_id');
    }
}

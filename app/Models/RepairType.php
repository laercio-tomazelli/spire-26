<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * Tipo de Reparo.
 *
 * @property int $id
 * @property string $code
 * @property string $name
 * @property string|null $color
 * @property bool $is_active
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection<int, ServiceOrder> $serviceOrders
 * @property-read int|null $service_orders_count
 *
 * @method static Builder<static>|RepairType active()
 * @method static Builder<static>|RepairType newModelQuery()
 * @method static Builder<static>|RepairType newQuery()
 * @method static Builder<static>|RepairType query()
 * @method static Builder<static>|RepairType whereCode($value)
 * @method static Builder<static>|RepairType whereColor($value)
 * @method static Builder<static>|RepairType whereCreatedAt($value)
 * @method static Builder<static>|RepairType whereId($value)
 * @method static Builder<static>|RepairType whereIsActive($value)
 * @method static Builder<static>|RepairType whereName($value)
 * @method static Builder<static>|RepairType whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class RepairType extends Model
{
    protected $fillable = [
        'code',
        'name',
        'color',
        'is_active',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    /**
     * Scope: only active.
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    /**
     * ServiceOrders with this repair type.
     *
     * @return HasMany<ServiceOrder, $this>
     */
    public function serviceOrders(): HasMany
    {
        return $this->hasMany(ServiceOrder::class);
    }
}

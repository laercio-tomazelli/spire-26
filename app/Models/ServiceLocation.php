<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * Local do Serviço (Balcão, Domicílio, Depósito).
 *
 * @property int $id
 * @property string $code
 * @property string $name Balcão, Domicílio, Depósito
 * @property string|null $color
 * @property int $display_order
 * @property bool $is_active
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection<int, ServiceOrder> $serviceOrders
 * @property-read int|null $service_orders_count
 *
 * @method static Builder<static>|ServiceLocation active()
 * @method static Builder<static>|ServiceLocation newModelQuery()
 * @method static Builder<static>|ServiceLocation newQuery()
 * @method static Builder<static>|ServiceLocation query()
 * @method static Builder<static>|ServiceLocation whereCode($value)
 * @method static Builder<static>|ServiceLocation whereColor($value)
 * @method static Builder<static>|ServiceLocation whereCreatedAt($value)
 * @method static Builder<static>|ServiceLocation whereDisplayOrder($value)
 * @method static Builder<static>|ServiceLocation whereId($value)
 * @method static Builder<static>|ServiceLocation whereIsActive($value)
 * @method static Builder<static>|ServiceLocation whereName($value)
 * @method static Builder<static>|ServiceLocation whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class ServiceLocation extends Model
{
    protected $fillable = [
        'code',
        'name',
        'color',
        'display_order',
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
            'display_order' => 'integer',
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
     * ServiceOrders with this location.
     *
     * @return HasMany<ServiceOrder, $this>
     */
    public function serviceOrders(): HasMany
    {
        return $this->hasMany(ServiceOrder::class);
    }
}

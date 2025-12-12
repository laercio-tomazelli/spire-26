<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * Tipo de Serviço (Reparo, Instalação, etc.).
 *
 * @property int $id
 * @property string $code
 * @property string $name Reparo, Instalação, etc.
 * @property string|null $color
 * @property int $display_order
 * @property bool $is_active
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection<int, ServiceOrder> $serviceOrders
 * @property-read int|null $service_orders_count
 *
 * @method static Builder<static>|ServiceType active()
 * @method static Builder<static>|ServiceType newModelQuery()
 * @method static Builder<static>|ServiceType newQuery()
 * @method static Builder<static>|ServiceType query()
 * @method static Builder<static>|ServiceType whereCode($value)
 * @method static Builder<static>|ServiceType whereColor($value)
 * @method static Builder<static>|ServiceType whereCreatedAt($value)
 * @method static Builder<static>|ServiceType whereDisplayOrder($value)
 * @method static Builder<static>|ServiceType whereId($value)
 * @method static Builder<static>|ServiceType whereIsActive($value)
 * @method static Builder<static>|ServiceType whereName($value)
 * @method static Builder<static>|ServiceType whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class ServiceType extends Model
{
    protected $fillable = [
        'code',
        'name',
        'color',
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
     * Scope: only active.
     */
    protected function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    // Relationships

    public function serviceOrders(): HasMany
    {
        return $this->hasMany(ServiceOrder::class);
    }
}

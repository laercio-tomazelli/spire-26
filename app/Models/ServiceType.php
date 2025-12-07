<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
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
 * @property-read Tenant|null $tenant
 *
 * @method static Builder<static>|ServiceType forTenant(int $tenantId)
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
    use BelongsToTenant;
    use HasFactory;

    protected $fillable = [
        'tenant_id',
        'name',
        'slug',
        'code',
        'description',
        'default_labor_value',
        'default_travel_value',
        'requires_scheduling',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'default_labor_value' => 'decimal:2',
            'default_travel_value' => 'decimal:2',
            'requires_scheduling' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    // Relationships

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function serviceOrders(): HasMany
    {
        return $this->hasMany(ServiceOrder::class);
    }
}

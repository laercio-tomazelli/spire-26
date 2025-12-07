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
 * @property-read Tenant|null $tenant
 *
 * @method static Builder<static>|ServiceOrderStatus forTenant(int $tenantId)
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
    use BelongsToTenant;
    use HasFactory;

    protected $fillable = [
        'tenant_id',
        'name',
        'slug',
        'description',
        'color',
        'icon',
        'sort_order',
        'is_initial',
        'is_final',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'sort_order' => 'integer',
            'is_initial' => 'boolean',
            'is_final' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    // Relationships

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function subStatuses(): HasMany
    {
        return $this->hasMany(ServiceOrderSubStatus::class, 'status_id');
    }

    public function serviceOrders(): HasMany
    {
        return $this->hasMany(ServiceOrder::class, 'status_id');
    }
}

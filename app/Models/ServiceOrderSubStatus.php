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

/**
 * @property-read Collection<int, ServiceOrder> $serviceOrders
 * @property-read int|null $service_orders_count
 * @property-read ServiceOrderStatus|null $status
 * @property-read Tenant|null $tenant
 *
 * @method static Builder<static>|ServiceOrderSubStatus forTenant(int $tenantId)
 * @method static Builder<static>|ServiceOrderSubStatus newModelQuery()
 * @method static Builder<static>|ServiceOrderSubStatus newQuery()
 * @method static Builder<static>|ServiceOrderSubStatus query()
 *
 * @mixin \Eloquent
 */
class ServiceOrderSubStatus extends Model
{
    use BelongsToTenant;
    use HasFactory;

    protected $table = 'service_order_sub_statuses';

    protected $fillable = [
        'tenant_id',
        'status_id',
        'name',
        'slug',
        'description',
        'color',
        'sort_order',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'sort_order' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    // Relationships

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(ServiceOrderStatus::class, 'status_id');
    }

    public function serviceOrders(): HasMany
    {
        return $this->hasMany(ServiceOrder::class, 'sub_status_id');
    }
}

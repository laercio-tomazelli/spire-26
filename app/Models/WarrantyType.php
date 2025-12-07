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
 * @property-read Tenant|null $tenant
 *
 * @method static Builder<static>|WarrantyType forTenant(int $tenantId)
 * @method static Builder<static>|WarrantyType newModelQuery()
 * @method static Builder<static>|WarrantyType newQuery()
 * @method static Builder<static>|WarrantyType query()
 *
 * @mixin \Eloquent
 */
class WarrantyType extends Model
{
    use BelongsToTenant;
    use HasFactory;

    protected $fillable = [
        'tenant_id',
        'name',
        'slug',
        'code',
        'description',
        'duration_months',
        'covers_parts',
        'covers_labor',
        'covers_travel',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'duration_months' => 'integer',
            'covers_parts' => 'boolean',
            'covers_labor' => 'boolean',
            'covers_travel' => 'boolean',
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

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
 * @property-read Brand|null $brand
 * @property-read Collection<int, ServiceOrder> $serviceOrdersFound
 * @property-read int|null $service_orders_found_count
 * @property-read Collection<int, ServiceOrder> $serviceOrdersReported
 * @property-read int|null $service_orders_reported_count
 * @property-read Tenant|null $tenant
 *
 * @method static Builder<static>|Defect forTenant(int $tenantId)
 * @method static Builder<static>|Defect newModelQuery()
 * @method static Builder<static>|Defect newQuery()
 * @method static Builder<static>|Defect query()
 *
 * @mixin \Eloquent
 */
class Defect extends Model
{
    use BelongsToTenant;
    use HasFactory;

    protected $fillable = [
        'tenant_id',
        'brand_id',
        'name',
        'code',
        'description',
        'category',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    // Relationships

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    public function serviceOrdersReported(): HasMany
    {
        return $this->hasMany(ServiceOrder::class, 'defect_id');
    }

    public function serviceOrdersFound(): HasMany
    {
        return $this->hasMany(ServiceOrder::class, 'defect_found_id');
    }
}

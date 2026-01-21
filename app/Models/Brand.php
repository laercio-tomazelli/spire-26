<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Concerns\BelongsToTenant;
use Database\Factories\BrandFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $manufacturer_id
 * @property string $name
 * @property string|null $logo_path
 * @property bool $is_active
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Manufacturer $manufacturer
 * @property-read Collection<int, MonthlyClosing> $monthlyClosings
 * @property-read int|null $monthly_closings_count
 * @property-read Collection<int, PartnerBrand> $partners
 * @property-read int|null $partners_count
 * @property-read Collection<int, Part> $parts
 * @property-read int|null $parts_count
 * @property-read Collection<int, ProductLine> $productLines
 * @property-read int|null $product_lines_count
 * @property-read Collection<int, ProductModel> $productModels
 * @property-read int|null $product_models_count
 * @property-read Collection<int, ServiceOrder> $serviceOrders
 * @property-read int|null $service_orders_count
 * @property-read Tenant|null $tenant
 *
 * @method static BrandFactory factory($count = null, $state = [])
 * @method static Builder<static>|Brand forTenant(int $tenantId)
 * @method static Builder<static>|Brand newModelQuery()
 * @method static Builder<static>|Brand newQuery()
 * @method static Builder<static>|Brand query()
 * @method static Builder<static>|Brand whereCreatedAt($value)
 * @method static Builder<static>|Brand whereId($value)
 * @method static Builder<static>|Brand whereIsActive($value)
 * @method static Builder<static>|Brand whereLogoPath($value)
 * @method static Builder<static>|Brand whereManufacturerId($value)
 * @method static Builder<static>|Brand whereName($value)
 * @method static Builder<static>|Brand whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class Brand extends Model
{
    use BelongsToTenant;
    use HasFactory;

    protected $fillable = [
        'tenant_id',
        'manufacturer_id',
        'name',
        'slug',
        'code',
        'logo_url',
        'settings',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'settings' => 'array',
            'is_active' => 'boolean',
        ];
    }

    // Relationships

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function manufacturer(): BelongsTo
    {
        return $this->belongsTo(Manufacturer::class);
    }

    public function productLines(): HasMany
    {
        return $this->hasMany(ProductLine::class);
    }

    public function productModels(): HasMany
    {
        return $this->hasMany(ProductModel::class);
    }

    public function parts(): HasMany
    {
        return $this->hasMany(Part::class);
    }

    public function serviceOrders(): HasMany
    {
        return $this->hasMany(ServiceOrder::class);
    }

    public function partners(): HasMany
    {
        return $this->hasMany(PartnerBrand::class);
    }

    public function monthlyClosings(): HasMany
    {
        return $this->hasMany(MonthlyClosing::class);
    }
}

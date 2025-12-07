<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Concerns\BelongsToTenant;
use Database\Factories\ProductModelFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $brand_id
 * @property int|null $product_category_id
 * @property string $model_code
 * @property string|null $model_name
 * @property string|null $manufacturer_model
 * @property string|null $ean
 * @property string|null $release_date
 * @property string|null $end_of_life_date
 * @property int|null $warranty_months
 * @property int|null $promotional_warranty_months
 * @property string|null $observations
 * @property bool $is_active
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property-read Collection<int, BillOfMaterial> $billOfMaterials
 * @property-read int|null $bill_of_materials_count
 * @property-read Brand $brand
 * @property-read ProductCategory|null $category
 * @property-read Collection<int, Part> $parts
 * @property-read int|null $parts_count
 * @property-read ProductLine|null $productLine
 * @property-read Collection<int, ServiceOrder> $serviceOrders
 * @property-read int|null $service_orders_count
 * @property-read Tenant|null $tenant
 *
 * @method static ProductModelFactory factory($count = null, $state = [])
 * @method static Builder<static>|ProductModel forTenant(int $tenantId)
 * @method static Builder<static>|ProductModel newModelQuery()
 * @method static Builder<static>|ProductModel newQuery()
 * @method static Builder<static>|ProductModel query()
 * @method static Builder<static>|ProductModel whereBrandId($value)
 * @method static Builder<static>|ProductModel whereCreatedAt($value)
 * @method static Builder<static>|ProductModel whereDeletedAt($value)
 * @method static Builder<static>|ProductModel whereEan($value)
 * @method static Builder<static>|ProductModel whereEndOfLifeDate($value)
 * @method static Builder<static>|ProductModel whereId($value)
 * @method static Builder<static>|ProductModel whereIsActive($value)
 * @method static Builder<static>|ProductModel whereManufacturerModel($value)
 * @method static Builder<static>|ProductModel whereModelCode($value)
 * @method static Builder<static>|ProductModel whereModelName($value)
 * @method static Builder<static>|ProductModel whereObservations($value)
 * @method static Builder<static>|ProductModel whereProductCategoryId($value)
 * @method static Builder<static>|ProductModel wherePromotionalWarrantyMonths($value)
 * @method static Builder<static>|ProductModel whereReleaseDate($value)
 * @method static Builder<static>|ProductModel whereUpdatedAt($value)
 * @method static Builder<static>|ProductModel whereWarrantyMonths($value)
 *
 * @mixin \Eloquent
 */
class ProductModel extends Model
{
    use BelongsToTenant;
    use HasFactory;

    protected $fillable = [
        'tenant_id',
        'brand_id',
        'product_line_id',
        'category_id',
        'name',
        'slug',
        'code',
        'sku',
        'description',
        'specifications',
        'image_url',
        'warranty_months',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'specifications' => 'array',
            'warranty_months' => 'integer',
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

    public function productLine(): BelongsTo
    {
        return $this->belongsTo(ProductLine::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(ProductCategory::class, 'category_id');
    }

    public function parts(): BelongsToMany
    {
        return $this->belongsToMany(Part::class, 'bill_of_materials')
            ->withPivot(['quantity', 'is_required', 'notes'])
            ->withTimestamps();
    }

    public function billOfMaterials(): HasMany
    {
        return $this->hasMany(BillOfMaterial::class);
    }

    public function serviceOrders(): HasMany
    {
        return $this->hasMany(ServiceOrder::class);
    }
}

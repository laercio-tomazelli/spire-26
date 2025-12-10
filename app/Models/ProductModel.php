<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\ProductModelFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
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
 * @property Carbon|null $deleted_at
 * @property-read Collection<int, BillOfMaterial> $billOfMaterials
 * @property-read int|null $bill_of_materials_count
 * @property-read Brand $brand
 * @property-read ProductCategory|null $category
 * @property-read Collection<int, Part> $parts
 * @property-read int|null $parts_count
 * @property-read ProductLine|null $productLine
 * @property-read Collection<int, ServiceOrder> $serviceOrders
 * @property-read int|null $service_orders_count
 *
 * @method static ProductModelFactory factory($count = null, $state = [])
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
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'brand_id',
        'product_category_id',
        'model_code',
        'model_name',
        'manufacturer_model',
        'ean',
        'release_date',
        'end_of_life_date',
        'warranty_months',
        'promotional_warranty_months',
        'observations',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'release_date' => 'date',
            'end_of_life_date' => 'date',
            'warranty_months' => 'integer',
            'promotional_warranty_months' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    // Relationships

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(ProductCategory::class, 'product_category_id');
    }

    /**
     * Get the product line through the category.
     */
    public function productLine(): ?ProductLine
    {
        return $this->category?->productLine;
    }

    public function parts(): BelongsToMany
    {
        return $this->belongsToMany(Part::class, 'bill_of_materials')
            ->withoutGlobalScopes() // Evita scope de tenant na tabela pivot
            ->withPivot(['quantity', 'line_position', 'is_provided'])
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

    // Accessors

    /**
     * Get display name (model_name or model_code).
     */
    protected function getDisplayNameAttribute(): string
    {
        return $this->model_name ?? $this->model_code;
    }
}

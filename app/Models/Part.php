<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Concerns\BelongsToTenant;
use Database\Factories\PartFactory;
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
 * @property int $tenant_id
 * @property string $part_code SKU único
 * @property string $description
 * @property string|null $short_description
 * @property string $unit UN, PC, KIT
 * @property string|null $ncm
 * @property string|null $cest
 * @property int|null $origin 0-Nacional, 1-Importado, etc.
 * @property string|null $ean
 * @property string|null $ean_packaging
 * @property string|null $manufacturer_code
 * @property string $price
 * @property string $cost_price
 * @property string|null $net_weight
 * @property string|null $gross_weight
 * @property string|null $width
 * @property string|null $height
 * @property string|null $depth
 * @property int|null $min_stock
 * @property int|null $max_stock
 * @property string|null $location
 * @property int $is_display
 * @property bool $is_active
 * @property int|null $bling_id ID integração Bling
 * @property string|null $synced_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property-read Collection<int, BillOfMaterial> $billOfMaterials
 * @property-read int|null $bill_of_materials_count
 * @property-read Brand|null $brand
 * @property-read int $total_stock
 * @property-read Collection<int, InventoryItem> $inventoryItems
 * @property-read int|null $inventory_items_count
 * @property-read Collection<int, OrderItem> $orderItems
 * @property-read int|null $order_items_count
 * @property-read Collection<int, ProductModel> $productModels
 * @property-read int|null $product_models_count
 * @property-read Collection<int, ServiceOrderPart> $serviceOrderParts
 * @property-read int|null $service_order_parts_count
 * @property-read Tenant $tenant
 *
 * @method static PartFactory factory($count = null, $state = [])
 * @method static Builder<static>|Part forTenant(int $tenantId)
 * @method static Builder<static>|Part newModelQuery()
 * @method static Builder<static>|Part newQuery()
 * @method static Builder<static>|Part query()
 * @method static Builder<static>|Part whereBlingId($value)
 * @method static Builder<static>|Part whereCest($value)
 * @method static Builder<static>|Part whereCostPrice($value)
 * @method static Builder<static>|Part whereCreatedAt($value)
 * @method static Builder<static>|Part whereDeletedAt($value)
 * @method static Builder<static>|Part whereDepth($value)
 * @method static Builder<static>|Part whereDescription($value)
 * @method static Builder<static>|Part whereEan($value)
 * @method static Builder<static>|Part whereEanPackaging($value)
 * @method static Builder<static>|Part whereGrossWeight($value)
 * @method static Builder<static>|Part whereHeight($value)
 * @method static Builder<static>|Part whereId($value)
 * @method static Builder<static>|Part whereIsActive($value)
 * @method static Builder<static>|Part whereIsDisplay($value)
 * @method static Builder<static>|Part whereLocation($value)
 * @method static Builder<static>|Part whereManufacturerCode($value)
 * @method static Builder<static>|Part whereMaxStock($value)
 * @method static Builder<static>|Part whereMinStock($value)
 * @method static Builder<static>|Part whereNcm($value)
 * @method static Builder<static>|Part whereNetWeight($value)
 * @method static Builder<static>|Part whereOrigin($value)
 * @method static Builder<static>|Part wherePartCode($value)
 * @method static Builder<static>|Part wherePrice($value)
 * @method static Builder<static>|Part whereShortDescription($value)
 * @method static Builder<static>|Part whereSyncedAt($value)
 * @method static Builder<static>|Part whereTenantId($value)
 * @method static Builder<static>|Part whereUnit($value)
 * @method static Builder<static>|Part whereUpdatedAt($value)
 * @method static Builder<static>|Part whereWidth($value)
 *
 * @mixin \Eloquent
 */
class Part extends Model
{
    use BelongsToTenant;
    use HasFactory;

    protected $fillable = [
        'tenant_id',
        'brand_id',
        'code',
        'sku',
        'name',
        'description',
        'unit',
        'unit_cost',
        'unit_price',
        'weight',
        'ncm',
        'origin',
        'minimum_stock',
        'maximum_stock',
        'reorder_point',
        'is_serialized',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'unit_cost' => 'decimal:4',
            'unit_price' => 'decimal:4',
            'weight' => 'decimal:3',
            'minimum_stock' => 'integer',
            'maximum_stock' => 'integer',
            'reorder_point' => 'integer',
            'is_serialized' => 'boolean',
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

    public function productModels(): BelongsToMany
    {
        return $this->belongsToMany(ProductModel::class, 'bill_of_materials')
            ->withPivot(['quantity', 'is_required', 'notes'])
            ->withTimestamps();
    }

    public function billOfMaterials(): HasMany
    {
        return $this->hasMany(BillOfMaterial::class);
    }

    public function inventoryItems(): HasMany
    {
        return $this->hasMany(InventoryItem::class);
    }

    public function serviceOrderParts(): HasMany
    {
        return $this->hasMany(ServiceOrderPart::class);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Get the total quantity in stock across all warehouses.
     */
    protected function getTotalStockAttribute(): int
    {
        return (int) $this->inventoryItems()
            ->where('is_available', true)
            ->sum('quantity');
    }
}

<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

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

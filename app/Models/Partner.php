<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Partner extends Model
{
    use BelongsToTenant;
    use HasFactory;

    protected $fillable = [
        'tenant_id',
        'code',
        'type',
        'company_name',
        'trade_name',
        'document',
        'state_registration',
        'municipal_registration',
        'email',
        'phone',
        'mobile',
        'website',
        'address',
        'address_number',
        'address_complement',
        'neighborhood',
        'city',
        'state',
        'postal_code',
        'country',
        'latitude',
        'longitude',
        'coverage_radius_km',
        'payment_terms',
        'commission_percentage',
        'notes',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'latitude' => 'decimal:8',
            'longitude' => 'decimal:8',
            'coverage_radius_km' => 'integer',
            'payment_terms' => 'integer',
            'commission_percentage' => 'decimal:2',
            'is_active' => 'boolean',
        ];
    }

    // Relationships

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function brands(): HasMany
    {
        return $this->hasMany(PartnerBrand::class);
    }

    public function contacts(): HasMany
    {
        return $this->hasMany(PartnerContact::class);
    }

    public function bankAccounts(): HasMany
    {
        return $this->hasMany(PartnerBankAccount::class);
    }

    public function serviceOrders(): HasMany
    {
        return $this->hasMany(ServiceOrder::class);
    }

    public function inventoryItems(): HasMany
    {
        return $this->hasMany(InventoryItem::class);
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    /**
     * Get the active brands for this partner.
     */
    public function activeBrands(): HasMany
    {
        return $this->hasMany(PartnerBrand::class)->where('is_active', true);
    }

    /**
     * Get all product lines this partner can service (pivot relationship).
     */
    public function productLines(): BelongsToMany
    {
        return $this->belongsToMany(ProductLine::class, 'partner_product_lines')
            ->withPivot('is_active')
            ->withTimestamps();
    }

    /**
     * Get active product lines for this partner.
     */
    public function activeProductLines(): BelongsToMany
    {
        return $this->belongsToMany(ProductLine::class, 'partner_product_lines')
            ->wherePivot('is_active', true)
            ->withTimestamps();
    }

    /**
     * Get all product categories this partner can service (pivot relationship).
     */
    public function productCategories(): BelongsToMany
    {
        return $this->belongsToMany(ProductCategory::class, 'partner_product_categories')
            ->withPivot('is_active')
            ->withTimestamps();
    }

    /**
     * Get active product categories for this partner.
     */
    public function activeProductCategories(): BelongsToMany
    {
        return $this->belongsToMany(ProductCategory::class, 'partner_product_categories')
            ->wherePivot('is_active', true)
            ->withTimestamps();
    }

    /**
     * Check if partner can service a specific brand.
     */
    public function canServiceBrand(int $brandId): bool
    {
        return $this->brands()
            ->where('brand_id', $brandId)
            ->where('is_active', true)
            ->exists();
    }

    /**
     * Check if partner can service a specific product category.
     */
    public function canServiceCategory(int $categoryId): bool
    {
        return $this->productCategories()
            ->wherePivot('product_category_id', $categoryId)
            ->wherePivot('is_active', true)
            ->exists();
    }

    /**
     * Check if partner can service a specific product line.
     */
    public function canServiceProductLine(int $productLineId): bool
    {
        return $this->productLines()
            ->wherePivot('product_line_id', $productLineId)
            ->wherePivot('is_active', true)
            ->exists();
    }
}

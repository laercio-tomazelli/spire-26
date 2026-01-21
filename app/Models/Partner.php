<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Concerns\BelongsToTenant;
use Database\Factories\PartnerFactory;
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
 * @property string $code Código único ex: SP002-GBR
 * @property string $document_type
 * @property string $document
 * @property string|null $state_registration IE
 * @property int $is_tax_exempt
 * @property string $company_name
 * @property string|null $trade_name
 * @property string|null $email
 * @property string|null $email_secondary
 * @property string|null $phone
 * @property string|null $phone_secondary
 * @property string|null $fax
 * @property string|null $contact_name
 * @property string $address
 * @property string|null $address_number
 * @property string|null $address_complement
 * @property string|null $neighborhood
 * @property string $city
 * @property string $state
 * @property string $postal_code
 * @property int|null $company_type_id
 * @property int|null $tax_regime_id
 * @property string $person_type
 * @property string $status
 * @property string|null $level A, B, C
 * @property string|null $category
 * @property string|null $bank_code
 * @property string|null $bank_name
 * @property string|null $bank_agency
 * @property string|null $bank_account
 * @property string|null $pix_key
 * @property int|null $pix_key_type_id
 * @property string|null $account_type
 * @property string|null $bank_observations
 * @property string|null $observations
 * @property int|null $bling_id
 * @property string|null $synced_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property-read Collection<int, PartnerBrand> $activeBrands
 * @property-read int|null $active_brands_count
 * @property-read Collection<int, ProductCategory> $activeProductCategories
 * @property-read int|null $active_product_categories_count
 * @property-read Collection<int, ProductLine> $activeProductLines
 * @property-read int|null $active_product_lines_count
 * @property-read Collection<int, PartnerBankAccount> $bankAccounts
 * @property-read int|null $bank_accounts_count
 * @property-read Collection<int, PartnerBrand> $brands
 * @property-read int|null $brands_count
 * @property-read Collection<int, PartnerContact> $contacts
 * @property-read int|null $contacts_count
 * @property-read Collection<int, InventoryItem> $inventoryItems
 * @property-read int|null $inventory_items_count
 * @property-read Collection<int, ProductCategory> $productCategories
 * @property-read int|null $product_categories_count
 * @property-read Collection<int, ProductLine> $productLines
 * @property-read int|null $product_lines_count
 * @property-read Collection<int, ServiceOrder> $serviceOrders
 * @property-read int|null $service_orders_count
 * @property-read Tenant $tenant
 * @property-read Collection<int, User> $users
 * @property-read int|null $users_count
 *
 * @method static PartnerFactory factory($count = null, $state = [])
 * @method static Builder<static>|Partner forTenant(int $tenantId)
 * @method static Builder<static>|Partner newModelQuery()
 * @method static Builder<static>|Partner newQuery()
 * @method static Builder<static>|Partner query()
 * @method static Builder<static>|Partner whereAccountType($value)
 * @method static Builder<static>|Partner whereAddress($value)
 * @method static Builder<static>|Partner whereAddressComplement($value)
 * @method static Builder<static>|Partner whereAddressNumber($value)
 * @method static Builder<static>|Partner whereBankAccount($value)
 * @method static Builder<static>|Partner whereBankAgency($value)
 * @method static Builder<static>|Partner whereBankCode($value)
 * @method static Builder<static>|Partner whereBankName($value)
 * @method static Builder<static>|Partner whereBankObservations($value)
 * @method static Builder<static>|Partner whereBlingId($value)
 * @method static Builder<static>|Partner whereCategory($value)
 * @method static Builder<static>|Partner whereCity($value)
 * @method static Builder<static>|Partner whereCode($value)
 * @method static Builder<static>|Partner whereCompanyName($value)
 * @method static Builder<static>|Partner whereCompanyTypeId($value)
 * @method static Builder<static>|Partner whereContactName($value)
 * @method static Builder<static>|Partner whereCreatedAt($value)
 * @method static Builder<static>|Partner whereDeletedAt($value)
 * @method static Builder<static>|Partner whereDocument($value)
 * @method static Builder<static>|Partner whereDocumentType($value)
 * @method static Builder<static>|Partner whereEmail($value)
 * @method static Builder<static>|Partner whereEmailSecondary($value)
 * @method static Builder<static>|Partner whereFax($value)
 * @method static Builder<static>|Partner whereId($value)
 * @method static Builder<static>|Partner whereIsTaxExempt($value)
 * @method static Builder<static>|Partner whereLevel($value)
 * @method static Builder<static>|Partner whereNeighborhood($value)
 * @method static Builder<static>|Partner whereObservations($value)
 * @method static Builder<static>|Partner wherePersonType($value)
 * @method static Builder<static>|Partner wherePhone($value)
 * @method static Builder<static>|Partner wherePhoneSecondary($value)
 * @method static Builder<static>|Partner wherePixKey($value)
 * @method static Builder<static>|Partner wherePixKeyTypeId($value)
 * @method static Builder<static>|Partner wherePostalCode($value)
 * @method static Builder<static>|Partner whereState($value)
 * @method static Builder<static>|Partner whereStateRegistration($value)
 * @method static Builder<static>|Partner whereStatus($value)
 * @method static Builder<static>|Partner whereSyncedAt($value)
 * @method static Builder<static>|Partner whereTaxRegimeId($value)
 * @method static Builder<static>|Partner whereTenantId($value)
 * @method static Builder<static>|Partner whereTradeName($value)
 * @method static Builder<static>|Partner whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
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

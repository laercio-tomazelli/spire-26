<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\TenantFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $name
 * @property string|null $trade_name
 * @property string|null $document CNPJ
 * @property string|null $email
 * @property string|null $phone
 * @property bool $is_active
 * @property array<array-key, mixed>|null $settings
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property-read Collection<int, Brand> $brands
 * @property-read int|null $brands_count
 * @property-read Collection<int, Customer> $customers
 * @property-read int|null $customers_count
 * @property-read Collection<int, Invoice> $invoices
 * @property-read int|null $invoices_count
 * @property-read Collection<int, Manufacturer> $manufacturers
 * @property-read int|null $manufacturers_count
 * @property-read Collection<int, Order> $orders
 * @property-read int|null $orders_count
 * @property-read Collection<int, Partner> $partners
 * @property-read int|null $partners_count
 * @property-read Collection<int, ServiceOrder> $serviceOrders
 * @property-read int|null $service_orders_count
 * @property-read Collection<int, User> $users
 * @property-read int|null $users_count
 * @property-read Collection<int, Warehouse> $warehouses
 * @property-read int|null $warehouses_count
 *
 * @method static TenantFactory factory($count = null, $state = [])
 * @method static Builder<static>|Tenant newModelQuery()
 * @method static Builder<static>|Tenant newQuery()
 * @method static Builder<static>|Tenant query()
 * @method static Builder<static>|Tenant whereCreatedAt($value)
 * @method static Builder<static>|Tenant whereDeletedAt($value)
 * @method static Builder<static>|Tenant whereDocument($value)
 * @method static Builder<static>|Tenant whereEmail($value)
 * @method static Builder<static>|Tenant whereId($value)
 * @method static Builder<static>|Tenant whereIsActive($value)
 * @method static Builder<static>|Tenant whereName($value)
 * @method static Builder<static>|Tenant wherePhone($value)
 * @method static Builder<static>|Tenant whereSettings($value)
 * @method static Builder<static>|Tenant whereTradeName($value)
 * @method static Builder<static>|Tenant whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class Tenant extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'document',
        'email',
        'phone',
        'address',
        'city',
        'state',
        'postal_code',
        'country',
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

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function brands(): HasMany
    {
        return $this->hasMany(Brand::class);
    }

    public function manufacturers(): HasMany
    {
        return $this->hasMany(Manufacturer::class);
    }

    public function partners(): HasMany
    {
        return $this->hasMany(Partner::class);
    }

    public function customers(): HasMany
    {
        return $this->hasMany(Customer::class);
    }

    public function serviceOrders(): HasMany
    {
        return $this->hasMany(ServiceOrder::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function warehouses(): HasMany
    {
        return $this->hasMany(Warehouse::class);
    }

    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }

    // public function integrations(): HasMany
    // {
    //     return $this->hasMany(Integration::class);
    // }
}

<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Concerns\BelongsToTenant;
use Database\Factories\CustomerFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $tenant_id
 * @property string $customer_type
 * @property string $document CPF/CNPJ
 * @property string|null $state_registration
 * @property string $name
 * @property string|null $trade_name
 * @property string|null $email
 * @property string|null $phone
 * @property string|null $phone_secondary
 * @property string|null $mobile
 * @property string|null $address
 * @property string|null $address_number
 * @property string|null $address_complement
 * @property string|null $neighborhood
 * @property string|null $city
 * @property string|null $city_code Código IBGE
 * @property string|null $state
 * @property string|null $postal_code
 * @property string $country
 * @property string $country_code
 * @property string|null $birth_date
 * @property string|null $observations
 * @property int $is_from_invoice
 * @property int|null $bling_id
 * @property string|null $synced_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property-read Collection<int, CustomerChange> $changes
 * @property-read int|null $changes_count
 * @property-read string $formatted_document
 * @property-read string $full_address
 * @property-read Collection<int, Order> $orders
 * @property-read int|null $orders_count
 * @property-read Collection<int, ServiceOrder> $serviceOrders
 * @property-read int|null $service_orders_count
 * @property-read Tenant $tenant
 *
 * @method static CustomerFactory factory($count = null, $state = [])
 * @method static Builder<static>|Customer forTenant(int $tenantId)
 * @method static Builder<static>|Customer newModelQuery()
 * @method static Builder<static>|Customer newQuery()
 * @method static Builder<static>|Customer query()
 * @method static Builder<static>|Customer whereAddress($value)
 * @method static Builder<static>|Customer whereAddressComplement($value)
 * @method static Builder<static>|Customer whereAddressNumber($value)
 * @method static Builder<static>|Customer whereBirthDate($value)
 * @method static Builder<static>|Customer whereBlingId($value)
 * @method static Builder<static>|Customer whereCity($value)
 * @method static Builder<static>|Customer whereCityCode($value)
 * @method static Builder<static>|Customer whereCountry($value)
 * @method static Builder<static>|Customer whereCountryCode($value)
 * @method static Builder<static>|Customer whereCreatedAt($value)
 * @method static Builder<static>|Customer whereCustomerType($value)
 * @method static Builder<static>|Customer whereDeletedAt($value)
 * @method static Builder<static>|Customer whereDocument($value)
 * @method static Builder<static>|Customer whereEmail($value)
 * @method static Builder<static>|Customer whereId($value)
 * @method static Builder<static>|Customer whereIsFromInvoice($value)
 * @method static Builder<static>|Customer whereMobile($value)
 * @method static Builder<static>|Customer whereName($value)
 * @method static Builder<static>|Customer whereNeighborhood($value)
 * @method static Builder<static>|Customer whereObservations($value)
 * @method static Builder<static>|Customer wherePhone($value)
 * @method static Builder<static>|Customer wherePhoneSecondary($value)
 * @method static Builder<static>|Customer wherePostalCode($value)
 * @method static Builder<static>|Customer whereState($value)
 * @method static Builder<static>|Customer whereStateRegistration($value)
 * @method static Builder<static>|Customer whereSyncedAt($value)
 * @method static Builder<static>|Customer whereTenantId($value)
 * @method static Builder<static>|Customer whereTradeName($value)
 * @method static Builder<static>|Customer whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class Customer extends Model
{
    use BelongsToTenant;
    use HasFactory;

    protected $fillable = [
        'tenant_id',
        'type',
        'document',
        'name',
        'trade_name',
        'email',
        'phone',
        'mobile',
        'address',
        'address_number',
        'address_complement',
        'neighborhood',
        'city',
        'city_code',
        'state',
        'postal_code',
        'country',
        'latitude',
        'longitude',
        'notes',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'latitude' => 'decimal:8',
            'longitude' => 'decimal:8',
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

    public function changes(): HasMany
    {
        return $this->hasMany(CustomerChange::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Get the formatted document (CPF or CNPJ).
     */
    protected function getFormattedDocumentAttribute(): string
    {
        $doc = $this->document;

        if (strlen($doc) === 11) {
            // CPF: 000.000.000-00
            return preg_replace('/(\d{3})(\d{3})(\d{3})(\d{2})/', '$1.$2.$3-$4', $doc);
        }

        if (strlen($doc) === 14) {
            // CNPJ: 00.000.000/0000-00
            return preg_replace('/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/', '$1.$2.$3/$4-$5', $doc);
        }

        return $doc;
    }

    /**
     * Get the full address.
     */
    protected function getFullAddressAttribute(): string
    {
        $parts = array_filter([
            $this->address,
            $this->address_number,
            $this->address_complement,
            $this->neighborhood,
            $this->city,
            $this->state,
            $this->postal_code,
        ]);

        return implode(', ', $parts);
    }
}

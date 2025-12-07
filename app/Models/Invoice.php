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
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $tenant_id
 * @property string $invoice_number
 * @property string|null $series
 * @property string $invoice_type
 * @property string|null $purpose Normal, Complementar, Devolução
 * @property string $operation_nature
 * @property string|null $presence_indicator
 * @property string|null $destination
 * @property string|null $final_consumer
 * @property string $issuer_document
 * @property string $issuer_name
 * @property string|null $issuer_trade_name
 * @property string $issuer_address
 * @property string $issuer_address_number
 * @property string $issuer_neighborhood
 * @property string|null $issuer_city
 * @property string|null $issuer_city_code
 * @property string $issuer_state
 * @property string $issuer_postal_code
 * @property string $issuer_country
 * @property string|null $issuer_country_code
 * @property string|null $issuer_phone
 * @property string|null $issuer_state_registration
 * @property string|null $issuer_tax_regime
 * @property string $recipient_document
 * @property string $recipient_name
 * @property string $recipient_address
 * @property string|null $recipient_address_number
 * @property string $recipient_neighborhood
 * @property string $recipient_city
 * @property string $recipient_city_code
 * @property string $recipient_state
 * @property string|null $recipient_postal_code
 * @property string|null $recipient_country
 * @property string $recipient_country_code
 * @property string|null $recipient_phone
 * @property string|null $recipient_state_registration
 * @property string|null $recipient_ie_indicator
 * @property numeric $products_total
 * @property numeric $freight_value
 * @property numeric $insurance_value
 * @property numeric $discount_value
 * @property numeric $other_expenses
 * @property numeric $invoice_total
 * @property numeric $icms_base
 * @property numeric $icms_value
 * @property numeric $icms_desonerated
 * @property numeric $icms_fcp
 * @property numeric $icms_st_base
 * @property numeric $icms_st_value
 * @property numeric $icms_fcp_st
 * @property numeric $ipi_value
 * @property numeric $pis_value
 * @property numeric $cofins_value
 * @property numeric $ii_value
 * @property numeric $total_taxed
 * @property string|null $invoice_key
 * @property string|null $status
 * @property string|null $reason
 * @property string|null $additional_info
 * @property Carbon $issue_date
 * @property Carbon $exit_entry_date
 * @property Carbon|null $receipt_date
 * @property bool $is_stock_updated
 * @property int|null $brand_id
 * @property array<array-key, mixed>|null $referenced_invoices
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Brand|null $brand
 * @property-read Collection<int, InvoiceComment> $comments
 * @property-read int|null $comments_count
 * @property-read Collection<int, InvoiceItem> $items
 * @property-read int|null $items_count
 * @property-read Tenant $tenant
 *
 * @method static Builder<static>|Invoice forTenant(int $tenantId)
 * @method static Builder<static>|Invoice newModelQuery()
 * @method static Builder<static>|Invoice newQuery()
 * @method static Builder<static>|Invoice query()
 * @method static Builder<static>|Invoice whereAdditionalInfo($value)
 * @method static Builder<static>|Invoice whereBrandId($value)
 * @method static Builder<static>|Invoice whereCofinsValue($value)
 * @method static Builder<static>|Invoice whereCreatedAt($value)
 * @method static Builder<static>|Invoice whereDestination($value)
 * @method static Builder<static>|Invoice whereDiscountValue($value)
 * @method static Builder<static>|Invoice whereExitEntryDate($value)
 * @method static Builder<static>|Invoice whereFinalConsumer($value)
 * @method static Builder<static>|Invoice whereFreightValue($value)
 * @method static Builder<static>|Invoice whereIcmsBase($value)
 * @method static Builder<static>|Invoice whereIcmsDesonerated($value)
 * @method static Builder<static>|Invoice whereIcmsFcp($value)
 * @method static Builder<static>|Invoice whereIcmsFcpSt($value)
 * @method static Builder<static>|Invoice whereIcmsStBase($value)
 * @method static Builder<static>|Invoice whereIcmsStValue($value)
 * @method static Builder<static>|Invoice whereIcmsValue($value)
 * @method static Builder<static>|Invoice whereId($value)
 * @method static Builder<static>|Invoice whereIiValue($value)
 * @method static Builder<static>|Invoice whereInsuranceValue($value)
 * @method static Builder<static>|Invoice whereInvoiceKey($value)
 * @method static Builder<static>|Invoice whereInvoiceNumber($value)
 * @method static Builder<static>|Invoice whereInvoiceTotal($value)
 * @method static Builder<static>|Invoice whereInvoiceType($value)
 * @method static Builder<static>|Invoice whereIpiValue($value)
 * @method static Builder<static>|Invoice whereIsStockUpdated($value)
 * @method static Builder<static>|Invoice whereIssueDate($value)
 * @method static Builder<static>|Invoice whereIssuerAddress($value)
 * @method static Builder<static>|Invoice whereIssuerAddressNumber($value)
 * @method static Builder<static>|Invoice whereIssuerCity($value)
 * @method static Builder<static>|Invoice whereIssuerCityCode($value)
 * @method static Builder<static>|Invoice whereIssuerCountry($value)
 * @method static Builder<static>|Invoice whereIssuerCountryCode($value)
 * @method static Builder<static>|Invoice whereIssuerDocument($value)
 * @method static Builder<static>|Invoice whereIssuerName($value)
 * @method static Builder<static>|Invoice whereIssuerNeighborhood($value)
 * @method static Builder<static>|Invoice whereIssuerPhone($value)
 * @method static Builder<static>|Invoice whereIssuerPostalCode($value)
 * @method static Builder<static>|Invoice whereIssuerState($value)
 * @method static Builder<static>|Invoice whereIssuerStateRegistration($value)
 * @method static Builder<static>|Invoice whereIssuerTaxRegime($value)
 * @method static Builder<static>|Invoice whereIssuerTradeName($value)
 * @method static Builder<static>|Invoice whereOperationNature($value)
 * @method static Builder<static>|Invoice whereOtherExpenses($value)
 * @method static Builder<static>|Invoice wherePisValue($value)
 * @method static Builder<static>|Invoice wherePresenceIndicator($value)
 * @method static Builder<static>|Invoice whereProductsTotal($value)
 * @method static Builder<static>|Invoice wherePurpose($value)
 * @method static Builder<static>|Invoice whereReason($value)
 * @method static Builder<static>|Invoice whereReceiptDate($value)
 * @method static Builder<static>|Invoice whereRecipientAddress($value)
 * @method static Builder<static>|Invoice whereRecipientAddressNumber($value)
 * @method static Builder<static>|Invoice whereRecipientCity($value)
 * @method static Builder<static>|Invoice whereRecipientCityCode($value)
 * @method static Builder<static>|Invoice whereRecipientCountry($value)
 * @method static Builder<static>|Invoice whereRecipientCountryCode($value)
 * @method static Builder<static>|Invoice whereRecipientDocument($value)
 * @method static Builder<static>|Invoice whereRecipientIeIndicator($value)
 * @method static Builder<static>|Invoice whereRecipientName($value)
 * @method static Builder<static>|Invoice whereRecipientNeighborhood($value)
 * @method static Builder<static>|Invoice whereRecipientPhone($value)
 * @method static Builder<static>|Invoice whereRecipientPostalCode($value)
 * @method static Builder<static>|Invoice whereRecipientState($value)
 * @method static Builder<static>|Invoice whereRecipientStateRegistration($value)
 * @method static Builder<static>|Invoice whereReferencedInvoices($value)
 * @method static Builder<static>|Invoice whereSeries($value)
 * @method static Builder<static>|Invoice whereStatus($value)
 * @method static Builder<static>|Invoice whereTenantId($value)
 * @method static Builder<static>|Invoice whereTotalTaxed($value)
 * @method static Builder<static>|Invoice whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class Invoice extends Model
{
    use BelongsToTenant;
    use HasFactory;

    protected $fillable = [
        'tenant_id',
        'brand_id',
        'invoice_number',
        'series',
        'invoice_type',
        'purpose',
        'operation_nature',
        'presence_indicator',
        'destination',
        'final_consumer',
        'issuer_document',
        'issuer_name',
        'issuer_trade_name',
        'issuer_address',
        'issuer_address_number',
        'issuer_neighborhood',
        'issuer_city',
        'issuer_city_code',
        'issuer_state',
        'issuer_postal_code',
        'issuer_country',
        'issuer_country_code',
        'issuer_phone',
        'issuer_state_registration',
        'issuer_tax_regime',
        'recipient_document',
        'recipient_name',
        'recipient_address',
        'recipient_address_number',
        'recipient_neighborhood',
        'recipient_city',
        'recipient_city_code',
        'recipient_state',
        'recipient_postal_code',
        'recipient_country',
        'recipient_country_code',
        'recipient_phone',
        'recipient_state_registration',
        'recipient_ie_indicator',
        'products_total',
        'freight_value',
        'insurance_value',
        'discount_value',
        'other_expenses',
        'invoice_total',
        'icms_base',
        'icms_value',
        'icms_desonerated',
        'icms_fcp',
        'icms_st_base',
        'icms_st_value',
        'icms_fcp_st',
        'ipi_value',
        'pis_value',
        'cofins_value',
        'ii_value',
        'total_taxed',
        'invoice_key',
        'status',
        'reason',
        'additional_info',
        'issue_date',
        'exit_entry_date',
        'receipt_date',
        'is_stock_updated',
        'referenced_invoices',
    ];

    protected function casts(): array
    {
        return [
            'products_total' => 'decimal:2',
            'freight_value' => 'decimal:2',
            'insurance_value' => 'decimal:2',
            'discount_value' => 'decimal:2',
            'other_expenses' => 'decimal:2',
            'invoice_total' => 'decimal:2',
            'icms_base' => 'decimal:2',
            'icms_value' => 'decimal:2',
            'icms_desonerated' => 'decimal:2',
            'icms_fcp' => 'decimal:2',
            'icms_st_base' => 'decimal:2',
            'icms_st_value' => 'decimal:2',
            'icms_fcp_st' => 'decimal:2',
            'ipi_value' => 'decimal:2',
            'pis_value' => 'decimal:2',
            'cofins_value' => 'decimal:2',
            'ii_value' => 'decimal:2',
            'total_taxed' => 'decimal:2',
            'issue_date' => 'datetime',
            'exit_entry_date' => 'datetime',
            'receipt_date' => 'datetime',
            'is_stock_updated' => 'boolean',
            'referenced_invoices' => 'array',
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

    public function items(): HasMany
    {
        return $this->hasMany(InvoiceItem::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(InvoiceComment::class);
    }

    // Helpers

    public function isEntrada(): bool
    {
        return $this->invoice_type === 'entrada';
    }

    public function isSaida(): bool
    {
        return $this->invoice_type === 'saida';
    }
}

<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

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

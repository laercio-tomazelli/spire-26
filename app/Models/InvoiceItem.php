<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InvoiceItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_id',
        'product_code',
        'ean',
        'product_name',
        'ncm',
        'cfop',
        'cest',
        'unit',
        'quantity',
        'unit_price',
        'total_price',
        'icms_origin',
        'icms_cst',
        'icms_base_mode',
        'icms_base',
        'icms_rate',
        'icms_value',
        'ipi_cst',
        'ipi_framework',
        'ipi_value',
        'pis_cst',
        'pis_base',
        'pis_rate',
        'pis_value',
        'cofins_cst',
        'cofins_base',
        'cofins_rate',
        'cofins_value',
    ];

    protected function casts(): array
    {
        return [
            'quantity' => 'decimal:4',
            'unit_price' => 'decimal:4',
            'total_price' => 'decimal:2',
            'icms_base' => 'decimal:2',
            'icms_rate' => 'decimal:2',
            'icms_value' => 'decimal:2',
            'ipi_value' => 'decimal:2',
            'pis_base' => 'decimal:2',
            'pis_rate' => 'decimal:4',
            'pis_value' => 'decimal:2',
            'cofins_base' => 'decimal:2',
            'cofins_rate' => 'decimal:4',
            'cofins_value' => 'decimal:2',
        ];
    }

    // Relationships

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }
}

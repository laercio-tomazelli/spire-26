<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $invoice_id
 * @property string|null $product_code
 * @property string|null $ean
 * @property string $product_name
 * @property string|null $ncm
 * @property string|null $cfop
 * @property string|null $cest
 * @property string|null $unit
 * @property numeric $quantity
 * @property numeric $unit_price
 * @property numeric $total_price
 * @property string|null $icms_origin
 * @property string|null $icms_cst
 * @property string|null $icms_base_mode
 * @property numeric $icms_base
 * @property numeric $icms_rate
 * @property numeric $icms_value
 * @property string|null $ipi_cst
 * @property string|null $ipi_framework
 * @property numeric $ipi_value
 * @property string|null $pis_cst
 * @property numeric $pis_base
 * @property numeric $pis_rate
 * @property numeric $pis_value
 * @property string|null $cofins_cst
 * @property numeric $cofins_base
 * @property numeric $cofins_rate
 * @property numeric $cofins_value
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Invoice $invoice
 *
 * @method static Builder<static>|InvoiceItem newModelQuery()
 * @method static Builder<static>|InvoiceItem newQuery()
 * @method static Builder<static>|InvoiceItem query()
 * @method static Builder<static>|InvoiceItem whereCest($value)
 * @method static Builder<static>|InvoiceItem whereCfop($value)
 * @method static Builder<static>|InvoiceItem whereCofinsBase($value)
 * @method static Builder<static>|InvoiceItem whereCofinsCst($value)
 * @method static Builder<static>|InvoiceItem whereCofinsRate($value)
 * @method static Builder<static>|InvoiceItem whereCofinsValue($value)
 * @method static Builder<static>|InvoiceItem whereCreatedAt($value)
 * @method static Builder<static>|InvoiceItem whereEan($value)
 * @method static Builder<static>|InvoiceItem whereIcmsBase($value)
 * @method static Builder<static>|InvoiceItem whereIcmsBaseMode($value)
 * @method static Builder<static>|InvoiceItem whereIcmsCst($value)
 * @method static Builder<static>|InvoiceItem whereIcmsOrigin($value)
 * @method static Builder<static>|InvoiceItem whereIcmsRate($value)
 * @method static Builder<static>|InvoiceItem whereIcmsValue($value)
 * @method static Builder<static>|InvoiceItem whereId($value)
 * @method static Builder<static>|InvoiceItem whereInvoiceId($value)
 * @method static Builder<static>|InvoiceItem whereIpiCst($value)
 * @method static Builder<static>|InvoiceItem whereIpiFramework($value)
 * @method static Builder<static>|InvoiceItem whereIpiValue($value)
 * @method static Builder<static>|InvoiceItem whereNcm($value)
 * @method static Builder<static>|InvoiceItem wherePisBase($value)
 * @method static Builder<static>|InvoiceItem wherePisCst($value)
 * @method static Builder<static>|InvoiceItem wherePisRate($value)
 * @method static Builder<static>|InvoiceItem wherePisValue($value)
 * @method static Builder<static>|InvoiceItem whereProductCode($value)
 * @method static Builder<static>|InvoiceItem whereProductName($value)
 * @method static Builder<static>|InvoiceItem whereQuantity($value)
 * @method static Builder<static>|InvoiceItem whereTotalPrice($value)
 * @method static Builder<static>|InvoiceItem whereUnit($value)
 * @method static Builder<static>|InvoiceItem whereUnitPrice($value)
 * @method static Builder<static>|InvoiceItem whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
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

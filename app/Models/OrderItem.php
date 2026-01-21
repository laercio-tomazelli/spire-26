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
 * @property int $order_id
 * @property int|null $part_id
 * @property int|null $service_order_part_id
 * @property string $part_code
 * @property string|null $substitute_part_code
 * @property numeric $quantity
 * @property numeric $unit_price
 * @property string $icms_value
 * @property string $ipi_value
 * @property string $st_value
 * @property string $total_value
 * @property string|null $invoice_number
 * @property string|null $invoice_date
 * @property string|null $manufacturer_invoice_number
 * @property string|null $manufacturer_invoice_date
 * @property int|null $invoice_binding_id
 * @property int $is_invoice_ok
 * @property string|null $billing_status
 * @property int $is_reserved
 * @property int $is_blocked
 * @property int $is_approved
 * @property string|null $verified_at
 * @property string|null $verified_by
 * @property string|null $separated_at
 * @property string|null $separated_by
 * @property string|null $collected_at
 * @property string|null $observations
 * @property string|null $uid
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read InventoryItem|null $inventoryItem
 * @property-read Order $order
 * @property-read Part|null $part
 *
 * @method static Builder<static>|OrderItem newModelQuery()
 * @method static Builder<static>|OrderItem newQuery()
 * @method static Builder<static>|OrderItem query()
 * @method static Builder<static>|OrderItem whereBillingStatus($value)
 * @method static Builder<static>|OrderItem whereCollectedAt($value)
 * @method static Builder<static>|OrderItem whereCreatedAt($value)
 * @method static Builder<static>|OrderItem whereIcmsValue($value)
 * @method static Builder<static>|OrderItem whereId($value)
 * @method static Builder<static>|OrderItem whereInvoiceBindingId($value)
 * @method static Builder<static>|OrderItem whereInvoiceDate($value)
 * @method static Builder<static>|OrderItem whereInvoiceNumber($value)
 * @method static Builder<static>|OrderItem whereIpiValue($value)
 * @method static Builder<static>|OrderItem whereIsApproved($value)
 * @method static Builder<static>|OrderItem whereIsBlocked($value)
 * @method static Builder<static>|OrderItem whereIsInvoiceOk($value)
 * @method static Builder<static>|OrderItem whereIsReserved($value)
 * @method static Builder<static>|OrderItem whereManufacturerInvoiceDate($value)
 * @method static Builder<static>|OrderItem whereManufacturerInvoiceNumber($value)
 * @method static Builder<static>|OrderItem whereObservations($value)
 * @method static Builder<static>|OrderItem whereOrderId($value)
 * @method static Builder<static>|OrderItem wherePartCode($value)
 * @method static Builder<static>|OrderItem wherePartId($value)
 * @method static Builder<static>|OrderItem whereQuantity($value)
 * @method static Builder<static>|OrderItem whereSeparatedAt($value)
 * @method static Builder<static>|OrderItem whereSeparatedBy($value)
 * @method static Builder<static>|OrderItem whereServiceOrderPartId($value)
 * @method static Builder<static>|OrderItem whereStValue($value)
 * @method static Builder<static>|OrderItem whereSubstitutePartCode($value)
 * @method static Builder<static>|OrderItem whereTotalValue($value)
 * @method static Builder<static>|OrderItem whereUid($value)
 * @method static Builder<static>|OrderItem whereUnitPrice($value)
 * @method static Builder<static>|OrderItem whereUpdatedAt($value)
 * @method static Builder<static>|OrderItem whereVerifiedAt($value)
 * @method static Builder<static>|OrderItem whereVerifiedBy($value)
 *
 * @mixin \Eloquent
 */
class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'part_id',
        'inventory_item_id',
        'part_code',
        'part_name',
        'quantity',
        'unit_price',
        'discount',
        'total_price',
        'serial_number',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'quantity' => 'decimal:4',
            'unit_price' => 'decimal:4',
            'discount' => 'decimal:2',
            'total_price' => 'decimal:2',
        ];
    }

    // Relationships

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function part(): BelongsTo
    {
        return $this->belongsTo(Part::class);
    }

    public function inventoryItem(): BelongsTo
    {
        return $this->belongsTo(InventoryItem::class);
    }

    // Boot method to calculate total_price
    protected static function boot(): void
    {
        parent::boot();

        static::saving(function ($model): void {
            $model->total_price = ($model->quantity * $model->unit_price) - $model->discount;
        });
    }
}

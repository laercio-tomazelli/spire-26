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
 * @property int $service_order_id
 * @property int|null $part_id
 * @property string $part_code
 * @property string|null $part_description
 * @property numeric $quantity
 * @property numeric $unit_price
 * @property numeric $total_price
 * @property string|null $section
 * @property string|null $defect_code
 * @property string|null $solution_code
 * @property string|null $symptom_code
 * @property string|null $position
 * @property string|null $type
 * @property string $request_type
 * @property string|null $status
 * @property int $is_approved
 * @property string|null $approval_reason
 * @property string|null $rejection_reason
 * @property string|null $request_reason
 * @property int|null $order_id
 * @property int|null $order_item_id
 * @property string|null $order_date
 * @property string|null $order_number
 * @property int $generates_order
 * @property string|null $invoice_number
 * @property string|null $invoice_date
 * @property string|null $eticket
 * @property string|null $sent_at
 * @property string|null $tracking_code_sent
 * @property string|null $return_date
 * @property string|null $tracking_code_return
 * @property string|null $shipping_observations
 * @property string|null $received_at_cr_date
 * @property string|null $substitute_part_code
 * @property int $is_received
 * @property Carbon|null $received_at
 * @property int $is_applied
 * @property string|null $applied_at
 * @property int $shipping_type
 * @property string|null $partner_part_code
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read InventoryItem|null $inventoryItem
 * @property-read Part|null $part
 * @property-read ServiceOrder $serviceOrder
 *
 * @method static Builder<static>|ServiceOrderPart newModelQuery()
 * @method static Builder<static>|ServiceOrderPart newQuery()
 * @method static Builder<static>|ServiceOrderPart query()
 * @method static Builder<static>|ServiceOrderPart whereAppliedAt($value)
 * @method static Builder<static>|ServiceOrderPart whereApprovalReason($value)
 * @method static Builder<static>|ServiceOrderPart whereCreatedAt($value)
 * @method static Builder<static>|ServiceOrderPart whereDefectCode($value)
 * @method static Builder<static>|ServiceOrderPart whereEticket($value)
 * @method static Builder<static>|ServiceOrderPart whereGeneratesOrder($value)
 * @method static Builder<static>|ServiceOrderPart whereId($value)
 * @method static Builder<static>|ServiceOrderPart whereInvoiceDate($value)
 * @method static Builder<static>|ServiceOrderPart whereInvoiceNumber($value)
 * @method static Builder<static>|ServiceOrderPart whereIsApplied($value)
 * @method static Builder<static>|ServiceOrderPart whereIsApproved($value)
 * @method static Builder<static>|ServiceOrderPart whereIsReceived($value)
 * @method static Builder<static>|ServiceOrderPart whereOrderDate($value)
 * @method static Builder<static>|ServiceOrderPart whereOrderId($value)
 * @method static Builder<static>|ServiceOrderPart whereOrderItemId($value)
 * @method static Builder<static>|ServiceOrderPart whereOrderNumber($value)
 * @method static Builder<static>|ServiceOrderPart wherePartCode($value)
 * @method static Builder<static>|ServiceOrderPart wherePartDescription($value)
 * @method static Builder<static>|ServiceOrderPart wherePartId($value)
 * @method static Builder<static>|ServiceOrderPart wherePartnerPartCode($value)
 * @method static Builder<static>|ServiceOrderPart wherePosition($value)
 * @method static Builder<static>|ServiceOrderPart whereQuantity($value)
 * @method static Builder<static>|ServiceOrderPart whereReceivedAt($value)
 * @method static Builder<static>|ServiceOrderPart whereReceivedAtCrDate($value)
 * @method static Builder<static>|ServiceOrderPart whereRejectionReason($value)
 * @method static Builder<static>|ServiceOrderPart whereRequestReason($value)
 * @method static Builder<static>|ServiceOrderPart whereRequestType($value)
 * @method static Builder<static>|ServiceOrderPart whereReturnDate($value)
 * @method static Builder<static>|ServiceOrderPart whereSection($value)
 * @method static Builder<static>|ServiceOrderPart whereSentAt($value)
 * @method static Builder<static>|ServiceOrderPart whereServiceOrderId($value)
 * @method static Builder<static>|ServiceOrderPart whereShippingObservations($value)
 * @method static Builder<static>|ServiceOrderPart whereShippingType($value)
 * @method static Builder<static>|ServiceOrderPart whereSolutionCode($value)
 * @method static Builder<static>|ServiceOrderPart whereStatus($value)
 * @method static Builder<static>|ServiceOrderPart whereSubstitutePartCode($value)
 * @method static Builder<static>|ServiceOrderPart whereSymptomCode($value)
 * @method static Builder<static>|ServiceOrderPart whereTotalPrice($value)
 * @method static Builder<static>|ServiceOrderPart whereTrackingCodeReturn($value)
 * @method static Builder<static>|ServiceOrderPart whereTrackingCodeSent($value)
 * @method static Builder<static>|ServiceOrderPart whereType($value)
 * @method static Builder<static>|ServiceOrderPart whereUnitPrice($value)
 * @method static Builder<static>|ServiceOrderPart whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class ServiceOrderPart extends Model
{
    use HasFactory;

    protected $fillable = [
        'service_order_id',
        'part_id',
        'inventory_item_id',
        'part_code',
        'part_name',
        'quantity',
        'unit_cost',
        'unit_price',
        'total_price',
        'serial_number',
        'status',
        'requested_at',
        'approved_at',
        'shipped_at',
        'received_at',
        'used_at',
        'returned_at',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'quantity' => 'decimal:4',
            'unit_cost' => 'decimal:4',
            'unit_price' => 'decimal:4',
            'total_price' => 'decimal:2',
            'requested_at' => 'datetime',
            'approved_at' => 'datetime',
            'shipped_at' => 'datetime',
            'received_at' => 'datetime',
            'used_at' => 'datetime',
            'returned_at' => 'datetime',
        ];
    }

    // Relationships

    public function serviceOrder(): BelongsTo
    {
        return $this->belongsTo(ServiceOrder::class);
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
            $model->total_price = $model->quantity * $model->unit_price;
        });
    }
}

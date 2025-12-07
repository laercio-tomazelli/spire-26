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
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $tenant_id
 * @property string|null $uuid
 * @property string $exchange_type
 * @property int|null $service_order_id
 * @property int|null $exchange_service_order_id OS troca
 * @property int|null $customer_id
 * @property int|null $partner_id
 * @property int|null $order_id
 * @property int|null $order_item_id
 * @property int|null $original_model_id
 * @property string|null $original_model_name
 * @property string|null $serial_number
 * @property string|null $retailer_name
 * @property string|null $purchase_invoice_number
 * @property string|null $purchase_invoice_date
 * @property string|null $purchase_value
 * @property string|null $reported_defect
 * @property string|null $product_conditions
 * @property string|null $exchange_decision
 * @property string|null $negotiated_value
 * @property int|null $exchange_model_id
 * @property string|null $exchange_model_name
 * @property int|null $exchange_reason_id
 * @property string|null $exchange_reason_text
 * @property int|null $status_id
 * @property string|null $invoice_evidence_path
 * @property string|null $label_evidence_path
 * @property string|null $defect_evidence_path
 * @property int|null $requested_by
 * @property Carbon|null $requested_at
 * @property int|null $approved_by
 * @property Carbon|null $approved_at
 * @property string|null $approval_notes
 * @property string|null $rejection_reason
 * @property string|null $observations
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read User|null $approvedByUser
 * @property-read Brand|null $brand
 * @property-read Collection<int, ExchangeComment> $comments
 * @property-read int|null $comments_count
 * @property-read Customer|null $customer
 * @property-read Collection<int, ExchangeEvidence> $evidence
 * @property-read int|null $evidence_count
 * @property-read ProductModel|null $productModel
 * @property-read ExchangeReason|null $reason
 * @property-read ServiceOrder|null $serviceOrder
 * @property-read Collection<int, ShipmentItem> $shipments
 * @property-read int|null $shipments_count
 * @property-read ExchangeStatus|null $status
 * @property-read Tenant $tenant
 *
 * @method static Builder<static>|Exchange forTenant(int $tenantId)
 * @method static Builder<static>|Exchange newModelQuery()
 * @method static Builder<static>|Exchange newQuery()
 * @method static Builder<static>|Exchange query()
 * @method static Builder<static>|Exchange whereApprovalNotes($value)
 * @method static Builder<static>|Exchange whereApprovedAt($value)
 * @method static Builder<static>|Exchange whereApprovedBy($value)
 * @method static Builder<static>|Exchange whereCreatedAt($value)
 * @method static Builder<static>|Exchange whereCustomerId($value)
 * @method static Builder<static>|Exchange whereDefectEvidencePath($value)
 * @method static Builder<static>|Exchange whereExchangeDecision($value)
 * @method static Builder<static>|Exchange whereExchangeModelId($value)
 * @method static Builder<static>|Exchange whereExchangeModelName($value)
 * @method static Builder<static>|Exchange whereExchangeReasonId($value)
 * @method static Builder<static>|Exchange whereExchangeReasonText($value)
 * @method static Builder<static>|Exchange whereExchangeServiceOrderId($value)
 * @method static Builder<static>|Exchange whereExchangeType($value)
 * @method static Builder<static>|Exchange whereId($value)
 * @method static Builder<static>|Exchange whereInvoiceEvidencePath($value)
 * @method static Builder<static>|Exchange whereLabelEvidencePath($value)
 * @method static Builder<static>|Exchange whereNegotiatedValue($value)
 * @method static Builder<static>|Exchange whereObservations($value)
 * @method static Builder<static>|Exchange whereOrderId($value)
 * @method static Builder<static>|Exchange whereOrderItemId($value)
 * @method static Builder<static>|Exchange whereOriginalModelId($value)
 * @method static Builder<static>|Exchange whereOriginalModelName($value)
 * @method static Builder<static>|Exchange wherePartnerId($value)
 * @method static Builder<static>|Exchange whereProductConditions($value)
 * @method static Builder<static>|Exchange wherePurchaseInvoiceDate($value)
 * @method static Builder<static>|Exchange wherePurchaseInvoiceNumber($value)
 * @method static Builder<static>|Exchange wherePurchaseValue($value)
 * @method static Builder<static>|Exchange whereRejectionReason($value)
 * @method static Builder<static>|Exchange whereReportedDefect($value)
 * @method static Builder<static>|Exchange whereRequestedAt($value)
 * @method static Builder<static>|Exchange whereRequestedBy($value)
 * @method static Builder<static>|Exchange whereRetailerName($value)
 * @method static Builder<static>|Exchange whereSerialNumber($value)
 * @method static Builder<static>|Exchange whereServiceOrderId($value)
 * @method static Builder<static>|Exchange whereStatusId($value)
 * @method static Builder<static>|Exchange whereTenantId($value)
 * @method static Builder<static>|Exchange whereUpdatedAt($value)
 * @method static Builder<static>|Exchange whereUuid($value)
 *
 * @mixin \Eloquent
 */
class Exchange extends Model
{
    use BelongsToTenant;
    use HasFactory;

    protected $fillable = [
        'tenant_id',
        'brand_id',
        'service_order_id',
        'customer_id',
        'exchange_number',
        'status_id',
        'reason_id',
        'type',
        'product_model_id',
        'product_serial_number',
        'defect_description',
        'requested_at',
        'approved_at',
        'approved_by',
        'shipped_at',
        'received_at',
        'completed_at',
        'canceled_at',
        'cancellation_reason',
        'tracking_code_outbound',
        'tracking_code_inbound',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'requested_at' => 'datetime',
            'approved_at' => 'datetime',
            'shipped_at' => 'datetime',
            'received_at' => 'datetime',
            'completed_at' => 'datetime',
            'canceled_at' => 'datetime',
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

    public function serviceOrder(): BelongsTo
    {
        return $this->belongsTo(ServiceOrder::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function productModel(): BelongsTo
    {
        return $this->belongsTo(ProductModel::class);
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(ExchangeStatus::class, 'status_id');
    }

    public function reason(): BelongsTo
    {
        return $this->belongsTo(ExchangeReason::class, 'reason_id');
    }

    public function approvedByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function comments(): HasMany
    {
        return $this->hasMany(ExchangeComment::class);
    }

    public function evidence(): HasMany
    {
        return $this->hasMany(ExchangeEvidence::class);
    }

    public function shipments(): MorphMany
    {
        return $this->morphMany(ShipmentItem::class, 'shippable');
    }

    // Helpers

    public function isCanceled(): bool
    {
        return ! is_null($this->canceled_at);
    }

    public function isCompleted(): bool
    {
        return ! is_null($this->completed_at);
    }
}

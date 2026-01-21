<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Attributes\Scope;
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
 * @property int $order_number
 * @property int|null $service_order_id
 * @property int|null $exchange_id
 * @property int|null $partner_id
 * @property int|null $brand_id
 * @property string $order_type
 * @property string|null $service_order_type
 * @property int|null $status_id
 * @property string|null $billing_status
 * @property string|null $gateway_status
 * @property int $total_items
 * @property string $total_value
 * @property int|null $gateway_order_id
 * @property string|null $gateway_order_date
 * @property int|null $gateway_input_order_id
 * @property string|null $gateway_input_order_date
 * @property int|null $bling_order_id
 * @property string|null $bling_order_date
 * @property string|null $order_date
 * @property string|null $verified_at
 * @property string|null $separated_at
 * @property string|null $collected_at
 * @property Carbon|null $delivered_at
 * @property string|null $estimated_delivery_date
 * @property int $is_approved
 * @property string|null $approved_at
 * @property int|null $approved_by
 * @property string|null $cancelled_at
 * @property string|null $cancellation_reason
 * @property string|null $observations
 * @property string|null $uid
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property-read Brand|null $brand
 * @property-read Collection<int, OrderComment> $comments
 * @property-read int|null $comments_count
 * @property-read Customer|null $customer
 * @property-read Collection<int, OrderItem> $items
 * @property-read int|null $items_count
 * @property-read Partner|null $partner
 * @property-read ServiceOrder|null $serviceOrder
 * @property-read Collection<int, ShipmentItem> $shipments
 * @property-read int|null $shipments_count
 * @property-read Tenant $tenant
 *
 * @method static Builder<static>|Order completed()
 * @method static Builder<static>|Order forTenant(int $tenantId)
 * @method static Builder<static>|Order newModelQuery()
 * @method static Builder<static>|Order newQuery()
 * @method static Builder<static>|Order pending()
 * @method static Builder<static>|Order query()
 * @method static Builder<static>|Order whereApprovedAt($value)
 * @method static Builder<static>|Order whereApprovedBy($value)
 * @method static Builder<static>|Order whereBillingStatus($value)
 * @method static Builder<static>|Order whereBlingOrderDate($value)
 * @method static Builder<static>|Order whereBlingOrderId($value)
 * @method static Builder<static>|Order whereBrandId($value)
 * @method static Builder<static>|Order whereCancellationReason($value)
 * @method static Builder<static>|Order whereCancelledAt($value)
 * @method static Builder<static>|Order whereCollectedAt($value)
 * @method static Builder<static>|Order whereCreatedAt($value)
 * @method static Builder<static>|Order whereDeletedAt($value)
 * @method static Builder<static>|Order whereDeliveredAt($value)
 * @method static Builder<static>|Order whereEstimatedDeliveryDate($value)
 * @method static Builder<static>|Order whereExchangeId($value)
 * @method static Builder<static>|Order whereGatewayInputOrderDate($value)
 * @method static Builder<static>|Order whereGatewayInputOrderId($value)
 * @method static Builder<static>|Order whereGatewayOrderDate($value)
 * @method static Builder<static>|Order whereGatewayOrderId($value)
 * @method static Builder<static>|Order whereGatewayStatus($value)
 * @method static Builder<static>|Order whereId($value)
 * @method static Builder<static>|Order whereIsApproved($value)
 * @method static Builder<static>|Order whereObservations($value)
 * @method static Builder<static>|Order whereOrderDate($value)
 * @method static Builder<static>|Order whereOrderNumber($value)
 * @method static Builder<static>|Order whereOrderType($value)
 * @method static Builder<static>|Order wherePartnerId($value)
 * @method static Builder<static>|Order whereSeparatedAt($value)
 * @method static Builder<static>|Order whereServiceOrderId($value)
 * @method static Builder<static>|Order whereServiceOrderType($value)
 * @method static Builder<static>|Order whereStatusId($value)
 * @method static Builder<static>|Order whereTenantId($value)
 * @method static Builder<static>|Order whereTotalItems($value)
 * @method static Builder<static>|Order whereTotalValue($value)
 * @method static Builder<static>|Order whereUid($value)
 * @method static Builder<static>|Order whereUpdatedAt($value)
 * @method static Builder<static>|Order whereVerifiedAt($value)
 *
 * @mixin \Eloquent
 */
class Order extends Model
{
    use BelongsToTenant;
    use HasFactory;

    protected $fillable = [
        'tenant_id',
        'brand_id',
        'partner_id',
        'customer_id',
        'service_order_id',
        'order_number',
        'status',
        'type',
        'subtotal',
        'discount_amount',
        'shipping_amount',
        'tax_amount',
        'total',
        'payment_method',
        'payment_status',
        'paid_at',
        'notes',
        'shipped_at',
        'delivered_at',
        'canceled_at',
        'cancellation_reason',
    ];

    protected function casts(): array
    {
        return [
            'subtotal' => 'decimal:2',
            'discount_amount' => 'decimal:2',
            'shipping_amount' => 'decimal:2',
            'tax_amount' => 'decimal:2',
            'total' => 'decimal:2',
            'paid_at' => 'datetime',
            'shipped_at' => 'datetime',
            'delivered_at' => 'datetime',
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

    public function partner(): BelongsTo
    {
        return $this->belongsTo(Partner::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function serviceOrder(): BelongsTo
    {
        return $this->belongsTo(ServiceOrder::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(OrderComment::class);
    }

    public function shipments(): MorphMany
    {
        return $this->morphMany(ShipmentItem::class, 'shippable');
    }

    // Scopes

    #[Scope]
    protected function pending($query)
    {
        return $query->where('status', 'pending');
    }

    #[Scope]
    protected function completed($query)
    {
        return $query->where('status', 'completed');
    }

    // Helpers

    public function isCanceled(): bool
    {
        return ! is_null($this->canceled_at);
    }

    public function isPaid(): bool
    {
        return $this->payment_status === 'paid';
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    /**
     * Recalculate totals.
     */
    public function recalculateTotals(): void
    {
        $this->subtotal = $this->items()->sum('total_price');
        $this->total = $this->subtotal - $this->discount_amount + $this->shipping_amount + $this->tax_amount;
        $this->save();
    }
}

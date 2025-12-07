<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

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

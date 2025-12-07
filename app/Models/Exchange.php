<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

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

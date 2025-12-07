<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ServiceOrder extends Model
{
    use BelongsToTenant;
    use HasFactory;

    protected $fillable = [
        'tenant_id',
        'brand_id',
        'partner_id',
        'customer_id',
        'product_model_id',
        'service_order_number',
        'external_reference',
        'status_id',
        'sub_status_id',
        'service_type_id',
        'warranty_type_id',
        'priority_id',
        'origin_id',
        'defect_id',
        'defect_found_id',
        'solution_id',
        'purchase_date',
        'warranty_expires_at',
        'product_serial_number',
        'product_condition',
        'reported_defect',
        'defect_description',
        'solution_description',
        'internal_notes',
        'received_at',
        'started_at',
        'completed_at',
        'closed_at',
        'canceled_at',
        'cancellation_reason',
        'scheduled_date',
        'scheduled_period',
        'technician_id',
        'assigned_by',
        'assigned_at',
        'rating',
        'rating_comment',
        'rated_at',
        'is_under_warranty',
        'requires_approval',
        'is_approved',
        'approved_by',
        'approved_at',
        'total_parts',
        'total_labor',
        'total_travel',
        'total_discount',
        'total',
        'invoiced_amount',
        'is_invoiced',
        'invoice_number',
        'invoice_key',
        'invoiced_at',
        'monthly_closing_id',
    ];

    protected function casts(): array
    {
        return [
            'purchase_date' => 'date',
            'warranty_expires_at' => 'date',
            'received_at' => 'datetime',
            'started_at' => 'datetime',
            'completed_at' => 'datetime',
            'closed_at' => 'datetime',
            'canceled_at' => 'datetime',
            'scheduled_date' => 'date',
            'assigned_at' => 'datetime',
            'rated_at' => 'datetime',
            'approved_at' => 'datetime',
            'invoiced_at' => 'datetime',
            'is_under_warranty' => 'boolean',
            'requires_approval' => 'boolean',
            'is_approved' => 'boolean',
            'is_invoiced' => 'boolean',
            'total_parts' => 'decimal:2',
            'total_labor' => 'decimal:2',
            'total_travel' => 'decimal:2',
            'total_discount' => 'decimal:2',
            'total' => 'decimal:2',
            'invoiced_amount' => 'decimal:2',
            'rating' => 'integer',
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

    public function productModel(): BelongsTo
    {
        return $this->belongsTo(ProductModel::class);
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(ServiceOrderStatus::class, 'status_id');
    }

    public function subStatus(): BelongsTo
    {
        return $this->belongsTo(ServiceOrderSubStatus::class, 'sub_status_id');
    }

    public function serviceType(): BelongsTo
    {
        return $this->belongsTo(ServiceType::class);
    }

    public function warrantyType(): BelongsTo
    {
        return $this->belongsTo(WarrantyType::class);
    }

    public function priority(): BelongsTo
    {
        return $this->belongsTo(ServiceOrderPriority::class, 'priority_id');
    }

    public function origin(): BelongsTo
    {
        return $this->belongsTo(ServiceOrderOrigin::class, 'origin_id');
    }

    public function defect(): BelongsTo
    {
        return $this->belongsTo(Defect::class);
    }

    public function defectFound(): BelongsTo
    {
        return $this->belongsTo(Defect::class, 'defect_found_id');
    }

    public function solution(): BelongsTo
    {
        return $this->belongsTo(Solution::class);
    }

    public function technician(): BelongsTo
    {
        return $this->belongsTo(User::class, 'technician_id');
    }

    public function assignedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function monthlyClosing(): BelongsTo
    {
        return $this->belongsTo(MonthlyClosing::class);
    }

    // Has Many Relationships

    public function parts(): HasMany
    {
        return $this->hasMany(ServiceOrderPart::class);
    }

    public function costs(): HasMany
    {
        return $this->hasMany(ServiceOrderCost::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(ServiceOrderComment::class);
    }

    public function supports(): HasMany
    {
        return $this->hasMany(ServiceOrderSupport::class);
    }

    public function invites(): HasMany
    {
        return $this->hasMany(ServiceOrderInvite::class);
    }

    public function schedules(): HasMany
    {
        return $this->hasMany(ServiceOrderSchedule::class);
    }

    public function evidence(): HasMany
    {
        return $this->hasMany(ServiceOrderEvidence::class);
    }

    public function changes(): HasMany
    {
        return $this->hasMany(CustomerChange::class);
    }

    // Scopes

    #[Scope]
    protected function open($query)
    {
        return $query->whereNull('closed_at')->whereNull('canceled_at');
    }

    #[Scope]
    protected function closed($query)
    {
        return $query->whereNotNull('closed_at');
    }

    #[Scope]
    protected function canceled($query)
    {
        return $query->whereNotNull('canceled_at');
    }

    #[Scope]
    protected function forBrand($query, int $brandId)
    {
        return $query->where('brand_id', $brandId);
    }

    #[Scope]
    protected function forPartner($query, int $partnerId)
    {
        return $query->where('partner_id', $partnerId);
    }

    // Helpers

    public function isOpen(): bool
    {
        return is_null($this->closed_at) && is_null($this->canceled_at);
    }

    public function isClosed(): bool
    {
        return ! is_null($this->closed_at);
    }

    public function isCanceled(): bool
    {
        return ! is_null($this->canceled_at);
    }

    public function isUnderWarranty(): bool
    {
        if ($this->warranty_expires_at) {
            return $this->warranty_expires_at->isFuture();
        }

        return (bool) $this->is_under_warranty;
    }

    /**
     * Recalculate totals.
     */
    public function recalculateTotals(): void
    {
        $this->total_parts = $this->parts()->sum('total_price');
        $this->total_labor = $this->costs()->where('type', 'labor')->sum('amount');
        $this->total_travel = $this->costs()->where('type', 'travel')->sum('amount');
        $this->total = $this->total_parts + $this->total_labor + $this->total_travel - $this->total_discount;
        $this->save();
    }
}

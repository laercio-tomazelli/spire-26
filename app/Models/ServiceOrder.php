<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Concerns\BelongsToTenant;
use Database\Factories\ServiceOrderFactory;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $tenant_id
 * @property int $order_number Número sequencial por tenant
 * @property string|null $protocol
 * @property string|null $manufacturer_pre_order
 * @property string|null $manufacturer_pre_order_date
 * @property string|null $manufacturer_order
 * @property string|null $manufacturer_order_date
 * @property string|null $partner_order
 * @property string|null $partner_order_date
 * @property string|null $external_id ID sistema externo TPV
 * @property int|null $customer_id
 * @property int|null $partner_id
 * @property int|null $brand_id
 * @property int|null $product_model_id
 * @property int|null $product_category_id
 * @property string|null $model_received Modelo informado
 * @property string|null $serial_number
 * @property string|null $retailer_name
 * @property string|null $purchase_invoice_number
 * @property string|null $purchase_invoice_date
 * @property string $purchase_value
 * @property string|null $purchase_invoice_file
 * @property int|null $service_location_id
 * @property int|null $service_order_type_id
 * @property int|null $service_type_id
 * @property int|null $repair_type_id
 * @property string|null $warranty_type
 * @property int|null $status_id
 * @property int|null $tracking_status_id
 * @property int|null $accept_status_id
 * @property string|null $manufacturer_status
 * @property string|null $reported_defect
 * @property string|null $confirmed_defect
 * @property string|null $defect_condition
 * @property string|null $symptom
 * @property string|null $repair_description
 * @property string|null $accessories
 * @property string|null $conditions
 * @property string|null $observations
 * @property int $is_reentry
 * @property int|null $reentry_order_id
 * @property int $is_critical
 * @property int $is_no_defect
 * @property int $has_parts_used
 * @property int $is_display
 * @property int $is_exchange
 * @property string|null $exchange_type
 * @property string|null $exchange_reason
 * @property int|null $exchange_model_id
 * @property string $exchange_negotiated_value
 * @property string|null $exchange_analysis_date
 * @property string|null $exchange_approval_date
 * @property int|null $exchange_analyzed_by
 * @property string|null $exchange_result
 * @property string $labor_cost
 * @property int|null $distance_km
 * @property string $km_cost
 * @property string $extra_cost
 * @property int|null $visit_count
 * @property string|null $opened_at
 * @property int|null $opened_by
 * @property string|null $evaluated_at
 * @property int|null $evaluated_by
 * @property string|null $repaired_at
 * @property int|null $repaired_by
 * @property Carbon|null $closed_at
 * @property int|null $closed_by
 * @property string|null $manufacturer_closed_at
 * @property string|null $manufacturer_approved_at
 * @property int|null $manufacturer_approved_by
 * @property string|null $accepted_at
 * @property int|null $accepted_by
 * @property string|null $rejected_at
 * @property int|null $rejected_by
 * @property string|null $rejection_reason
 * @property string|null $entry_invoice_number
 * @property string|null $entry_invoice_date
 * @property string|null $entry_tracking_code
 * @property Carbon|null $received_at
 * @property string|null $received_serial
 * @property string|null $exit_invoice_number
 * @property string|null $exit_invoice_date
 * @property string|null $exit_tracking_code
 * @property string|null $exit_sent_at
 * @property string|null $delivered_at
 * @property string|null $collection_invoice_number
 * @property string|null $collection_invoice_date
 * @property string|null $collection_number
 * @property string|null $collection_date
 * @property string|null $scheduled_visit_date
 * @property int|null $closing_type_id
 * @property int $is_admin_closed
 * @property string|null $process_observations
 * @property int|null $parent_order_id OS pai
 * @property int|null $exchange_origin_order_id
 * @property int|null $exchange_order_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property-read User|null $approvedBy
 * @property-read User|null $assignedBy
 * @property-read Brand|null $brand
 * @property-read Collection<int, CustomerChange> $changes
 * @property-read int|null $changes_count
 * @property-read Collection<int, ServiceOrderComment> $comments
 * @property-read int|null $comments_count
 * @property-read Collection<int, ServiceOrderCost> $costs
 * @property-read int|null $costs_count
 * @property-read Customer|null $customer
 * @property-read Defect|null $defect
 * @property-read Defect|null $defectFound
 * @property-read Collection<int, ServiceOrderEvidence> $evidence
 * @property-read int|null $evidence_count
 * @property-read Collection<int, ServiceOrderInvite> $invites
 * @property-read int|null $invites_count
 * @property-read MonthlyClosing|null $monthlyClosing
 * @property-read ServiceOrderOrigin|null $origin
 * @property-read Partner|null $partner
 * @property-read Collection<int, ServiceOrderPart> $parts
 * @property-read int|null $parts_count
 * @property-read ServiceOrderPriority|null $priority
 * @property-read ProductModel|null $productModel
 * @property-read Collection<int, ServiceOrderSchedule> $schedules
 * @property-read int|null $schedules_count
 * @property-read ServiceType|null $serviceType
 * @property-read Solution|null $solution
 * @property-read ServiceOrderStatus|null $status
 * @property-read ServiceOrderSubStatus|null $subStatus
 * @property-read Collection<int, ServiceOrderSupport> $supports
 * @property-read int|null $supports_count
 * @property-read User|null $technician
 * @property-read Tenant $tenant
 * @property-read WarrantyType|null $warrantyType
 *
 * @method static Builder<static>|ServiceOrder canceled()
 * @method static Builder<static>|ServiceOrder closed()
 * @method static ServiceOrderFactory factory($count = null, $state = [])
 * @method static Builder<static>|ServiceOrder forBrand(int $brandId)
 * @method static Builder<static>|ServiceOrder forPartner(int $partnerId)
 * @method static Builder<static>|ServiceOrder forTenant(int $tenantId)
 * @method static Builder<static>|ServiceOrder newModelQuery()
 * @method static Builder<static>|ServiceOrder newQuery()
 * @method static Builder<static>|ServiceOrder open()
 * @method static Builder<static>|ServiceOrder query()
 * @method static Builder<static>|ServiceOrder whereAcceptStatusId($value)
 * @method static Builder<static>|ServiceOrder whereAcceptedAt($value)
 * @method static Builder<static>|ServiceOrder whereAcceptedBy($value)
 * @method static Builder<static>|ServiceOrder whereAccessories($value)
 * @method static Builder<static>|ServiceOrder whereBrandId($value)
 * @method static Builder<static>|ServiceOrder whereClosedAt($value)
 * @method static Builder<static>|ServiceOrder whereClosedBy($value)
 * @method static Builder<static>|ServiceOrder whereClosingTypeId($value)
 * @method static Builder<static>|ServiceOrder whereCollectionDate($value)
 * @method static Builder<static>|ServiceOrder whereCollectionInvoiceDate($value)
 * @method static Builder<static>|ServiceOrder whereCollectionInvoiceNumber($value)
 * @method static Builder<static>|ServiceOrder whereCollectionNumber($value)
 * @method static Builder<static>|ServiceOrder whereConditions($value)
 * @method static Builder<static>|ServiceOrder whereConfirmedDefect($value)
 * @method static Builder<static>|ServiceOrder whereCreatedAt($value)
 * @method static Builder<static>|ServiceOrder whereCustomerId($value)
 * @method static Builder<static>|ServiceOrder whereDefectCondition($value)
 * @method static Builder<static>|ServiceOrder whereDeletedAt($value)
 * @method static Builder<static>|ServiceOrder whereDeliveredAt($value)
 * @method static Builder<static>|ServiceOrder whereDistanceKm($value)
 * @method static Builder<static>|ServiceOrder whereEntryInvoiceDate($value)
 * @method static Builder<static>|ServiceOrder whereEntryInvoiceNumber($value)
 * @method static Builder<static>|ServiceOrder whereEntryTrackingCode($value)
 * @method static Builder<static>|ServiceOrder whereEvaluatedAt($value)
 * @method static Builder<static>|ServiceOrder whereEvaluatedBy($value)
 * @method static Builder<static>|ServiceOrder whereExchangeAnalysisDate($value)
 * @method static Builder<static>|ServiceOrder whereExchangeAnalyzedBy($value)
 * @method static Builder<static>|ServiceOrder whereExchangeApprovalDate($value)
 * @method static Builder<static>|ServiceOrder whereExchangeModelId($value)
 * @method static Builder<static>|ServiceOrder whereExchangeNegotiatedValue($value)
 * @method static Builder<static>|ServiceOrder whereExchangeOrderId($value)
 * @method static Builder<static>|ServiceOrder whereExchangeOriginOrderId($value)
 * @method static Builder<static>|ServiceOrder whereExchangeReason($value)
 * @method static Builder<static>|ServiceOrder whereExchangeResult($value)
 * @method static Builder<static>|ServiceOrder whereExchangeType($value)
 * @method static Builder<static>|ServiceOrder whereExitInvoiceDate($value)
 * @method static Builder<static>|ServiceOrder whereExitInvoiceNumber($value)
 * @method static Builder<static>|ServiceOrder whereExitSentAt($value)
 * @method static Builder<static>|ServiceOrder whereExitTrackingCode($value)
 * @method static Builder<static>|ServiceOrder whereExternalId($value)
 * @method static Builder<static>|ServiceOrder whereExtraCost($value)
 * @method static Builder<static>|ServiceOrder whereHasPartsUsed($value)
 * @method static Builder<static>|ServiceOrder whereId($value)
 * @method static Builder<static>|ServiceOrder whereIsAdminClosed($value)
 * @method static Builder<static>|ServiceOrder whereIsCritical($value)
 * @method static Builder<static>|ServiceOrder whereIsDisplay($value)
 * @method static Builder<static>|ServiceOrder whereIsExchange($value)
 * @method static Builder<static>|ServiceOrder whereIsNoDefect($value)
 * @method static Builder<static>|ServiceOrder whereIsReentry($value)
 * @method static Builder<static>|ServiceOrder whereKmCost($value)
 * @method static Builder<static>|ServiceOrder whereLaborCost($value)
 * @method static Builder<static>|ServiceOrder whereManufacturerApprovedAt($value)
 * @method static Builder<static>|ServiceOrder whereManufacturerApprovedBy($value)
 * @method static Builder<static>|ServiceOrder whereManufacturerClosedAt($value)
 * @method static Builder<static>|ServiceOrder whereManufacturerOrder($value)
 * @method static Builder<static>|ServiceOrder whereManufacturerOrderDate($value)
 * @method static Builder<static>|ServiceOrder whereManufacturerPreOrder($value)
 * @method static Builder<static>|ServiceOrder whereManufacturerPreOrderDate($value)
 * @method static Builder<static>|ServiceOrder whereManufacturerStatus($value)
 * @method static Builder<static>|ServiceOrder whereModelReceived($value)
 * @method static Builder<static>|ServiceOrder whereObservations($value)
 * @method static Builder<static>|ServiceOrder whereOpenedAt($value)
 * @method static Builder<static>|ServiceOrder whereOpenedBy($value)
 * @method static Builder<static>|ServiceOrder whereOrderNumber($value)
 * @method static Builder<static>|ServiceOrder whereParentOrderId($value)
 * @method static Builder<static>|ServiceOrder wherePartnerId($value)
 * @method static Builder<static>|ServiceOrder wherePartnerOrder($value)
 * @method static Builder<static>|ServiceOrder wherePartnerOrderDate($value)
 * @method static Builder<static>|ServiceOrder whereProcessObservations($value)
 * @method static Builder<static>|ServiceOrder whereProductCategoryId($value)
 * @method static Builder<static>|ServiceOrder whereProductModelId($value)
 * @method static Builder<static>|ServiceOrder whereProtocol($value)
 * @method static Builder<static>|ServiceOrder wherePurchaseInvoiceDate($value)
 * @method static Builder<static>|ServiceOrder wherePurchaseInvoiceFile($value)
 * @method static Builder<static>|ServiceOrder wherePurchaseInvoiceNumber($value)
 * @method static Builder<static>|ServiceOrder wherePurchaseValue($value)
 * @method static Builder<static>|ServiceOrder whereReceivedAt($value)
 * @method static Builder<static>|ServiceOrder whereReceivedSerial($value)
 * @method static Builder<static>|ServiceOrder whereReentryOrderId($value)
 * @method static Builder<static>|ServiceOrder whereRejectedAt($value)
 * @method static Builder<static>|ServiceOrder whereRejectedBy($value)
 * @method static Builder<static>|ServiceOrder whereRejectionReason($value)
 * @method static Builder<static>|ServiceOrder whereRepairDescription($value)
 * @method static Builder<static>|ServiceOrder whereRepairTypeId($value)
 * @method static Builder<static>|ServiceOrder whereRepairedAt($value)
 * @method static Builder<static>|ServiceOrder whereRepairedBy($value)
 * @method static Builder<static>|ServiceOrder whereReportedDefect($value)
 * @method static Builder<static>|ServiceOrder whereRetailerName($value)
 * @method static Builder<static>|ServiceOrder whereScheduledVisitDate($value)
 * @method static Builder<static>|ServiceOrder whereSerialNumber($value)
 * @method static Builder<static>|ServiceOrder whereServiceLocationId($value)
 * @method static Builder<static>|ServiceOrder whereServiceOrderTypeId($value)
 * @method static Builder<static>|ServiceOrder whereServiceTypeId($value)
 * @method static Builder<static>|ServiceOrder whereStatusId($value)
 * @method static Builder<static>|ServiceOrder whereSymptom($value)
 * @method static Builder<static>|ServiceOrder whereTenantId($value)
 * @method static Builder<static>|ServiceOrder whereTrackingStatusId($value)
 * @method static Builder<static>|ServiceOrder whereUpdatedAt($value)
 * @method static Builder<static>|ServiceOrder whereVisitCount($value)
 * @method static Builder<static>|ServiceOrder whereWarrantyType($value)
 *
 * @mixin \Eloquent
 */
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

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
 * @property int $cost_type_id
 * @property int $unit_count
 * @property numeric $unit_value
 * @property string $total_value
 * @property string $variable_value
 * @property bool|null $is_approved
 * @property int|null $approved_by
 * @property Carbon|null $approved_at
 * @property string|null $observations
 * @property string|null $validation_observations
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read User|null $approvedByUser
 * @property-read ServiceOrder $serviceOrder
 *
 * @method static Builder<static>|ServiceOrderCost newModelQuery()
 * @method static Builder<static>|ServiceOrderCost newQuery()
 * @method static Builder<static>|ServiceOrderCost query()
 * @method static Builder<static>|ServiceOrderCost whereApprovedAt($value)
 * @method static Builder<static>|ServiceOrderCost whereApprovedBy($value)
 * @method static Builder<static>|ServiceOrderCost whereCostTypeId($value)
 * @method static Builder<static>|ServiceOrderCost whereCreatedAt($value)
 * @method static Builder<static>|ServiceOrderCost whereId($value)
 * @method static Builder<static>|ServiceOrderCost whereIsApproved($value)
 * @method static Builder<static>|ServiceOrderCost whereObservations($value)
 * @method static Builder<static>|ServiceOrderCost whereServiceOrderId($value)
 * @method static Builder<static>|ServiceOrderCost whereTotalValue($value)
 * @method static Builder<static>|ServiceOrderCost whereUnitCount($value)
 * @method static Builder<static>|ServiceOrderCost whereUnitValue($value)
 * @method static Builder<static>|ServiceOrderCost whereUpdatedAt($value)
 * @method static Builder<static>|ServiceOrderCost whereValidationObservations($value)
 * @method static Builder<static>|ServiceOrderCost whereVariableValue($value)
 *
 * @mixin \Eloquent
 */
class ServiceOrderCost extends Model
{
    use HasFactory;

    protected $fillable = [
        'service_order_id',
        'type',
        'description',
        'quantity',
        'unit_value',
        'amount',
        'is_billable',
        'is_approved',
        'approved_by',
        'approved_at',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'quantity' => 'decimal:4',
            'unit_value' => 'decimal:2',
            'amount' => 'decimal:2',
            'is_billable' => 'boolean',
            'is_approved' => 'boolean',
            'approved_at' => 'datetime',
        ];
    }

    // Relationships

    public function serviceOrder(): BelongsTo
    {
        return $this->belongsTo(ServiceOrder::class);
    }

    public function approvedByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    // Boot method to calculate amount
    protected static function boot(): void
    {
        parent::boot();

        static::saving(function ($model): void {
            if (is_null($model->amount)) {
                $model->amount = $model->quantity * $model->unit_value;
            }
        });
    }
}

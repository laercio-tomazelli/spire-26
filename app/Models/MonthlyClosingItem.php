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
 * @property int $monthly_closing_id
 * @property int $service_order_id
 * @property string $service_order_number
 * @property string $status
 * @property Carbon|null $completed_at
 * @property numeric $parts_total
 * @property numeric $labor_total
 * @property numeric $travel_total
 * @property numeric $total
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read MonthlyClosing $monthlyClosing
 * @property-read ServiceOrder $serviceOrder
 *
 * @method static Builder<static>|MonthlyClosingItem newModelQuery()
 * @method static Builder<static>|MonthlyClosingItem newQuery()
 * @method static Builder<static>|MonthlyClosingItem query()
 * @method static Builder<static>|MonthlyClosingItem whereCompletedAt($value)
 * @method static Builder<static>|MonthlyClosingItem whereCreatedAt($value)
 * @method static Builder<static>|MonthlyClosingItem whereId($value)
 * @method static Builder<static>|MonthlyClosingItem whereLaborTotal($value)
 * @method static Builder<static>|MonthlyClosingItem whereMonthlyClosingId($value)
 * @method static Builder<static>|MonthlyClosingItem wherePartsTotal($value)
 * @method static Builder<static>|MonthlyClosingItem whereServiceOrderId($value)
 * @method static Builder<static>|MonthlyClosingItem whereServiceOrderNumber($value)
 * @method static Builder<static>|MonthlyClosingItem whereStatus($value)
 * @method static Builder<static>|MonthlyClosingItem whereTotal($value)
 * @method static Builder<static>|MonthlyClosingItem whereTravelTotal($value)
 * @method static Builder<static>|MonthlyClosingItem whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class MonthlyClosingItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'monthly_closing_id',
        'service_order_id',
        'service_order_number',
        'status',
        'completed_at',
        'parts_total',
        'labor_total',
        'travel_total',
        'total',
    ];

    protected function casts(): array
    {
        return [
            'completed_at' => 'date',
            'parts_total' => 'decimal:2',
            'labor_total' => 'decimal:2',
            'travel_total' => 'decimal:2',
            'total' => 'decimal:2',
        ];
    }

    // Relationships

    public function monthlyClosing(): BelongsTo
    {
        return $this->belongsTo(MonthlyClosing::class);
    }

    public function serviceOrder(): BelongsTo
    {
        return $this->belongsTo(ServiceOrder::class);
    }
}

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
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $tenant_id
 * @property int $brand_id
 * @property int $year
 * @property int $month
 * @property string $status
 * @property Carbon|null $closing_date
 * @property int|null $closed_by
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Brand $brand
 * @property-read User|null $closedByUser
 * @property-read Collection<int, MonthlyClosingDiscount> $discounts
 * @property-read int|null $discounts_count
 * @property-read string $period_label
 * @property-read Collection<int, MonthlyClosingItem> $items
 * @property-read int|null $items_count
 * @property-read Collection<int, ServiceOrder> $serviceOrders
 * @property-read int|null $service_orders_count
 * @property-read Collection<int, MonthlyClosingSummary> $summaries
 * @property-read int|null $summaries_count
 * @property-read Tenant $tenant
 *
 * @method static Builder<static>|MonthlyClosing forTenant(int $tenantId)
 * @method static Builder<static>|MonthlyClosing newModelQuery()
 * @method static Builder<static>|MonthlyClosing newQuery()
 * @method static Builder<static>|MonthlyClosing query()
 * @method static Builder<static>|MonthlyClosing whereBrandId($value)
 * @method static Builder<static>|MonthlyClosing whereClosedBy($value)
 * @method static Builder<static>|MonthlyClosing whereClosingDate($value)
 * @method static Builder<static>|MonthlyClosing whereCreatedAt($value)
 * @method static Builder<static>|MonthlyClosing whereId($value)
 * @method static Builder<static>|MonthlyClosing whereMonth($value)
 * @method static Builder<static>|MonthlyClosing whereStatus($value)
 * @method static Builder<static>|MonthlyClosing whereTenantId($value)
 * @method static Builder<static>|MonthlyClosing whereUpdatedAt($value)
 * @method static Builder<static>|MonthlyClosing whereYear($value)
 *
 * @mixin \Eloquent
 */
class MonthlyClosing extends Model
{
    use BelongsToTenant;
    use HasFactory;

    protected $fillable = [
        'tenant_id',
        'brand_id',
        'year',
        'month',
        'status',
        'closing_date',
        'closed_by',
    ];

    protected function casts(): array
    {
        return [
            'year' => 'integer',
            'month' => 'integer',
            'closing_date' => 'date',
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

    public function closedByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'closed_by');
    }

    public function items(): HasMany
    {
        return $this->hasMany(MonthlyClosingItem::class);
    }

    public function discounts(): HasMany
    {
        return $this->hasMany(MonthlyClosingDiscount::class);
    }

    public function summaries(): HasMany
    {
        return $this->hasMany(MonthlyClosingSummary::class);
    }

    public function serviceOrders(): HasMany
    {
        return $this->hasMany(ServiceOrder::class);
    }

    // Helpers

    public function isOpen(): bool
    {
        return $this->status === 'open';
    }

    public function isClosed(): bool
    {
        return $this->status === 'closed';
    }

    /**
     * Get the period label (e.g., "Janeiro 2024").
     */
    protected function getPeriodLabelAttribute(): string
    {
        $months = [
            1 => 'Janeiro', 2 => 'Fevereiro', 3 => 'Março',
            4 => 'Abril', 5 => 'Maio', 6 => 'Junho',
            7 => 'Julho', 8 => 'Agosto', 9 => 'Setembro',
            10 => 'Outubro', 11 => 'Novembro', 12 => 'Dezembro',
        ];

        return $months[$this->month].' '.$this->year;
    }
}

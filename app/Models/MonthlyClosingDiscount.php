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
 * @property string $description
 * @property numeric $amount
 * @property string|null $reason
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read MonthlyClosing $monthlyClosing
 *
 * @method static Builder<static>|MonthlyClosingDiscount newModelQuery()
 * @method static Builder<static>|MonthlyClosingDiscount newQuery()
 * @method static Builder<static>|MonthlyClosingDiscount query()
 * @method static Builder<static>|MonthlyClosingDiscount whereAmount($value)
 * @method static Builder<static>|MonthlyClosingDiscount whereCreatedAt($value)
 * @method static Builder<static>|MonthlyClosingDiscount whereDescription($value)
 * @method static Builder<static>|MonthlyClosingDiscount whereId($value)
 * @method static Builder<static>|MonthlyClosingDiscount whereMonthlyClosingId($value)
 * @method static Builder<static>|MonthlyClosingDiscount whereReason($value)
 * @method static Builder<static>|MonthlyClosingDiscount whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class MonthlyClosingDiscount extends Model
{
    use HasFactory;

    protected $fillable = [
        'monthly_closing_id',
        'description',
        'amount',
        'reason',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
        ];
    }

    // Relationships

    public function monthlyClosing(): BelongsTo
    {
        return $this->belongsTo(MonthlyClosing::class);
    }
}

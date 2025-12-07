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
 * @property string $category
 * @property int $quantity
 * @property numeric $amount
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read MonthlyClosing $monthlyClosing
 *
 * @method static Builder<static>|MonthlyClosingSummary newModelQuery()
 * @method static Builder<static>|MonthlyClosingSummary newQuery()
 * @method static Builder<static>|MonthlyClosingSummary query()
 * @method static Builder<static>|MonthlyClosingSummary whereAmount($value)
 * @method static Builder<static>|MonthlyClosingSummary whereCategory($value)
 * @method static Builder<static>|MonthlyClosingSummary whereCreatedAt($value)
 * @method static Builder<static>|MonthlyClosingSummary whereId($value)
 * @method static Builder<static>|MonthlyClosingSummary whereMonthlyClosingId($value)
 * @method static Builder<static>|MonthlyClosingSummary whereQuantity($value)
 * @method static Builder<static>|MonthlyClosingSummary whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class MonthlyClosingSummary extends Model
{
    use HasFactory;

    protected $table = 'monthly_closing_summaries';

    protected $fillable = [
        'monthly_closing_id',
        'category',
        'quantity',
        'amount',
    ];

    protected function casts(): array
    {
        return [
            'quantity' => 'integer',
            'amount' => 'decimal:2',
        ];
    }

    // Relationships

    public function monthlyClosing(): BelongsTo
    {
        return $this->belongsTo(MonthlyClosing::class);
    }
}

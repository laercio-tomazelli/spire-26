<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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

<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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

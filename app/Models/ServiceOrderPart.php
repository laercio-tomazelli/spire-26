<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ServiceOrderPart extends Model
{
    use HasFactory;

    protected $fillable = [
        'service_order_id',
        'part_id',
        'inventory_item_id',
        'part_code',
        'part_name',
        'quantity',
        'unit_cost',
        'unit_price',
        'total_price',
        'serial_number',
        'status',
        'requested_at',
        'approved_at',
        'shipped_at',
        'received_at',
        'used_at',
        'returned_at',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'quantity' => 'decimal:4',
            'unit_cost' => 'decimal:4',
            'unit_price' => 'decimal:4',
            'total_price' => 'decimal:2',
            'requested_at' => 'datetime',
            'approved_at' => 'datetime',
            'shipped_at' => 'datetime',
            'received_at' => 'datetime',
            'used_at' => 'datetime',
            'returned_at' => 'datetime',
        ];
    }

    // Relationships

    public function serviceOrder(): BelongsTo
    {
        return $this->belongsTo(ServiceOrder::class);
    }

    public function part(): BelongsTo
    {
        return $this->belongsTo(Part::class);
    }

    public function inventoryItem(): BelongsTo
    {
        return $this->belongsTo(InventoryItem::class);
    }

    // Boot method to calculate total_price
    protected static function boot(): void
    {
        parent::boot();

        static::saving(function ($model): void {
            $model->total_price = $model->quantity * $model->unit_price;
        });
    }
}

<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'part_id',
        'inventory_item_id',
        'part_code',
        'part_name',
        'quantity',
        'unit_price',
        'discount',
        'total_price',
        'serial_number',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'quantity' => 'decimal:4',
            'unit_price' => 'decimal:4',
            'discount' => 'decimal:2',
            'total_price' => 'decimal:2',
        ];
    }

    // Relationships

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
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
            $model->total_price = ($model->quantity * $model->unit_price) - $model->discount;
        });
    }
}

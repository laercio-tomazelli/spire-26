<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $warehouse_id
 * @property int $part_id
 * @property string $part_code
 * @property int $available_quantity
 * @property int $reserved_quantity
 * @property int $pending_quantity
 * @property int $defective_quantity
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property-read Part $part
 * @property-read Collection<int, InventoryTransaction> $transactions
 * @property-read int|null $transactions_count
 * @property-read Warehouse $warehouse
 *
 * @method static Builder<static>|InventoryItem newModelQuery()
 * @method static Builder<static>|InventoryItem newQuery()
 * @method static Builder<static>|InventoryItem query()
 * @method static Builder<static>|InventoryItem whereAvailableQuantity($value)
 * @method static Builder<static>|InventoryItem whereCreatedAt($value)
 * @method static Builder<static>|InventoryItem whereDefectiveQuantity($value)
 * @method static Builder<static>|InventoryItem whereDeletedAt($value)
 * @method static Builder<static>|InventoryItem whereId($value)
 * @method static Builder<static>|InventoryItem wherePartCode($value)
 * @method static Builder<static>|InventoryItem wherePartId($value)
 * @method static Builder<static>|InventoryItem wherePendingQuantity($value)
 * @method static Builder<static>|InventoryItem whereReservedQuantity($value)
 * @method static Builder<static>|InventoryItem whereUpdatedAt($value)
 * @method static Builder<static>|InventoryItem whereWarehouseId($value)
 *
 * @mixin \Eloquent
 */
class InventoryItem extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'warehouse_id',
        'part_id',
        'part_code',
        'available_quantity',
        'reserved_quantity',
        'pending_quantity',
        'defective_quantity',
    ];

    protected function casts(): array
    {
        return [
            'available_quantity' => 'integer',
            'reserved_quantity' => 'integer',
            'pending_quantity' => 'integer',
            'defective_quantity' => 'integer',
        ];
    }

    // Relationships

    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function part(): BelongsTo
    {
        return $this->belongsTo(Part::class);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(InventoryTransaction::class);
    }

    /**
     * Get total quantity (available + reserved).
     */
    protected function getTotalQuantityAttribute(): int
    {
        return $this->available_quantity + $this->reserved_quantity;
    }
}

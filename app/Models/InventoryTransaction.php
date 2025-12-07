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
 * @property int $warehouse_id
 * @property int $part_id
 * @property string $part_code
 * @property int $user_id
 * @property int $transaction_type_id
 * @property int $document_type_id
 * @property string|null $document_number
 * @property numeric $quantity
 * @property string $unit_price
 * @property string $cost_price
 * @property string|null $observations
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read InventoryItem|null $inventoryItem
 * @property-read Part $part
 * @property-read User $user
 * @property-read Warehouse $warehouse
 *
 * @method static Builder<static>|InventoryTransaction newModelQuery()
 * @method static Builder<static>|InventoryTransaction newQuery()
 * @method static Builder<static>|InventoryTransaction query()
 * @method static Builder<static>|InventoryTransaction whereCostPrice($value)
 * @method static Builder<static>|InventoryTransaction whereCreatedAt($value)
 * @method static Builder<static>|InventoryTransaction whereDocumentNumber($value)
 * @method static Builder<static>|InventoryTransaction whereDocumentTypeId($value)
 * @method static Builder<static>|InventoryTransaction whereId($value)
 * @method static Builder<static>|InventoryTransaction whereObservations($value)
 * @method static Builder<static>|InventoryTransaction wherePartCode($value)
 * @method static Builder<static>|InventoryTransaction wherePartId($value)
 * @method static Builder<static>|InventoryTransaction whereQuantity($value)
 * @method static Builder<static>|InventoryTransaction whereTransactionTypeId($value)
 * @method static Builder<static>|InventoryTransaction whereUnitPrice($value)
 * @method static Builder<static>|InventoryTransaction whereUpdatedAt($value)
 * @method static Builder<static>|InventoryTransaction whereUserId($value)
 * @method static Builder<static>|InventoryTransaction whereWarehouseId($value)
 *
 * @mixin \Eloquent
 */
class InventoryTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'warehouse_id',
        'inventory_item_id',
        'part_id',
        'type',
        'quantity',
        'unit_cost',
        'reference_type',
        'reference_id',
        'reason',
        'notes',
        'user_id',
    ];

    protected function casts(): array
    {
        return [
            'quantity' => 'decimal:4',
            'unit_cost' => 'decimal:4',
        ];
    }

    // Relationships

    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function inventoryItem(): BelongsTo
    {
        return $this->belongsTo(InventoryItem::class);
    }

    public function part(): BelongsTo
    {
        return $this->belongsTo(Part::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the reference model (polymorphic).
     */
    public function reference(): ?Model
    {
        if (! $this->reference_type || ! $this->reference_id) {
            return null;
        }

        return $this->reference_type::find($this->reference_id);
    }
}

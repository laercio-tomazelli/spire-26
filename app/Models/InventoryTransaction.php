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
        'part_id',
        'part_code',
        'user_id',
        'transaction_type_id',
        'document_type_id',
        'document_number',
        'quantity',
        'unit_price',
        'cost_price',
        'observations',
    ];

    protected function casts(): array
    {
        return [
            'quantity' => 'integer',
            'unit_price' => 'decimal:2',
            'cost_price' => 'decimal:2',
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

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function transactionType(): BelongsTo
    {
        return $this->belongsTo(TransactionType::class);
    }

    public function documentType(): BelongsTo
    {
        return $this->belongsTo(DocumentType::class);
    }

    /**
     * Check if this is an entry transaction.
     */
    public function isEntry(): bool
    {
        return $this->quantity > 0;
    }

    /**
     * Check if this is an exit transaction.
     */
    public function isExit(): bool
    {
        return $this->quantity < 0;
    }
}

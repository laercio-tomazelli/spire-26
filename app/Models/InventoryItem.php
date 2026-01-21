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
 * @property int $warehouse_id
 * @property int $part_id
 * @property string $part_code
 * @property float $available_quantity
 * @property numeric $reserved_quantity
 * @property int $pending_quantity
 * @property int $defective_quantity
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property-read Part $part
 * @property-read Partner|null $partner
 * @property-read Tenant|null $tenant
 * @property-read Collection<int, InventoryTransaction> $transactions
 * @property-read int|null $transactions_count
 * @property-read Warehouse $warehouse
 *
 * @method static Builder<static>|InventoryItem forTenant(int $tenantId)
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
    use BelongsToTenant;
    use HasFactory;

    protected $fillable = [
        'tenant_id',
        'warehouse_id',
        'partner_id',
        'part_id',
        'quantity',
        'reserved_quantity',
        'unit_cost',
        'lot_number',
        'serial_number',
        'expiration_date',
        'location',
        'is_available',
    ];

    protected function casts(): array
    {
        return [
            'quantity' => 'decimal:4',
            'reserved_quantity' => 'decimal:4',
            'unit_cost' => 'decimal:4',
            'expiration_date' => 'date',
            'is_available' => 'boolean',
        ];
    }

    // Relationships

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function partner(): BelongsTo
    {
        return $this->belongsTo(Partner::class);
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
     * Get available quantity (total - reserved).
     */
    protected function getAvailableQuantityAttribute(): float
    {
        return (float) $this->quantity - (float) $this->reserved_quantity;
    }

    /**
     * Check if item is expired.
     */
    public function isExpired(): bool
    {
        if (! $this->expiration_date) {
            return false;
        }

        return $this->expiration_date->isPast();
    }
}

<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Concerns\BelongsToTenant;
use Database\Factories\WarehouseFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $tenant_id
 * @property string $code
 * @property string $name
 * @property string|null $description
 * @property string|null $location
 * @property string $type
 * @property int|null $brand_id
 * @property int $is_brand_default
 * @property int|null $partner_id
 * @property int|null $bling_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection<int, InventoryItem> $availableItems
 * @property-read int|null $available_items_count
 * @property-read Collection<int, InventoryItem> $inventoryItems
 * @property-read int|null $inventory_items_count
 * @property-read Collection<int, InventoryTransaction> $inventoryTransactions
 * @property-read int|null $inventory_transactions_count
 * @property-read Partner|null $partner
 * @property-read Tenant $tenant
 *
 * @method static WarehouseFactory factory($count = null, $state = [])
 * @method static Builder<static>|Warehouse forTenant(int $tenantId)
 * @method static Builder<static>|Warehouse newModelQuery()
 * @method static Builder<static>|Warehouse newQuery()
 * @method static Builder<static>|Warehouse query()
 * @method static Builder<static>|Warehouse whereBlingId($value)
 * @method static Builder<static>|Warehouse whereBrandId($value)
 * @method static Builder<static>|Warehouse whereCode($value)
 * @method static Builder<static>|Warehouse whereCreatedAt($value)
 * @method static Builder<static>|Warehouse whereDescription($value)
 * @method static Builder<static>|Warehouse whereId($value)
 * @method static Builder<static>|Warehouse whereIsBrandDefault($value)
 * @method static Builder<static>|Warehouse whereLocation($value)
 * @method static Builder<static>|Warehouse whereName($value)
 * @method static Builder<static>|Warehouse wherePartnerId($value)
 * @method static Builder<static>|Warehouse whereTenantId($value)
 * @method static Builder<static>|Warehouse whereType($value)
 * @method static Builder<static>|Warehouse whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class Warehouse extends Model
{
    use BelongsToTenant;
    use HasFactory;

    protected $fillable = [
        'tenant_id',
        'partner_id',
        'name',
        'code',
        'type',
        'address',
        'city',
        'state',
        'postal_code',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    // Relationships

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function partner(): BelongsTo
    {
        return $this->belongsTo(Partner::class);
    }

    public function inventoryItems(): HasMany
    {
        return $this->hasMany(InventoryItem::class);
    }

    public function inventoryTransactions(): HasMany
    {
        return $this->hasMany(InventoryTransaction::class);
    }

    /**
     * Get available inventory items.
     */
    public function availableItems(): HasMany
    {
        return $this->hasMany(InventoryItem::class)
            ->where('is_available', true)
            ->where('quantity', '>', 0);
    }
}

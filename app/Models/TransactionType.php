<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property string $type
 * @property string|null $description
 * @property string $operation
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
class TransactionType extends Model
{
    protected $fillable = [
        'type',
        'description',
        'operation',
    ];

    /**
     * Get the transactions for this type.
     */
    public function transactions(): HasMany
    {
        return $this->hasMany(InventoryTransaction::class);
    }

    /**
     * Check if this is an entry operation.
     */
    public function isEntry(): bool
    {
        return $this->operation === 'in';
    }

    /**
     * Check if this is an exit operation.
     */
    public function isExit(): bool
    {
        return $this->operation === 'out';
    }

    /**
     * Check if this is a transfer operation.
     */
    public function isTransfer(): bool
    {
        return $this->operation === 'transfer';
    }
}

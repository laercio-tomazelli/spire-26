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
 * @property int $product_model_id
 * @property int $part_id
 * @property numeric $quantity
 * @property string|null $line_position
 * @property int $is_provided Fornecido pelo fabricante
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Part $part
 * @property-read ProductModel $productModel
 *
 * @method static Builder<static>|BillOfMaterial newModelQuery()
 * @method static Builder<static>|BillOfMaterial newQuery()
 * @method static Builder<static>|BillOfMaterial query()
 * @method static Builder<static>|BillOfMaterial whereCreatedAt($value)
 * @method static Builder<static>|BillOfMaterial whereId($value)
 * @method static Builder<static>|BillOfMaterial whereIsProvided($value)
 * @method static Builder<static>|BillOfMaterial whereLinePosition($value)
 * @method static Builder<static>|BillOfMaterial wherePartId($value)
 * @method static Builder<static>|BillOfMaterial whereProductModelId($value)
 * @method static Builder<static>|BillOfMaterial whereQuantity($value)
 * @method static Builder<static>|BillOfMaterial whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class BillOfMaterial extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_model_id',
        'part_id',
        'quantity',
        'is_required',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'quantity' => 'decimal:4',
            'is_required' => 'boolean',
        ];
    }

    // Relationships

    public function productModel(): BelongsTo
    {
        return $this->belongsTo(ProductModel::class);
    }

    public function part(): BelongsTo
    {
        return $this->belongsTo(Part::class);
    }
}

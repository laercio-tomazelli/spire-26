<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\ProductCategoryFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $product_line_id
 * @property string $name TV, Monitor, Geladeira, etc.
 * @property string|null $description
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read ProductLine $productLine
 * @property-read Collection<int, ProductModel> $productModels
 * @property-read int|null $product_models_count
 *
 * @method static ProductCategoryFactory factory($count = null, $state = [])
 * @method static Builder<static>|ProductCategory newModelQuery()
 * @method static Builder<static>|ProductCategory newQuery()
 * @method static Builder<static>|ProductCategory query()
 * @method static Builder<static>|ProductCategory whereCreatedAt($value)
 * @method static Builder<static>|ProductCategory whereDescription($value)
 * @method static Builder<static>|ProductCategory whereId($value)
 * @method static Builder<static>|ProductCategory whereName($value)
 * @method static Builder<static>|ProductCategory whereProductLineId($value)
 * @method static Builder<static>|ProductCategory whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class ProductCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_line_id',
        'name',
        'description',
    ];

    // Relationships

    public function productLine(): BelongsTo
    {
        return $this->belongsTo(ProductLine::class);
    }

    public function productModels(): HasMany
    {
        return $this->hasMany(ProductModel::class, 'product_category_id');
    }
}

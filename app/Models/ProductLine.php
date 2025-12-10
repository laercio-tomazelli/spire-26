<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\ProductLineFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $name Linha Branca, Linha Marrom, Informática
 * @property string|null $description
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection<int, Brand> $brands
 * @property-read int|null $brands_count
 * @property-read Collection<int, ProductCategory> $categories
 * @property-read int|null $categories_count
 *
 * @method static ProductLineFactory factory($count = null, $state = [])
 * @method static Builder<static>|ProductLine newModelQuery()
 * @method static Builder<static>|ProductLine newQuery()
 * @method static Builder<static>|ProductLine query()
 * @method static Builder<static>|ProductLine whereCreatedAt($value)
 * @method static Builder<static>|ProductLine whereDescription($value)
 * @method static Builder<static>|ProductLine whereId($value)
 * @method static Builder<static>|ProductLine whereName($value)
 * @method static Builder<static>|ProductLine whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class ProductLine extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
    ];

    // Relationships

    public function brands(): BelongsToMany
    {
        return $this->belongsToMany(Brand::class, 'brand_product_line');
    }

    public function categories(): HasMany
    {
        return $this->hasMany(ProductCategory::class);
    }
}

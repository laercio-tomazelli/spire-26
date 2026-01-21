<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Concerns\BelongsToTenant;
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
 * @property-read Collection<int, ProductCategory> $children
 * @property-read int|null $children_count
 * @property-read ProductCategory|null $parent
 * @property-read Collection<int, ProductModel> $productModels
 * @property-read int|null $product_models_count
 * @property-read Tenant|null $tenant
 *
 * @method static ProductCategoryFactory factory($count = null, $state = [])
 * @method static Builder<static>|ProductCategory forTenant(int $tenantId)
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
    use BelongsToTenant;
    use HasFactory;

    protected $fillable = [
        'tenant_id',
        'name',
        'slug',
        'description',
        'parent_id',
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

    public function parent(): BelongsTo
    {
        return $this->belongsTo(ProductCategory::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(ProductCategory::class, 'parent_id');
    }

    public function productModels(): HasMany
    {
        return $this->hasMany(ProductModel::class, 'category_id');
    }
}

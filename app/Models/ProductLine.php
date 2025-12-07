<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Concerns\BelongsToTenant;
use Database\Factories\ProductLineFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $name Linha Branca, Linha Marrom, Informática
 * @property string|null $description
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Brand|null $brand
 * @property-read Collection<int, ProductModel> $productModels
 * @property-read int|null $product_models_count
 * @property-read Tenant|null $tenant
 *
 * @method static ProductLineFactory factory($count = null, $state = [])
 * @method static Builder<static>|ProductLine forTenant(int $tenantId)
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
    use BelongsToTenant;
    use HasFactory;

    protected $fillable = [
        'tenant_id',
        'brand_id',
        'name',
        'slug',
        'code',
        'description',
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

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    public function productModels(): HasMany
    {
        return $this->hasMany(ProductModel::class);
    }
}

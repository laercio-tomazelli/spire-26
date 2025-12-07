<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Concerns\BelongsToTenant;
use Database\Factories\ManufacturerFactory;
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
 * @property string $name
 * @property string|null $trade_name
 * @property string|null $document CNPJ
 * @property bool $is_active
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property-read Collection<int, Brand> $brands
 * @property-read int|null $brands_count
 * @property-read Tenant $tenant
 *
 * @method static ManufacturerFactory factory($count = null, $state = [])
 * @method static Builder<static>|Manufacturer forTenant(int $tenantId)
 * @method static Builder<static>|Manufacturer newModelQuery()
 * @method static Builder<static>|Manufacturer newQuery()
 * @method static Builder<static>|Manufacturer query()
 * @method static Builder<static>|Manufacturer whereCreatedAt($value)
 * @method static Builder<static>|Manufacturer whereDeletedAt($value)
 * @method static Builder<static>|Manufacturer whereDocument($value)
 * @method static Builder<static>|Manufacturer whereId($value)
 * @method static Builder<static>|Manufacturer whereIsActive($value)
 * @method static Builder<static>|Manufacturer whereName($value)
 * @method static Builder<static>|Manufacturer whereTenantId($value)
 * @method static Builder<static>|Manufacturer whereTradeName($value)
 * @method static Builder<static>|Manufacturer whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class Manufacturer extends Model
{
    use BelongsToTenant;
    use HasFactory;

    protected $fillable = [
        'tenant_id',
        'name',
        'slug',
        'document',
        'email',
        'phone',
        'website',
        'logo_url',
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

    public function brands(): HasMany
    {
        return $this->hasMany(Brand::class);
    }
}

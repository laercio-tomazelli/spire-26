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
 * @property string $code
 * @property string $description
 * @property bool $is_active
 * @property int $display_order
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection<int, Exchange> $exchanges
 * @property-read int|null $exchanges_count
 * @property-read Tenant|null $tenant
 *
 * @method static Builder<static>|ExchangeReason forTenant(int $tenantId)
 * @method static Builder<static>|ExchangeReason newModelQuery()
 * @method static Builder<static>|ExchangeReason newQuery()
 * @method static Builder<static>|ExchangeReason query()
 * @method static Builder<static>|ExchangeReason whereCode($value)
 * @method static Builder<static>|ExchangeReason whereCreatedAt($value)
 * @method static Builder<static>|ExchangeReason whereDescription($value)
 * @method static Builder<static>|ExchangeReason whereDisplayOrder($value)
 * @method static Builder<static>|ExchangeReason whereId($value)
 * @method static Builder<static>|ExchangeReason whereIsActive($value)
 * @method static Builder<static>|ExchangeReason whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class ExchangeReason extends Model
{
    use BelongsToTenant;
    use HasFactory;

    protected $fillable = [
        'tenant_id',
        'name',
        'slug',
        'description',
        'requires_evidence',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'requires_evidence' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    // Relationships

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function exchanges(): HasMany
    {
        return $this->hasMany(Exchange::class, 'reason_id');
    }
}

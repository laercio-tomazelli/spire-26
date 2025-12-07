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
 * @property string $name
 * @property string|null $color
 * @property int $display_order
 * @property bool $is_active
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection<int, Exchange> $exchanges
 * @property-read int|null $exchanges_count
 * @property-read Tenant|null $tenant
 *
 * @method static Builder<static>|ExchangeStatus forTenant(int $tenantId)
 * @method static Builder<static>|ExchangeStatus newModelQuery()
 * @method static Builder<static>|ExchangeStatus newQuery()
 * @method static Builder<static>|ExchangeStatus query()
 * @method static Builder<static>|ExchangeStatus whereCode($value)
 * @method static Builder<static>|ExchangeStatus whereColor($value)
 * @method static Builder<static>|ExchangeStatus whereCreatedAt($value)
 * @method static Builder<static>|ExchangeStatus whereDisplayOrder($value)
 * @method static Builder<static>|ExchangeStatus whereId($value)
 * @method static Builder<static>|ExchangeStatus whereIsActive($value)
 * @method static Builder<static>|ExchangeStatus whereName($value)
 * @method static Builder<static>|ExchangeStatus whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class ExchangeStatus extends Model
{
    use BelongsToTenant;
    use HasFactory;

    protected $table = 'exchange_statuses';

    protected $fillable = [
        'tenant_id',
        'name',
        'slug',
        'color',
        'sort_order',
        'is_final',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'sort_order' => 'integer',
            'is_final' => 'boolean',
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
        return $this->hasMany(Exchange::class, 'status_id');
    }
}

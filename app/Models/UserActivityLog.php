<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int|null $tenant_id
 * @property int|null $user_id
 * @property string $action
 * @property string|null $model_type
 * @property int|null $model_id
 * @property array<array-key, mixed>|null $old_values
 * @property array<array-key, mixed>|null $new_values
 * @property string|null $ip_address
 * @property string|null $user_agent
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Model|null $model
 * @property-read Tenant|null $tenant
 * @property-read User|null $user
 *
 * @method static Builder<static>|UserActivityLog action(string $action)
 * @method static Builder<static>|UserActivityLog forModel(string $modelType, ?int $modelId = null)
 * @method static Builder<static>|UserActivityLog newModelQuery()
 * @method static Builder<static>|UserActivityLog newQuery()
 * @method static Builder<static>|UserActivityLog query()
 * @method static Builder<static>|UserActivityLog whereAction($value)
 * @method static Builder<static>|UserActivityLog whereCreatedAt($value)
 * @method static Builder<static>|UserActivityLog whereId($value)
 * @method static Builder<static>|UserActivityLog whereIpAddress($value)
 * @method static Builder<static>|UserActivityLog whereModelId($value)
 * @method static Builder<static>|UserActivityLog whereModelType($value)
 * @method static Builder<static>|UserActivityLog whereNewValues($value)
 * @method static Builder<static>|UserActivityLog whereOldValues($value)
 * @method static Builder<static>|UserActivityLog whereTenantId($value)
 * @method static Builder<static>|UserActivityLog whereUpdatedAt($value)
 * @method static Builder<static>|UserActivityLog whereUserAgent($value)
 * @method static Builder<static>|UserActivityLog whereUserId($value)
 *
 * @mixin \Eloquent
 */
class UserActivityLog extends Model
{
    use BelongsToTenant;
    use HasFactory;

    protected $fillable = [
        'tenant_id',
        'user_id',
        'action',
        'model_type',
        'model_id',
        'old_values',
        'new_values',
        'ip_address',
        'user_agent',
    ];

    protected function casts(): array
    {
        return [
            'old_values' => 'array',
            'new_values' => 'array',
        ];
    }

    // Relationships

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the related model (polymorphic).
     */
    protected function getModelAttribute(): ?Model
    {
        if (! $this->model_type || ! $this->model_id) {
            return null;
        }

        return $this->model_type::find($this->model_id);
    }

    /**
     * Scope to filter by action.
     */
    #[Scope]
    protected function action($query, string $action)
    {
        return $query->where('action', $action);
    }

    /**
     * Scope to filter by model type.
     */
    #[Scope]
    protected function forModel($query, string $modelType, ?int $modelId = null)
    {
        $query->where('model_type', $modelType);

        if ($modelId) {
            $query->where('model_id', $modelId);
        }

        return $query;
    }
}

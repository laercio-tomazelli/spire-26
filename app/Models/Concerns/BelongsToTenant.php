<?php

declare(strict_types=1);

namespace App\Models\Concerns;

use App\Models\Tenant;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;

/**
 * Trait for models that belong to a tenant.
 *
 * @property int $tenant_id
 * @property-read Tenant $tenant
 */
trait BelongsToTenant
{
    /**
     * Boot the trait.
     */
    public static function bootBelongsToTenant(): void
    {
        static::creating(function ($model): void {
            /** @var User|null $user */
            $user = Auth::user();

            if (empty($model->tenant_id) && $user?->tenant_id) {
                $model->tenant_id = $user->tenant_id;
            }
        });

        // Global scope to filter by current tenant
        static::addGlobalScope('tenant', function (Builder $builder): void {
            /** @var User|null $user */
            $user = Auth::user();

            if ($user?->tenant_id) {
                $builder->where($builder->getModel()->getTable().'.tenant_id', $user->tenant_id);
            }
        });
    }

    /**
     * Get the tenant that owns the model.
     */
    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    /**
     * Scope a query to a specific tenant.
     */
    protected function scopeForTenant(Builder $query, int $tenantId): Builder
    {
        return $query->withoutGlobalScope('tenant')->where('tenant_id', $tenantId);
    }
}

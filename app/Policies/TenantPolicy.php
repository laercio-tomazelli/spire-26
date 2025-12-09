<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Tenant;
use App\Models\User;

final class TenantPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // Only Spire users can view tenants
        return $user->isSpire();
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Tenant $tenant): bool
    {
        // Spire can view all
        if ($user->isSpire()) {
            return true;
        }

        // Users can view their own tenant
        return $user->tenant_id === $tenant->id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Only Spire can create tenants
        return $user->isSpire();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Tenant $tenant): bool
    {
        // Only Spire can update tenants
        return $user->isSpire();
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Tenant $tenant): bool
    {
        // Only Spire can delete tenants
        return $user->isSpire();
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Tenant $tenant): bool
    {
        return $user->isSpire();
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Tenant $tenant): bool
    {
        return $user->isSpire();
    }
}

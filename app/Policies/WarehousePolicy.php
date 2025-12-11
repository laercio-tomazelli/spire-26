<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\User;
use App\Models\Warehouse;

final class WarehousePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        if ($user->isSpire()) {
            return true;
        }

        return $user->hasPermission('warehouses.view');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Warehouse $warehouse): bool
    {
        if ($user->isSpire()) {
            return true;
        }

        // Verificar se pertence ao mesmo tenant
        if ($user->tenant_id !== $warehouse->tenant_id) {
            return false;
        }

        return $user->hasPermission('warehouses.view');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        if ($user->isSpire()) {
            return true;
        }

        return $user->hasPermission('warehouses.create');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Warehouse $warehouse): bool
    {
        if ($user->isSpire()) {
            return true;
        }

        if ($user->tenant_id !== $warehouse->tenant_id) {
            return false;
        }

        return $user->hasPermission('warehouses.edit');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Warehouse $warehouse): bool
    {
        if ($user->isSpire()) {
            return true;
        }

        if ($user->tenant_id !== $warehouse->tenant_id) {
            return false;
        }

        return $user->hasPermission('warehouses.delete');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user): bool
    {
        return $user->isSpire();
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user): bool
    {
        return $user->isSpire();
    }
}

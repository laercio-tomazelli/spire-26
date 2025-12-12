<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Customer;
use App\Models\User;

final class CustomerPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        if ($user->isSpire()) {
            return true;
        }

        return $user->hasPermission('customers.view');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Customer $customer): bool
    {
        if ($user->isSpire()) {
            return true;
        }

        // Verificar se pertence ao mesmo tenant
        if ($user->tenant_id !== $customer->tenant_id) {
            return false;
        }

        return $user->hasPermission('customers.view');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        if ($user->isSpire()) {
            return true;
        }

        return $user->hasPermission('customers.create');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Customer $customer): bool
    {
        if ($user->isSpire()) {
            return true;
        }

        if ($user->tenant_id !== $customer->tenant_id) {
            return false;
        }

        return $user->hasPermission('customers.edit');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Customer $customer): bool
    {
        if ($user->isSpire()) {
            return true;
        }

        if ($user->tenant_id !== $customer->tenant_id) {
            return false;
        }

        return $user->hasPermission('customers.delete');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Customer $customer): bool
    {
        return $this->delete($user, $customer);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user): bool
    {
        return $user->isSpire();
    }
}

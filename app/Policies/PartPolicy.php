<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Part;
use App\Models\User;

final class PartPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // Spire users can view all
        if ($user->isSpire()) {
            return true;
        }

        // Other users need permission
        return $user->hasPermission('parts.view');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Part $part): bool
    {
        // Spire can view all
        if ($user->isSpire()) {
            return true;
        }

        // Check tenant and permission
        return $user->tenant_id === $part->tenant_id
            && $user->hasPermission('parts.view');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Spire users can create
        if ($user->isSpire()) {
            return true;
        }

        return $user->hasPermission('parts.create');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Part $part): bool
    {
        // Spire can update all
        if ($user->isSpire()) {
            return true;
        }

        // Check tenant and permission
        return $user->tenant_id === $part->tenant_id
            && $user->hasPermission('parts.edit');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Part $part): bool
    {
        // Spire can delete all
        if ($user->isSpire()) {
            return true;
        }

        // Check tenant and permission
        return $user->tenant_id === $part->tenant_id
            && $user->hasPermission('parts.delete');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Part $part): bool
    {
        // Spire can restore all
        if ($user->isSpire()) {
            return true;
        }

        // Check tenant and permission
        return $user->tenant_id === $part->tenant_id
            && $user->hasPermission('parts.edit');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Part $part): bool
    {
        // Spire can force delete all
        if ($user->isSpire()) {
            return true;
        }

        // Check tenant and permission
        return $user->tenant_id === $part->tenant_id
            && $user->hasPermission('parts.delete');
    }
}

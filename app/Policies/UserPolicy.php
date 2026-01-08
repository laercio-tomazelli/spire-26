<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\User;

final class UserPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // Spire can view all users
        if ($user->isSpire()) {
            return true;
        }

        // Partner admin can view users
        if ($user->isPartnerAdmin()) {
            return true;
        }

        // Manufacturer admin can view users
        return $user->isManufacturer() && $user->hasPermission('users.view');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, User $model): bool
    {
        // Spire can view all
        if ($user->isSpire()) {
            return true;
        }

        // Users can view themselves
        if ($user->id === $model->id) {
            return true;
        }

        // Partner admin can view users from their partner
        if ($user->isPartnerAdmin()) {
            return $user->partner_id === $model->partner_id;
        }

        // Manufacturer can view users from their manufacturer
        if ($user->isManufacturer()) {
            return $user->manufacturer_id === $model->manufacturer_id;
        }

        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Spire can create users
        if ($user->isSpire()) {
            return true;
        }

        // Partner admin can create users for their partner
        return $user->isPartnerAdmin();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, User $model): bool
    {
        // Spire can update all users
        if ($user->isSpire()) {
            return true;
        }

        // Users can update themselves (profile)
        if ($user->id === $model->id) {
            return true;
        }

        // Partner admin can update users from their partner
        if ($user->isPartnerAdmin()) {
            return $user->partner_id === $model->partner_id
                && ! $model->is_partner_admin; // Can't update other admins
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, User $model): bool
    {
        // Can't delete yourself
        if ($user->id === $model->id) {
            return false;
        }

        // Spire can delete users
        if ($user->isSpire()) {
            return true;
        }

        // Partner admin can delete non-admin users from their partner
        if ($user->isPartnerAdmin()) {
            return $user->partner_id === $model->partner_id
                && ! $model->is_partner_admin;
        }

        return false;
    }

    /**
     * Determine whether the user can change the model's password.
     */
    public function changePassword(User $user, User $model): bool
    {
        // Users can change their own password
        if ($user->id === $model->id) {
            return true;
        }

        // Spire can change passwords
        if ($user->isSpire()) {
            return $user->hasPermission('users.update');
        }

        // Partner admin can change passwords for their users
        if ($user->isPartnerAdmin()) {
            return $user->partner_id === $model->partner_id;
        }

        return false;
    }

    /**
     * Determine whether the user can change the model's roles.
     */
    public function manageRoles(User $user): bool
    {
        // Only Spire can manage roles
        if (! $user->isSpire()) {
            return false;
        }

        return $user->hasPermission('users.manage-roles');
    }

    /**
     * Determine whether the user can impersonate the model.
     */
    public function impersonate(User $user, User $model): bool
    {
        // Only Spire can impersonate
        if (! $user->isSpire()) {
            return false;
        }

        // Can't impersonate yourself
        if ($user->id === $model->id) {
            return false;
        }

        return $user->hasPermission('users.impersonate');
    }
}

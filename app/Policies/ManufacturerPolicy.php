<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Manufacturer;
use App\Models\User;

final class ManufacturerPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // Spire can view all
        if ($user->isSpire()) {
            return true;
        }

        // Manufacturer users can view manufacturers
        return $user->isManufacturer();
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Manufacturer $manufacturer): bool
    {
        // Spire can view all
        if ($user->isSpire()) {
            return true;
        }

        // Manufacturer users can view their own
        if ($user->isManufacturer()) {
            return $user->manufacturer_id === $manufacturer->id;
        }

        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Only Spire can create manufacturers
        return $user->isSpire();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Manufacturer $manufacturer): bool
    {
        // Spire can update all
        if ($user->isSpire()) {
            return true;
        }

        // Manufacturer admin can update their own
        if ($user->isManufacturer() && $user->manufacturer_id === $manufacturer->id) {
            return $user->hasPermission('manufacturers.update');
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user): bool
    {
        // Only Spire can delete manufacturers
        return $user->isSpire();
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

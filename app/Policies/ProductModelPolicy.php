<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\ProductModel;
use App\Models\User;

final class ProductModelPolicy
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

        // Manufacturer users can view their product models
        if ($user->isManufacturer()) {
            return true;
        }

        // Partner users can view product models
        return $user->hasPermission('product_models.view');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, ProductModel $productModel): bool
    {
        // Spire can view all
        if ($user->isSpire()) {
            return true;
        }

        // Manufacturer can view their own product models
        if ($user->isManufacturer()) {
            return $productModel->brand?->manufacturer_id === $user->manufacturer_id;
        }

        // Partners can view active product models
        return $productModel->is_active && $user->hasPermission('product_models.view');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Spire can create
        if ($user->isSpire()) {
            return true;
        }

        // Manufacturer can create product models for their brands
        return $user->isManufacturer() && $user->hasPermission('product_models.create');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, ProductModel $productModel): bool
    {
        // Spire can update all
        if ($user->isSpire()) {
            return true;
        }

        // Manufacturer can update their own product models
        if ($user->isManufacturer()) {
            return $productModel->brand?->manufacturer_id === $user->manufacturer_id
                && $user->hasPermission('product_models.update');
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, ProductModel $productModel): bool
    {
        // Spire can delete all
        if ($user->isSpire()) {
            return true;
        }

        // Manufacturer can delete their own product models (if no service orders)
        if ($user->isManufacturer()) {
            return $productModel->brand?->manufacturer_id === $user->manufacturer_id
                && $user->hasPermission('product_models.delete');
        }

        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, ProductModel $productModel): bool
    {
        return $this->delete($user, $productModel);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user): bool
    {
        // Only Spire can force delete
        return $user->isSpire();
    }
}

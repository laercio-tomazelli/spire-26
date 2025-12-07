<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Partner;
use App\Models\User;

final class PartnerPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // Spire and Manufacturers can view partner list
        if ($user->isSpire()) {
            return true;
        }

        return $user->isManufacturer();
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Partner $partner): bool
    {
        // Spire can view all
        if ($user->isSpire()) {
            return true;
        }

        // Partner users can view their own partner
        if ($user->isPartner()) {
            return $user->partner_id === $partner->id;
        }

        // Manufacturer can view partners that work with their brands
        if ($user->isManufacturer()) {
            return $this->partnerWorksWithManufacturer($partner, $user);
        }

        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Only Spire can create new partners
        return $user->isSpire() && $user->hasPermission('partners.create');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Partner $partner): bool
    {
        // Spire can update all
        if ($user->isSpire()) {
            return $user->hasPermission('partners.update');
        }

        // Partner admin can update their own partner
        if ($user->isPartnerAdmin()) {
            return $user->partner_id === $partner->id;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user): bool
    {
        // Only Spire can delete partners
        return $user->isSpire() && $user->hasPermission('partners.delete');
    }

    /**
     * Determine whether the user can manage users for this partner.
     */
    public function manageUsers(User $user, Partner $partner): bool
    {
        // Spire can manage users
        if ($user->isSpire()) {
            return $user->hasPermission('partners.manage-users');
        }

        // Partner admin can manage their own users
        if ($user->isPartnerAdmin()) {
            return $user->partner_id === $partner->id;
        }

        return false;
    }

    /**
     * Determine whether the user can view financial data.
     */
    public function viewFinancials(User $user, Partner $partner): bool
    {
        // Spire can view financials
        if ($user->isSpire()) {
            return $user->hasPermission('partners.view-financials');
        }

        // Partner admin can view their own financials
        if ($user->isPartnerAdmin()) {
            return $user->partner_id === $partner->id;
        }

        return false;
    }

    /**
     * Determine whether the user can manage inventory for this partner.
     */
    public function manageInventory(User $user, Partner $partner): bool
    {
        // Spire can manage inventory
        if ($user->isSpire()) {
            return true;
        }

        // Partner users can manage their own inventory
        if ($user->isPartner()) {
            return $user->partner_id === $partner->id;
        }

        return false;
    }

    /**
     * Check if partner works with the manufacturer's brands.
     */
    private function partnerWorksWithManufacturer(Partner $partner, User $user): bool
    {
        if (! $user->manufacturer_id) {
            return false;
        }

        return $partner->brands()
            ->where('manufacturer_id', $user->manufacturer_id)
            ->exists();
    }
}

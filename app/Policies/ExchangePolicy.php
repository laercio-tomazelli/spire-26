<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Exchange;
use App\Models\User;

final class ExchangePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(): bool
    {
        // All authenticated users can view exchanges
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Exchange $exchange): bool
    {
        // Spire can view all
        if ($user->isSpire()) {
            return true;
        }

        // Partner can view their own exchanges
        if ($user->isPartner()) {
            return $user->partner_id === $exchange->partner_id;
        }

        // Manufacturer can view exchanges for their brands
        if ($user->isManufacturer()) {
            return $this->isExchangeFromManufacturer($user, $exchange);
        }

        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Spire and Partner can create exchanges
        if ($user->isSpire()) {
            return true;
        }

        return $user->isPartner();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Exchange $exchange): bool
    {
        // Spire can update all
        if ($user->isSpire()) {
            return true;
        }

        // Partner can update their pending exchanges
        if ($user->isPartner() && $user->partner_id === $exchange->partner_id) {
            return $exchange->isPending();
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user): bool
    {
        // Only Spire can delete exchanges
        if (! $user->isSpire()) {
            return false;
        }

        return $user->hasPermission('exchanges.delete');
    }

    /**
     * Determine whether the user can approve the exchange.
     */
    public function approve(User $user, Exchange $exchange): bool
    {
        // Spire can approve all
        if ($user->isSpire()) {
            return $user->hasPermission('exchanges.approve');
        }

        // Manufacturer can approve their brand exchanges
        if ($user->isManufacturer()) {
            return $this->isExchangeFromManufacturer($user, $exchange)
                && $user->hasPermission('exchanges.approve');
        }

        return false;
    }

    /**
     * Determine whether the user can reject the exchange.
     */
    public function reject(User $user, Exchange $exchange): bool
    {
        return $this->approve($user, $exchange);
    }

    /**
     * Determine whether the user can add evidence to the exchange.
     */
    public function addEvidence(User $user, Exchange $exchange): bool
    {
        // Spire can add evidence
        if ($user->isSpire()) {
            return true;
        }

        // Partner can add evidence to their exchanges
        if ($user->isPartner()) {
            return $user->partner_id === $exchange->partner_id;
        }

        return false;
    }

    /**
     * Check if the exchange is from a brand the manufacturer manages.
     */
    private function isExchangeFromManufacturer(User $user, Exchange $exchange): bool
    {
        if (! $user->manufacturer_id) {
            return false;
        }

        // Load the service order to check the manufacturer
        $serviceOrder = $exchange->serviceOrder;

        if (! $serviceOrder) {
            return false;
        }

        $productModel = $serviceOrder->productModel;

        if (! $productModel) {
            return false;
        }

        return $productModel->brand->manufacturer_id === $user->manufacturer_id;
    }
}

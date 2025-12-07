<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Order;
use App\Models\User;

final class OrderPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(): bool
    {
        // All authenticated users can view orders
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Order $order): bool
    {
        // Spire can view all
        if ($user->isSpire()) {
            return true;
        }

        // Partner can view their own orders
        if ($user->isPartner()) {
            return $user->partner_id === $order->partner_id;
        }

        // Manufacturer can view orders for their brands
        if ($user->isManufacturer()) {
            return $this->isOrderFromManufacturer($user, $order);
        }

        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Spire and Partner can create orders
        if ($user->isSpire()) {
            return true;
        }

        return $user->isPartner();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Order $order): bool
    {
        // Spire can update all
        if ($user->isSpire()) {
            return true;
        }

        // Partner can update their pending orders
        if ($user->isPartner() && $user->partner_id === $order->partner_id) {
            return $order->isPending();
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user): bool
    {
        // Only Spire can delete orders
        if (! $user->isSpire()) {
            return false;
        }

        return $user->hasPermission('orders.delete');
    }

    /**
     * Determine whether the user can approve the order.
     */
    public function approve(User $user, Order $order): bool
    {
        // Spire can approve all orders
        if ($user->isSpire()) {
            return $user->hasPermission('orders.approve');
        }

        // Manufacturer can approve orders for their brands
        if ($user->isManufacturer()) {
            return $this->isOrderFromManufacturer($user, $order)
                && $user->hasPermission('orders.approve');
        }

        return false;
    }

    /**
     * Determine whether the user can cancel the order.
     */
    public function cancel(User $user, Order $order): bool
    {
        // Spire can cancel any order
        if ($user->isSpire()) {
            return true;
        }

        // Partner can cancel their pending orders
        if ($user->isPartner() && $user->partner_id === $order->partner_id) {
            return $order->isPending();
        }

        // Manufacturer can cancel orders for their brands
        if ($user->isManufacturer()) {
            return $this->isOrderFromManufacturer($user, $order);
        }

        return false;
    }

    /**
     * Check if the order is from a brand the manufacturer manages.
     */
    private function isOrderFromManufacturer(User $user, Order $order): bool
    {
        if (! $user->manufacturer_id) {
            return false;
        }

        return $order->manufacturer_id === $user->manufacturer_id;
    }
}

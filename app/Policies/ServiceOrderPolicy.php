<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\ServiceOrder;
use App\Models\User;

final class ServiceOrderPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // Clients can only see their own orders (handled by scope)
        // All internal users can view orders list
        if ($user->isInternal()) {
            return true;
        }

        return $user->isClient();
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, ServiceOrder $serviceOrder): bool
    {
        // Spire can view all
        if ($user->isSpire()) {
            return true;
        }

        // Partner can view orders from their partner
        if ($user->isPartner()) {
            return $user->partner_id === $serviceOrder->partner_id;
        }

        // Manufacturer can view orders for their brands
        if ($user->isManufacturer()) {
            return $this->isOrderFromManufacturerBrand($user, $serviceOrder);
        }

        // Client can view their own orders
        if ($user->isClient()) {
            return $user->customer_id === $serviceOrder->customer_id;
        }

        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Only Spire and Partner users can create service orders
        if ($user->isSpire()) {
            return true;
        }

        return $user->isPartner();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, ServiceOrder $serviceOrder): bool
    {
        // Spire can update all
        if ($user->isSpire()) {
            return true;
        }

        // Partner can update their own orders
        if ($user->isPartner()) {
            return $user->partner_id === $serviceOrder->partner_id;
        }

        // Manufacturer can update orders for their brands (limited fields)
        if ($user->isManufacturer()) {
            return $this->isOrderFromManufacturerBrand($user, $serviceOrder);
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user): bool
    {
        // Only Spire can delete service orders
        if (! $user->isSpire()) {
            return false;
        }

        // Can only delete draft/pending orders
        return $user->hasPermission('service-orders.delete');
    }

    /**
     * Determine whether the user can change the status of the service order.
     */
    public function changeStatus(User $user, ServiceOrder $serviceOrder): bool
    {
        // Spire can always change status
        if ($user->isSpire()) {
            return true;
        }

        // Partner can change status of their orders
        if ($user->isPartner()) {
            return $user->partner_id === $serviceOrder->partner_id;
        }

        // Manufacturer can change status for their brand orders
        if ($user->isManufacturer()) {
            return $this->isOrderFromManufacturerBrand($user, $serviceOrder);
        }

        return false;
    }

    /**
     * Determine whether the user can assign a technician.
     */
    public function assignTechnician(User $user, ServiceOrder $serviceOrder): bool
    {
        // Only Partner admin or Spire can assign technicians
        if ($user->isSpire()) {
            return true;
        }

        if ($user->isPartnerAdmin()) {
            return $user->partner_id === $serviceOrder->partner_id;
        }

        return false;
    }

    /**
     * Determine whether the user can add parts to the service order.
     */
    public function addParts(User $user, ServiceOrder $serviceOrder): bool
    {
        // Spire can add parts
        if ($user->isSpire()) {
            return true;
        }

        // Partner (including technicians) can add parts to their orders
        if ($user->isPartner()) {
            return $user->partner_id === $serviceOrder->partner_id;
        }

        return false;
    }

    /**
     * Determine whether the user can close the service order.
     */
    public function close(User $user, ServiceOrder $serviceOrder): bool
    {
        return $this->changeStatus($user, $serviceOrder);
    }

    /**
     * Check if the order is from a brand the manufacturer manages.
     */
    private function isOrderFromManufacturerBrand(User $user, ServiceOrder $serviceOrder): bool
    {
        if (! $user->manufacturer_id) {
            return false;
        }

        // Load the product model's brand to check manufacturer
        $productModel = $serviceOrder->productModel;

        if (! $productModel) {
            return false;
        }

        return $productModel->brand->manufacturer_id === $user->manufacturer_id;
    }
}

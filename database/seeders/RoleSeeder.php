<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use App\Models\Tenant;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $tenant = Tenant::first();

        if (! $tenant) {
            return;
        }

        // Super Admin - system level (no tenant)
        $superAdmin = Role::updateOrCreate(
            ['slug' => 'super-admin'],
            [
                'tenant_id' => null,
                'name' => 'Super Administrador',
                'description' => 'Acesso total ao sistema (bypass all)',
                'is_system' => true,
            ],
        );
        $superAdmin->permissions()->sync(Permission::pluck('id'));

        // Admin - todas as permissões do tenant
        $admin = Role::updateOrCreate(
            ['slug' => 'admin', 'tenant_id' => $tenant->id],
            [
                'name' => 'Administrador',
                'description' => 'Acesso total ao sistema',
                'is_system' => true,
            ],
        );
        $admin->permissions()->sync(Permission::pluck('id'));

        // Manager
        $manager = Role::updateOrCreate(
            ['slug' => 'manager', 'tenant_id' => $tenant->id],
            [
                'name' => 'Gerente',
                'description' => 'Gerenciamento de operações',
                'is_system' => false,
            ],
        );
        $managerPermissions = Permission::whereIn('slug', [
            'service-orders.view', 'service-orders.create', 'service-orders.update',
            'service-orders.change-status', 'service-orders.assign-technician',
            'service-orders.add-parts', 'service-orders.close', 'service-orders.export',
            'customers.view', 'customers.create', 'customers.update',
            'partners.view',
            'parts.view',
            'inventory.view', 'inventory.adjust', 'inventory.transfer',
            'orders.view', 'orders.create', 'orders.update', 'orders.cancel',
            'exchanges.view', 'exchanges.create', 'exchanges.update',
            'shipments.view', 'shipments.track',
            'reports.view', 'reports.export',
            'users.view',
        ])->pluck('id');
        $manager->permissions()->sync($managerPermissions);

        // Operator
        $operator = Role::updateOrCreate(
            ['slug' => 'operator', 'tenant_id' => $tenant->id],
            [
                'name' => 'Operador',
                'description' => 'Operações do dia-a-dia',
                'is_system' => false,
            ],
        );
        $operatorPermissions = Permission::whereIn('slug', [
            'service-orders.view', 'service-orders.create', 'service-orders.update',
            'service-orders.change-status', 'service-orders.add-parts',
            'customers.view', 'customers.create', 'customers.update',
            'parts.view',
            'inventory.view',
            'orders.view', 'orders.create',
            'exchanges.view', 'exchanges.create',
            'shipments.view',
        ])->pluck('id');
        $operator->permissions()->sync($operatorPermissions);

        // Technician
        $technician = Role::updateOrCreate(
            ['slug' => 'technician', 'tenant_id' => $tenant->id],
            [
                'name' => 'Técnico',
                'description' => 'Acesso para técnicos de campo',
                'is_system' => false,
            ],
        );
        $technicianPermissions = Permission::whereIn('slug', [
            'service-orders.view', 'service-orders.update', 'service-orders.change-status',
            'service-orders.add-parts',
            'customers.view',
            'parts.view',
            'inventory.view',
        ])->pluck('id');
        $technician->permissions()->sync($technicianPermissions);

        // Manufacturer Manager
        $manufacturerManager = Role::updateOrCreate(
            ['slug' => 'manufacturer-manager', 'tenant_id' => $tenant->id],
            [
                'name' => 'Gerente de Fabricante',
                'description' => 'Gerenciamento de operações do fabricante',
                'is_system' => false,
            ],
        );
        $manufacturerPermissions = Permission::whereIn('slug', [
            'service-orders.view', 'service-orders.view-all', 'service-orders.export',
            'service-orders.approve', 'service-orders.change-status',
            'partners.view',
            'parts.view',
            'orders.view', 'orders.approve',
            'exchanges.view', 'exchanges.approve', 'exchanges.reject',
            'shipments.view', 'shipments.create', 'shipments.track',
            'reports.view', 'reports.export',
            'products.view', 'products.create', 'products.update',
            'products.manage-parts', 'products.manage-bom',
        ])->pluck('id');
        $manufacturerManager->permissions()->sync($manufacturerPermissions);

        // Manufacturer Operator
        $manufacturerOperator = Role::updateOrCreate(
            ['slug' => 'manufacturer-operator', 'tenant_id' => $tenant->id],
            [
                'name' => 'Operador de Fabricante',
                'description' => 'Operações do fabricante',
                'is_system' => false,
            ],
        );
        $manufacturerOpPermissions = Permission::whereIn('slug', [
            'service-orders.view', 'service-orders.view-all',
            'partners.view',
            'parts.view',
            'orders.view',
            'exchanges.view',
            'shipments.view', 'shipments.track',
            'products.view',
        ])->pluck('id');
        $manufacturerOperator->permissions()->sync($manufacturerOpPermissions);

        // Viewer
        Role::updateOrCreate(
            ['slug' => 'viewer', 'tenant_id' => $tenant->id],
            [
                'name' => 'Visualizador',
                'description' => 'Somente visualização',
                'is_system' => false,
            ],
        );
    }
}

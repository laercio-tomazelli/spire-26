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

        // Admin - todas as permissões
        $admin = Role::create([
            'tenant_id' => $tenant->id,
            'name' => 'Administrador',
            'slug' => 'admin',
            'description' => 'Acesso total ao sistema',
            'is_system' => true,
        ]);
        $admin->permissions()->attach(Permission::pluck('id'));

        // Manager
        $manager = Role::create([
            'tenant_id' => $tenant->id,
            'name' => 'Gerente',
            'slug' => 'manager',
            'description' => 'Gerenciamento de operações',
            'is_system' => false,
        ]);
        $managerPermissions = Permission::whereIn('slug', [
            'service-orders.view', 'service-orders.create', 'service-orders.edit',
            'service-orders.approve', 'service-orders.invoice',
            'customers.view', 'customers.create', 'customers.edit',
            'partners.view', 'partners.create', 'partners.edit',
            'parts.view', 'parts.create', 'parts.edit',
            'inventory.view', 'inventory.adjust',
            'orders.view', 'orders.create', 'orders.edit',
            'exchanges.view', 'exchanges.create', 'exchanges.approve',
            'invoices.view', 'invoices.issue',
            'reports.view', 'reports.export',
        ])->pluck('id');
        $manager->permissions()->attach($managerPermissions);

        // Operator
        $operator = Role::create([
            'tenant_id' => $tenant->id,
            'name' => 'Operador',
            'slug' => 'operator',
            'description' => 'Operações do dia-a-dia',
            'is_system' => false,
        ]);
        $operatorPermissions = Permission::whereIn('slug', [
            'service-orders.view', 'service-orders.create', 'service-orders.edit',
            'customers.view', 'customers.create', 'customers.edit',
            'parts.view',
            'inventory.view',
            'orders.view', 'orders.create',
            'exchanges.view', 'exchanges.create',
        ])->pluck('id');
        $operator->permissions()->attach($operatorPermissions);

        // Technician
        $technician = Role::create([
            'tenant_id' => $tenant->id,
            'name' => 'Técnico',
            'slug' => 'technician',
            'description' => 'Acesso para técnicos de campo',
            'is_system' => false,
        ]);
        $technicianPermissions = Permission::whereIn('slug', [
            'service-orders.view', 'service-orders.edit',
            'customers.view',
            'parts.view',
            'inventory.view',
        ])->pluck('id');
        $technician->permissions()->attach($technicianPermissions);

        // Viewer
        Role::create([
            'tenant_id' => $tenant->id,
            'name' => 'Visualizador',
            'slug' => 'viewer',
            'description' => 'Somente visualização',
            'is_system' => false,
        ]);
    }
}

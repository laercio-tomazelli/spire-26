<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            // Service Orders
            ['name' => 'Ver Ordens de Serviço', 'slug' => 'service-orders.view', 'group' => 'service_orders'],
            ['name' => 'Criar Ordens de Serviço', 'slug' => 'service-orders.create', 'group' => 'service_orders'],
            ['name' => 'Editar Ordens de Serviço', 'slug' => 'service-orders.edit', 'group' => 'service_orders'],
            ['name' => 'Cancelar Ordens de Serviço', 'slug' => 'service-orders.cancel', 'group' => 'service_orders'],
            ['name' => 'Aprovar Ordens de Serviço', 'slug' => 'service-orders.approve', 'group' => 'service_orders'],
            ['name' => 'Faturar Ordens de Serviço', 'slug' => 'service-orders.invoice', 'group' => 'service_orders'],

            // Customers
            ['name' => 'Ver Clientes', 'slug' => 'customers.view', 'group' => 'customers'],
            ['name' => 'Criar Clientes', 'slug' => 'customers.create', 'group' => 'customers'],
            ['name' => 'Editar Clientes', 'slug' => 'customers.edit', 'group' => 'customers'],
            ['name' => 'Excluir Clientes', 'slug' => 'customers.delete', 'group' => 'customers'],

            // Partners
            ['name' => 'Ver Parceiros', 'slug' => 'partners.view', 'group' => 'partners'],
            ['name' => 'Criar Parceiros', 'slug' => 'partners.create', 'group' => 'partners'],
            ['name' => 'Editar Parceiros', 'slug' => 'partners.edit', 'group' => 'partners'],
            ['name' => 'Excluir Parceiros', 'slug' => 'partners.delete', 'group' => 'partners'],

            // Parts & Inventory
            ['name' => 'Ver Peças', 'slug' => 'parts.view', 'group' => 'inventory'],
            ['name' => 'Criar Peças', 'slug' => 'parts.create', 'group' => 'inventory'],
            ['name' => 'Editar Peças', 'slug' => 'parts.edit', 'group' => 'inventory'],
            ['name' => 'Ver Estoque', 'slug' => 'inventory.view', 'group' => 'inventory'],
            ['name' => 'Ajustar Estoque', 'slug' => 'inventory.adjust', 'group' => 'inventory'],

            // Orders
            ['name' => 'Ver Pedidos', 'slug' => 'orders.view', 'group' => 'orders'],
            ['name' => 'Criar Pedidos', 'slug' => 'orders.create', 'group' => 'orders'],
            ['name' => 'Editar Pedidos', 'slug' => 'orders.edit', 'group' => 'orders'],
            ['name' => 'Cancelar Pedidos', 'slug' => 'orders.cancel', 'group' => 'orders'],

            // Exchanges
            ['name' => 'Ver Trocas', 'slug' => 'exchanges.view', 'group' => 'exchanges'],
            ['name' => 'Criar Trocas', 'slug' => 'exchanges.create', 'group' => 'exchanges'],
            ['name' => 'Aprovar Trocas', 'slug' => 'exchanges.approve', 'group' => 'exchanges'],

            // Invoices
            ['name' => 'Ver Notas Fiscais', 'slug' => 'invoices.view', 'group' => 'invoices'],
            ['name' => 'Emitir Notas Fiscais', 'slug' => 'invoices.issue', 'group' => 'invoices'],

            // Reports
            ['name' => 'Ver Relatórios', 'slug' => 'reports.view', 'group' => 'reports'],
            ['name' => 'Exportar Relatórios', 'slug' => 'reports.export', 'group' => 'reports'],

            // Users & ACL
            ['name' => 'Ver Usuários', 'slug' => 'users.view', 'group' => 'users'],
            ['name' => 'Criar Usuários', 'slug' => 'users.create', 'group' => 'users'],
            ['name' => 'Editar Usuários', 'slug' => 'users.edit', 'group' => 'users'],
            ['name' => 'Gerenciar Permissões', 'slug' => 'users.permissions', 'group' => 'users'],

            // Settings
            ['name' => 'Ver Configurações', 'slug' => 'settings.view', 'group' => 'settings'],
            ['name' => 'Editar Configurações', 'slug' => 'settings.edit', 'group' => 'settings'],
        ];

        foreach ($permissions as $permission) {
            Permission::create($permission);
        }
    }
}

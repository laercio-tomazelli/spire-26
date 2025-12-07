<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = $this->getPermissions();

        foreach ($permissions as $group => $groupPermissions) {
            foreach ($groupPermissions as $slug => $name) {
                Permission::updateOrCreate(
                    ['slug' => $slug],
                    [
                        'name' => $name,
                        'group' => $group,
                        'description' => "Permissão: {$name}",
                    ],
                );
            }
        }
    }

    /**
     * Get all permissions organized by group.
     *
     * @return array<string, array<string, string>>
     */
    private function getPermissions(): array
    {
        return [
            // Service Orders
            'service-orders' => [
                'service-orders.view' => 'Visualizar ordens de serviço',
                'service-orders.create' => 'Criar ordens de serviço',
                'service-orders.update' => 'Atualizar ordens de serviço',
                'service-orders.delete' => 'Excluir ordens de serviço',
                'service-orders.change-status' => 'Alterar status da OS',
                'service-orders.assign-technician' => 'Atribuir técnico',
                'service-orders.add-parts' => 'Adicionar peças à OS',
                'service-orders.close' => 'Fechar ordem de serviço',
                'service-orders.reopen' => 'Reabrir ordem de serviço',
                'service-orders.view-all' => 'Visualizar todas as OS (sem filtro)',
                'service-orders.export' => 'Exportar ordens de serviço',
                'service-orders.cancel' => 'Cancelar ordens de serviço',
                'service-orders.approve' => 'Aprovar ordens de serviço',
                'service-orders.invoice' => 'Faturar ordens de serviço',
            ],

            // Partners
            'partners' => [
                'partners.view' => 'Visualizar postos autorizados',
                'partners.create' => 'Criar postos autorizados',
                'partners.update' => 'Atualizar postos autorizados',
                'partners.delete' => 'Excluir postos autorizados',
                'partners.manage-users' => 'Gerenciar usuários do posto',
                'partners.view-financials' => 'Visualizar dados financeiros',
                'partners.manage-inventory' => 'Gerenciar estoque do posto',
                'partners.manage-brands' => 'Gerenciar marcas do posto',
            ],

            // Orders (Parts)
            'orders' => [
                'orders.view' => 'Visualizar pedidos de peças',
                'orders.create' => 'Criar pedidos de peças',
                'orders.update' => 'Atualizar pedidos de peças',
                'orders.delete' => 'Excluir pedidos de peças',
                'orders.approve' => 'Aprovar pedidos de peças',
                'orders.cancel' => 'Cancelar pedidos de peças',
                'orders.view-all' => 'Visualizar todos os pedidos',
            ],

            // Exchanges
            'exchanges' => [
                'exchanges.view' => 'Visualizar trocas',
                'exchanges.create' => 'Criar solicitações de troca',
                'exchanges.update' => 'Atualizar trocas',
                'exchanges.delete' => 'Excluir trocas',
                'exchanges.approve' => 'Aprovar trocas',
                'exchanges.reject' => 'Rejeitar trocas',
                'exchanges.add-evidence' => 'Adicionar evidências',
            ],

            // Inventory
            'inventory' => [
                'inventory.view' => 'Visualizar estoque',
                'inventory.adjust' => 'Ajustar estoque',
                'inventory.transfer' => 'Transferir estoque',
                'inventory.receive' => 'Receber mercadorias',
                'inventory.ship' => 'Enviar mercadorias',
            ],

            // Shipments
            'shipments' => [
                'shipments.view' => 'Visualizar remessas',
                'shipments.create' => 'Criar remessas',
                'shipments.update' => 'Atualizar remessas',
                'shipments.track' => 'Rastrear remessas',
            ],

            // Users
            'users' => [
                'users.view' => 'Visualizar usuários',
                'users.create' => 'Criar usuários',
                'users.update' => 'Atualizar usuários',
                'users.delete' => 'Excluir usuários',
                'users.manage-roles' => 'Gerenciar papéis do usuário',
                'users.impersonate' => 'Personificar usuário',
                'users.manage' => 'Gerenciar usuários',
                'users.permissions' => 'Gerenciar permissões do usuário',
            ],

            // Roles & Permissions
            'roles' => [
                'roles.view' => 'Visualizar papéis',
                'roles.create' => 'Criar papéis',
                'roles.update' => 'Atualizar papéis',
                'roles.delete' => 'Excluir papéis',
                'roles.manage' => 'Gerenciar papéis',
            ],

            'permissions' => [
                'permissions.view' => 'Visualizar permissões',
                'permissions.manage' => 'Gerenciar permissões',
            ],

            // Reports
            'reports' => [
                'reports.view' => 'Visualizar relatórios',
                'reports.service-orders' => 'Relatório de ordens de serviço',
                'reports.parts' => 'Relatório de peças',
                'reports.financial' => 'Relatório financeiro',
                'reports.performance' => 'Relatório de desempenho',
                'reports.export' => 'Exportar relatórios',
            ],

            // Financial
            'financial' => [
                'financial.view' => 'Visualizar dados financeiros',
                'financial.invoices' => 'Gerenciar notas fiscais',
                'financial.closings' => 'Gerenciar fechamentos',
                'financial.discounts' => 'Gerenciar descontos',
            ],

            // Invoices
            'invoices' => [
                'invoices.view' => 'Visualizar notas fiscais',
                'invoices.issue' => 'Emitir notas fiscais',
                'invoices.cancel' => 'Cancelar notas fiscais',
            ],

            // Parts
            'parts' => [
                'parts.view' => 'Visualizar peças',
                'parts.create' => 'Criar peças',
                'parts.update' => 'Atualizar peças',
                'parts.delete' => 'Excluir peças',
            ],

            // Products (Manufacturers)
            'products' => [
                'products.view' => 'Visualizar produtos',
                'products.create' => 'Criar produtos',
                'products.update' => 'Atualizar produtos',
                'products.delete' => 'Excluir produtos',
                'products.manage-parts' => 'Gerenciar peças',
                'products.manage-bom' => 'Gerenciar lista de materiais',
            ],

            // Manufacturers
            'manufacturers' => [
                'manufacturers.view' => 'Visualizar fabricantes',
                'manufacturers.create' => 'Criar fabricantes',
                'manufacturers.update' => 'Atualizar fabricantes',
                'manufacturers.delete' => 'Excluir fabricantes',
                'manufacturers.manage-brands' => 'Gerenciar marcas',
            ],

            // Customers
            'customers' => [
                'customers.view' => 'Visualizar clientes',
                'customers.create' => 'Criar clientes',
                'customers.update' => 'Atualizar clientes',
                'customers.delete' => 'Excluir clientes',
                'customers.merge' => 'Mesclar clientes duplicados',
            ],

            // Settings
            'settings' => [
                'settings.view' => 'Visualizar configurações',
                'settings.update' => 'Atualizar configurações',
                'settings.manage-status' => 'Gerenciar status/sub-status',
                'settings.manage-service-types' => 'Gerenciar tipos de serviço',
                'settings.manage-defects' => 'Gerenciar defeitos',
                'settings.manage-solutions' => 'Gerenciar soluções',
            ],

            // Administration
            'admin' => [
                'admin.access' => 'Acessar administração',
                'admin.tenants' => 'Gerenciar tenants',
                'admin.system' => 'Configurações do sistema',
            ],

            // Audit
            'audit' => [
                'audit.view' => 'Visualizar log de auditoria',
                'audit.export' => 'Exportar log de auditoria',
            ],

            // Data
            'data' => [
                'data.export' => 'Exportar dados',
                'data.import' => 'Importar dados',
                'data.backup' => 'Backup de dados',
            ],
        ];
    }
}

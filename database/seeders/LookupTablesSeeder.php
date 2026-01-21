<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LookupTablesSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedServiceOrderStatuses();
        $this->seedServiceTypes();
        $this->seedServiceLocations();
        $this->seedRepairTypes();
        $this->seedTrackingStatuses();
        $this->seedAcceptStatuses();
        $this->seedClosingTypes();
        $this->seedCommentPrivacies();
        $this->seedEvidenceTypes();
    }

    private function seedServiceOrderStatuses(): void
    {
        $statuses = [
            ['code' => 'open', 'name' => 'Aberta', 'color' => '#3498db', 'display_order' => 1],
            ['code' => 'analyzing', 'name' => 'Em Análise', 'color' => '#9b59b6', 'display_order' => 2],
            ['code' => 'waiting-parts', 'name' => 'Aguardando Peças', 'color' => '#f39c12', 'display_order' => 3],
            ['code' => 'waiting-approval', 'name' => 'Aguardando Aprovação', 'color' => '#e67e22', 'display_order' => 4],
            ['code' => 'in-progress', 'name' => 'Em Execução', 'color' => '#2ecc71', 'display_order' => 5],
            ['code' => 'completed', 'name' => 'Concluída', 'color' => '#27ae60', 'display_order' => 6],
            ['code' => 'closed', 'name' => 'Fechada', 'color' => '#95a5a6', 'display_order' => 7],
            ['code' => 'canceled', 'name' => 'Cancelada', 'color' => '#e74c3c', 'display_order' => 8],
        ];

        foreach ($statuses as $status) {
            DB::table('service_order_statuses')->insert([
                ...$status,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    private function seedServiceTypes(): void
    {
        $types = [
            ['code' => 'repair', 'name' => 'Reparo', 'color' => '#3498db', 'display_order' => 1],
            ['code' => 'installation', 'name' => 'Instalação', 'color' => '#2ecc71', 'display_order' => 2],
            ['code' => 'maintenance', 'name' => 'Manutenção Preventiva', 'color' => '#9b59b6', 'display_order' => 3],
            ['code' => 'inspection', 'name' => 'Vistoria', 'color' => '#f39c12', 'display_order' => 4],
        ];

        foreach ($types as $type) {
            DB::table('service_types')->insert([
                ...$type,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    private function seedServiceLocations(): void
    {
        $locations = [
            ['code' => 'counter', 'name' => 'Balcão', 'color' => '#3498db', 'display_order' => 1],
            ['code' => 'home', 'name' => 'Domicílio', 'color' => '#2ecc71', 'display_order' => 2],
            ['code' => 'warehouse', 'name' => 'Depósito', 'color' => '#95a5a6', 'display_order' => 3],
        ];

        foreach ($locations as $location) {
            DB::table('service_locations')->insert([
                ...$location,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    private function seedRepairTypes(): void
    {
        $types = [
            ['code' => 'warranty', 'name' => 'Garantia', 'color' => '#2ecc71'],
            ['code' => 'paid', 'name' => 'Pago', 'color' => '#3498db'],
            ['code' => 'courtesy', 'name' => 'Cortesia', 'color' => '#9b59b6'],
        ];

        foreach ($types as $type) {
            DB::table('repair_types')->insert([
                ...$type,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    private function seedTrackingStatuses(): void
    {
        $statuses = [
            ['code' => 'pending', 'name' => 'Pendente', 'color' => '#f39c12'],
            ['code' => 'shipped', 'name' => 'Enviado', 'color' => '#3498db'],
            ['code' => 'in-transit', 'name' => 'Em Trânsito', 'color' => '#9b59b6'],
            ['code' => 'delivered', 'name' => 'Entregue', 'color' => '#2ecc71'],
            ['code' => 'returned', 'name' => 'Devolvido', 'color' => '#e74c3c'],
        ];

        foreach ($statuses as $status) {
            DB::table('tracking_statuses')->insert([
                ...$status,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    private function seedAcceptStatuses(): void
    {
        $statuses = [
            ['name' => 'Pendente', 'text_color' => '#856404', 'bg_color' => '#fff3cd', 'icon' => 'clock'],
            ['name' => 'Aceito', 'text_color' => '#155724', 'bg_color' => '#d4edda', 'icon' => 'check'],
            ['name' => 'Recusado', 'text_color' => '#721c24', 'bg_color' => '#f8d7da', 'icon' => 'x'],
        ];

        foreach ($statuses as $status) {
            DB::table('accept_statuses')->insert([
                ...$status,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    private function seedClosingTypes(): void
    {
        $types = [
            ['name' => 'Mensal', 'description' => 'Fechamento mensal padrão'],
            ['name' => 'Quinzenal', 'description' => 'Fechamento quinzenal'],
            ['name' => 'Semanal', 'description' => 'Fechamento semanal'],
        ];

        foreach ($types as $type) {
            DB::table('closing_types')->insert([
                ...$type,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    private function seedCommentPrivacies(): void
    {
        $privacies = [
            ['name' => 'Público', 'description' => 'Visível para todos', 'color' => '#2ecc71', 'icon' => 'globe', 'is_default' => true],
            ['name' => 'Interno', 'description' => 'Visível apenas internamente', 'color' => '#f39c12', 'icon' => 'lock', 'is_default' => false],
            ['name' => 'Privado', 'description' => 'Visível apenas para administradores', 'color' => '#e74c3c', 'icon' => 'shield', 'is_default' => false],
        ];

        foreach ($privacies as $privacy) {
            DB::table('comment_privacies')->insert([
                ...$privacy,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    private function seedEvidenceTypes(): void
    {
        $types = [
            ['name' => 'Foto Antes', 'file_name_pattern' => 'antes_*.jpg', 'is_mandatory' => false, 'applies_to' => 'os'],
            ['name' => 'Foto Depois', 'file_name_pattern' => 'depois_*.jpg', 'is_mandatory' => false, 'applies_to' => 'os'],
            ['name' => 'Nota Fiscal', 'file_name_pattern' => 'nf_*.pdf', 'is_mandatory' => true, 'applies_to' => 'both'],
            ['name' => 'Laudo Técnico', 'file_name_pattern' => 'laudo_*.pdf', 'is_mandatory' => false, 'applies_to' => 'os'],
            ['name' => 'Termo de Aceite', 'file_name_pattern' => 'termo_*.pdf', 'is_mandatory' => false, 'applies_to' => 'both'],
        ];

        foreach ($types as $type) {
            DB::table('evidence_types')->insert([
                ...$type,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}

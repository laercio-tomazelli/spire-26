<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\DocumentType;
use App\Models\TransactionType;
use Illuminate\Database\Seeder;

class InventoryTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Transaction Types
        $transactionTypes = [
            // Entradas
            ['type' => 'COMPRA', 'description' => 'Entrada por compra de peças', 'operation' => 'in'],
            ['type' => 'DEVOLUCAO_CLIENTE', 'description' => 'Devolução de peça pelo cliente', 'operation' => 'in'],
            ['type' => 'TRANSFERENCIA_ENTRADA', 'description' => 'Entrada por transferência entre depósitos', 'operation' => 'in'],
            ['type' => 'AJUSTE_ENTRADA', 'description' => 'Ajuste manual de entrada', 'operation' => 'in'],
            ['type' => 'CONSERTO', 'description' => 'Entrada de peça consertada', 'operation' => 'in'],
            ['type' => 'PRODUCAO', 'description' => 'Entrada por produção interna', 'operation' => 'in'],

            // Saídas
            ['type' => 'VENDA', 'description' => 'Saída por venda de peças', 'operation' => 'out'],
            ['type' => 'CONSUMO_OS', 'description' => 'Consumo em ordem de serviço', 'operation' => 'out'],
            ['type' => 'DEVOLUCAO_FORNECEDOR', 'description' => 'Devolução ao fornecedor', 'operation' => 'out'],
            ['type' => 'TRANSFERENCIA_SAIDA', 'description' => 'Saída por transferência entre depósitos', 'operation' => 'out'],
            ['type' => 'AJUSTE_SAIDA', 'description' => 'Ajuste manual de saída', 'operation' => 'out'],
            ['type' => 'DESCARTE', 'description' => 'Descarte de peça defeituosa', 'operation' => 'out'],
            ['type' => 'PERDA', 'description' => 'Perda ou extravio', 'operation' => 'out'],

            // Transferências
            ['type' => 'TRANSFERENCIA', 'description' => 'Transferência entre depósitos', 'operation' => 'transfer'],
        ];

        foreach ($transactionTypes as $type) {
            TransactionType::updateOrCreate(
                ['type' => $type['type']],
                $type,
            );
        }

        // Document Types
        $documentTypes = [
            ['type' => 'NF', 'description' => 'Nota Fiscal'],
            ['type' => 'NFE', 'description' => 'Nota Fiscal Eletrônica'],
            ['type' => 'NFSE', 'description' => 'Nota Fiscal de Serviço Eletrônica'],
            ['type' => 'OS', 'description' => 'Ordem de Serviço'],
            ['type' => 'PEDIDO', 'description' => 'Pedido de Compra/Venda'],
            ['type' => 'ROMANEIO', 'description' => 'Romaneio de entrega'],
            ['type' => 'REQUISICAO', 'description' => 'Requisição interna'],
            ['type' => 'INVENTARIO', 'description' => 'Inventário/Contagem'],
            ['type' => 'MANUAL', 'description' => 'Lançamento manual'],
            ['type' => 'OUTROS', 'description' => 'Outros documentos'],
        ];

        foreach ($documentTypes as $type) {
            DocumentType::updateOrCreate(
                ['type' => $type['type']],
                $type,
            );
        }

        $this->command->info('Inventory types seeded successfully!');
    }
}

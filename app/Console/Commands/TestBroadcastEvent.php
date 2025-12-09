<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Events\OrderCancelled;
use App\Events\OrderUpdated;
use Illuminate\Console\Command;

class TestBroadcastEvent extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'broadcast:test
                            {type=cancelled : Tipo do evento (cancelled, updated)}
                            {--order-id=1 : ID do pedido}
                            {--order-number=OS-TEST-0001 : Número do pedido}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Dispara eventos de broadcast para teste do Laravel Reverb';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $type = $this->argument('type');
        $orderId = (int) $this->option('order-id');
        $orderNumber = $this->option('order-number');

        $this->info("🚀 Disparando evento de broadcast...\n");

        match ($type) {
            'cancelled' => $this->testOrderCancelled($orderId, $orderNumber),
            'updated' => $this->testOrderUpdated($orderId, $orderNumber),
            default => $this->error("Tipo de evento desconhecido: {$type}"),
        };

        return Command::SUCCESS;
    }

    private function testOrderCancelled(int $orderId, string $orderNumber): void
    {
        $this->table(
            ['Campo', 'Valor'],
            [
                ['Evento', 'OrderCancelled'],
                ['Order ID', $orderId],
                ['Order Number', $orderNumber],
                ['Motivo', 'Teste de broadcast via comando'],
                ['Cancelado por', 'Sistema (CLI)'],
                ['Data/Hora', now()->toISOString()],
            ],
        );

        event(new OrderCancelled(
            orderId: $orderId,
            orderNumber: $orderNumber,
            reason: 'Teste de broadcast via comando',
            cancelledBy: 'Sistema (CLI)',
            cancelledAt: now()->toISOString(),
        ));

        $this->newLine();
        $this->info('✅ Evento OrderCancelled disparado com sucesso!');
        $this->info("📡 Canais: private-orders, private-orders.{$orderId}");
    }

    private function testOrderUpdated(int $orderId, string $orderNumber): void
    {
        $fields = ['status', 'valor_total', 'observacao', 'tecnico_id'];
        $field = $fields[array_rand($fields)];
        $oldValue = 'valor_antigo';
        $newValue = 'valor_novo_'.\random_int(100, 999);

        $this->table(
            ['Campo', 'Valor'],
            [
                ['Evento', 'OrderUpdated'],
                ['Order ID', $orderId],
                ['Order Number', $orderNumber],
                ['Campo alterado', $field],
                ['Valor antigo', $oldValue],
                ['Valor novo', $newValue],
                ['Atualizado por', 'Sistema (CLI)'],
                ['Data/Hora', now()->toISOString()],
            ],
        );

        event(new OrderUpdated(
            orderId: $orderId,
            orderNumber: $orderNumber,
            field: $field,
            oldValue: $oldValue,
            newValue: $newValue,
            updatedBy: 'Sistema (CLI)',
            updatedAt: now()->toISOString(),
        ));

        $this->newLine();
        $this->info('✅ Evento OrderUpdated disparado com sucesso!');
        $this->info("📡 Canal: private-orders.{$orderId}");
    }
}

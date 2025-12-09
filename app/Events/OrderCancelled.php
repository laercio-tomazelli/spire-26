<?php

declare(strict_types=1);

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class OrderCancelled implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(
        public int $orderId,
        public string $orderNumber,
        public string $reason,
        public string $cancelledBy,
        public string $cancelledAt,
    ) {}

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, Channel>
     */
    public function broadcastOn(): array
    {
        return [
            // Canal geral de pedidos (todos recebem)
            new PrivateChannel('orders'),
            // Canal específico do pedido (quem está visualizando)
            new PrivateChannel('orders.'.$this->orderId),
        ];
    }

    /**
     * Dados que serão enviados pelo broadcast
     */
    public function broadcastWith(): array
    {
        return [
            'order_id' => $this->orderId,
            'order_number' => $this->orderNumber,
            'reason' => $this->reason,
            'cancelled_by' => $this->cancelledBy,
            'cancelled_at' => $this->cancelledAt,
        ];
    }

    /**
     * Nome do evento no frontend
     */
    public function broadcastAs(): string
    {
        return 'order.cancelled';
    }
}

<?php

declare(strict_types=1);

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class OrderUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(
        public int $orderId,
        public string $orderNumber,
        public string $field,
        public mixed $oldValue,
        public mixed $newValue,
        public string $updatedBy,
        public string $updatedAt,
    ) {}

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, Channel>
     */
    public function broadcastOn(): array
    {
        return [
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
            'field' => $this->field,
            'old_value' => $this->oldValue,
            'new_value' => $this->newValue,
            'updated_by' => $this->updatedBy,
            'updated_at' => $this->updatedAt,
        ];
    }

    /**
     * Nome do evento no frontend
     */
    public function broadcastAs(): string
    {
        return 'order.updated';
    }
}

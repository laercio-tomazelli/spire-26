/**
 * SPIRE 26 - Real-time Order Events Listener
 *
 * Este módulo escuta eventos de pedidos em tempo real via Laravel Reverb.
 * Usado para atualizar a interface quando pedidos são cancelados ou modificados.
 *
 * @example
 * // Inicializar listener para um pedido específico
 * import { OrderEvents } from './order-events';
 *
 * const listener = new OrderEvents(123); // orderId = 123
 * listener.onCancelled((data) => {
 *     alert(`Pedido ${data.order_number} foi cancelado!`);
 * });
 * listener.onUpdated((data) => {
 *     console.log(`Campo ${data.field} alterado de ${data.old_value} para ${data.new_value}`);
 * });
 *
 * // Quando sair da página
 * listener.disconnect();
 */

export class OrderEvents {
    private orderId: number;
    private channel: any;
    private generalChannel: any;
    private callbacks: {
        cancelled: ((data: OrderCancelledData) => void)[];
        updated: ((data: OrderUpdatedData) => void)[];
    } = {
        cancelled: [],
        updated: [],
    };

    constructor(orderId: number) {
        this.orderId = orderId;
        this.subscribe();
    }

    /**
     * Subscribe to order channels
     */
    private subscribe(): void {
        // Canal específico do pedido
        this.channel = window.Echo.private(`orders.${this.orderId}`);

        this.channel.listen(".order.cancelled", (data: OrderCancelledData) => {
            this.callbacks.cancelled.forEach((cb) => cb(data));
        });

        this.channel.listen(".order.updated", (data: OrderUpdatedData) => {
            this.callbacks.updated.forEach((cb) => cb(data));
        });

        // Canal geral de pedidos (cancelamentos globais)
        this.generalChannel = window.Echo.private("orders");

        this.generalChannel.listen(
            ".order.cancelled",
            (data: OrderCancelledData) => {
                // Só dispara se for o pedido que estamos observando
                if (data.order_id === this.orderId) {
                    this.callbacks.cancelled.forEach((cb) => cb(data));
                }
            }
        );
    }

    /**
     * Register callback for order cancelled event
     */
    onCancelled(callback: (data: OrderCancelledData) => void): this {
        this.callbacks.cancelled.push(callback);
        return this;
    }

    /**
     * Register callback for order updated event
     */
    onUpdated(callback: (data: OrderUpdatedData) => void): this {
        this.callbacks.updated.push(callback);
        return this;
    }

    /**
     * Disconnect from channels
     */
    disconnect(): void {
        if (this.channel) {
            window.Echo.leave(`orders.${this.orderId}`);
        }
        if (this.generalChannel) {
            window.Echo.leave("orders");
        }
    }
}

/**
 * Listen to all order events (for listings/dashboards)
 */
export class AllOrdersListener {
    private channel: any;
    private callbacks: {
        cancelled: ((data: OrderCancelledData) => void)[];
    } = {
        cancelled: [],
    };

    constructor() {
        this.subscribe();
    }

    private subscribe(): void {
        this.channel = window.Echo.private("orders");

        this.channel.listen(".order.cancelled", (data: OrderCancelledData) => {
            this.callbacks.cancelled.forEach((cb) => cb(data));
        });
    }

    onCancelled(callback: (data: OrderCancelledData) => void): this {
        this.callbacks.cancelled.push(callback);
        return this;
    }

    disconnect(): void {
        window.Echo.leave("orders");
    }
}

// Types
export interface OrderCancelledData {
    order_id: number;
    order_number: string;
    reason: string;
    cancelled_by: string;
    cancelled_at: string;
}

export interface OrderUpdatedData {
    order_id: number;
    order_number: string;
    field: string;
    old_value: any;
    new_value: any;
    updated_by: string;
    updated_at: string;
}

// Extend Window interface for Echo
declare global {
    interface Window {
        Echo: any;
    }
}

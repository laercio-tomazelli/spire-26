/**
 * SPIRE 26 - Exemplo de uso do Real-time Events
 *
 * Este arquivo mostra como integrar os eventos em tempo real
 * em uma página de detalhes de pedido.
 */

import { OrderEvents, AllOrdersListener } from "./order-events";

/**
 * Exemplo 1: Página de detalhes do pedido
 * Escuta eventos específicos daquele pedido
 */
export function initOrderDetailPage(orderId: number): void {
    const listener = new OrderEvents(orderId);

    // Quando o pedido for cancelado
    listener.onCancelled((data) => {
        // Mostrar alerta
        showNotification(
            "error",
            `Pedido ${data.order_number} foi cancelado por ${data.cancelled_by}`,
            `Motivo: ${data.reason}`
        );

        // Atualizar status na tela
        const statusBadge = document.querySelector("[data-order-status]");
        if (statusBadge) {
            statusBadge.textContent = "Cancelado";
            statusBadge.classList.remove("bg-green-500", "bg-yellow-500");
            statusBadge.classList.add("bg-red-500");
        }

        // Desabilitar botões de ação
        document
            .querySelectorAll("[data-order-action]")
            .forEach((btn) => btn.setAttribute("disabled", "true"));
    });

    // Quando algum campo do pedido for atualizado
    listener.onUpdated((data) => {
        showNotification(
            "info",
            `Pedido atualizado por ${data.updated_by}`,
            `${data.field}: ${data.old_value} → ${data.new_value}`
        );

        // Atualizar o campo específico na tela
        const fieldElement = document.querySelector(
            `[data-field="${data.field}"]`
        );
        if (fieldElement) {
            fieldElement.textContent = String(data.new_value);
            fieldElement.classList.add("animate-pulse", "bg-yellow-100");
            setTimeout(() => {
                fieldElement.classList.remove("animate-pulse", "bg-yellow-100");
            }, 2000);
        }
    });

    // Limpar quando sair da página
    window.addEventListener("beforeunload", () => {
        listener.disconnect();
    });
}

/**
 * Exemplo 2: Listagem de pedidos
 * Escuta cancelamentos de qualquer pedido
 */
export function initOrderListPage(): void {
    const listener = new AllOrdersListener();

    listener.onCancelled((data) => {
        // Encontrar a linha do pedido na tabela
        const row = document.querySelector(
            `[data-order-id="${data.order_id}"]`
        );
        if (row) {
            // Atualizar status visual
            row.classList.add("opacity-50", "line-through");

            const statusCell = row.querySelector("[data-order-status]");
            if (statusCell) {
                statusCell.textContent = "Cancelado";
                statusCell.classList.add("text-red-500");
            }
        }

        // Mostrar toast notification
        showNotification(
            "warning",
            `Pedido ${data.order_number} cancelado`,
            `Por: ${data.cancelled_by}`
        );
    });

    window.addEventListener("beforeunload", () => {
        listener.disconnect();
    });
}

/**
 * Helper para mostrar notificações
 * (Integrar com seu sistema de notificações existente)
 */
function showNotification(
    type: "info" | "warning" | "error" | "success",
    title: string,
    message: string
): void {
    // TODO: Integrar com o sistema de toast/notifications do spire-ui
    console.log(`[${type.toUpperCase()}] ${title}: ${message}`);

    // Exemplo básico com alert (substituir pelo seu componente)
    if (type === "error") {
        // Para erros críticos como cancelamento
        const confirmed = confirm(`${title}\n\n${message}\n\nRecarregar página?`);
        if (confirmed) {
            window.location.reload();
        }
    }
}

/**
 * Exemplo 3: Inicialização automática baseada em data attributes
 *
 * No HTML:
 * <div data-realtime-order="123"></div>
 * <div data-realtime-orders-list></div>
 */
export function initRealtimeFromAttributes(): void {
    // Página de detalhe do pedido
    const orderDetail = document.querySelector<HTMLElement>(
        "[data-realtime-order]"
    );
    if (orderDetail) {
        const orderId = parseInt(
            orderDetail.dataset.realtimeOrder || "0",
            10
        );
        if (orderId > 0) {
            initOrderDetailPage(orderId);
            console.log(`🔴 Real-time ativo para pedido #${orderId}`);
        }
    }

    // Listagem de pedidos
    const orderList = document.querySelector("[data-realtime-orders-list]");
    if (orderList) {
        initOrderListPage();
        console.log("🔴 Real-time ativo para listagem de pedidos");
    }
}

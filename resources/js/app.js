import "./bootstrap";

/**
 * Real-time Events Listener (teste)
 * Remove após confirmar que funciona
 */
if (window.Echo) {
    console.log("🔴 Echo conectado ao Reverb");

    // Escutar canal público de pedidos (para teste)
    window.Echo.private("orders")
        .listen(".order.cancelled", (data) => {
            console.log("📢 Evento recebido:", data);
            alert(
                `🚨 Pedido ${data.order_number} foi CANCELADO!\n\n` +
                    `Motivo: ${data.reason}\n` +
                    `Por: ${data.cancelled_by}`
            );
        })
        .error((error) => {
            console.error("❌ Erro no canal:", error);
        });

    console.log("📡 Escutando canal: private-orders");
} else {
    console.warn("⚠️ Echo não está disponível");
}

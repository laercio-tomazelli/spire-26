import "./bootstrap";

/**
 * Real-time Events Listener (teste)
 * Remove após confirmar que funciona
 *
 * Só conecta ao canal privado se o usuário estiver autenticado
 * (verificado através da meta tag user-authenticated)
 */
if (window.Echo) {
    console.log("🔴 Echo conectado ao Reverb");

    // Verificar se usuário está autenticado antes de conectar ao canal privado
    const isAuthenticated =
        document.querySelector('meta[name="user-authenticated"]')?.content ===
        "true";

    if (isAuthenticated) {
        // Escutar canal privado de pedidos (requer autenticação)
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
        console.log(
            "ℹ️ Usuário não autenticado - canais privados desabilitados"
        );
    }
} else {
    console.warn("⚠️ Echo não está disponível");
}

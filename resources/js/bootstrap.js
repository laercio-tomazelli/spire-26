import axios from "axios";
import Echo from "laravel-echo";
import Pusher from "pusher-js";

window.axios = axios;
window.axios.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest";

/**
 * Laravel Echo - WebSocket client for real-time events
 * Connects to Laravel Reverb server
 * Only initialize if VITE_REVERB_APP_KEY is configured with a non-empty value
 */
window.Pusher = Pusher;

const reverbAppKey = import.meta.env.VITE_REVERB_APP_KEY;
if (reverbAppKey && reverbAppKey.trim() !== "") {
    window.Echo = new Echo({
        broadcaster: "reverb",
        key: reverbAppKey,
        wsHost: import.meta.env.VITE_REVERB_HOST,
        wsPort: import.meta.env.VITE_REVERB_PORT ?? 8080,
        wssPort: import.meta.env.VITE_REVERB_PORT ?? 8080,
        forceTLS: (import.meta.env.VITE_REVERB_SCHEME ?? "http") === "https",
        enabledTransports: ["ws", "wss"],
    });
}

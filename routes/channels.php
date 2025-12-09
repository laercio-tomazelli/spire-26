<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Broadcast;

/**
 * Canal privado do usuário (notificações pessoais)
 */
Broadcast::channel('App.Models.User.{id}', fn ($user, $id): bool => (int) $user->id === (int) $id);

/**
 * Canal de pedidos - todos usuários autenticados podem ouvir
 * Usado para: cancelamentos, atualizações de status, etc.
 */
Broadcast::channel('orders', fn ($user): bool => $user !== null);

/**
 * Canal de um pedido específico - só quem tem acesso ao pedido
 */
Broadcast::channel('orders.{orderId}', fn ($user, $orderId): bool =>
    // Aqui você pode verificar se o usuário tem permissão para ver o pedido
    // Por enquanto, qualquer usuário autenticado pode ouvir
    $user !== null);

/**
 * Canal de presença para saber quem está online
 */
Broadcast::channel('presence.online', fn ($user): array => [
    'id' => $user->id,
    'name' => $user->name,
]);

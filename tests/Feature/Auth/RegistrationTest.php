<?php

declare(strict_types=1);

// Registro público desabilitado - usuários são criados por administradores
// Estes testes foram removidos pois a funcionalidade foi desativada intencionalmente

test('registration route is disabled', function (): void {
    /** @var \Tests\TestCase $this */
    $response = $this->get('/register');

    $response->assertStatus(404);
});

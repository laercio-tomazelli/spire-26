<?php

declare(strict_types=1);

use App\Models\Tenant;
use App\Models\User;
use Tests\TestCase;

test('registration screen can be rendered', function (): void {
    /** @var TestCase $this */
    $response = $this->get('/register');

    $response->assertStatus(200);
});

test('new users can register', function (): void {
    /** @var TestCase $this */

    // Criar o tenant padrão necessário para o registro
    Tenant::factory()->create(['id' => 1]);

    $response = $this->post('/register', [
        'name' => 'Test User',
        'email' => 'testregister@example.com',
        'password' => 'Password123!',
        'password_confirmation' => 'Password123!',
    ]);

    // Debug: show response details
    $response->assertSessionHasNoErrors();
    $response->assertRedirect(route('dashboard'));

    // Verificar que o usuário foi criado
    $user = User::where('email', 'testregister@example.com')->first();
    $this->assertNotNull($user);
    $this->assertEquals('Test User', $user->name);
    $this->assertEquals(1, $user->tenant_id);
});

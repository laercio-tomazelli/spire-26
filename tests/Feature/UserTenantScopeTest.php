<?php

declare(strict_types=1);

use App\Enums\UserType;
use App\Models\Partner;
use App\Models\Role;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Support\Str;

function createTenantForUserScope(string $name): Tenant
{
    return Tenant::create([
        'name' => $name,
        'document' => fake()->unique()->numerify('##############'),
        'is_active' => true,
    ]);
}

function createPartnerForUserScope(Tenant $tenant, string $code): Partner
{
    return Partner::create([
        'tenant_id' => $tenant->id,
        'code' => $code,
        'company_name' => "{$code} Assistencia Tecnica",
        'trade_name' => "{$code} Assistencia",
        'document' => fake()->unique()->numerify('##############'),
        'address' => 'Rua Teste',
        'city' => 'Sao Paulo',
        'state' => 'SP',
        'postal_code' => '01000-000',
    ]);
}

function validPartnerUserPayloadForScope(Partner $partner, array $overrides = []): array
{
    $password = 'Spire26!'.Str::random(24);

    return array_merge([
        'name' => 'Tecnico Teste',
        'email' => fake()->unique()->safeEmail(),
        'username' => fake()->unique()->userName(),
        'password' => $password,
        'password_confirmation' => $password,
        'user_type' => UserType::Partner->value,
        'is_active' => true,
        'is_partner_admin' => false,
        'tenant_id' => $partner->tenant_id,
        'partner_id' => $partner->id,
        'manufacturer_id' => null,
    ], $overrides);
}

test('partner admin cannot create a user for another partner', function (): void {
    $tenantA = createTenantForUserScope('Tenant A');
    $tenantB = createTenantForUserScope('Tenant B');
    $partnerA = createPartnerForUserScope($tenantA, 'PA001');
    $partnerB = createPartnerForUserScope($tenantB, 'PB001');

    $admin = User::factory()->create([
        'tenant_id' => $tenantA->id,
        'partner_id' => $partnerA->id,
        'user_type' => UserType::Partner,
        'is_partner_admin' => true,
    ]);

    $payload = validPartnerUserPayloadForScope($partnerB);

    $response = $this->actingAs($admin)->post(route('users.store'), $payload);

    $response->assertSessionHasErrors(['partner_id', 'tenant_id']);
    $this->assertDatabaseMissing('users', ['email' => $payload['email']]);
});

test('partner admin can create a regular user for their own partner without sending tenant id', function (): void {
    $tenant = createTenantForUserScope('Tenant Principal');
    $partner = createPartnerForUserScope($tenant, 'PA002');

    $admin = User::factory()->create([
        'tenant_id' => $tenant->id,
        'partner_id' => $partner->id,
        'user_type' => UserType::Partner,
        'is_partner_admin' => true,
    ]);

    $payload = validPartnerUserPayloadForScope($partner, ['tenant_id' => null]);

    $response = $this->actingAs($admin)->post(route('users.store'), $payload);

    $response->assertRedirect();
    $response->assertSessionHasNoErrors();
    $this->assertDatabaseHas('users', [
        'email' => $payload['email'],
        'tenant_id' => $tenant->id,
        'partner_id' => $partner->id,
        'is_partner_admin' => false,
    ]);
});

test('partner admin cannot promote another partner user to admin', function (): void {
    $tenant = createTenantForUserScope('Tenant Escopo');
    $partner = createPartnerForUserScope($tenant, 'PA003');

    $admin = User::factory()->create([
        'tenant_id' => $tenant->id,
        'partner_id' => $partner->id,
        'user_type' => UserType::Partner,
        'is_partner_admin' => true,
    ]);

    $target = User::factory()->create([
        'tenant_id' => $tenant->id,
        'partner_id' => $partner->id,
        'user_type' => UserType::Partner,
        'is_partner_admin' => false,
    ]);

    $response = $this->actingAs($admin)->patch(route('users.update', $target), [
        'name' => $target->name,
        'email' => $target->email,
        'username' => $target->username,
        'password' => null,
        'password_confirmation' => null,
        'user_type' => UserType::Partner->value,
        'is_active' => true,
        'is_partner_admin' => true,
        'tenant_id' => $tenant->id,
        'partner_id' => $partner->id,
        'manufacturer_id' => null,
    ]);

    $response->assertSessionHasErrors(['is_partner_admin']);
    expect($target->refresh()->is_partner_admin)->toBeFalse();
});

test('spire admin cannot create a partner user with mismatched tenant and partner', function (): void {
    $tenantA = createTenantForUserScope('Tenant Alfa');
    $tenantB = createTenantForUserScope('Tenant Beta');
    $partnerB = createPartnerForUserScope($tenantB, 'PB002');

    $admin = User::factory()->create([
        'user_type' => UserType::Spire,
        'tenant_id' => null,
    ]);

    $role = Role::create([
        'name' => 'Super Admin',
        'slug' => 'super-admin',
        'is_system' => true,
    ]);

    $admin->roles()->attach($role);

    $payload = validPartnerUserPayloadForScope($partnerB, ['tenant_id' => $tenantA->id]);

    $response = $this->actingAs($admin)->post(route('users.store'), $payload);

    $response->assertSessionHasErrors(['tenant_id']);
    $this->assertDatabaseMissing('users', ['email' => $payload['email']]);
});

<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Partner;
use App\Models\Tenant;
use App\Models\Warehouse;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Warehouse>
 */
class WarehouseFactory extends Factory
{
    protected $model = Warehouse::class;

    public function definition(): array
    {
        return [
            'tenant_id' => Tenant::factory(),
            'partner_id' => null,
            'name' => fake()->company().' - '.fake()->randomElement(['Matriz', 'Filial', 'CD', 'Estoque']),
            'code' => strtoupper(fake()->bothify('WH-###')),
            'type' => fake()->randomElement(['main', 'partner', 'transit', 'defective']),
            'address' => fake()->streetAddress(),
            'city' => fake()->city(),
            'state' => fake()->stateAbbr(),
            'postal_code' => fake()->postcode(),
            'is_active' => true,
        ];
    }

    public function forPartner(Partner $partner): static
    {
        return $this->state(fn (array $attributes): array => [
            'partner_id' => $partner->id,
            'tenant_id' => $partner->tenant_id,
            'type' => 'partner',
        ]);
    }
}

<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Customer;
use App\Models\Tenant;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Customer>
 */
class CustomerFactory extends Factory
{
    protected $model = Customer::class;

    public function definition(): array
    {
        $type = fake()->randomElement(['individual', 'company']);
        $isCompany = $type === 'company';

        return [
            'tenant_id' => Tenant::factory(),
            'type' => $type,
            'document' => $isCompany
                ? fake()->numerify('##.###.###/####-##')
                : fake()->numerify('###.###.###-##'),
            'name' => $isCompany ? fake()->company() : fake()->name(),
            'trade_name' => $isCompany ? fake()->company() : null,
            'email' => fake()->email(),
            'phone' => fake()->phoneNumber(),
            'mobile' => fake()->phoneNumber(),
            'address' => fake()->streetAddress(),
            'address_number' => fake()->buildingNumber(),
            'address_complement' => fake()->optional()->secondaryAddress(),
            'neighborhood' => fake()->citySuffix(),
            'city' => fake()->city(),
            'city_code' => fake()->numerify('#######'),
            'state' => fake()->stateAbbr(),
            'postal_code' => fake()->postcode(),
            'country' => 'Brasil',
            'latitude' => fake()->latitude(-33.7, -3.8),
            'longitude' => fake()->longitude(-73.9, -34.8),
            'notes' => fake()->optional()->sentence(),
            'is_active' => true,
        ];
    }

    public function individual(): static
    {
        return $this->state(fn (array $attributes): array => [
            'type' => 'individual',
            'document' => fake()->numerify('###.###.###-##'),
            'name' => fake()->name(),
            'trade_name' => null,
        ]);
    }

    public function company(): static
    {
        return $this->state(fn (array $attributes): array => [
            'type' => 'company',
            'document' => fake()->numerify('##.###.###/####-##'),
            'name' => fake()->company(),
            'trade_name' => fake()->company(),
        ]);
    }
}

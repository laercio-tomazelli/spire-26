<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Partner;
use App\Models\Tenant;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Partner>
 */
class PartnerFactory extends Factory
{
    protected $model = Partner::class;

    public function definition(): array
    {
        return [
            'tenant_id' => Tenant::factory(),
            'code' => strtoupper(fake()->bothify('PAR-####')),
            'type' => fake()->randomElement(['authorized', 'independent', 'internal']),
            'company_name' => fake()->company(),
            'trade_name' => fake()->company(),
            'document' => fake()->numerify('##.###.###/####-##'),
            'state_registration' => fake()->numerify('###.###.###.###'),
            'municipal_registration' => fake()->numerify('#######'),
            'email' => fake()->companyEmail(),
            'phone' => fake()->phoneNumber(),
            'mobile' => fake()->phoneNumber(),
            'website' => fake()->url(),
            'address' => fake()->streetAddress(),
            'address_number' => fake()->buildingNumber(),
            'address_complement' => fake()->optional()->secondaryAddress(),
            'neighborhood' => fake()->citySuffix(),
            'city' => fake()->city(),
            'state' => fake()->stateAbbr(),
            'postal_code' => fake()->postcode(),
            'country' => 'Brasil',
            'latitude' => fake()->latitude(-33.7, -3.8),
            'longitude' => fake()->longitude(-73.9, -34.8),
            'coverage_radius_km' => fake()->numberBetween(10, 100),
            'payment_terms' => fake()->randomElement([15, 30, 45, 60]),
            'commission_percentage' => fake()->randomFloat(2, 5, 25),
            'notes' => fake()->optional()->paragraph(),
            'is_active' => true,
        ];
    }

    public function authorized(): static
    {
        return $this->state(fn (array $attributes): array => [
            'type' => 'authorized',
        ]);
    }

    public function independent(): static
    {
        return $this->state(fn (array $attributes): array => [
            'type' => 'independent',
        ]);
    }

    public function internal(): static
    {
        return $this->state(fn (array $attributes): array => [
            'type' => 'internal',
        ]);
    }
}

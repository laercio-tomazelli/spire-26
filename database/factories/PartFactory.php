<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Brand;
use App\Models\Part;
use App\Models\Tenant;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Part>
 */
class PartFactory extends Factory
{
    protected $model = Part::class;

    public function definition(): array
    {
        return [
            'tenant_id' => Tenant::factory(),
            'brand_id' => Brand::factory(),
            'code' => strtoupper(fake()->bothify('???-######')),
            'sku' => fake()->ean13(),
            'name' => fake()->words(4, true),
            'description' => fake()->sentence(),
            'unit' => fake()->randomElement(['UN', 'PC', 'KG', 'MT', 'CX']),
            'unit_cost' => fake()->randomFloat(4, 10, 500),
            'unit_price' => fake()->randomFloat(4, 20, 1000),
            'weight' => fake()->randomFloat(3, 0.01, 10),
            'ncm' => fake()->numerify('########'),
            'origin' => fake()->randomElement(['0', '1', '2']),
            'minimum_stock' => fake()->numberBetween(5, 20),
            'maximum_stock' => fake()->numberBetween(50, 200),
            'reorder_point' => fake()->numberBetween(10, 30),
            'is_serialized' => fake()->boolean(20),
            'is_active' => true,
        ];
    }

    public function serialized(): static
    {
        return $this->state(fn (array $attributes): array => [
            'is_serialized' => true,
        ]);
    }
}

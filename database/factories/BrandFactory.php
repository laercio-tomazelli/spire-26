<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Brand;
use App\Models\Manufacturer;
use App\Models\Tenant;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Brand>
 */
class BrandFactory extends Factory
{
    protected $model = Brand::class;

    public function definition(): array
    {
        $name = fake()->company().' '.fake()->randomElement(['Electronics', 'Home', 'Tech', 'Systems']);

        return [
            'tenant_id' => Tenant::factory(),
            'manufacturer_id' => Manufacturer::factory(),
            'name' => $name,
            'slug' => Str::slug($name),
            'code' => strtoupper(fake()->lexify('???')),
            'logo_url' => null,
            'settings' => [],
            'is_active' => true,
        ];
    }

    public function inactive(): static
    {
        return $this->state(fn (array $attributes): array => [
            'is_active' => false,
        ]);
    }
}

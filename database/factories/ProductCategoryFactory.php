<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\ProductCategory;
use App\Models\Tenant;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<ProductCategory>
 */
class ProductCategoryFactory extends Factory
{
    protected $model = ProductCategory::class;

    public function definition(): array
    {
        $name = fake()->randomElement([
            'Refrigeradores',
            'Lavadoras',
            'Ar Condicionado',
            'Fogões',
            'Micro-ondas',
            'Televisores',
            'Áudio',
            'Smartphones',
            'Notebooks',
            'Eletrodomésticos',
        ]);

        return [
            'tenant_id' => Tenant::factory(),
            'name' => $name,
            'slug' => Str::slug($name),
            'description' => fake()->sentence(),
            'parent_id' => null,
            'is_active' => true,
        ];
    }

    public function child(ProductCategory $parent): static
    {
        return $this->state(fn (array $attributes): array => [
            'parent_id' => $parent->id,
            'tenant_id' => $parent->tenant_id,
        ]);
    }
}

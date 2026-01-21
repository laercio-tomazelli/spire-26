<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Brand;
use App\Models\ProductCategory;
use App\Models\ProductLine;
use App\Models\ProductModel;
use App\Models\Tenant;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<ProductModel>
 */
class ProductModelFactory extends Factory
{
    protected $model = ProductModel::class;

    public function definition(): array
    {
        $name = fake()->words(3, true);

        return [
            'tenant_id' => Tenant::factory(),
            'brand_id' => Brand::factory(),
            'product_line_id' => ProductLine::factory(),
            'category_id' => ProductCategory::factory(),
            'name' => $name,
            'slug' => Str::slug($name),
            'code' => strtoupper(fake()->bothify('???-####')),
            'sku' => fake()->ean13(),
            'description' => fake()->paragraph(),
            'specifications' => [
                'weight' => fake()->randomFloat(2, 0.5, 50).' kg',
                'dimensions' => fake()->numerify('## x ## x ## cm'),
                'power' => fake()->randomElement(['110V', '220V', 'Bivolt']),
            ],
            'image_url' => null,
            'warranty_months' => fake()->randomElement([6, 12, 24, 36]),
            'is_active' => true,
        ];
    }
}

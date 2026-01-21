<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Brand;
use App\Models\ProductLine;
use App\Models\Tenant;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<ProductLine>
 */
class ProductLineFactory extends Factory
{
    protected $model = ProductLine::class;

    public function definition(): array
    {
        $name = fake()->randomElement([
            'Linha Profissional',
            'Linha Residencial',
            'Linha Comercial',
            'Linha Premium',
            'Linha Básica',
            'Linha Industrial',
        ]);

        return [
            'tenant_id' => Tenant::factory(),
            'brand_id' => Brand::factory(),
            'name' => $name,
            'slug' => Str::slug($name),
            'code' => strtoupper(fake()->lexify('??')),
            'description' => fake()->sentence(),
            'is_active' => true,
        ];
    }
}

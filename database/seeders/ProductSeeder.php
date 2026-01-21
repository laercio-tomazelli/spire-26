<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\Part;
use App\Models\ProductCategory;
use App\Models\ProductModel;
use App\Models\Tenant;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $tenant = Tenant::first();
        $brands = Brand::all();

        // Create product lines first
        $productLines = [
            ['name' => 'Linha Branca', 'description' => 'Refrigeradores, Lavadoras, etc.'],
            ['name' => 'Linha Marrom', 'description' => 'TVs, Som, etc.'],
            ['name' => 'Pequenos Eletrodomésticos', 'description' => 'Micro-ondas, Liquidificadores, etc.'],
        ];

        $lineIds = [];
        foreach ($productLines as $line) {
            $id = DB::table('product_lines')->insertGetId([
                'name' => $line['name'],
                'description' => $line['description'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $lineIds[$line['name']] = $id;
        }

        // Create product categories
        $categories = [
            ['name' => 'Refrigerador', 'line' => 'Linha Branca'],
            ['name' => 'Lavadora', 'line' => 'Linha Branca'],
            ['name' => 'Ar Condicionado', 'line' => 'Linha Branca'],
            ['name' => 'Televisor', 'line' => 'Linha Marrom'],
            ['name' => 'Micro-ondas', 'line' => 'Pequenos Eletrodomésticos'],
        ];

        $categoryIds = [];
        foreach ($categories as $cat) {
            $category = ProductCategory::create([
                'product_line_id' => $lineIds[$cat['line']],
                'name' => $cat['name'],
                'description' => 'Categoria de '.$cat['name'],
            ]);
            $categoryIds[$cat['name']] = $category->id;
        }

        // Create product models for each brand
        foreach ($brands as $brand) {
            foreach ($categoryIds as $catName => $catId) {
                ProductModel::create([
                    'brand_id' => $brand->id,
                    'product_category_id' => $catId,
                    'model_code' => strtoupper(substr((string) $brand->name, 0, 3)).'-'.strtoupper(substr($catName, 0, 3)).'-'.fake()->numerify('###'),
                    'model_name' => $brand->name.' '.$catName.' Premium',
                    'warranty_months' => 12,
                    'is_active' => true,
                ]);
            }
        }

        // Create parts for each brand
        $partTypes = [
            ['name' => 'Compressor', 'price' => 650.00, 'cost' => 450.00],
            ['name' => 'Motor Ventilador', 'price' => 180.00, 'cost' => 120.00],
            ['name' => 'Placa Eletrônica', 'price' => 420.00, 'cost' => 280.00],
            ['name' => 'Sensor de Temperatura', 'price' => 55.00, 'cost' => 35.00],
            ['name' => 'Termostato', 'price' => 130.00, 'cost' => 85.00],
            ['name' => 'Borracha da Porta', 'price' => 95.00, 'cost' => 65.00],
        ];

        foreach ($brands as $brand) {
            foreach ($partTypes as $partType) {
                Part::create([
                    'tenant_id' => $tenant->id,
                    'part_code' => strtoupper(substr((string) $brand->name, 0, 3)).'-'.strtoupper(substr($partType['name'], 0, 4)).'-'.fake()->numerify('####'),
                    'description' => $partType['name'].' '.$brand->name,
                    'short_description' => $partType['name'],
                    'unit' => 'UN',
                    'price' => $partType['price'],
                    'cost_price' => $partType['cost'],
                    'min_stock' => 5,
                    'max_stock' => 50,
                    'is_active' => true,
                ]);
            }
        }
    }
}

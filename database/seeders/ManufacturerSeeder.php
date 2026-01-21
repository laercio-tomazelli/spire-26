<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\Manufacturer;
use App\Models\Tenant;
use Illuminate\Database\Seeder;

class ManufacturerSeeder extends Seeder
{
    public function run(): void
    {
        $tenant = Tenant::first();

        $manufacturers = [
            [
                'name' => 'Electrolux',
                'trade_name' => 'Electrolux do Brasil',
                'brands' => ['Electrolux', 'Frigidaire'],
            ],
            [
                'name' => 'Whirlpool',
                'trade_name' => 'Whirlpool S.A.',
                'brands' => ['Brastemp', 'Consul', 'KitchenAid'],
            ],
            [
                'name' => 'Samsung',
                'trade_name' => 'Samsung Electronics',
                'brands' => ['Samsung'],
            ],
            [
                'name' => 'LG Electronics',
                'trade_name' => 'LG Electronics do Brasil',
                'brands' => ['LG'],
            ],
        ];

        foreach ($manufacturers as $data) {
            $manufacturer = Manufacturer::create([
                'tenant_id' => $tenant->id,
                'name' => $data['name'],
                'trade_name' => $data['trade_name'],
                'is_active' => true,
            ]);

            foreach ($data['brands'] as $brandName) {
                Brand::create([
                    'manufacturer_id' => $manufacturer->id,
                    'name' => $brandName,
                    'is_active' => true,
                ]);
            }
        }
    }
}

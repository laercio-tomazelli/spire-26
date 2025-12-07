<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            TenantSeeder::class,
            PermissionSeeder::class,
            RoleSeeder::class,
            LookupTablesSeeder::class,
            ManufacturerSeeder::class,
            ProductSeeder::class,       // Before PartnerSeeder (creates product_lines & categories)
            PartnerSeeder::class,       // After ProductSeeder (needs product_lines & categories)
            CustomerSeeder::class,
            UserSeeder::class,          // After Partners, Manufacturers, Customers
            ServiceOrderSeeder::class,
        ]);
    }
}

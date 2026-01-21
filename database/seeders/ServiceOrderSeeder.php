<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ServiceOrderSeeder extends Seeder
{
    public function run(): void
    {
        $tenant = DB::table('tenants')->first();
        $customers = DB::table('customers')->get();
        $partners = DB::table('partners')->get();
        $productModels = DB::table('product_models')->get();
        $parts = DB::table('parts')->get();
        $brands = DB::table('brands')->get();
        $productCategories = DB::table('product_categories')->get();
        $user = DB::table('users')->first();

        // Get lookup data from migration 000009
        $statuses = DB::table('service_order_statuses')->get();
        $serviceTypes = DB::table('service_types')->get();
        $serviceLocations = DB::table('service_locations')->get();
        $serviceOrderTypes = DB::table('service_order_types')->get();
        $repairTypes = DB::table('repair_types')->get();
        $closingTypes = DB::table('closing_types')->get();

        if ($customers->isEmpty() || $partners->isEmpty()) {
            return;
        }

        // Create sample service orders
        for ($i = 0; $i < 20; $i++) {
            $customer = $customers->random();
            $partner = $partners->random();
            $productModel = $productModels->isNotEmpty() ? $productModels->random() : null;
            $brand = $brands->isNotEmpty() ? $brands->random() : null;
            $category = $productCategories->isNotEmpty() ? $productCategories->random() : null;
            $status = $statuses->isNotEmpty() ? $statuses->random() : null;
            $serviceType = $serviceTypes->isNotEmpty() ? $serviceTypes->random() : null;
            $serviceLocation = $serviceLocations->isNotEmpty() ? $serviceLocations->random() : null;
            $orderType = $serviceOrderTypes->isNotEmpty() ? $serviceOrderTypes->random() : null;
            $repairType = $repairTypes->isNotEmpty() ? $repairTypes->random() : null;

            $openedAt = fake()->dateTimeBetween('-60 days', '-5 days');
            $isClosed = $status && in_array($status->code, ['closed', 'delivered', 'cancelled']);

            $serviceOrderId = DB::table('service_orders')->insertGetId([
                'tenant_id' => $tenant->id,
                'order_number' => $i + 1,
                'protocol' => 'PROT-'.now()->format('Y').'-'.str_pad((string) ($i + 1), 6, '0', STR_PAD_LEFT),

                // External references
                'manufacturer_order' => fake()->boolean(30) ? 'MFG-'.fake()->numerify('######') : null,
                'manufacturer_order_date' => fake()->boolean(30) ? fake()->dateTimeBetween('-30 days', 'now') : null,
                'partner_order' => fake()->boolean(40) ? 'PTN-'.fake()->numerify('####') : null,
                'partner_order_date' => fake()->boolean(40) ? fake()->dateTimeBetween('-20 days', 'now') : null,

                // Relationships
                'customer_id' => $customer->id,
                'partner_id' => $partner->id,
                'brand_id' => $brand?->id,
                'product_model_id' => $productModel?->id,
                'product_category_id' => $category?->id,

                // Product data
                'model_received' => $productModel->name ?? fake()->words(2, true),
                'serial_number' => strtoupper(fake()->bothify('??######??')),

                // Purchase data
                'retailer_name' => fake()->company(),
                'purchase_invoice_number' => fake()->numerify('######'),
                'purchase_invoice_date' => fake()->dateTimeBetween('-2 years', '-6 months'),
                'purchase_value' => fake()->randomFloat(2, 200, 5000),

                // Classification
                'service_location_id' => $serviceLocation?->id,
                'service_order_type_id' => $orderType?->id,
                'service_type_id' => $serviceType?->id,
                'repair_type_id' => $repairType?->id,
                'warranty_type' => fake()->randomElement(['in_warranty', 'out_of_warranty']),

                // Status
                'status_id' => $status?->id,

                // Defect and Repair
                'reported_defect' => fake()->sentence(8),
                'confirmed_defect' => fake()->boolean(60) ? fake()->sentence(6) : null,
                'symptom' => fake()->randomElement(['Não liga', 'Ruído estranho', 'Vazamento', 'Não gela', 'Não aquece']),
                'repair_description' => fake()->boolean(50) ? fake()->sentence(10) : null,
                'accessories' => fake()->boolean(40) ? fake()->randomElement(['Controle remoto', 'Cabo de força', 'Manual']) : null,
                'conditions' => fake()->boolean(30) ? fake()->randomElement(['Bom estado', 'Arranhões', 'Amassado']) : null,
                'observations' => fake()->boolean(30) ? fake()->paragraph() : null,

                // Flags
                'is_reentry' => fake()->boolean(10),
                'is_critical' => fake()->boolean(5),
                'is_no_defect' => fake()->boolean(8),
                'has_parts_used' => fake()->boolean(40),
                'is_display' => fake()->boolean(3),

                // Exchange
                'is_exchange' => fake()->boolean(15),
                'exchange_type' => fake()->boolean(15) ? fake()->randomElement(['product', 'refund']) : null,

                // Costs
                'labor_cost' => fake()->randomFloat(2, 50, 300),
                'distance_km' => $serviceLocation?->code === 'home' ? fake()->numberBetween(5, 100) : null,
                'km_cost' => $serviceLocation?->code === 'home' ? fake()->randomFloat(2, 10, 150) : 0,

                // Workflow dates
                'opened_at' => $openedAt,
                'opened_by' => $user->id,
                'evaluated_at' => fake()->boolean(70) ? fake()->dateTimeBetween($openedAt, 'now') : null,
                'evaluated_by' => fake()->boolean(70) ? $user->id : null,
                'repaired_at' => fake()->boolean(50) ? fake()->dateTimeBetween($openedAt, 'now') : null,
                'repaired_by' => fake()->boolean(50) ? $user->id : null,
                'closed_at' => $isClosed ? fake()->dateTimeBetween($openedAt, 'now') : null,
                'closed_by' => $isClosed ? $user->id : null,

                // Control
                'closing_type_id' => $isClosed && $closingTypes->isNotEmpty() ? $closingTypes->random()->id : null,

                'created_at' => $openedAt,
                'updated_at' => now(),
            ]);

            // Add parts to service order (using service_order_parts from migration 000011)
            if ($parts->isNotEmpty() && fake()->boolean(60)) {
                $numParts = fake()->numberBetween(1, 3);
                $selectedParts = $parts->count() >= $numParts
                    ? $parts->random($numParts)
                    : $parts;

                foreach ($selectedParts as $part) {
                    $quantity = fake()->numberBetween(1, 2);
                    $unitPrice = (float) $part->price;
                    $totalPrice = $quantity * $unitPrice;

                    DB::table('service_order_parts')->insert([
                        'service_order_id' => $serviceOrderId,
                        'part_id' => $part->id,
                        'part_code' => $part->part_code,
                        'part_description' => $part->description,
                        'quantity' => $quantity,
                        'unit_price' => $unitPrice,
                        'total_price' => $totalPrice,
                        'section' => fake()->randomElement(['A', 'B', 'C']),
                        'status' => fake()->randomElement(['requested', 'approved', 'received', 'applied']),
                        'is_approved' => fake()->boolean(70),
                        'is_received' => fake()->boolean(50),
                        'is_applied' => fake()->boolean(40),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }

            // Add comments (follow-up)
            if (fake()->boolean(70)) {
                $numComments = fake()->numberBetween(1, 3);
                for ($c = 0; $c < $numComments; $c++) {
                    DB::table('service_order_comments')->insert([
                        'service_order_id' => $serviceOrderId,
                        'user_id' => $user->id,
                        'username' => $user->name,
                        'comment' => fake()->paragraph(),
                        'comment_type' => fake()->randomElement(['user', 'system']),
                        'created_at' => fake()->dateTimeBetween($openedAt, 'now'),
                        'updated_at' => now(),
                    ]);
                }
            }
        }
    }
}

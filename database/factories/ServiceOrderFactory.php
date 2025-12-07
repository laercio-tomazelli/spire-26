<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Brand;
use App\Models\Customer;
use App\Models\Partner;
use App\Models\ProductModel;
use App\Models\ServiceOrder;
use App\Models\Tenant;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ServiceOrder>
 */
class ServiceOrderFactory extends Factory
{
    protected $model = ServiceOrder::class;

    public function definition(): array
    {
        $receivedAt = fake()->dateTimeBetween('-3 months', 'now');
        $isUnderWarranty = fake()->boolean(60);

        return [
            'tenant_id' => Tenant::factory(),
            'brand_id' => Brand::factory(),
            'partner_id' => Partner::factory(),
            'customer_id' => Customer::factory(),
            'product_model_id' => ProductModel::factory(),
            'service_order_number' => strtoupper(fake()->bothify('OS-########')),
            'external_reference' => fake()->optional()->numerify('REF-######'),
            'status_id' => null,
            'sub_status_id' => null,
            'service_type_id' => null,
            'warranty_type_id' => null,
            'priority_id' => null,
            'origin_id' => null,
            'defect_id' => null,
            'defect_found_id' => null,
            'solution_id' => null,
            'purchase_date' => fake()->dateTimeBetween('-2 years', '-1 month'),
            'warranty_expires_at' => $isUnderWarranty
                ? fake()->dateTimeBetween('+1 month', '+2 years')
                : fake()->dateTimeBetween('-1 year', '-1 day'),
            'product_serial_number' => strtoupper(fake()->bothify('SN-##########')),
            'product_condition' => fake()->randomElement(['good', 'damaged', 'dirty']),
            'reported_defect' => fake()->sentence(),
            'defect_description' => fake()->paragraph(),
            'solution_description' => null,
            'internal_notes' => fake()->optional()->paragraph(),
            'received_at' => $receivedAt,
            'started_at' => null,
            'completed_at' => null,
            'closed_at' => null,
            'canceled_at' => null,
            'cancellation_reason' => null,
            'scheduled_date' => null,
            'scheduled_period' => null,
            'technician_id' => null,
            'assigned_by' => null,
            'assigned_at' => null,
            'rating' => null,
            'rating_comment' => null,
            'rated_at' => null,
            'is_under_warranty' => $isUnderWarranty,
            'requires_approval' => ! $isUnderWarranty,
            'is_approved' => $isUnderWarranty,
            'approved_by' => null,
            'approved_at' => $isUnderWarranty ? $receivedAt : null,
            'total_parts' => 0,
            'total_labor' => 0,
            'total_travel' => 0,
            'total_discount' => 0,
            'total' => 0,
            'invoiced_amount' => 0,
            'is_invoiced' => false,
            'invoice_number' => null,
            'invoice_key' => null,
            'invoiced_at' => null,
            'monthly_closing_id' => null,
        ];
    }

    public function underWarranty(): static
    {
        return $this->state(fn (array $attributes): array => [
            'is_under_warranty' => true,
            'requires_approval' => false,
            'is_approved' => true,
            'warranty_expires_at' => fake()->dateTimeBetween('+1 month', '+2 years'),
        ]);
    }

    public function outOfWarranty(): static
    {
        return $this->state(fn (array $attributes): array => [
            'is_under_warranty' => false,
            'requires_approval' => true,
            'is_approved' => false,
            'warranty_expires_at' => fake()->dateTimeBetween('-1 year', '-1 day'),
        ]);
    }

    public function completed(): static
    {
        $completedAt = fake()->dateTimeBetween('-1 month', 'now');

        return $this->state(fn (array $attributes): array => [
            'started_at' => fake()->dateTimeBetween('-2 months', $completedAt),
            'completed_at' => $completedAt,
            'solution_description' => fake()->paragraph(),
            'total_parts' => fake()->randomFloat(2, 100, 1000),
            'total_labor' => fake()->randomFloat(2, 50, 300),
            'total_travel' => fake()->randomFloat(2, 0, 100),
            'total' => fake()->randomFloat(2, 150, 1400),
        ]);
    }

    public function closed(): static
    {
        $closedAt = fake()->dateTimeBetween('-1 week', 'now');

        return $this->completed()->state(fn (array $attributes): array => [
            'closed_at' => $closedAt,
            'is_invoiced' => true,
            'invoiced_at' => $closedAt,
        ]);
    }

    public function canceled(): static
    {
        return $this->state(fn (array $attributes): array => [
            'canceled_at' => fake()->dateTimeBetween('-1 month', 'now'),
            'cancellation_reason' => fake()->sentence(),
        ]);
    }
}

<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\InspectionStatus;
use App\Models\Inspection;
use App\Models\Tenant;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Inspection>
 */
class InspectionFactory extends Factory
{
    protected $model = Inspection::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'tenant_id' => Tenant::factory(),
            'claim_number' => (string) $this->faker->unique()->numberBetween(1_000_000, 9_999_999),
            'reference_date' => $this->faker->dateTimeBetween('-30 days', 'now'),
            'tat_days' => $this->faker->numberBetween(1, 21),
            'customer_name' => $this->faker->name(),
            'customer_contact' => $this->faker->phoneNumber(),
            'product' => $this->faker->randomElement(['REFRIGERADOR', 'PC', 'TV', 'LAVADORA', 'MICRO-ONDAS']),
            'has_electrical_damage' => $this->faker->boolean(30),
            'reports_count' => 1,
            'status' => InspectionStatus::ToSchedule,
            'remark' => null,
        ];
    }
}

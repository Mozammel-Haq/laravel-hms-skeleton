<?php

namespace Database\Factories;

use App\Models\Clinic;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Department>
 */
class DepartmentFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->unique()->words(2, true),
            'description' => fake()->sentence(),
            'floor_number' => (string)fake()->numberBetween(1, 10),
            'status' => 'active',
            'clinic_id' => function () {
                 return Clinic::query()->value('id') ?? Clinic::create([
                    'name' => 'Test Clinic',
                    'code' => 'TEST-CLINIC-' . fake()->unique()->randomNumber(3),
                    'address_line_1' => '123 Test Street',
                    'city' => 'Test City',
                    'country' => 'Testland',
                    'timezone' => 'UTC',
                    'currency' => 'USD',
                ])->id;
            },
        ];
    }
}

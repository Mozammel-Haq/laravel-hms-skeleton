<?php

namespace Database\Factories;

use App\Models\Department;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Doctor>
 */
class DoctorFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'primary_department_id' => Department::factory(),
            'specialization' => ['General'],
            'license_number' => fake()->unique()->numerify('LIC-####'),
            'experience_years' => fake()->numberBetween(1, 20),
            'status' => 'active',
            'clinic_id' => function (array $attributes) {
                // Try to get clinic_id from user if available
                if (isset($attributes['user_id'])) {
                     $user = User::find($attributes['user_id']);
                     if ($user) return $user->clinic_id;
                }
                // Or from department
                 if (isset($attributes['primary_department_id'])) {
                     $dept = Department::find($attributes['primary_department_id']);
                     if ($dept) return $dept->clinic_id;
                }
                
                // Fallback
                return User::factory()->create()->clinic_id;
            },
        ];
    }
}

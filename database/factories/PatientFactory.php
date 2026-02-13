<?php

namespace Database\Factories;

use App\Models\Clinic;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Patient>
 */
class PatientFactory extends Factory
{
    public function definition(): array
    {
        return [
            'clinic_id' => function () {
                return Clinic::query()->value('id') ?? Clinic::create([
                    'name' => 'Test Clinic',
                    'code' => 'TEST-CLINIC-'.fake()->unique()->randomNumber(3),
                    'address_line_1' => '123 Test Street',
                    'city' => 'Test City',
                    'country' => 'Testland',
                    'timezone' => 'UTC',
                    'currency' => 'USD',
                ])->id;
            },
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'password' => Hash::make('password'),
            'date_of_birth' => fake()->date(),
            'gender' => fake()->randomElement(['male', 'female', 'other']),
            'address' => fake()->address(),
            'phone' => fake()->phoneNumber(),
            'blood_group' => fake()->randomElement(['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-']),
            'patient_code' => fake()->unique()->numerify('PAT-####'),
        ];
    }
}

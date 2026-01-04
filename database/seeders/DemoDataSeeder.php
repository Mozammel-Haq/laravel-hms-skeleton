<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use App\Models\Clinic;
use App\Models\Department;
use App\Models\Doctor;
use Illuminate\Support\Facades\Hash;

class DemoDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Ensure Clinic Exists
        $clinic = Clinic::firstOrCreate(
            ['code' => 'DEMO-001'],
            [
                'name' => 'Demo General Hospital',
                'address_line_1' => '123 Health Ave',
                'city' => 'Metropolis',
                'country' => 'USA',
                'timezone' => 'UTC',
                'currency' => 'USD',
            ]
        );

        // 2. Create Departments (Needed for Doctors)
        $cardiology = Department::firstOrCreate(
            ['name' => 'Cardiology', 'clinic_id' => $clinic->id],
            ['description' => 'Heart Health']
        );
        $pediatrics = Department::firstOrCreate(
            ['name' => 'Pediatrics', 'clinic_id' => $clinic->id],
            ['description' => 'Child Health']
        );

        // 3. Define Demo Users and Roles
        $users = [
            [
                'name' => 'Super Admin',
                'email' => 'superadmin@hospital.com',
                'role' => 'Super Admin',
                'clinic_id' => $clinic->id, // Super Admin technically doesn't need one, but good for testing
            ],
            [
                'name' => 'Clinic Administrator',
                'email' => 'admin@hospital.com',
                'role' => 'Clinic Admin',
                'clinic_id' => $clinic->id,
            ],
            [
                'name' => 'Dr. John Doe',
                'email' => 'doctor@hospital.com',
                'role' => 'Doctor',
                'clinic_id' => $clinic->id,
                'specialization' => 'Cardiologist',
                'department_id' => $cardiology->id,
            ],
            [
                'name' => 'Head Nurse Mary',
                'email' => 'nurse@hospital.com',
                'role' => 'Nurse',
                'clinic_id' => $clinic->id,
            ],
            [
                'name' => 'Front Desk Sarah',
                'email' => 'receptionist@hospital.com',
                'role' => 'Receptionist',
                'clinic_id' => $clinic->id,
            ],
            [
                'name' => 'Lab Tech Mike',
                'email' => 'lab@hospital.com',
                'role' => 'Lab Technician',
                'clinic_id' => $clinic->id,
            ],
            [
                'name' => 'Pharmacist Lisa',
                'email' => 'pharmacist@hospital.com',
                'role' => 'Pharmacist',
                'clinic_id' => $clinic->id,
            ],
            [
                'name' => 'Accountant Tom',
                'email' => 'accountant@hospital.com',
                'role' => 'Accountant',
                'clinic_id' => $clinic->id,
            ],
        ];

        foreach ($users as $userData) {
            $user = User::firstOrCreate(
                ['email' => $userData['email']],
                [
                    'name' => $userData['name'],
                    'password' => Hash::make('password'),
                    'clinic_id' => $userData['clinic_id'],
                    'email_verified_at' => now(),
                ]
            );

            // Assign Role
            $role = Role::where('name', $userData['role'])->first();
            if ($role) {
                $user->assignRole($role);
            }

            // Create Related Models (e.g., Doctor)
            if ($userData['role'] === 'Doctor') {
                Doctor::firstOrCreate(
                    ['user_id' => $user->id],
                    [
                        'clinic_id' => $clinic->id,
                        'primary_department_id' => $userData['department_id'],
                        'specialization' => $userData['specialization'],
                        'license_number' => 'MD-' . rand(10000, 99999),
                        'status' => 'active',
                    ]
                );
            }
        }
    }
}

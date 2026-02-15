<?php

namespace Database\Seeders;

use App\Models\Clinic;
use App\Models\Department;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Get the Standard Clinic (created in StandardDataSeeder)
        $clinic = Clinic::first();

        if (! $clinic) {
            // Fallback if StandardDataSeeder wasn't run (though it should be)
            $clinic = Clinic::create([
                'name' => 'Dhaka Medical Center',
                'code' => 'DMC-001',
                'address_line_1' => '123 Hospital Road',
                'city' => 'Dhaka',
                'country' => 'Bangladesh',
                'timezone' => 'Asia/Dhaka',
                'currency' => 'BDT',
                'status' => 'active',
            ]);
        }

        // 2. Define Exact Users
        $users = [
            [
                'name' => 'Super Admin',
                'email' => 'superadmin@hospital.com',
                'role' => 'Super Admin',
            ],
            [
                'name' => 'Clinic Admin',
                'email' => 'admin@hospital.com',
                'role' => 'Clinic Admin',
            ],
            [
                'name' => 'HR Admin',
                'email' => 'hr@hospital.com',
                'role' => 'HR Admin',
            ],
            [
                'name' => 'Default Doctor',
                'email' => 'doctor@hospital.com',
                'role' => 'Doctor',
            ],
            [
                'name' => 'Default Nurse',
                'email' => 'nurse@hospital.com',
                'role' => 'Nurse',
            ],
            [
                'name' => 'Default Receptionist',
                'email' => 'receptionist@hospital.com',
                'role' => 'Receptionist',
            ],
            [
                'name' => 'Default Lab Technician',
                'email' => 'lab@hospital.com',
                'role' => 'Lab Technician',
            ],
            [
                'name' => 'Default Pharmacist',
                'email' => 'pharmacist@hospital.com',
                'role' => 'Pharmacist',
            ],
            [
                'name' => 'Default Accountant',
                'email' => 'accountant@hospital.com',
                'role' => 'Accountant',
            ],
        ];

        foreach ($users as $userData) {
            $user = User::firstOrCreate(
                ['email' => $userData['email']],
                [
                    'clinic_id' => $clinic->id,
                    'name' => $userData['name'],
                    'password' => Hash::make('password'),
                    'status' => 'active',
                    'email_verified_at' => now(),
                    'profile_photo_path' => 'assets/img/profile/'.Str::slug($userData['name']).'.jpg',
                ]
            );

            // Assign Role
            $role = Role::where('name', $userData['role'])->first();
            if ($role) {
                if (! $user->roles()->where('role_id', $role->id)->exists()) {
                    $user->roles()->attach($role->id);
                }
            }

            // If Doctor, create Doctor profile
            if ($userData['role'] === 'Doctor') {
                // Try to find a department (e.g., General Medicine or Internal Medicine)
                $department = Department::where('clinic_id', $clinic->id)
                    ->where(function ($q) {
                        $q->where('name', 'LIKE', '%Medicine%')
                            ->orWhere('name', 'LIKE', '%General%');
                    })->first();

                if (! $department) {
                    $department = Department::firstOrCreate(
                        ['name' => 'General Medicine', 'clinic_id' => $clinic->id],
                        ['description' => 'General Medicine Department', 'status' => 'active']
                    );
                }

                $doctor = Doctor::firstOrCreate(
                    ['user_id' => $user->id],
                    [
                        'clinic_id' => $clinic->id,
                        'primary_department_id' => $department->id,
                        'specialization' => ['General Physician'],
                        'consultation_fee' => 1000,
                        'status' => 'active',
                        'license_number' => 'BMDC-'.rand(10000, 99999),
                        'experience_years' => 10,
                    ]
                );

                if (! $doctor->clinics()->where('clinic_id', $clinic->id)->exists()) {
                    $doctor->clinics()->attach($clinic->id);
                }
            }
        }

        // 3. Create a Patient (Standard Test Patient)
        Patient::firstOrCreate(
            ['email' => 'patient@example.com'],
            [
                'clinic_id' => $clinic->id,
                'name' => 'Mr. Patient',
                'password' => Hash::make('password'),
                'date_of_birth' => '1990-01-01',
                'gender' => 'male',
                'phone' => '01700000000',
                'blood_group' => 'B+',
                'address' => 'Dhaka, Bangladesh',
                'patient_code' => 'PAT-001',
            ]
        );
    }
}

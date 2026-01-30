<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\Role;
use App\Models\Clinic;
use App\Models\Doctor;
use App\Models\Department;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Get or Create Default Clinic
        $clinic = Clinic::firstOrCreate(
            ['email' => 'dhcchms@citycare.com'],
            [
                'name' => 'Dhanmondi CityCare',
                'code' => 'CC-HMS-1',
                'registration_number' => '1210784863',
                'address_line_1' => 'Nizam\'s Shankar Plaza, 72 Satmasjid Road, Dhaka 1209',
                'city' => 'Dhaka',
                'country' => 'Bangladesh',
                'postal_code' => '1209',
                'phone' => '+11110000',
                'website' => 'citycare.com',
                'logo_path' => 'dh-cc.png',
                'timezone' => 'UTC +6',
                'currency' => 'TK',
                'opening_time' => '08:00:00',
                'closing_time' => '22:00:00',
                'status' => 'active'
            ]
        );

        // 2. Define Demo Users
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
                'name' => 'Default LabTech',
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
                    'profile_photo_path' => 'assets/img/profile/' . Str::slug($userData['name']) . '.jpg',
                ]
            );

            // Assign Role
            $role = Role::where('name', $userData['role'])->first();
            if ($role) {
                if (!$user->roles()->where('role_id', $role->id)->exists()) {
                    $user->roles()->attach($role->id);
                }
            }

            // If Doctor, create Doctor profile
            if ($userData['role'] === 'Doctor') {
                $department = Department::firstOrCreate(
                    ['name' => 'General Medicine', 'clinic_id' => $clinic->id],
                    ['description' => 'General Medicine Department', 'status' => 'active']
                );

                $doctor = Doctor::firstOrCreate(
                    ['user_id' => $user->id],
                    [
                        'primary_department_id' => $department->id,
                        'specialization' => 'General Physician',
                        'consultation_fee' => 1000,
                        'status' => 'active'
                    ]
                );

                if (!$doctor->clinics()->where('clinic_id', $clinic->id)->exists()) {
                    $doctor->clinics()->attach($clinic->id);
                }
            }
        }
    }
}

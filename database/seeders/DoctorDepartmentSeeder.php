<?php

namespace Database\Seeders;

use App\Models\Clinic;
use App\Models\Department;
use App\Models\Doctor;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DoctorDepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $clinic = Clinic::first();
        if (!$clinic) {
            return;
        }

        // Departments
        $departments = [
            'Cardiology',
            'Pediatrics',
            'Neurology',
            'Orthopedics',
            'General Medicine',
            'Dermatology'
        ];

        foreach ($departments as $deptName) {
            Department::firstOrCreate(
                ['clinic_id' => $clinic->id, 'name' => $deptName],
                ['description' => $deptName . ' Department']
            );
        }

        // Doctors
        $doctorRole = Role::where('name', 'doctor')->first();

        $doctorsData = [
            [
                'name' => 'Dr. Smith',
                'email' => 'smith@clinic.com',
                'dept' => 'Cardiology',
                'specialization' => 'Cardiologist',
            ],
            [
                'name' => 'Dr. Jane',
                'email' => 'jane@clinic.com',
                'dept' => 'Pediatrics',
                'specialization' => 'Pediatrician',
            ]
        ];

        foreach ($doctorsData as $data) {
            $user = User::firstOrCreate(
                ['email' => $data['email']],
                [
                    'name' => $data['name'],
                    'password' => Hash::make('password'),
                    'clinic_id' => $clinic->id,
                ]
            );

            if ($doctorRole) {
                $user->assignRole($doctorRole);
            }

            $dept = Department::where('clinic_id', $clinic->id)->where('name', $data['dept'])->first();

            Doctor::firstOrCreate(
                ['user_id' => $user->id],
                [
                    'clinic_id' => $clinic->id,
                    'primary_department_id' => $dept->id,
                    'specialization' => $data['specialization'],
                    'license_number' => 'LIC-' . rand(1000, 9999),
                    'status' => 'active',
                ]
            );
        }
    }
}

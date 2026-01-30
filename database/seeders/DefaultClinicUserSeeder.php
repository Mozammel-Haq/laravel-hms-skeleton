<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DefaultClinicUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $prefix = env('DB_PREFIX', '');
        $clinics = $prefix . 'clinics';
        $users = $prefix . 'users';
        $roles = $prefix . 'roles';
        $userRole = $prefix . 'user_role';

        $clinicId = DB::table($clinics)->where('email', 'dhcchms@citycare.com')->value('id');

        if (!$clinicId) {
            $clinicId = DB::table($clinics)->insertGetId([
                'name' => 'Dhanmondi CityCare',
                'code' => 'CC-HMS-1',
                'registration_number' => '1210784863',
                'address_line_1' => 'Nizam\'s Shankar Plaza, 72 Satmasjid Road, Dhaka 1209',
                'address_line_2' => null,
                'city' => 'Dhaka',
                'state' => 'BD',
                'country' => 'Bangladesh',
                'postal_code' => '1209',
                'phone' => '+8801711223344',
                'email' => 'dhcchms@citycare.com',
                'website' => 'citycare.com.bd',
                'logo_path' => 'dh-cc.png',
                'timezone' => 'Asia/Dhaka',
                'currency' => 'BDT',
                'opening_time' => '08:00:00',
                'closing_time' => '22:00:00',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $user = DB::table($users)->where('email', 'admin@hospital.com')->first();

        if (!$user) {
            $user_id = DB::table($users)->insertGetId([
                'clinic_id' => $clinicId,
                'email' => 'admin@hospital.com',
                'name' => 'Clinic Admin',
                'password' => Hash::make('password'),
                'phone' => '+8801711223344',
                'email_verified_at' => now(),
                'last_login_at' => null,
                'is_two_factor_enabled' => 0,
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } else {
            $user_id = $user->id;
        }

        $role_id = DB::table($roles)->where('name', 'Clinic Admin')->value('id');

        if ($role_id) {
            $exists = DB::table($userRole)->where('user_id', $user_id)->where('role_id', $role_id)->exists();
            if (!$exists) {
                DB::table($userRole)->insert([
                    'user_id' => $user_id,
                    'role_id' => $role_id
                ]);
            }
        }
    }
}

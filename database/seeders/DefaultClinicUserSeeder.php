<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DefaultClinicUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $clinicId = DB::table('clinics')->where('email', 'dhcchms@citycare.com')->value('id');

        if (!$clinicId) {
            $clinicId = DB::table('clinics')->insertGetId([
                'name' => 'Dhanmondi CityCare',
                'code' => 'CC-HMS-1',
                'registration_number' => '1210784863',
                'address_line_1' => 'Nizam\'s Shankar Plaza, 72 Satmasjid Road, Dhaka 1209',
                'address_line_2' => null,
                'city' => 'Dhaka',
                'state' => 'BD',
                'country' => 'Bangladesh',
                'postal_code' => '1209',
                'phone' => '+11110000',
                'email' => 'dhcchms@citycare.com',
                'website' => 'https://citycare.com',
                'logo_path' => 'dh-cc.png',
                'timezone' => 'UTC +6',
                'currency' => 'BDT',
                'opening_time' => '08:00:00',
                'closing_time' => '01:30:00',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $user = DB::table('users')->where('email', 'hmojammel29@gmail.com')->first();

        if (!$user) {
            $user_id = DB::table('users')->insertGetId([
                'clinic_id' => $clinicId,
                'email' => 'hmojammel29@gmail.com',
                'name' => 'Mozammel Haq',
                'password' => '$2y$12$MkVB1YRWFc.Rjzo1cvMKAOo8Wfa0eWdLew2I3rpOQTd8/eXpY.c2',
                'phone' => '01799007398',
                'email_verified_at' => null,
                'last_login_at' => null,
                'is_two_factor_enabled' => 0,
                'status' => 'active',
                'created_at' => null,
                'updated_at' => '2026-01-01 06:25:47',
            ]);
        } else {
            $user_id = $user->id;
        }

        $role_id = DB::table('roles')->where('name', 'Clinic Admin')->value('id');

        if ($role_id) {
            $exists = DB::table('user_role')->where('user_id', $user_id)->where('role_id', $role_id)->exists();
            if (!$exists) {
                DB::table('user_role')->insert([
                    'user_id' => $user_id,
                    'role_id' => $role_id
                ]);
            }
        }
    }
}

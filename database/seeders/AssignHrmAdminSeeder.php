<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AssignHrmAdminSeeder extends Seeder
{
    public function run(): void
    {
        $role = Role::firstOrCreate(['name' => 'Clinic Admin'], ['description' => 'Administrator for the clinic']);

        $emails = [
            'admin@hospital.com',
            'superadmin@hospital.com',
        ];

        foreach ($emails as $email) {
            $user = User::where('email', $email)->first();
            if (! $user) {
                continue;
            }
            if (! $user->roles()->where('role_id', $role->id)->exists()) {
                $user->roles()->attach($role->id);
            }
            $user->unsetRelation('roles');
        }
    }
}

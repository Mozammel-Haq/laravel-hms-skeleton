<?php

use Illuminate\Database\Migrations\Migration;

return new class extends Migration {
    public function up(): void
    {
        try {
            $role = \App\Models\Role::firstOrCreate(['name' => 'Clinic Admin'], ['description' => 'Clinic Administrator']);

            $needed = [
                ['name' => 'view_ipd', 'module' => 'ipd'],
                ['name' => 'view_billing', 'module' => 'billing'],
                ['name' => 'view_doctors', 'module' => 'clinic'],
            ];

            $permIds = [];
            foreach ($needed as $perm) {
                $p = \App\Models\Permission::firstOrCreate(['name' => $perm['name']], ['module' => $perm['module']]);
                $permIds[] = $p->id;
            }

            $role->permissions()->syncWithoutDetaching($permIds);
        } catch (\Throwable $e) {
            // Fail silently to avoid breaking migrations if tables not seeded yet
        }
    }

    public function down(): void
    {
        try {
            $role = \App\Models\Role::where('name', 'Clinic Admin')->first();
            if ($role) {
                $perms = \App\Models\Permission::whereIn('name', ['view_ipd', 'view_billing', 'view_doctors'])->get();
                $role->permissions()->detach($perms->pluck('id'));
            }
        } catch (\Throwable $e) {
        }
    }
};

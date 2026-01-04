# 02. Authentication & RBAC

## 1. Role-Based Access Control System
We implemented a custom RBAC system instead of using a package to maintain full control over the multi-tenancy aspect.

### Database Schema
We need 3 tables for RBAC:
1. `roles` (id, name, description)
2. `permissions` (id, name)
3. `role_permission` (role_id, permission_id)
4. `user_role` (user_id, role_id)

## 2. Models

### Role Model
**File:** `app/Models/Role.php`
```php
class Role extends Model
{
    protected $fillable = ['name', 'description'];

    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'role_permission');
    }
}
```

### Permission Model
**File:** `app/Models/Permission.php`
```php
class Permission extends Model
{
    protected $fillable = ['name'];
}
```

### User Model Extensions
Add these methods to `app/Models/User.php` to handle checks.

```php
public function roles()
{
    return $this->belongsToMany(Role::class, 'user_role');
}

public function hasRole($role)
{
    if (is_string($role)) {
        return $this->roles->contains('name', $role);
    }
    return $this->roles->contains($role);
}

public function hasPermission($permission)
{
    return $this->roles->flatMap->permissions->contains('name', $permission);
}
```

## 3. Blade Directives
To make checking roles easy in the frontend, we registered a custom directive in `AppServiceProvider`.

**File:** `app/Providers/AppServiceProvider.php`
```php
public function boot(): void
{
    Blade::if('role', function ($role) {
        return auth()->check() && auth()->user()->hasRole($role);
    });
}
```
**Usage:**
```blade
@role('Doctor')
    <a href="/doctor/dashboard">Doctor Dashboard</a>
@endrole
```

## 4. Seeding Roles & Permissions
We define all system roles and permissions in a seeder. This is the source of truth for access control.

**File:** `database/seeders/RolePermissionSeeder.php`
(See codebase for full list of permissions)

```php
public function run(): void
{
    // 1. Define Permissions
    $permissions = [
        'view_dashboard',
        'view_patients',
        'create_patients',
        // ... list all permissions
    ];

    foreach ($permissions as $permissionName) {
        Permission::firstOrCreate(['name' => $permissionName]);
    }

    // 2. Define Roles
    $doctor = Role::firstOrCreate(['name' => 'Doctor']);
    
    // 3. Assign Permissions
    $doctor->permissions()->sync(Permission::whereIn('name', [
        'view_dashboard',
        'view_patients',
        'create_prescriptions'
    ])->pluck('id'));
}
```

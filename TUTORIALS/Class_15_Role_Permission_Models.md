# Class 15: Role & Permission Models

## Introduction
Now that we have the tables, let's build the Eloquent models to interact with them.

## 1. The Role Model
Open `app/Models/Role.php`.

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'label'];

    /**
     * A Role belongs to many Users.
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'user_role');
    }

    /**
     * A Role belongs to many Permissions.
     */
    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'role_permission');
    }
}
```

## 2. The Permission Model
Open `app/Models/Permission.php`.

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'label'];

    /**
     * A Permission belongs to many Roles.
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_permission');
    }
}
```

## 3. Updating the User Model
We need to add the relationship to the `User` model (`app/Models/User.php`).

```php
// Add inside User class

public function roles()
{
    return $this->belongsToMany(Role::class, 'user_role');
}

/**
 * Check if user has a specific role.
 * Usage: $user->hasRole('admin')
 */
public function hasRole($roleName)
{
    return $this->roles->contains('name', $roleName);
}

/**
 * Check if user has a specific permission (via their roles).
 * Usage: $user->hasPermission('edit_posts')
 */
public function hasPermission($permissionName)
{
    // Eager load roles and permissions if not already loaded to avoid N+1
    // For simplicity in this tutorial logic:
    foreach ($this->roles as $role) {
        if ($role->permissions->contains('name', $permissionName)) {
            return true;
        }
    }
    return false;
}
```

## 4. Optimization Note
The `hasPermission` method above is naive. In a real app, you would cache permissions or use eager loading (`$user->load('roles.permissions')`) to prevent running database queries every time you check a button's visibility.

## Summary
We have connected our Users, Roles, and Permissions.

In the next class, we will seed the database with the default roles for our HMS.

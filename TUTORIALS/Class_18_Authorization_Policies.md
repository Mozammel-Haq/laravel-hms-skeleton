# Class 18: Authorization Policies

## Introduction
Controllers should be slim. They shouldn't contain logic like "If user is doctor, allow this, else deny". That logic belongs in **Policies**.

## 1. Creating a Policy
Let's create a policy for the `User` model itself (e.g., who can edit a user?).

```bash
php artisan make:policy UserPolicy --model=User
```

## 2. Implementing the Policy
Open `app/Policies/UserPolicy.php`.

```php
<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('manage_users');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, User $model): bool
    {
        // 1. Admin with permission can edit anyone
        if ($user->hasPermission('manage_users')) {
            return true;
        }

        // 2. Users can usually edit their own profile
        return $user->id === $model->id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, User $model): bool
    {
        return $user->hasPermission('manage_users');
    }
}
```

## 3. Registering the Policy
In modern Laravel (11+), policies are auto-discovered.
In Laravel 10, check `app/Providers/AuthServiceProvider.php`:

```php
protected $policies = [
    User::class => UserPolicy::class,
];
```

## 4. Using the Policy
In your Controller:

```php
public function update(Request $request, User $user)
{
    $this->authorize('update', $user); // Throws 403 if false
    
    // Proceed with update...
}
```

## Summary
Policies provide a centralized, clean, and reusable way to handle authorization logic.

In the next class, we will look at Middleware for broader role-based protection.

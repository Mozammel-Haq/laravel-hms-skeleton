# Class 03: Service Provider Configuration

## Introduction
Service Providers are the central place of all Laravel application bootstrapping. In this class, we will configure the `AppServiceProvider` to set up default behaviors for our application, such as string lengths for database migrations and forcing HTTPS in production.

## 1. Understanding `AppServiceProvider`
Open `app/Providers/AppServiceProvider.php`. This class has two main methods:
- `register()`: Use this to bind things into the Service Container.
- `boot()`: Use this to execute code after all other services have been registered.

## 2. Configuring Default String Length
Older versions of MySQL (older than 5.7.7) may crash if you try to create unique indexes on string columns with the default length. To prevent this, we set a default string length.

**Action:** Update `boot()` method.

```php
// app/Providers/AppServiceProvider.php

use Illuminate\Support\Facades\Schema; // Import this
use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Model;

class AppServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        // Set default string length to 191 to support older MySQL versions
        Schema::defaultStringLength(191);

        // Prevent lazy loading in non-production environments
        // This helps catch N+1 query problems early during development
        Model::preventLazyLoading(! $this->app->isProduction());
        
        // Force HTTPS in production
        if($this->app->environment('production')) {
            \Illuminate\Support\Facades\URL::forceScheme('https');
        }
    }
}
```

## 3. Unguarding Models (Optional but Recommended)
By default, Laravel protects against mass assignment. You must define `$fillable` or `$guarded` in every model.
Some developers prefer to "unguard" models globally and rely on validation requests for security.

If you want to unguard globally (we will NOT do this for this tutorial to be explicit, but it's good to know):
```php
// In boot()
Model::unguard();
```
*Note: We will stick to defining `$fillable` in our models for clarity.*

## 4. Configuring Timezones
In a global app, timezones are tricky. We set the application timezone in `config/app.php`.
Open `config/app.php` and verify:
```php
'timezone' => 'UTC',
```
We will store everything in UTC in the database and convert it to the clinic's timezone when displaying data.

## Summary
We have configured the application boot process:
1.  Fixed potential database key length issues.
2.  Enabled strict mode (`preventLazyLoading`) to improve performance awareness.
3.  Ensured security with HTTPS enforcement.

In the next class, we will create our `Support` directory and add a `TenantContext` helper class, which is the heart of our multi-tenancy system.

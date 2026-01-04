# 01. Project Architecture & Setup

## 1. Project Overview
This Hospital Management System (HMS) is a multi-tenant application designed to host multiple clinics/hospitals on a single database (Shared Database, Shared Schema). It isolates data using a `clinic_id` column on every tenant-specific table.

### Key Features
- **Multi-tenancy**: Data isolation via Global Scopes.
- **RBAC**: Custom Role-Based Access Control.
- **Service Layer**: Business logic separated from Controllers.
- **Modules**: OPD, IPD, Pharmacy, Lab, Billing, HR.

## 2. Directory Structure
The project follows standard Laravel 11 structure with key additions:

```
app/
├── Http/
│   ├── Middleware/
│   │   └── EnsureClinicContext.php  <-- Tenant Context Middleware
├── Models/
│   ├── Concerns/
│   │   └── BelongsToClinic.php      <-- Tenant Scope Trait
├── Services/                        <-- Business Logic Layer
│   ├── AppointmentService.php
│   ├── BillingService.php
│   ├── IpdService.php
│   └── PharmacyService.php
```

## 3. Multi-Tenancy Implementation

### A. The `BelongsToClinic` Trait
This trait is applied to **every model** that belongs to a specific clinic. It automatically adds `where clinic_id = ?` to queries and sets `clinic_id` on creation.

**File:** `app/Models/Concerns/BelongsToClinic.php`
```php
<?php

namespace App\Models\Concerns;

use App\Support\TenantContext;
use Illuminate\Database\Eloquent\Builder;

trait BelongsToClinic
{
    protected static function bootBelongsToClinic()
    {
        static::addGlobalScope('clinic', function (Builder $builder) {
            if (!TenantContext::hasClinic()) {
                return;
            }

            $builder->where(
                $builder->getModel()->getTable() . '.clinic_id',
                TenantContext::getClinicId()
            );
        });

        static::creating(function ($model) {
            if (TenantContext::hasClinic() && empty($model->clinic_id)) {
                $model->clinic_id = TenantContext::getClinicId();
            }
        });
    }
}
```

### B. Tenant Context Helper
We use a simple static helper to manage the current clinic ID state.

**File:** `app/Support/TenantContext.php` (Create if not exists)
```php
<?php

namespace App\Support;

class TenantContext
{
    protected static $clinicId;

    public static function setClinicId($id)
    {
        self::$clinicId = $id;
    }

    public static function getClinicId()
    {
        return self::$clinicId;
    }

    public static function hasClinic()
    {
        return !is_null(self::$clinicId);
    }
}
```

### C. The Middleware
This middleware ensures that every request has a valid clinic context. For Super Admins, it might pick from the session; for regular users, it uses their assigned clinic.

**File:** `app/Http/Middleware/EnsureClinicContext.php`
```php
<?php

namespace App\Http\Middleware;

use App\Support\TenantContext;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class EnsureClinicContext
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();
        $selectedClinicId = Session::get('selected_clinic_id');
        
        // Super Admin can switch contexts, others are locked to their clinic
        $clinicId = $user->hasRole('Super Admin') && $selectedClinicId ? $selectedClinicId : $user->clinic_id;

        if (!$clinicId) {
            abort(403, 'Clinic context missing');
        }

        // Set tenant context ONCE per request
        TenantContext::setClinicId($clinicId);

        return $next($request);
    }
}
```

### D. User Model Configuration
The User model must use the trait (unless it's a global Super Admin, but usually even users belong to a clinic or we handle nulls).

**File:** `app/Models/User.php`
```php
namespace App\Models;

use App\Models\Concerns\BelongsToClinic;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use BelongsToClinic; // Apply the scope

    protected $fillable = [
        'clinic_id', // Important
        'name',
        'email',
        'password',
    ];
    
    // ... relations ...
}
```

## 4. Database Schema Note
Every table that stores tenant data MUST have a `clinic_id` column.
Example Migration:
```php
$table->foreignId('clinic_id')->constrained()->onDelete('cascade');
```

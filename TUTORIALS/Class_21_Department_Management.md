# Class 21: Department Management

## Introduction
Every hospital is divided into departments (Cardiology, Neurology, OPD, IPD, etc.). This is the simplest tenant-specific entity we will build, making it the perfect starting point for Module 4.

## 1. The Migration
Run: `php artisan make:model Department -m`

Open the migration file:
```php
public function up(): void
{
    Schema::create('departments', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('clinic_id'); // Tenant ID
        $table->string('name');
        $table->text('description')->nullable();
        $table->string('status')->default('active'); // active, inactive
        $table->timestamps();
        $table->softDeletes();

        $table->foreign('clinic_id')->references('id')->on('clinics')->onDelete('cascade');
    });
}
```

## 2. The Model
Open `app/Models/Department.php`.

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

// IMPORTANT: Extend BaseTenantModel, not Model
class Department extends BaseTenantModel 
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'status',
        'clinic_id', // BaseTenantModel handles this automatically, but safe to keep
    ];
    
    // Relationships
    public function doctors()
    {
        return $this->hasMany(Doctor::class);
    }
}
```

## 3. The Controller
Run: `php artisan make:controller DepartmentController --resource`

```php
public function index()
{
    // Thanks to BaseTenantModel, this only returns departments for the current clinic
    $departments = Department::paginate(10); 
    return view('departments.index', compact('departments'));
}

public function store(Request $request)
{
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',
        'status' => 'required|in:active,inactive',
    ]);

    // The Observer in BaseTenantModel will automatically set clinic_id
    Department::create($validated);

    return redirect()->route('departments.index')->with('success', 'Department created.');
}
```

## Summary
This is our "Hello World" of tenant data. It proves that our `BaseTenantModel` and `TenantContext` work correctly in a real CRUD scenario.

# Class 02: Database Architecture & Schema Design

## Introduction
In this class, we will design the database schema for our Hospital Management System. A well-structured database is critical for a complex application like an HMS. We will use a **Multi-Tenant Single Database** approach, where all clinics share the same database, but every record is linked to a `clinic_id`.

## 1. Core Entity Relationship Diagram (ERD) Concepts
Our system revolves around these key entities:
1.  **Clinic**: The tenant (Hospital/Clinic).
2.  **User**: Staff members (Doctors, Nurses, Admins).
3.  **Patient**: The people receiving care.
4.  **Visit/Admission**: The event of care (OPD or IPD).

## 2. Migration Strategy
We will create migrations in a specific order to avoid foreign key constraint errors.

### Step 1: The `clinics` Table
This is the root table. Every other table will reference this.

```php
// database/migrations/2024_01_01_000001_create_clinics_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('clinics', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique(); // Unique identifier for the clinic
            $table->string('address_line_1');
            $table->string('address_line_2')->nullable();
            $table->string('city');
            $table->string('state')->nullable();
            $table->string('country');
            $table->string('postal_code')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('website')->nullable();
            $table->string('logo')->nullable();
            $table->string('timezone')->default('UTC');
            $table->string('currency')->default('USD');
            $table->enum('status', ['active', 'inactive', 'suspended'])->default('active');
            $table->timestamps();
            $table->softDeletes(); // Allow restoring deleted clinics
        });
    }

    public function down()
    {
        Schema::dropIfExists('clinics');
    }
};
```

### Step 2: The `users` Table
We modify the default users table to include `clinic_id`.

```php
// database/migrations/2024_01_01_000002_create_users_table.php
Schema::create('users', function (Blueprint $table) {
    $table->id();
    $table->foreignId('clinic_id')->nullable()->constrained()->onDelete('cascade');
    $table->string('name');
    $table->string('email')->unique();
    $table->timestamp('email_verified_at')->nullable();
    $table->string('password');
    $table->string('phone')->nullable();
    $table->string('profile_photo_path')->nullable();
    $table->boolean('is_super_admin')->default(false); // For system-wide admins
    $table->enum('status', ['active', 'inactive'])->default('active');
    $table->rememberToken();
    $table->timestamps();
    $table->softDeletes();
});
```

### Step 3: The `departments` Table
Departments belong to a clinic.

```php
// database/migrations/2024_01_01_000003_create_departments_table.php
Schema::create('departments', function (Blueprint $table) {
    $table->id();
    $table->foreignId('clinic_id')->constrained()->onDelete('cascade');
    $table->string('name');
    $table->string('code')->nullable();
    $table->text('description')->nullable();
    $table->enum('status', ['active', 'inactive'])->default('active');
    $table->timestamps();
    $table->softDeletes();
});
```

## 3. The "BaseTenantModel" Concept
To ensure every query automatically filters by `clinic_id`, we will later create a `BaseTenantModel`. For now, just remember that **almost every table** we create from now on MUST have:
`$table->foreignId('clinic_id')->constrained()->onDelete('cascade');`

## Summary
We have defined the foundation of our schema:
1.  **Clinics**: The top-level entity.
2.  **Users**: Linked to clinics.
3.  **Departments**: The organizational units.

In the next class, we will configure the `AppServiceProvider` and creating our first Helper classes.

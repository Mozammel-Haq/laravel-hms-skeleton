# Class 31: Patient Model Design

## Introduction
The `Patient` model is arguably the most important entity in a Hospital Management System. Unlike a simple customer record, a patient record contains sensitive demographic data, identifiers (like National ID or Passport), and must be designed to support long-term medical history tracking.

## 1. The Migration Strategy
We need to capture essential information while allowing for future extensibility.

Run the command:
```bash
php artisan make:model Patient -m
```

Open `database/migrations/xxxx_xx_xx_xxxxxx_create_patients_table.php`:

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('patients', function (Blueprint $table) {
            $table->id();
            // We do NOT add 'clinic_id' here if we are going for a "Global Patient" design 
            // where a patient can exist across multiple clinics. 
            // However, to keep the initial tutorial simple and secure (strict isolation), 
            // we will start with a 'clinic_id' (Single Clinic Patient) and then 
            // refactor to Global in Class 32.
            
            // For this specific implementation (Module 5 start), let's assume strict tenancy first,
            // as it's the safest default for most developers.
            $table->unsignedBigInteger('clinic_id')->nullable(); 
            // Nullable? If nullable, it's a global patient. If set, it's private.
            
            // Core Identity
            $table->string('first_name');
            $table->string('last_name');
            $table->date('dob'); // Date of Birth is critical for medical calculations
            $table->string('gender'); // male, female, other
            $table->string('blood_group')->nullable(); // A+, B-, etc.
            
            // Contact
            $table->string('phone')->index(); // Indexed for fast search
            $table->string('email')->nullable();
            $table->text('address')->nullable();
            
            // Official Identifiers
            $table->string('nid')->nullable()->index(); // National ID
            $table->string('passport_number')->nullable();
            
            // System Metadata
            $table->string('registration_number')->unique(); // Clinic specific ID (e.g. PAT-2023-001)
            
            $table->timestamps();
            $table->softDeletes();
            
            // Foreign Keys
            $table->foreign('clinic_id')->references('id')->on('clinics')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('patients');
    }
};
```

## 2. The Patient Model
Open `app/Models/Patient.php`.

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class Patient extends BaseTenantModel
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'clinic_id',
        'first_name',
        'last_name',
        'dob',
        'gender',
        'blood_group',
        'phone',
        'email',
        'address',
        'nid',
        'passport_number',
        'registration_number',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'dob' => 'date',
    ];

    /**
     * Accessor: Full Name
     */
    public function getNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }

    /**
     * Accessor: Age
     * Calculates age dynamically from DOB.
     */
    public function getAgeAttribute()
    {
        return Carbon::parse($this->dob)->age;
    }
}
```

## 3. Generating Registration Numbers
We need a way to generate unique IDs like `PAT-2023-0001`. We can use a Model Observer for this.

Run: `php artisan make:observer PatientObserver --model=Patient`

In `app/Observers/PatientObserver.php`:

```php
public function creating(Patient $patient): void
{
    // Generate unique registration number if not set
    if (empty($patient->registration_number)) {
        $year = now()->year;
        $latest = Patient::whereYear('created_at', $year)->max('id') ?? 0;
        $next = $latest + 1;
        $patient->registration_number = 'PAT-' . $year . '-' . str_pad($next, 5, '0', STR_PAD_LEFT);
    }
    
    // BaseTenantModel handles clinic_id, but we can double check here
}
```

Register the observer in `AppServiceProvider`:
```php
use App\Models\Patient;
use App\Observers\PatientObserver;

public function boot(): void
{
    Patient::observe(PatientObserver::class);
}
```

## Summary
We have designed a robust `Patient` model that captures demographics and auto-generates unique IDs. In the next class, we will tackle the complex issue of "Global Patients".

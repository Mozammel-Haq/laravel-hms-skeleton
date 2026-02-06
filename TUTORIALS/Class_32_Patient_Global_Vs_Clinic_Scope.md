# Class 32: Patient Global vs Clinic Scope

## Introduction
In a standard multi-tenant app, data is strictly isolated. Tenant A cannot see Tenant B's data. However, in healthcare, a patient might visit multiple clinics using the same software (e.g., a chain of hospitals). It is beneficial to have a "Single Patient Record" to avoid duplication.

## 1. The Pivot Table Approach
To support this, we need to change our thinking.
-   **Old Way**: `patients` table has `clinic_id`. One patient = One Clinic.
-   **New Way**: `patients` table has NO `clinic_id` (it's global). A pivot table `clinic_patient` links them.

## 2. Creating the Pivot Migration
Run: `php artisan make:migration create_clinic_patient_table`

```php
public function up(): void
{
    Schema::create('clinic_patient', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('clinic_id');
        $table->unsignedBigInteger('patient_id');
        $table->string('local_registration_number')->nullable(); // ID specific to this clinic
        $table->timestamps();

        $table->foreign('clinic_id')->references('id')->on('clinics')->onDelete('cascade');
        $table->foreign('patient_id')->references('id')->on('patients')->onDelete('cascade');
        
        $table->unique(['clinic_id', 'patient_id']);
    });
}
```

## 3. Modifying the Patient Model
We need to remove `BaseTenantModel` inheritance because the Patient is no longer *owned* by a single tenant.

Open `app/Models/Patient.php`:

```php
// extends Model, NOT BaseTenantModel
class Patient extends Model 
{
    // ... traits ...

    // We define a Global Scope manually to filter by the pivot table
    protected static function booted()
    {
        static::addGlobalScope('clinic_access', function (Builder $builder) {
            if (auth()->check() && auth()->user()->clinic_id) {
                // Only show patients linked to the current user's clinic
                $builder->whereHas('clinics', function($q) {
                    $q->where('clinics.id', auth()->user()->clinic_id);
                });
            }
        });
    }

    public function clinics()
    {
        return $this->belongsToMany(Clinic::class, 'clinic_patient')
                    ->withPivot('local_registration_number')
                    ->withTimestamps();
    }
}
```

## 4. The Registration Challenge
When a receptionist registers a patient:
1.  Check if patient exists globally (by Phone/NID).
2.  If YES: Link existing patient to this clinic (`$patient->clinics()->attach(...)`).
3.  If NO: Create new patient, then link.

## 5. Helper Service
Create `app/Services/PatientService.php`:

```php
public function findOrRegister($data, $clinicId)
{
    // 1. Search Global
    $patient = Patient::withoutGlobalScope('clinic_access')
                      ->where('phone', $data['phone'])
                      ->first();

    if (!$patient) {
        // 2. Create New
        $patient = Patient::create($data);
    }

    // 3. Link to Clinic (if not already linked)
    if (!$patient->clinics()->where('clinic_id', $clinicId)->exists()) {
        $patient->clinics()->attach($clinicId, [
            'local_registration_number' => $this->generateId($clinicId)
        ]);
    }

    return $patient;
}
```

## Summary
This "Many-to-Many" architecture allows for a "Networked" HMS where patient history *could* theoretically be shared (with consent), while still maintaining the illusion of isolation for the receptionist.

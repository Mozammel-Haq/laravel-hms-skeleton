# Class 34: Patient Medical History

## Introduction
A patient is more than just a name. We need to know their allergies, past surgeries, and chronic conditions.

## 1. Migrations
We could use a JSON column on the `patients` table, but relational tables are better for querying (e.g., "Find all patients with Penicillin allergy").

```bash
php artisan make:model PatientAllergy -m
php artisan make:model PatientCondition -m
php artisan make:model PatientSurgery -m
```

### Allergies
```php
Schema::create('patient_allergies', function (Blueprint $table) {
    $table->id();
    $table->foreignId('patient_id')->constrained()->cascadeOnDelete();
    $table->string('allergen'); // "Peanuts", "Penicillin"
    $table->string('severity')->default('mild'); // mild, moderate, severe
    $table->text('reaction')->nullable(); // "Hives", "Anaphylaxis"
    $table->timestamps();
});
```

### Conditions (Chronic Diseases)
```php
Schema::create('patient_conditions', function (Blueprint $table) {
    $table->id();
    $table->foreignId('patient_id')->constrained()->cascadeOnDelete();
    $table->string('condition_name'); // "Diabetes Type 2"
    $table->date('diagnosed_at')->nullable();
    $table->string('status')->default('active'); // active, cured
    $table->timestamps();
});
```

## 2. Models & Relationships
In `Patient.php`:

```php
public function allergies()
{
    return $this->hasMany(PatientAllergy::class);
}

public function conditions()
{
    return $this->hasMany(PatientCondition::class);
}

public function surgeries()
{
    return $this->hasMany(PatientSurgery::class);
}
```

## 3. UI Implementation
We usually manage this in the Patient Profile view using a modal or a dedicated "Medical History" tab.

Controller method to add allergy:
```php
public function storeAllergy(Request $request, Patient $patient)
{
    $patient->allergies()->create($request->validate([
        'allergen' => 'required',
        'severity' => 'required'
    ]));
    
    return back();
}
```

## Summary
Structuring medical history as separate related models allows for powerful analytics and critical safety checks (e.g., "Warning: Prescribing Penicillin to allergic patient").

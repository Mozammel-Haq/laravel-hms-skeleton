# Class 25: Doctor Professional Details

## Introduction
A single table isn't enough for a doctor's full CV. They have multiple degrees (MBBS, MD), multiple certifications, and awards. We need related tables.

## 1. Migrations
```bash
php artisan make:model DoctorEducation -m
php artisan make:model DoctorCertification -m
```

### Education
```php
Schema::create('doctor_educations', function (Blueprint $table) {
    $table->id();
    $table->unsignedBigInteger('doctor_id'); // No clinic_id needed? 
    // Actually, data usually belongs to the tenant. But education is personal to the doctor.
    // However, for consistency and cascade deletes, let's keep it simple.
    // If we want strict tenancy, we add clinic_id.
    
    $table->string('degree'); // "MBBS"
    $table->string('institution'); // "Dhaka Medical College"
    $table->year('year'); // "2010"
    $table->timestamps();

    $table->foreign('doctor_id')->references('id')->on('doctors')->onDelete('cascade');
});
```

## 2. Relationships
In `Doctor.php`:

```php
public function educations()
{
    return $this->hasMany(DoctorEducation::class);
}

public function certifications()
{
    return $this->hasMany(DoctorCertification::class);
}
```

## 3. Saving Data
When creating a doctor profile, we might accept an array of education data.

```php
// DoctorController.php
foreach ($request->education as $edu) {
    $doctor->educations()->create([
        'degree' => $edu['degree'],
        'institution' => $edu['institution'],
        'year' => $edu['year'],
    ]);
}
```

## Summary
We now have a rich data structure for Doctors, capable of generating a full CV or profile page for the hospital website.

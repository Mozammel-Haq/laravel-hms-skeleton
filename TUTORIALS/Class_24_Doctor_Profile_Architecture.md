# Class 24: Doctor Profile Architecture

## Introduction
Doctors are the core entity. A simple `User` or `Staff` record isn't enough. We need:
-   Specialization (Cardiologist, Dentist)
-   License Number (for prescriptions)
-   Consultation Fees
-   Bio/Description (for the public website)

## 1. Migration
```bash
php artisan make:model Doctor -m
```

```php
Schema::create('doctors', function (Blueprint $table) {
    $table->id();
    $table->unsignedBigInteger('clinic_id');
    $table->unsignedBigInteger('user_id')->unique();
    $table->unsignedBigInteger('department_id')->nullable(); // Link to Dept
    
    $table->string('license_number')->nullable();
    $table->string('specialization'); // e.g. "Cardiology"
    $table->text('bio')->nullable();
    $table->integer('experience_years')->default(0);
    $table->decimal('consultation_fee', 10, 2)->default(0);
    
    $table->timestamps();
    $table->softDeletes();

    $table->foreign('clinic_id')->references('id')->on('clinics')->onDelete('cascade');
    $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
    $table->foreign('department_id')->references('id')->on('departments')->onDelete('set null');
});
```

## 2. The Model
```php
class Doctor extends BaseTenantModel
{
    use HasFactory, SoftDeletes;
    
    protected $fillable = [
        'user_id', 'department_id', 'license_number', 
        'specialization', 'bio', 'experience_years', 'consultation_fee'
    ];

    // Access user details (name, email)
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function department()
    {
        return $this->belongsTo(Department::class);
    }
}
```

## Summary
The `Doctor` model acts as an extension of the `User` model. When we display a doctor, we usually load `$doctor->load('user', 'department')`.

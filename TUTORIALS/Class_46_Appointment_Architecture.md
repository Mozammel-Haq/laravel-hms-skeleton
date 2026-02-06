# Class 46: Appointment Architecture

## Introduction
The `Appointment` model connects a Patient, a Doctor, and a Time Slot. It is the central transaction of the Out-Patient Department (OPD).

## 1. Migration
Run: `php artisan make:model Appointment -m`

```php
Schema::create('appointments', function (Blueprint $table) {
    $table->id();
    $table->foreignId('clinic_id')->constrained()->cascadeOnDelete();
    $table->foreignId('patient_id')->constrained()->cascadeOnDelete();
    $table->foreignId('doctor_id')->constrained()->cascadeOnDelete();
    
    // Using simple date + time columns is often easier for querying than a single datetime
    $table->date('date'); 
    $table->time('start_time');
    $table->time('end_time');
    
    $table->string('status')->default('scheduled'); // scheduled, checked_in, completed, cancelled, no_show
    $table->string('type')->default('consultation'); // consultation, follow_up
    
    $table->text('reason')->nullable(); // "Headache"
    
    $table->timestamps();
    $table->softDeletes();
    
    // Index for fast lookup of doctor's day
    $table->index(['doctor_id', 'date']);
});
```

## 2. Model
`app/Models/Appointment.php`

```php
class Appointment extends BaseTenantModel
{
    use HasFactory, SoftDeletes;
    
    protected $fillable = [
        'patient_id', 'doctor_id', 'date', 'start_time', 'end_time', 'status', 'reason'
    ];
    
    protected $casts = [
        'date' => 'date',
        // 'start_time' => 'datetime', // Optional, depends on how you want to access it
    ];
    
    public function patient() { return $this->belongsTo(Patient::class); }
    public function doctor() { return $this->belongsTo(Doctor::class); }
}
```

## Summary
The database structure is ready. The `status` field controls the workflow state machine.

<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable as AuthenticatableTrait;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Concerns\LogsActivity;

class Patient extends Model implements AuthenticatableContract
{
    use SoftDeletes, HasApiTokens, AuthenticatableTrait, LogsActivity;

    protected $casts = [
        'date_of_birth' => 'date',
        'last_login_at' => 'datetime',
        'password' => 'hashed',
    ];
    protected $fillable = [
        'clinic_id', // Kept for legacy compatibility if needed, but nullable now
        'name',
        'email',
        'password',
        'profile_photo',
        'date_of_birth',
        'phone',
        'blood_group',
        'nid_number',
        'birth_certificate_number',
        'passport_number',
        'patient_code',
        'must_change_password', // Added based on recent migration usage
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function getAgeAttribute($value)
    {
        if ($value !== null) {
            return $value;
        }
        if ($this->date_of_birth) {
            return \Carbon\Carbon::parse($this->date_of_birth)->age;
        }
        return null;
    }

    protected static function booted()
    {
        static::created(function ($patient) {
            if (empty($patient->patient_code)) {
                $patient->update([
                    'patient_code' => 'P-' . str_pad($patient->id, 4, '0', STR_PAD_LEFT),
                ]);
            }
        });

        // Global Scope to filter patients by the current user's clinic
        static::addGlobalScope('clinic_access', function (Builder $builder) {
            if (auth()->check() && auth()->user()->clinic_id) {
                // Filter patients that belong to the current user's clinic
                // OR patients created by this clinic (legacy clinic_id)
                $builder->where(function ($q) use ($builder) {
                    $q->whereHas('clinics', function ($q2) {
                        $q2->where($q2->qualifyColumn('id'), auth()->user()->clinic_id);
                    })
                    ->orWhere($builder->qualifyColumn('clinic_id'), auth()->user()->clinic_id);
                });
            }
        });
    }

    // Helper to bypass the scope (naming convention from BaseTenantModel)
    public static function withoutTenant()
    {
        return static::withoutGlobalScope('clinic_access');
    }

    public function clinics()
    {
        return $this->belongsToMany(Clinic::class, 'clinic_patient');
    }

    // Deprecated but kept for backward compatibility if code uses $patient->clinic
    public function clinic()
    {
        return $this->belongsTo(Clinic::class);
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }
    public function allergies()
    {
        return $this->hasMany(PatientAllergy::class);
    }
    public function medicalHistory()
    {
        return $this->hasMany(PatientMedicalHistory::class);
    }
    public function consultations()
    {
        return $this->hasMany(Consultation::class);
    }
    public function labTestResults()
    {
        return $this->hasMany(LabTestResult::class);
    }
    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }
    public function admissions()
    {
        return $this->hasMany(Admission::class);
    }
    public function vitals()
    {
        return $this->hasMany(PatientVital::class);
    }
}

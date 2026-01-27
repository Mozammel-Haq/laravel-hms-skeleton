<?php

namespace App\Models;

use App\Models\Base\BaseTenantModel;
use Illuminate\Auth\Authenticatable as AuthenticatableTrait;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Sanctum\HasApiTokens;

class Patient extends BaseTenantModel implements AuthenticatableContract
{
    use SoftDeletes, HasApiTokens, AuthenticatableTrait;

    protected $casts = [
        'date_of_birth' => 'date',
        'last_login_at' => 'datetime',
        'password' => 'hashed',
    ];
    protected $fillable = [
        'clinic_id',
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
            $patient->update([
                'patient_code' => 'P-' . str_pad($patient->id, 4, '0', STR_PAD_LEFT),
            ]);
        });
    }
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

<?php

namespace App\Models;

use App\Models\Base\BaseTenantModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Patient extends BaseTenantModel
{
    use SoftDeletes;
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

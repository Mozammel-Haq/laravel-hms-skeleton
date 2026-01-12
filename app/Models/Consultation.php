<?php

namespace App\Models;

use App\Models\Base\BaseTenantModel;

class Consultation extends BaseTenantModel
{
    public $timestamps = true;

    protected $fillable = [
        'visit_id',
        'doctor_id',
        'patient_id',
        'doctor_notes',
        'diagnosis',
        'follow_up_required',
        'follow_up_date',
    ];

    public function visit()
    {
        return $this->belongsTo(Visit::class);
    }

    public function prescriptions()
    {
        return $this->hasMany(Prescription::class);
    }

    public function prescription()
    {
        return $this->hasOne(Prescription::class)->latestOfMany();
    }

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }
}

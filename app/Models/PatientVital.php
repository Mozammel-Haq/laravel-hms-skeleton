<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PatientVital extends Model
{
    protected $table = 'patient_vitals';

    protected $fillable = [
        'patient_id',
        'visit_id',
        'admission_id',
        'inpatient_round_id',
        'blood_pressure',
        'heart_rate',
        'temperature',
        'spo2',
        'respiratory_rate',
        'weight',
        'height',
        'bmi',
        'recorded_by',
        'recorded_at',
    ];

    protected $casts = [
        'recorded_at' => 'datetime',
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function visit()
    {
        return $this->belongsTo(Visit::class);
    }

    public function admission()
    {
        return $this->belongsTo(Admission::class);
    }

    public function round()
    {
        return $this->belongsTo(\App\Models\InpatientRound::class, 'inpatient_round_id');
    }

    public function recorder()
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }
}

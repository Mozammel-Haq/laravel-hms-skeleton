<?php

namespace App\Models;

use App\Models\Base\BaseTenantModel;
use Illuminate\Database\Eloquent\SoftDeletes;

class Prescription extends BaseTenantModel
{
    use SoftDeletes;
    public function items()
    {
        return $this->hasMany(PrescriptionItem::class);
    }
    public function consultation()
    {
        return $this->belongsTo(Consultation::class,"consultation_id");
    }
    public function complaints()
    {
        return $this->belongsToMany(PatientComplaint::class, 'prescription_complaint', 'prescription_id', 'complaint_id');
    }
    public function clinic()
    {
        return $this->belongsTo(Clinic::class);
    }
    public function doctors()
    {
        return $this->hasManyThrough(Doctor::class, Consultation::class, 'doctor_id', 'consultation_id');
    }
}

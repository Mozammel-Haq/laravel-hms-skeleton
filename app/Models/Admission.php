<?php

namespace App\Models;

use App\Models\Base\BaseTenantModel;
use Illuminate\Database\Eloquent\SoftDeletes;

class Admission extends BaseTenantModel
{
    use SoftDeletes;
    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }
    public function doctor()
    {
        return $this->belongsTo(Doctor::class, 'admitting_doctor_id');
    }
    public function bedAssignments()
    {
        return $this->hasMany(BedAssignment::class);
    }
    public function services()
    {
        return $this->hasMany(InpatientService::class);
    }
    public function rounds()
    {
        return $this->hasMany(InpatientRound::class);
    }
    public function vitals()
    {
        return $this->hasMany(PatientVital::class);
    }
}

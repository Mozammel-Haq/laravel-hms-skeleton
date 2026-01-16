<?php

namespace App\Models;

use App\Models\Base\BaseTenantModel;
use Illuminate\Database\Eloquent\SoftDeletes;

class Visit extends BaseTenantModel
{
    use SoftDeletes;
    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }
    public function consultation()
    {
        return $this->hasOne(Consultation::class);
    }
    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }
    public function vitals()
    {
        return $this->hasMany(PatientVital::class, 'visit_id');
    }
}

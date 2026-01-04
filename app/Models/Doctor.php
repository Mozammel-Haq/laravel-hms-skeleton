<?php

namespace App\Models;

use App\Models\Base\BaseTenantModel;
use Illuminate\Database\Eloquent\SoftDeletes;

class Doctor extends BaseTenantModel
{
    use SoftDeletes;
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function department()
    {
        return $this->belongsTo(Department::class, 'primary_department_id');
    }
    public function schedules()
    {
        return $this->hasMany(DoctorSchedule::class);
    }
    public function exceptions()
    {
        return $this->hasMany(DoctorScheduleException::class);
    }
    public function clinics()
    {
        return $this->belongsToMany(Clinic::class, 'doctor_clinic');
    }
}

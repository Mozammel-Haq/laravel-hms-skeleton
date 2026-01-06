<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Concerns\LogsActivity;

class Doctor extends Model
{
    use SoftDeletes, LogsActivity;

    protected $guarded = ['id'];

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
    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Concerns\LogsActivity;
use App\Models\DoctorAward;
use App\Models\DoctorCertification;
use App\Models\DoctorEducation;

class Doctor extends Model
{
    use SoftDeletes, LogsActivity;

    protected $guarded = ['id'];

    public function user()
    {
        return $this->belongsTo(User::class)->withoutGlobalScope('clinic');
    }

    public function department()
    {
        return $this->belongsTo(Department::class, 'primary_department_id')->withoutGlobalScope('clinic');
    }

    public function primaryDepartment()
    {
        return $this->belongsTo(Department::class, 'primary_department_id')->withoutGlobalScope('clinic');
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
    public function consultations()
    {
        return $this->hasMany(Consultation::class);
    }
    public function prescriptions()
    {
        return $this->hasManyThrough(Prescription::class, Consultation::class, 'doctor_id', 'consultation_id');
    }
    public function educations()
    {
        return $this->hasMany(DoctorEducation::class)->orderByDesc('end_year')->orderByDesc('start_year');
    }
    public function awards()
    {
        return $this->hasMany(DoctorAward::class)->orderByDesc('year');
    }
    public function certifications()
    {
        return $this->hasMany(DoctorCertification::class)->orderByDesc('issued_date');
    }
    protected $casts = [
        'created_at' => 'date',
        'specialization' => 'array',
    ];
}

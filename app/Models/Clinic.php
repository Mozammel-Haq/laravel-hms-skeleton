<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Clinic extends Model
{
    protected $guarded = ['id'];
    public function departments() { return $this->hasMany(Department::class); }
    public function users() { return $this->hasMany(User::class); }
    public function patients() { return $this->hasMany(Patient::class); }
    public function appointments() { return $this->hasMany(Appointment::class); }
    public function wards() { return $this->hasMany(Ward::class); }
    public function invoices() { return $this->hasMany(Invoice::class); }
    public function doctors() { return $this->belongsToMany(Doctor::class, 'doctor_clinic'); }
}

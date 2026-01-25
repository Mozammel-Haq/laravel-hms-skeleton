<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DoctorEducation extends Model
{
    protected $guarded = ['id'];

    protected $table = 'doctor_education';

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }
    protected $casts = [
        'created_at' => 'date',
    ];
}

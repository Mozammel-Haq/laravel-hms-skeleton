<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DoctorCertification extends Model
{
    protected $guarded = ['id'];

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }
    protected $casts = [
        'created_at' => 'date',
    ];
}

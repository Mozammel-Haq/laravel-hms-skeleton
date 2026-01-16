<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DoctorAward extends Model
{
    protected $guarded = ['id'];

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }
}


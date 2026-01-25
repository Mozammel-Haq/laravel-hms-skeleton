<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PatientAllergy extends Model
{
    //
    protected $casts = [
        'created_at' => 'date',
    ];
}

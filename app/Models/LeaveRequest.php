<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeaveRequest extends Model
{
    protected $casts = [
        'created_at' => 'date',
    ];
}

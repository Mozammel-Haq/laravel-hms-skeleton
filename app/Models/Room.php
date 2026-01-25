<?php

namespace App\Models;

use App\Models\Base\BaseTenantModel;

class Room extends BaseTenantModel
{
    public function ward()
    {
        return $this->belongsTo(Ward::class);
    }

    public function beds()
    {
        return $this->hasMany(Bed::class);
    }
    protected $casts = [
        'created_at' => 'date',
    ];
}

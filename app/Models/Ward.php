<?php

namespace App\Models;

use App\Models\Base\BaseTenantModel;

class Ward extends BaseTenantModel
{
    public function rooms()
    {
        return $this->hasMany(Room::class);
    }
    protected $casts = [
        'created_at' => 'date',
    ];
}

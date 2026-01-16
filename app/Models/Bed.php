<?php

namespace App\Models;

use App\Models\Base\BaseTenantModel;
use Illuminate\Database\Eloquent\Model;

class Bed extends BaseTenantModel
{
    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function assignments()
    {
        return $this->hasMany(BedAssignment::class);
    }
}

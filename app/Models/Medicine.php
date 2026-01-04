<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Medicine extends Model
{
    protected $guarded = ['id'];

    public function batches()
    {
        return $this->hasMany(MedicineBatch::class);
    }
}

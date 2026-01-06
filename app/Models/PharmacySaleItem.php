<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PharmacySaleItem extends Model
{
    protected $guarded = ['id'];
    public $timestamps = false;

    public function sale()
    {
        return $this->belongsTo(PharmacySale::class, 'pharmacy_sale_id');
    }

    public function medicine()
    {
        return $this->belongsTo(Medicine::class);
    }
}

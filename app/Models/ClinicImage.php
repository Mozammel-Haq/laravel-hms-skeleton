<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClinicImage extends Model
{
    use HasFactory;

    protected $fillable = ['clinic_id', 'image_path', 'sort_order'];

    public function clinic()
    {
        return $this->belongsTo(Clinic::class);
    }
    protected $casts = [
        'created_at' => 'date',
    ];
}

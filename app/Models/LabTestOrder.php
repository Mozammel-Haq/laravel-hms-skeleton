<?php

namespace App\Models;

use App\Models\Base\BaseTenantModel;
use Illuminate\Database\Eloquent\SoftDeletes;

class LabTestOrder extends BaseTenantModel
{
    use SoftDeletes;

    protected $guarded = ['id'];

    protected $casts = [
        'order_date' => 'date',
    ];

    public function results()
    {
        return $this->hasMany(LabTestResult::class);
    }
    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }
    public function test()
    {
        return $this->belongsTo(LabTest::class, 'lab_test_id');
    }
}

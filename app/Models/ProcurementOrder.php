<?php

namespace App\Models;

use App\Models\Base\BaseTenantModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProcurementOrder extends BaseTenantModel
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'clinic_id',
        'supplier_name',
        'order_number',
        'order_date',
        'total_amount',
        'status', // pending, ordered, received, cancelled
        'payment_status', // unpaid, partially_paid, paid
        'user_id', // Purchase Manager
    ];

    protected $casts = [
        'order_date' => 'date',
        'total_amount' => 'decimal:2',
    ];

    public function items()
    {
        return $this->hasMany(ProcurementItem::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getActivityDescription($action)
    {
        return ucfirst($action) . " procurement order: {$this->order_number} (Supplier: {$this->supplier_name})";
    }
}

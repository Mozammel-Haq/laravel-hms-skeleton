<?php

namespace App\Models;

use App\Models\Base\BaseTenantModel;
use App\Models\Concerns\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payment extends BaseTenantModel
{
    use SoftDeletes, LogsActivity;
    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }
    public function receivedBy()
    {
        return $this->belongsTo(User::class, 'received_by');
    }
}

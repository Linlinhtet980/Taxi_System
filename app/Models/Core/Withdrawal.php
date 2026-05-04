<?php

namespace App\Models\Core;

use App\Models\Auth\Driver;
use Illuminate\Database\Eloquent\Model;

class Withdrawal extends Model
{
    protected $fillable = ['driver_id', 'amount', 'status', 'payment_method', 'notes', 'screenshot'];

    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }
}

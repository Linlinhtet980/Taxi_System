<?php

namespace App\Models\Core;

use App\Models\Auth\Customer;
use App\Models\Auth\Driver;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id',
        'driver_id',
        'customer_id',
        'amount',
        'commission_amount',
        'driver_amount',
        'payment_method',
        'type',
        'status',
        'reference_number',
        'note'
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}

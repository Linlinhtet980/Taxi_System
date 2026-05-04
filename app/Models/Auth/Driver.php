<?php

namespace App\Models\Auth;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Core\Vehicle;
use App\Models\Core\Booking;

class Driver extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'full_name',
        'gender',
        'email',
        'password',
        'phone_no',
        'emergency_contact_no',
        'license_no',
        'driver_status',
        'profile_picture',
        'license_photo',
        'license_photo_back',
        'nric_photo',
        'nric_photo_back',
        'address',
        'date_of_birth',
        'identity_card_no',
        'wallet_balance',
        'loyalty_points',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];


    public function vehicle()
    {
        return $this->hasOne(Vehicle::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}

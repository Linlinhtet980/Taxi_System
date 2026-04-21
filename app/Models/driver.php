<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Driver extends Model
{
    use HasFactory;

    protected $fillable = [
        'full_name',
        'gender',
        'email',
        'phone_no',
        'emergency_contact_no',
        'license_no',
        'vehicle_no',
        'vehicle_type',
        'driver_status',
        'profile_picture',
        'license_photo',
        'nric_photo',
        'address',
        'date_of_birth',
        'identity_card_no',
    ];
}

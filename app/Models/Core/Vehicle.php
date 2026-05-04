<?php

namespace App\Models\Core;

use App\Models\Auth\Driver;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin \Illuminate\Database\Eloquent\Builder
 * @mixin \Illuminate\Database\Eloquent\Model
 */
class Vehicle extends Model
{
    use HasFactory;

    protected $fillable = [
        'driver_id',
        'license_plate',
        'brand',
        'model',
        'vehicle_type',
        'color',
        'year',
        'status',
        'vehicle_photo',
        'front_photo',
        'back_photo',
        'left_side_photo',
        'right_side_photo',
        'interior_photo',
        'last_maintenance_at',
        'next_maintenance_at',
        'inspection_status',
        'mileage',
    ];

    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }
}

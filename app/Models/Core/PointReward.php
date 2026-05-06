<?php

namespace App\Models\Core;

use Illuminate\Database\Eloquent\Model;

class PointReward extends Model
{
    protected $fillable = [
        'title',
        'points_required',
        'reward_amount',
        'description',
        'is_active'
    ];
}

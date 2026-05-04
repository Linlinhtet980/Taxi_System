<?php

namespace App\Models\Core;

use App\Models\Auth\Driver;
use Illuminate\Database\Eloquent\Model;

class OnlineLog extends Model
{
    protected $fillable = ['driver_id', 'started_at', 'ended_at', 'duration_minutes'];

    protected $casts = [
        'started_at' => 'datetime',
        'ended_at' => 'datetime',
    ];

    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }
}

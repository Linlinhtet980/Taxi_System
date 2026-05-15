<?php

namespace App\Models\Core;

use Illuminate\Database\Eloquent\Model;

class ChatMessage extends Model
{
    protected $fillable = [
        'booking_id',
        'sender_type',
        'message',
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }
}

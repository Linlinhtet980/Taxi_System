<?php

namespace App\Models\Core;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $fillable = ['title', 'message', 'type', 'is_read', 'link'];

    public static function send($title, $message, $type = 'info', $link = null)
    {
        return self::create([
            'title' => $title,
            'message' => $message,
            'type' => $type,
            'link' => $link
        ]);
    }
}

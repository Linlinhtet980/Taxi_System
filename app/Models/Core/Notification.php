<?php

namespace App\Models\Core;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $fillable = ['user_id', 'user_type', 'title', 'message', 'type', 'is_read', 'link'];

    public function user()
    {
        return $this->morphTo();
    }

    public static function send($title, $message, $type = 'info', $link = null, $user = null)
    {
        $data = [
            'title' => $title,
            'message' => $message,
            'type' => $type,
            'link' => $link
        ];

        if ($user) {
            $data['user_id'] = $user->id;
            $data['user_type'] = get_class($user);
        }

        return self::create($data);
    }
}

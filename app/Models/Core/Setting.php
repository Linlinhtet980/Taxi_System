<?php

namespace App\Models\Core;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = ['key', 'value', 'label', 'group'];

    public static function get(string $key, mixed $default = null): mixed
    {
        $setting = self::query()->where('key', $key)->first();
        return $setting ? $setting->value : $default;
    }

    public static function set(string $key, mixed $value, ?string $label = null, string $group = 'general'): self
    {
        return self::query()->updateOrCreate(
            ['key' => $key],
            ['value' => $value, 'label' => $label, 'group' => $group]
        );
    }
}

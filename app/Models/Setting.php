<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = ['key', 'value'];

    protected static function boot()
    {
        parent::boot();

        static::saved(function ($setting) {
            if (function_exists('clear_settings_cache')) {
                clear_settings_cache();
            }
        });
    }

    public static function get(string $key, $default = null)
    {
        $setting = self::where('key', $key)->first();
        return $setting?->value ?? $default;
    }

    public static function set(string $key, $value): void
    {
        self::updateOrCreate(['key' => $key], ['value' => $value]);
    }
}

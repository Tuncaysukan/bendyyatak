<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    protected $fillable = ['key', 'value', 'group'];

    public static function get(string $key, mixed $default = null): mixed
    {
        try {
            return Cache::rememberForever('setting_' . $key, function () use ($key, $default) {
                $setting = static::where('key', $key)->first();
                return $setting ? $setting->value : $default;
            });
        } catch (\Exception $e) {
            return $default;
        }
    }

    public static function set(string $key, mixed $value): void
    {
        try {
            static::updateOrCreate(['key' => $key], ['value' => $value]);
            Cache::forget('setting_' . $key);
        } catch (\Exception $e) {
            // Silently fail if DB not ready
        }
    }

    public static function getGroup(string $group): array
    {
        try {
            return static::where('group', $group)->pluck('value', 'key')->toArray();
        } catch (\Exception $e) {
            return [];
        }
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class BusinessSetting extends Model
{
    protected $fillable = [
        'key',
        'value',
        'group',
    ];

    public static function get(string $key, mixed $default = null): mixed
    {
        return Cache::remember("business_setting:{$key}", now()->addDay(), function () use ($key, $default) {
            $setting = static::where('key', $key)->first();

            return $setting ? $setting->value : $default;
        });
    }

    public static function set(string $key, mixed $value): void
    {
        static::updateOrCreate(
            ['key' => $key],
            ['value' => $value]
        );

        Cache::forget("business_setting:{$key}");
    }

    public static function getGroup(string $group): array
    {
        return Cache::remember("business_settings:group:{$group}", now()->addDay(), function () use ($group) {
            return static::where('group', $group)
                ->pluck('value', 'key')
                ->toArray();
        });
    }

    public static function clearGroupCache(string $group): void
    {
        $keys = static::where('group', $group)->pluck('key');

        foreach ($keys as $key) {
            Cache::forget("business_setting:{$key}");
        }

        Cache::forget("business_settings:group:{$group}");
    }
}

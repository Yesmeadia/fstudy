<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    protected $fillable = [
        'key',
        'value',
        'group',
        'type',
        'description',
    ];

    public static function get(string $key, mixed $default = null): mixed
    {
        return Cache::rememberForever("setting.{$key}", function () use ($key, $default) {
            $setting = self::where('key', $key)->first();
            if (! $setting) {
                return $default;
            }

            return match ($setting->type) {
                'number', 'integer' => (int) $setting->value,
                'decimal', 'float' => (float) $setting->value,
                'boolean' => filter_var($setting->value, FILTER_VALIDATE_BOOLEAN),
                'json', 'array' => json_decode($setting->value, true),
                default => $setting->value,
            };
        });
    }

    public static function set(string $key, mixed $value, string $group = 'general', string $type = 'string', ?string $description = null): self
    {
        $storedValue = is_array($value) ? json_encode($value) : (string) $value;

        $setting = self::updateOrCreate(
            ['key' => $key],
            [
                'value' => $storedValue,
                'group' => $group,
                'type' => $type,
                'description' => $description,
            ]
        );

        Cache::forget("setting.{$key}");

        return $setting;
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    protected $fillable = ['key', 'value', 'type', 'group', 'label'];

    public static function get(string $key, $default = null): mixed
    {
        return Cache::remember("setting_{$key}", 3600, function () use ($key, $default) {
            $setting = static::where('key', $key)->first();
            return $setting ? $setting->value : $default;
        });
    }

    public static function set(string $key, mixed $value): void
    {
        static::where('key', $key)->update(['value' => $value]);
        Cache::forget("setting_{$key}");
    }

    public static function getGroup(string $group): array
    {
        return static::where('group', $group)->get()
            ->mapWithKeys(fn($s) => [$s->key => $s->value])
            ->toArray();
    }

    public static function allGrouped(): array
    {
        return static::all()->groupBy('group')
            ->map(fn($g) => $g->keyBy('key'))
            ->toArray();
    }
}

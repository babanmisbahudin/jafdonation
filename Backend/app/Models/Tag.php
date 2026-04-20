<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Tag extends Model
{
    protected $fillable = ['name', 'slug'];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($tag) {
            if (empty($tag->slug)) {
                $tag->slug = Str::slug($tag->name);
            }
        });
    }

    public function articles()
    {
        return $this->belongsToMany(Article::class);
    }

    public static function findOrCreateByNames(array $names): \Illuminate\Support\Collection
    {
        return collect($names)
            ->filter()
            ->map(fn($name) => trim($name))
            ->unique()
            ->map(fn($name) => static::firstOrCreate(
                ['slug' => Str::slug($name)],
                ['name' => $name]
            ));
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HeroSlide extends Model
{
    protected $fillable = [
        'tag', 'tag_color', 'title_1', 'title_2',
        'description', 'quote', 'author',
        'bg_color', 'image', 'cta_text', 'cta_url',
        'sort_order', 'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('sort_order');
    }

    public function getImageUrlAttribute(): ?string
    {
        return $this->image ? asset('uploads/hero/' . $this->image) : null;
    }
}

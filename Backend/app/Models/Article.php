<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Article extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'slug',
        'excerpt',
        'content',
        'thumbnail',
        'category_id',
        'author_id',
        'region',
        'status',
        'is_featured',
        'views',
        'meta_title',
        'meta_description',
        'published_at',
    ];

    protected function casts(): array
    {
        return [
            'is_featured'  => 'boolean',
            'published_at' => 'datetime',
        ];
    }

    const REGIONS = [
        'jakarta'      => 'Jakarta',
        'jawa-bali'    => 'Jawa-Bali',
        'sumatera'     => 'Sumatera',
        'kalimantan'   => 'Kalimantan',
        'sulawesi'     => 'Sulawesi',
        'papua'        => 'Papua',
        'internasional'=> 'Internasional',
    ];

    const STATUSES = [
        'draft'     => 'Draft',
        'published' => 'Published',
        'archived'  => 'Archived',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($article) {
            if (empty($article->slug)) {
                $article->slug = Str::slug($article->title);
            }
        });
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function getRegionLabelAttribute(): string
    {
        return self::REGIONS[$this->region] ?? $this->region;
    }

    public function getThumbnailUrlAttribute(): string
    {
        if ($this->thumbnail && file_exists(public_path('uploads/' . $this->thumbnail))) {
            return asset('uploads/' . $this->thumbnail);
        }
        return asset('images/placeholder.jpg');
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }

    public function getTagListAttribute(): string
    {
        return $this->tags->pluck('name')->implode(', ');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
}

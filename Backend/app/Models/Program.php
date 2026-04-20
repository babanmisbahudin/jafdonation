<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Program extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'content',
        'thumbnail',
        'category_id',
        'target_amount',
        'collected_amount',
        'region',
        'status',
        'is_featured',
        'start_date',
        'end_date',
    ];

    protected function casts(): array
    {
        return [
            'target_amount'    => 'decimal:2',
            'collected_amount' => 'decimal:2',
            'is_featured'      => 'boolean',
            'start_date'       => 'date',
            'end_date'         => 'date',
        ];
    }

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($program) {
            if (empty($program->slug)) {
                $program->slug = Str::slug($program->name);
            }
        });
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function donations()
    {
        return $this->hasMany(Donation::class);
    }

    public function getProgressPercentageAttribute(): float
    {
        if ($this->target_amount <= 0) return 0;
        return min(100, round(($this->collected_amount / $this->target_amount) * 100, 1));
    }

    public function getHasTargetAttribute(): bool
    {
        return $this->target_amount > 0;
    }

    public function getThumbnailUrlAttribute(): ?string
    {
        if ($this->thumbnail && file_exists(public_path('uploads/' . $this->thumbnail))) {
            return asset('uploads/' . $this->thumbnail);
        }
        return null;
    }

    public function getDaysLeftAttribute(): ?int
    {
        if (!$this->end_date) return null;
        $days = now()->diffInDays($this->end_date, false);
        return max(0, $days);
    }
}

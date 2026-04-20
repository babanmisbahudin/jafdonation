<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Volunteer extends Model
{
    protected $fillable = [
        'name', 'email', 'phone', 'city', 'province',
        'birth_date', 'gender', 'occupation', 'motivation',
        'skills', 'availability', 'status', 'admin_notes', 'approved_at',
    ];

    protected $casts = [
        'birth_date'  => 'date',
        'approved_at' => 'datetime',
        'skills'      => 'array',
    ];

    public const STATUSES = [
        'pending'  => 'Menunggu',
        'approved' => 'Diterima',
        'rejected' => 'Ditolak',
    ];

    public const AVAILABILITIES = [
        'weekend'  => 'Akhir Pekan',
        'weekday'  => 'Hari Kerja',
        'fulltime' => 'Full Time',
    ];

    public function getStatusLabelAttribute(): string
    {
        return self::STATUSES[$this->status] ?? $this->status;
    }

    public function getStatusBadgeAttribute(): string
    {
        return match ($this->status) {
            'approved' => 'bg-success-subtle text-success',
            'rejected' => 'bg-danger-subtle text-danger',
            default    => 'bg-warning-subtle text-warning',
        };
    }

    public function getGenderLabelAttribute(): string
    {
        return match ($this->gender) {
            'male'   => 'Laki-laki',
            'female' => 'Perempuan',
            default  => '-',
        };
    }
}

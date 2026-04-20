<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Donation extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'donor_name',
        'donor_email',
        'donor_phone',
        'program_id',
        'amount',
        'payment_method',
        'payment_status',
        'midtrans_transaction_id',
        'midtrans_token',
        'midtrans_redirect_url',
        'midtrans_response',
        'note',
        'is_anonymous',
        'paid_at',
    ];

    protected function casts(): array
    {
        return [
            'amount'             => 'decimal:2',
            'is_anonymous'       => 'boolean',
            'paid_at'            => 'datetime',
            'midtrans_response'  => 'array',
        ];
    }

    const STATUSES = [
        'pending'  => 'Menunggu Pembayaran',
        'paid'     => 'Sudah Dibayar',
        'failed'   => 'Gagal',
        'expired'  => 'Kadaluarsa',
        'refund'   => 'Dikembalikan',
    ];

    const PAYMENT_METHODS = [
        'bank_transfer' => 'Transfer Bank',
        'qris'          => 'QRIS',
        'gopay'         => 'GoPay',
        'ovo'           => 'OVO',
        'dana'          => 'DANA',
        'shopeepay'     => 'ShopeePay',
        'credit_card'   => 'Kartu Kredit',
    ];

    public function program()
    {
        return $this->belongsTo(Program::class);
    }

    public function getStatusLabelAttribute(): string
    {
        return self::STATUSES[$this->payment_status] ?? $this->payment_status;
    }

    public function getStatusBadgeAttribute(): string
    {
        return match ($this->payment_status) {
            'paid'    => 'success',
            'pending' => 'warning',
            'failed'  => 'danger',
            'expired' => 'secondary',
            'refund'  => 'info',
            default   => 'secondary',
        };
    }

    public static function generateOrderId(): string
    {
        return 'JAF-' . strtoupper(uniqid()) . '-' . now()->format('Ymd');
    }
}

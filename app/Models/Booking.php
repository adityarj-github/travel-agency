<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Booking extends Model
{
    use HasFactory, SoftDeletes;

    public const STATUSES = ['pending', 'confirmed', 'cancelled', 'completed'];

    public const PAYMENT_STATUSES = ['unpaid', 'paid', 'failed', 'refunded'];

    protected $fillable = [
        'reference', 'user_id', 'package_id', 'destination_id', 'name', 'email', 'phone',
        'travel_date', 'adults', 'children', 'message', 'status', 'admin_note',
        'coupon_id', 'coupon_code', 'subtotal', 'discount_amount', 'total',
        'payment_status', 'payment_method', 'payment_token', 'is_payment_link',
        'razorpay_order_id', 'razorpay_payment_id', 'razorpay_signature',
        'amount_paid', 'paid_at',
    ];

    protected $hidden = ['payment_token', 'razorpay_signature'];

    protected $casts = [
        'travel_date' => 'date',
        'adults' => 'integer',
        'children' => 'integer',
        'subtotal' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'total' => 'decimal:2',
        'amount_paid' => 'decimal:2',
        'paid_at' => 'datetime',
        'is_payment_link' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::creating(function ($booking) {
            if (empty($booking->reference)) {
                $booking->reference = 'BK-' . strtoupper(Str::random(8));
            }
            if (empty($booking->payment_token)) {
                $booking->payment_token = Str::random(48);
            }
        });
    }

    /** A priced booking that still owes money. */
    public function requiresPayment(): bool
    {
        return (float) $this->total > 0 && $this->payment_status !== 'paid';
    }

    public function isPaid(): bool
    {
        return $this->payment_status === 'paid';
    }

    /** Shareable, token-guarded payment URL a guest can open to pay this booking. */
    public function getPayUrlAttribute(): string
    {
        return route('booking.pay', ['booking' => $this, 'token' => $this->payment_token]);
    }

    /** Bookings created via the admin "payment link" generator. */
    public function scopePaymentLinks($query)
    {
        return $query->where('is_payment_link', true);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function package()
    {
        return $this->belongsTo(Package::class);
    }

    public function destination()
    {
        return $this->belongsTo(Destination::class);
    }

    public function coupon()
    {
        return $this->belongsTo(Coupon::class);
    }

    public function scopeStatus($query, ?string $status)
    {
        return $status ? $query->where('status', $status) : $query;
    }

    public function getStatusBadgeAttribute(): string
    {
        return match ($this->status) {
            'confirmed' => 'bg-green-100 text-green-700',
            'cancelled' => 'bg-red-100 text-red-700',
            'completed' => 'bg-blue-100 text-blue-700',
            default => 'bg-yellow-100 text-yellow-700',
        };
    }

    public function getPaymentBadgeAttribute(): string
    {
        return match ($this->payment_status) {
            'paid' => 'bg-green-100 text-green-700',
            'failed' => 'bg-red-100 text-red-700',
            'refunded' => 'bg-slate-200 text-slate-600',
            default => 'bg-amber-100 text-amber-700',
        };
    }
}

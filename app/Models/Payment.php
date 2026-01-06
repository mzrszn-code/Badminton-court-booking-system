<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id',
        'amount',
        'payment_method',
        'reference_number',
        'proof_image',
        'payment_status',
        'payment_time',
        'admin_notes',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'payment_time' => 'datetime',
    ];

    /**
     * Get the booking for this payment.
     */
    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    /**
     * Check if payment is completed.
     */
    public function isCompleted(): bool
    {
        return $this->payment_status === 'completed';
    }

    /**
     * Check if payment is pending.
     */
    public function isPending(): bool
    {
        return $this->payment_status === 'pending';
    }
}

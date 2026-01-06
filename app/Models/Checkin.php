<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Checkin extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id',
        'qr_code',
        'checkin_time',
        'is_checked_in',
    ];

    protected $casts = [
        'checkin_time' => 'datetime',
        'is_checked_in' => 'boolean',
    ];

    /**
     * Get the booking for this check-in.
     */
    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }
}

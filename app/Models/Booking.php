<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'court_id',
        'booking_date',
        'start_time',
        'end_time',
        'status',
        'notes',
    ];

    protected $casts = [
        'booking_date' => 'date',
        'start_time' => 'datetime:H:i',
        'end_time' => 'datetime:H:i',
    ];

    /**
     * Get the user that owns the booking.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the court for this booking.
     */
    public function court()
    {
        return $this->belongsTo(Court::class);
    }

    /**
     * Get the payment for this booking.
     */
    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    /**
     * Get the check-in for this booking.
     */
    public function checkin()
    {
        return $this->hasOne(Checkin::class);
    }

    /**
     * Check if booking is pending.
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * Check if booking is approved.
     */
    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    /**
     * Check if the booking session has ended.
     */
    public function hasSessionEnded(): bool
    {
        $now = \Carbon\Carbon::now();
        $bookingDate = \Carbon\Carbon::parse($this->booking_date);
        $endTime = \Carbon\Carbon::parse($this->end_time);

        // Create full datetime for end of session
        $sessionEnd = $bookingDate->setTime($endTime->hour, $endTime->minute);

        // Handle overnight bookings (end time 0:00-03:00 means next day)
        if ($endTime->hour <= 3) {
            $sessionEnd->addDay();
        }

        return $now->greaterThan($sessionEnd);
    }

    /**
     * Get the display status (shows 'completed' for ended approved sessions).
     */
    public function getDisplayStatusAttribute(): string
    {
        if ($this->status === 'approved' && $this->hasSessionEnded()) {
            return 'completed';
        }

        return $this->status;
    }
}

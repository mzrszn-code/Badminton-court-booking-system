<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Court extends Model
{
    use HasFactory;

    protected $fillable = [
        'court_name',
        'court_type',
        'location',
        'status',
        'description',
        'image',
        'hourly_rate',
    ];

    protected $casts = [
        'hourly_rate' => 'decimal:2',
    ];

    /**
     * Get the bookings for this court.
     */
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    /**
     * Get the schedules for this court.
     */
    public function schedules()
    {
        return $this->hasMany(CourtSchedule::class);
    }

    /**
     * Get the maintenance records for this court.
     */
    public function maintenances()
    {
        return $this->hasMany(Maintenance::class);
    }

    /**
     * Check if the court is available.
     */
    public function isAvailable(): bool
    {
        return $this->status === 'available';
    }

    /**
     * Check if the court is under maintenance.
     */
    public function isUnderMaintenance(): bool
    {
        return $this->status === 'maintenance';
    }
}

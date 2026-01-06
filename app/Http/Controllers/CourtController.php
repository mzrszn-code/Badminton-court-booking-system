<?php

namespace App\Http\Controllers;

use App\Models\Court;
use App\Models\Booking;
use Illuminate\Http\Request;

class CourtController extends Controller
{
    /**
     * Display a listing of all courts.
     */
    public function index()
    {
        $courts = Court::withCount(['bookings' => function ($query) {
            $query->where('booking_date', '>=', now()->format('Y-m-d'))
                  ->where('status', 'approved');
        }])->get();

        return view('courts.index', compact('courts'));
    }

    /**
     * Display the specified court.
     */
    public function show(Court $court)
    {
        // Get upcoming bookings for this court
        $upcomingBookings = Booking::where('court_id', $court->id)
            ->where('booking_date', '>=', now()->format('Y-m-d'))
            ->where('status', 'approved')
            ->orderBy('booking_date')
            ->orderBy('start_time')
            ->take(10)
            ->get();

        // Get available time slots for today
        $todaySlots = $this->getAvailableSlots($court, now()->format('Y-m-d'));

        return view('courts.show', compact('court', 'upcomingBookings', 'todaySlots'));
    }

    /**
     * Get available time slots for a court on a specific date.
     */
    private function getAvailableSlots(Court $court, string $date): array
    {
        $slots = [];
        $operatingHours = [
            'start' => 8, // 8 AM
            'end' => 22,  // 10 PM
        ];

        // Get booked slots
        $bookedSlots = Booking::where('court_id', $court->id)
            ->where('booking_date', $date)
            ->whereIn('status', ['pending', 'approved'])
            ->get()
            ->map(function ($booking) {
                return [
                    'start' => substr($booking->start_time, 0, 5),
                    'end' => substr($booking->end_time, 0, 5),
                ];
            })
            ->toArray();

        // Generate hourly slots
        for ($hour = $operatingHours['start']; $hour < $operatingHours['end']; $hour++) {
            $startTime = sprintf('%02d:00', $hour);
            $endTime = sprintf('%02d:00', $hour + 1);

            $isBooked = false;
            foreach ($bookedSlots as $booked) {
                if ($startTime >= $booked['start'] && $startTime < $booked['end']) {
                    $isBooked = true;
                    break;
                }
            }

            $slots[] = [
                'start' => $startTime,
                'end' => $endTime,
                'available' => !$isBooked && $court->status === 'available',
            ];
        }

        return $slots;
    }
}

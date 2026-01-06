<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Court;
use Illuminate\Http\Request;

class CalendarController extends Controller
{
    /**
     * Display the calendar view.
     */
    public function index()
    {
        $courts = Court::all();
        return view('calendar.index', compact('courts'));
    }

    /**
     * Get calendar events (AJAX endpoint).
     */
    public function getEvents(Request $request)
    {
        $start = $request->get('start');
        $end = $request->get('end');
        $courtId = $request->get('court_id');

        $query = Booking::with('court')
            ->whereIn('status', ['approved', 'pending'])
            ->whereBetween('booking_date', [$start, $end]);

        if ($courtId) {
            $query->where('court_id', $courtId);
        }

        $bookings = $query->get();

        $events = $bookings->map(function ($booking) {
            $statusColors = [
                'pending' => '#f59e0b',    // Orange
                'approved' => '#10b981',   // Green
            ];

            return [
                'id' => $booking->id,
                'title' => $booking->court->court_name,
                'start' => $booking->booking_date->format('Y-m-d') . 'T' . $booking->start_time,
                'end' => $booking->booking_date->format('Y-m-d') . 'T' . $booking->end_time,
                'backgroundColor' => $statusColors[$booking->status] ?? '#6b7280',
                'borderColor' => $statusColors[$booking->status] ?? '#6b7280',
                'extendedProps' => [
                    'court' => $booking->court->court_name,
                    'status' => $booking->status,
                ],
            ];
        });

        return response()->json($events);
    }
}

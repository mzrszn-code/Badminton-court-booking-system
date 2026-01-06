<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Court;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Show the user dashboard.
     */
    public function index()
    {
        $user = auth()->user();

        // Get user's bookings
        $upcomingBookings = Booking::where('user_id', $user->id)
            ->where('booking_date', '>=', now()->format('Y-m-d'))
            ->whereIn('status', ['pending', 'approved'])
            ->with('court')
            ->orderBy('booking_date')
            ->orderBy('start_time')
            ->take(5)
            ->get();

        // Get recent bookings for history
        $recentBookings = Booking::where('user_id', $user->id)
            ->with('court')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Count stats
        $stats = [
            'total_bookings' => Booking::where('user_id', $user->id)->count(),
            'pending_bookings' => Booking::where('user_id', $user->id)->where('status', 'pending')->count(),
            'approved_bookings' => Booking::where('user_id', $user->id)->where('status', 'approved')->count(),
            'completed_bookings' => Booking::where('user_id', $user->id)->where('status', 'completed')->count(),
        ];

        // Available courts
        $availableCourts = Court::where('status', 'available')->count();

        return view('dashboard', compact('upcomingBookings', 'recentBookings', 'stats', 'availableCourts'));
    }
}

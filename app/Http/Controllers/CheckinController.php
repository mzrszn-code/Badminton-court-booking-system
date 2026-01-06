<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Checkin;
use Illuminate\Http\Request;

class CheckinController extends Controller
{
    /**
     * Verify a QR code check-in (user side).
     */
    public function verify($code)
    {
        $checkin = Checkin::where('qr_code', $code)->with('booking.court', 'booking.user')->first();

        if (!$checkin) {
            return view('checkin.invalid');
        }

        $booking = $checkin->booking;

        // Check if booking belongs to current user
        if ($booking->user_id !== auth()->id()) {
            return view('checkin.invalid', ['message' => 'This booking does not belong to you.']);
        }

        // Check if booking is approved
        if ($booking->status !== 'approved') {
            return view('checkin.invalid', ['message' => 'This booking is not approved.']);
        }

        // Check if it's the booking day
        if ($booking->booking_date->format('Y-m-d') !== now()->format('Y-m-d')) {
            return view('checkin.invalid', ['message' => 'Check-in is only available on the booking day.']);
        }

        return view('checkin.verify', compact('checkin', 'booking'));
    }

    /**
     * Admin verification of QR code.
     */
    public function adminVerify(Request $request)
    {
        $validated = $request->validate([
            'qr_code' => 'required|string',
        ]);

        $checkin = Checkin::where('qr_code', $validated['qr_code'])
            ->with('booking.court', 'booking.user')
            ->first();

        if (!$checkin) {
            return response()->json(['success' => false, 'message' => 'Invalid QR code.'], 404);
        }

        $booking = $checkin->booking;

        // Check if booking is approved
        if ($booking->status !== 'approved') {
            return response()->json(['success' => false, 'message' => 'Booking is not approved.'], 400);
        }

        // Check if it's the booking day
        if ($booking->booking_date->format('Y-m-d') !== now()->format('Y-m-d')) {
            return response()->json(['success' => false, 'message' => 'Check-in is only available on the booking day.'], 400);
        }

        // Mark as checked in
        if (!$checkin->is_checked_in) {
            $checkin->update([
                'is_checked_in' => true,
                'checkin_time' => now(),
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Check-in successful!',
            'data' => [
                'user' => $booking->user->name,
                'court' => $booking->court->court_name,
                'time' => $booking->start_time . ' - ' . $booking->end_time,
                'checkin_time' => $checkin->checkin_time->format('H:i:s'),
            ],
        ]);
    }
}

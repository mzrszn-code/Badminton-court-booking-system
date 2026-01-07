<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Payment;
use App\Models\UserActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PaymentController extends Controller
{
    /**
     * Bank account details for payment.
     */
    private const BANK_DETAILS = [
        'bank_name' => 'Maybank',
        'account_name' => 'Flotilla Badminton Center Sdn Bhd',
        'account_number' => '5621 8754 3210',
    ];

    /**
     * Show payment page for a booking.
     */
    public function show(Booking $booking)
    {
        // Ensure user owns this booking
        if ($booking->user_id !== auth()->id()) {
            abort(403);
        }

        // Check if booking is cancelled or rejected
        if (in_array($booking->status, ['cancelled', 'rejected'])) {
            return redirect()->route('bookings.show', $booking)
                ->with('error', 'This booking cannot be paid for.');
        }

        $booking->load(['court', 'payment']);

        // Calculate total with overnight normalization (0-3 AM treated as 24-27)
        $startHour = (int) \Carbon\Carbon::parse($booking->start_time)->format('H');
        $endHour = (int) \Carbon\Carbon::parse($booking->end_time)->format('H');
        if ($startHour >= 8 && $endHour <= 3) {
            $endHour += 24; // Treat 0-3 AM as next day (24-27)
        }
        $hours = $endHour - $startHour;
        $total = $booking->court->hourly_rate * $hours;

        return view('payments.show', [
            'booking' => $booking,
            'bankDetails' => self::BANK_DETAILS,
            'total' => $total,
        ]);
    }

    /**
     * Submit payment proof.
     */
    public function submit(Request $request, Booking $booking)
    {
        // Ensure user owns this booking
        if ($booking->user_id !== auth()->id()) {
            abort(403);
        }

        $validated = $request->validate([
            'payment_method' => 'required|in:bank_transfer,qr_payment',
            'reference_number' => 'required|string|max:100',
            'proof_image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Calculate amount with overnight normalization (0-3 AM treated as 24-27)
        $startHour = (int) \Carbon\Carbon::parse($booking->start_time)->format('H');
        $endHour = (int) \Carbon\Carbon::parse($booking->end_time)->format('H');
        if ($startHour >= 8 && $endHour <= 3) {
            $endHour += 24; // Treat 0-3 AM as next day (24-27)
        }
        $hours = $endHour - $startHour;
        $amount = $booking->court->hourly_rate * $hours;

        // Store proof image
        $imagePath = null;
        if ($request->hasFile('proof_image')) {
            $imagePath = $request->file('proof_image')->store('payment_proofs', 'public');
        }

        // Create or update payment
        $payment = Payment::updateOrCreate(
            ['booking_id' => $booking->id],
            [
                'amount' => $amount,
                'payment_method' => $validated['payment_method'],
                'reference_number' => $validated['reference_number'],
                'proof_image' => $imagePath,
                'payment_status' => 'pending',
                'payment_time' => now(),
            ]
        );

        // Log activity
        UserActivityLog::log(auth()->id(), 'payment_submitted', "Submitted payment for booking #{$booking->id}");

        return redirect()->route('bookings.show', $booking)
            ->with('success', 'Payment submitted successfully! Awaiting admin verification.');
    }
}

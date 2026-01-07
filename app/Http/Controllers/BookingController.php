<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Checkin;
use App\Models\Court;
use App\Models\UserActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BookingController extends Controller
{
    /**
     * Display a listing of user's bookings.
     */
    public function index()
    {
        $bookings = Booking::where('user_id', auth()->id())
            ->with(['court', 'checkin'])
            ->orderBy('booking_date', 'desc')
            ->orderBy('start_time', 'desc')
            ->paginate(10);

        return view('bookings.index', compact('bookings'));
    }

    /**
     * Show the form for creating a new booking.
     */
    public function create(Request $request)
    {
        $courts = Court::where('status', 'available')->get();
        $selectedCourt = $request->has('court') ? Court::find($request->court) : null;
        
        // Get selected date from request, default to today
        $selectedDate = $request->get('date', now()->format('Y-m-d'));
        
        // Ensure selected date is not in the past
        if ($selectedDate < now()->format('Y-m-d')) {
            $selectedDate = now()->format('Y-m-d');
        }

        return view('bookings.create', compact('courts', 'selectedCourt', 'selectedDate'));
    }

    /**
     * Store a newly created booking.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'court_id' => 'required|exists:courts,id',
            'booking_date' => 'required|date|after_or_equal:today',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i',
            'notes' => 'nullable|string|max:500',
        ]);

        // Custom validation for overnight bookings (8AM-3AM operating hours)
        // Hours 0-3 are treated as 24-27 for comparison (continuation of previous day)
        $startHour = (int) substr($validated['start_time'], 0, 2);
        $endHour = (int) substr($validated['end_time'], 0, 2);

        $normalizedStart = $startHour < 8 ? $startHour + 24 : $startHour;
        $normalizedEnd = $endHour <= 3 ? $endHour + 24 : $endHour;

        if ($normalizedEnd <= $normalizedStart) {
            return back()->withErrors(['end_time' => 'End time must be after start time.'])->withInput();
        }

        // Check if court is available
        $court = Court::findOrFail($validated['court_id']);
        if ($court->status !== 'available') {
            return back()->withErrors(['court_id' => 'This court is not available for booking.']);
        }

        // Check for conflicting bookings (with overnight normalization)
        $bookedSlots = Booking::where('court_id', $validated['court_id'])
            ->whereDate('booking_date', $validated['booking_date'])
            ->whereIn('status', ['pending', 'approved'])
            ->get();

        $conflicting = $bookedSlots->contains(function ($booking) use ($normalizedStart, $normalizedEnd) {
            $bookingStartHour = (int) substr($booking->start_time, 0, 2);
            $bookingEndHour = (int) substr($booking->end_time, 0, 2);

            // Normalize booking hours for overnight comparison
            $bookingNormalizedStart = $bookingStartHour < 8 ? $bookingStartHour + 24 : $bookingStartHour;
            $bookingNormalizedEnd = $bookingEndHour <= 3 ? $bookingEndHour + 24 : $bookingEndHour;

            // Check for overlap: new booking overlaps if it starts before existing ends AND ends after existing starts
            return ($normalizedStart < $bookingNormalizedEnd && $normalizedEnd > $bookingNormalizedStart);
        });

        if ($conflicting) {
            return back()->withErrors(['start_time' => 'This time slot is already booked.']);
        }

        // Create booking - set to pending until admin approves payment
        $booking = Booking::create([
            'user_id' => auth()->id(),
            'court_id' => $validated['court_id'],
            'booking_date' => $validated['booking_date'],
            'start_time' => $validated['start_time'],
            'end_time' => $validated['end_time'],
            'status' => 'pending', // Pending until admin approves payment
            'notes' => $validated['notes'],
        ]);

        // Generate QR code for check-in
        $qrCode = Str::uuid()->toString();
        Checkin::create([
            'booking_id' => $booking->id,
            'qr_code' => $qrCode,
        ]);

        // Log activity
        UserActivityLog::log(auth()->id(), 'booking_created', "Created booking for {$court->court_name} on {$validated['booking_date']}");

        // Redirect to payment page immediately
        return redirect()->route('payments.show', $booking)
            ->with('success', 'Booking created! Please complete payment to confirm your reservation.');
    }

    /**
     * Display the specified booking.
     */
    public function show(Booking $booking)
    {
        // Ensure user can only view their own bookings
        if ($booking->user_id !== auth()->id() && !auth()->user()->isAdmin()) {
            abort(403);
        }

        $booking->load(['court', 'checkin', 'payment']);

        return view('bookings.show', compact('booking'));
    }

    /**
     * Cancel a booking.
     */
    public function cancel(Booking $booking)
    {
        // Ensure user can only cancel their own bookings
        if ($booking->user_id !== auth()->id()) {
            abort(403);
        }

        if (!in_array($booking->status, ['pending', 'approved'])) {
            return back()->withErrors(['status' => 'This booking cannot be cancelled.']);
        }

        // Check if payment is completed - prevent cancellation
        $booking->load('payment');
        if ($booking->payment && $booking->payment->payment_status === 'completed') {
            return back()->with('error', 'This booking cannot be cancelled because payment has been verified.');
        }

        $booking->update(['status' => 'cancelled']);

        // Update payment status to cancelled if exists
        if ($booking->payment) {
            $booking->payment->update(['payment_status' => 'cancelled']);
        }

        // Log activity
        UserActivityLog::log(auth()->id(), 'booking_cancelled', "Cancelled booking #{$booking->id}");

        return redirect()->route('bookings.index')
            ->with('success', 'Booking cancelled successfully.');
    }

    /**
     * Get available time slots (AJAX endpoint).
     */
    public function getAvailableSlots(Request $request)
    {
        $validated = $request->validate([
            'court_id' => 'required|exists:courts,id',
            'date' => 'required|date',
        ]);

        $court = Court::findOrFail($validated['court_id']);
        $date = $validated['date'];

        $slots = [];
        // Operating hours: 8 AM to 3 AM (next day) = 19 hours total
        $hours = [8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 0, 1, 2];

        // Get booked slots for this court and date
        $bookedSlots = Booking::where('court_id', $court->id)
            ->whereDate('booking_date', $date)
            ->whereIn('status', ['pending', 'approved'])
            ->get();

        foreach ($hours as $index => $hour) {
            $nextHour = $hour + 1;
            if ($nextHour == 24)
                $nextHour = 0;
            if ($hour == 2)
                $nextHour = 3; // Last slot ends at 3 AM

            // Check if this hour slot overlaps with any existing booking
            // Normalize hours: 0-3 AM treated as 24-27 (continuation of previous day)
            $normalizedSlotHour = $hour < 8 ? $hour + 24 : $hour;

            $isBooked = $bookedSlots->contains(function ($booking) use ($normalizedSlotHour) {
                // Extract hours from booking times
                $bookingStartTime = is_string($booking->start_time) ? $booking->start_time : $booking->start_time->format('H:i:s');
                $bookingEndTime = is_string($booking->end_time) ? $booking->end_time : $booking->end_time->format('H:i:s');

                // Get just the hour part
                $bookingStartHour = (int) substr($bookingStartTime, 0, 2);
                $bookingEndHour = (int) substr($bookingEndTime, 0, 2);

                // Normalize booking hours for overnight comparison
                $normalizedBookingStart = $bookingStartHour < 8 ? $bookingStartHour + 24 : $bookingStartHour;
                $normalizedBookingEnd = $bookingEndHour <= 3 ? $bookingEndHour + 24 : $bookingEndHour;

                // A booking covers this slot if: normalizedSlotHour >= start AND normalizedSlotHour < end
                return ($normalizedSlotHour >= $normalizedBookingStart && $normalizedSlotHour < $normalizedBookingEnd);
            });

            // Check if slot is in the past (for today)
            $isPast = ($date === now()->format('Y-m-d') && $hour >= 8 && $hour <= now()->hour);

            $slots[] = [
                'start' => sprintf('%02d:00', $hour),
                'end' => sprintf('%02d:00', $nextHour),
                'available' => !$isBooked && !$isPast && $court->status === 'available',
            ];
        }

        return response()->json($slots);
    }
}

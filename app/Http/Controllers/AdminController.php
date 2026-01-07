<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Court;
use App\Models\Maintenance;
use App\Models\Payment;
use App\Models\User;
use App\Models\UserActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    /**
     * Admin dashboard with statistics.
     */
    public function dashboard()
    {
        // Basic stats
        $stats = [
            'total_users' => User::where('role', 'user')->count(),
            'total_courts' => Court::count(),
            'total_bookings' => Booking::count(),
            'pending_bookings' => Booking::where('status', 'pending')->count(),
            'approved_bookings' => Booking::where('status', 'approved')->count(),
            'today_bookings' => Booking::where('booking_date', now()->format('Y-m-d'))->count(),
            'pending_payments' => Payment::where('payment_status', 'pending')->count(),
        ];

        // Peak hours analysis (bookings by hour)
        $peakHours = Booking::selectRaw("HOUR(start_time) as hour, COUNT(*) as count")
            ->whereIn('status', ['approved', 'completed'])
            ->groupBy('hour')
            ->orderBy('count', 'desc')
            ->limit(5)
            ->get();

        // Most booked courts
        $popularCourts = Court::withCount([
            'bookings' => function ($query) {
                $query->whereIn('status', ['approved', 'completed']);
            }
        ])
            ->orderBy('bookings_count', 'desc')
            ->take(5)
            ->get();

        // Recent bookings
        $recentBookings = Booking::with(['user', 'court'])
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        // Bookings by status for chart
        $bookingsByStatus = Booking::selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        // Recent activity
        $recentActivity = UserActivityLog::with('user')
            ->orderBy('activity_time', 'desc')
            ->take(10)
            ->get();

        // Active maintenances
        $activeMaintenances = Maintenance::with('court')
            ->where('status', '!=', 'completed')
            ->orderBy('start_date')
            ->get();

        return view('admin.dashboard', compact(
            'stats',
            'peakHours',
            'popularCourts',
            'recentBookings',
            'bookingsByStatus',
            'recentActivity',
            'activeMaintenances'
        ));
    }

    /**
     * Manage bookings.
     */
    public function bookings(Request $request)
    {
        $query = Booking::with(['user', 'court', 'payment']);

        // Filter by status (based on display_status logic)
        if ($request->has('status') && $request->status !== 'all') {
            $status = $request->status;
            $now = now();
            
            if ($status === 'completed') {
                // Show approved bookings whose session has ended
                $query->where(function ($q) use ($now) {
                    $q->where('status', 'approved')
                      ->where(function ($q2) use ($now) {
                          // Session has ended: booking_date + end_time < now
                          // Handle overnight bookings (end time 0:00-03:00 means next day)
                          $q2->whereRaw("
                              CASE 
                                  WHEN HOUR(end_time) <= 3 THEN CONCAT(DATE_ADD(booking_date, INTERVAL 1 DAY), ' ', end_time)
                                  ELSE CONCAT(booking_date, ' ', end_time)
                              END < ?
                          ", [$now]);
                      });
                });
            } elseif ($status === 'approved') {
                // Show approved bookings whose session has NOT ended yet
                $query->where('status', 'approved')
                      ->where(function ($q) use ($now) {
                          // Session has NOT ended
                          $q->whereRaw("
                              CASE 
                                  WHEN HOUR(end_time) <= 3 THEN CONCAT(DATE_ADD(booking_date, INTERVAL 1 DAY), ' ', end_time)
                                  ELSE CONCAT(booking_date, ' ', end_time)
                              END >= ?
                          ", [$now]);
                      });
            } else {
                // For other statuses (pending, rejected, cancelled), use direct match
                $query->where('status', $status);
            }
        }

        // Filter by date
        if ($request->has('date')) {
            $query->where('booking_date', $request->date);
        }

        $bookings = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('admin.bookings.index', compact('bookings'));
    }

    /**
     * Approve a booking.
     */
    public function approveBooking(Booking $booking)
    {
        $booking->update(['status' => 'approved']);

        // Log activity
        UserActivityLog::log(auth()->id(), 'booking_approved', "Approved booking #{$booking->id}");

        return back()->with('success', 'Booking approved successfully.');
    }

    /**
     * Reject a booking.
     */
    public function rejectBooking(Request $request, Booking $booking)
    {
        $booking->update(['status' => 'rejected']);

        // Log activity
        UserActivityLog::log(auth()->id(), 'booking_rejected', "Rejected booking #{$booking->id}");

        return back()->with('success', 'Booking rejected.');
    }

    /**
     * Manage courts.
     */
    public function courts()
    {
        $courts = Court::withCount('bookings')->get();
        return view('admin.courts.index', compact('courts'));
    }

    /**
     * Show create court form.
     */
    public function createCourt()
    {
        return view('admin.courts.create');
    }

    /**
     * Store a new court.
     */
    public function storeCourt(Request $request)
    {
        $validated = $request->validate([
            'court_name' => 'required|string|max:255',
            'court_type' => 'required|string|max:50',
            'location' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'hourly_rate' => 'required|numeric|min:0',
        ]);

        Court::create($validated);

        // Log activity
        UserActivityLog::log(auth()->id(), 'court_created', "Created court: {$validated['court_name']}");

        return redirect()->route('admin.courts.index')->with('success', 'Court created successfully.');
    }

    /**
     * Show edit court form.
     */
    public function editCourt(Court $court)
    {
        return view('admin.courts.edit', compact('court'));
    }

    /**
     * Update a court.
     */
    public function updateCourt(Request $request, Court $court)
    {
        $validated = $request->validate([
            'court_name' => 'required|string|max:255',
            'court_type' => 'required|string|max:50',
            'location' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'hourly_rate' => 'required|numeric|min:0',
            'status' => 'required|in:available,booked,maintenance',
        ]);

        $court->update($validated);

        // Log activity
        UserActivityLog::log(auth()->id(), 'court_updated', "Updated court: {$court->court_name}");

        return redirect()->route('admin.courts.index')->with('success', 'Court updated successfully.');
    }

    /**
     * Delete a court.
     */
    public function deleteCourt(Court $court)
    {
        $courtName = $court->court_name;
        $court->delete();

        // Log activity
        UserActivityLog::log(auth()->id(), 'court_deleted', "Deleted court: {$courtName}");

        return redirect()->route('admin.courts.index')->with('success', 'Court deleted successfully.');
    }

    /**
     * Maintenance management.
     */
    public function maintenance()
    {
        $maintenances = Maintenance::with('court')->orderBy('start_date', 'desc')->paginate(15);
        $courts = Court::all();
        return view('admin.maintenance.index', compact('maintenances', 'courts'));
    }

    /**
     * Store maintenance record.
     */
    public function storeMaintenance(Request $request)
    {
        $validated = $request->validate([
            'court_id' => 'required|exists:courts,id',
            'description' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        Maintenance::create([
            ...$validated,
            'status' => 'scheduled',
        ]);

        // Update court status
        Court::find($validated['court_id'])->update(['status' => 'maintenance']);

        // Log activity
        UserActivityLog::log(auth()->id(), 'maintenance_scheduled', "Scheduled maintenance for court ID: {$validated['court_id']}");

        return back()->with('success', 'Maintenance scheduled successfully.');
    }

    /**
     * Toggle court maintenance mode.
     */
    public function toggleMaintenance(Court $court)
    {
        $newStatus = $court->status === 'maintenance' ? 'available' : 'maintenance';
        $court->update(['status' => $newStatus]);

        // Sync with maintenances table
        if ($newStatus === 'maintenance') {
            // Create a new maintenance record
            Maintenance::create([
                'court_id' => $court->id,
                'description' => 'Court under maintenance',
                'start_date' => now(),
                'end_date' => now()->addDays(7), // Default 7 days, can be updated
                'status' => 'in_progress',
            ]);
        } else {
            // Complete any active maintenance records for this court
            Maintenance::where('court_id', $court->id)
                ->where('status', '!=', 'completed')
                ->update([
                    'status' => 'completed',
                    'end_date' => now(),
                ]);
        }

        // Log activity
        $action = $newStatus === 'maintenance' ? 'enabled' : 'disabled';
        UserActivityLog::log(auth()->id(), 'maintenance_toggled', "Maintenance {$action} for {$court->court_name}");

        return back()->with('success', "Court is now {$newStatus}.");
    }

    /**
     * Manage users.
     */
    public function users()
    {
        $users = User::withCount('bookings')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('admin.users.index', compact('users'));
    }

    /**
     * View activity logs.
     */
    public function activities(Request $request)
    {
        $query = UserActivityLog::with('user');

        // Filter by type
        if ($request->has('type') && $request->type !== 'all') {
            $query->where('activity_type', $request->type);
        }

        $activities = $query->orderBy('activity_time', 'desc')->paginate(20);

        // Get unique activity types for filter
        $activityTypes = UserActivityLog::distinct()->pluck('activity_type');

        return view('admin.activities.index', compact('activities', 'activityTypes'));
    }

    /**
     * Manage payments.
     */
    public function payments(Request $request)
    {
        $query = Payment::with(['booking.user', 'booking.court']);

        // Filter by status
        if ($request->has('status') && $request->status !== 'all') {
            $query->where('payment_status', $request->status);
        }

        $payments = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('admin.payments.index', compact('payments'));
    }

    /**
     * Approve a payment.
     */
    public function approvePayment(Payment $payment)
    {
        $payment->update([
            'payment_status' => 'completed',
            'payment_time' => now(),
        ]);

        // Update booking status to approved
        $payment->booking->update(['status' => 'approved']);

        // Log activity
        UserActivityLog::log(auth()->id(), 'payment_approved', "Approved payment #{$payment->id} for booking #{$payment->booking_id}");

        return back()->with('success', 'Payment approved successfully. Booking is now confirmed and cannot be cancelled.');
    }

    /**
     * Reject a payment.
     */
    public function rejectPayment(Request $request, Payment $payment)
    {
        $payment->update([
            'payment_status' => 'rejected',
            'admin_notes' => $request->input('admin_notes'),
        ]);

        // Update booking status to rejected so the time slot becomes available again
        $payment->booking->update(['status' => 'rejected']);

        // Log activity
        UserActivityLog::log(auth()->id(), 'payment_rejected', "Rejected payment #{$payment->id} for booking #{$payment->booking_id}");

        return back()->with('success', 'Payment rejected. The time slot is now available for other bookings.');
    }
}


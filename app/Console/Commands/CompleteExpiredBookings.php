<?php

namespace App\Console\Commands;

use App\Models\Booking;
use Carbon\Carbon;
use Illuminate\Console\Command;

class CompleteExpiredBookings extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bookings:complete-expired';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update approved bookings to completed status after their session ends';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $now = Carbon::now();
        $today = $now->toDateString();
        $currentTime = $now->format('H:i:s');

        // Find all approved bookings where the session has ended
        $expiredBookings = Booking::where('status', 'approved')
            ->where(function ($query) use ($today, $currentTime) {
                // Bookings from past dates
                $query->where('booking_date', '<', $today)
                    // Or bookings from today where end_time has passed
                    ->orWhere(function ($q) use ($today, $currentTime) {
                    $q->where('booking_date', '=', $today)
                        ->where('end_time', '<=', $currentTime);
                });
            })
            ->get();

        $count = $expiredBookings->count();

        if ($count > 0) {
            foreach ($expiredBookings as $booking) {
                $booking->update(['status' => 'completed']);
            }
            $this->info("Successfully updated {$count} booking(s) to completed status.");
        } else {
            $this->info('No expired bookings to update.');
        }

        return Command::SUCCESS;
    }
}

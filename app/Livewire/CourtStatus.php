<?php

namespace App\Livewire;

use App\Models\Court;
use Livewire\Component;
use Livewire\Attributes\Computed;

class CourtStatus extends Component
{
    // Auto-refresh every 10 seconds
    public function poll()
    {
        // This method is called when polling
    }

    #[Computed]
    public function courts()
    {
        return Court::withCount([
            'bookings' => function ($query) {
                $query->whereDate('booking_date', now()->format('Y-m-d'))
                    ->whereIn('status', ['pending', 'approved']);
            }
        ])->get();
    }

    public function render()
    {
        return view('livewire.court-status');
    }
}

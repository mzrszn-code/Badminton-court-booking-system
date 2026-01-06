@extends('layouts.app')

@section('title', $court->court_name)

@section('content')
<div style="margin-bottom: 1rem;">
    <a href="{{ route('courts.index') }}" style="color: var(--text-secondary); text-decoration: none; font-size: 0.875rem;">
        <i class="fas fa-arrow-left"></i> Back to Courts
    </a>
</div>

<div class="grid grid-cols-3" style="grid-template-columns: 2fr 1fr;">
    <!-- Court Details -->
    <div class="card">
        <div style="display: flex; align-items: center; gap: 1.5rem; margin-bottom: 2rem;">
            <div style="width: 100px; height: 100px; background: var(--gradient-primary); border-radius: var(--radius-lg); display: flex; align-items: center; justify-content: center; color: white; font-weight: 800; font-size: 3rem;">
                {{ strtoupper(substr($court->court_name, -1)) }}
            </div>
            <div>
                <h1 style="font-size: 2rem; font-weight: 800; margin-bottom: 0.5rem;">{{ $court->court_name }}</h1>
                <span class="badge badge-{{ $court->status }}" style="font-size: 0.9rem; padding: 0.375rem 1rem;">{{ ucfirst($court->status) }}</span>
            </div>
        </div>
        
        <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 1.5rem; margin-bottom: 2rem;">
            <div style="text-align: center; padding: 1.5rem; background: var(--bg-card-hover); border-radius: var(--radius-md);">
                <i class="fas fa-tag" style="font-size: 1.5rem; color: var(--primary); margin-bottom: 0.5rem;"></i>
                <div style="font-size: 1.25rem; font-weight: 700;">{{ ucfirst($court->court_type) }}</div>
                <div style="font-size: 0.75rem; color: var(--text-secondary);">Court Type</div>
            </div>
            <div style="text-align: center; padding: 1.5rem; background: var(--bg-card-hover); border-radius: var(--radius-md);">
                <i class="fas fa-location-dot" style="font-size: 1.5rem; color: var(--secondary); margin-bottom: 0.5rem;"></i>
                <div style="font-size: 1rem; font-weight: 700;">{{ $court->location ?? 'Main' }}</div>
                <div style="font-size: 0.75rem; color: var(--text-secondary);">Location</div>
            </div>
            <div style="text-align: center; padding: 1.5rem; background: var(--bg-card-hover); border-radius: var(--radius-md);">
                <i class="fas fa-coins" style="font-size: 1.5rem; color: var(--accent); margin-bottom: 0.5rem;"></i>
                <div style="font-size: 1.25rem; font-weight: 700;">RM {{ number_format($court->hourly_rate, 2) }}</div>
                <div style="font-size: 0.75rem; color: var(--text-secondary);">Per Hour</div>
            </div>
        </div>
        
        <h3 style="font-weight: 600; margin-bottom: 1rem;">Description</h3>
        <p style="color: var(--text-secondary); line-height: 1.8; margin-bottom: 2rem;">
            {{ $court->description ?? 'This is a quality badminton court with professional flooring and proper lighting. Perfect for recreational and competitive play.' }}
        </p>
        
        @if($court->status === 'available')
            <a href="{{ route('bookings.create', ['court' => $court->id]) }}" class="btn btn-primary btn-lg" style="width: 100%;">
                <i class="fas fa-calendar-plus"></i> Book This Court
            </a>
        @else
            <div class="alert alert-warning">
                <i class="fas fa-exclamation-triangle"></i>
                This court is currently {{ $court->status }} and cannot be booked.
            </div>
        @endif
    </div>
    
    <!-- Today's Availability -->
    <div>
        <div class="card">
            <div class="card-header">
                <h2 class="card-title"><i class="fas fa-clock" style="color: var(--primary); margin-right: 0.5rem;"></i> Today's Slots</h2>
            </div>
            
            <p style="font-size: 0.75rem; color: var(--text-secondary); margin-bottom: 1rem;">
                <i class="fas fa-info-circle"></i> Click available slots to book
            </p>
            
            <div style="display: grid; gap: 0.5rem;">
                @foreach($todaySlots as $slot)
                    @if($slot['available'] && $court->status === 'available')
                        <a href="{{ route('bookings.create', ['court' => $court->id, 'date' => now()->format('Y-m-d')]) }}" 
                           style="display: flex; justify-content: space-between; align-items: center; padding: 0.75rem 1rem; background: rgba(16, 185, 129, 0.1); border-radius: var(--radius-sm); border-left: 3px solid var(--success); text-decoration: none; color: inherit; transition: var(--transition-fast);"
                           onmouseover="this.style.transform='translateX(4px)'; this.style.background='rgba(16, 185, 129, 0.2)';"
                           onmouseout="this.style.transform=''; this.style.background='rgba(16, 185, 129, 0.1)';">
                            <span style="font-weight: 500; font-size: 0.875rem;">{{ $slot['start'] }} - {{ $slot['end'] }}</span>
                            <span class="badge badge-available">Book Now</span>
                        </a>
                    @else
                        <div style="display: flex; justify-content: space-between; align-items: center; padding: 0.75rem 1rem; background: var(--bg-card-hover); border-radius: var(--radius-sm); border-left: 3px solid var(--text-muted); opacity: 0.6;">
                            <span style="font-weight: 500; font-size: 0.875rem;">{{ $slot['start'] }} - {{ $slot['end'] }}</span>
                            <span class="badge badge-booked">Booked</span>
                        </div>
                    @endif
                @endforeach
            </div>
        </div>
        
        @if($upcomingBookings->count() > 0)
            <div class="card" style="margin-top: 1.5rem;">
                <div class="card-header">
                    <h2 class="card-title">Upcoming Bookings</h2>
                </div>
                
                @foreach($upcomingBookings as $booking)
                    <div style="padding: 0.75rem 0; border-bottom: 1px solid var(--border-color);">
                        <div style="font-weight: 500; font-size: 0.875rem;">{{ $booking->booking_date->format('D, M d') }}</div>
                        <div style="font-size: 0.75rem; color: var(--text-secondary);">
                            {{ \Carbon\Carbon::parse($booking->start_time)->format('g:i A') }} - {{ \Carbon\Carbon::parse($booking->end_time)->format('g:i A') }}
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection

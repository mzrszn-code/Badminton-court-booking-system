@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="page-header">
    <h1 class="page-title">Welcome back, {{ auth()->user()->name }}!</h1>
    <p class="page-subtitle">Here's an overview of your bookings and activity.</p>
</div>

<!-- Stats Grid -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon primary"><i class="fas fa-calendar-check"></i></div>
        <div class="stat-content">
            <h3>{{ $stats['total_bookings'] }}</h3>
            <p>Total Bookings</p>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon accent"><i class="fas fa-clock"></i></div>
        <div class="stat-content">
            <h3>{{ $stats['pending_bookings'] }}</h3>
            <p>Pending Approval</p>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon success"><i class="fas fa-check-circle"></i></div>
        <div class="stat-content">
            <h3>{{ $stats['approved_bookings'] }}</h3>
            <p>Approved</p>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon secondary"><i class="fas fa-table-tennis-paddle-ball"></i></div>
        <div class="stat-content">
            <h3>{{ $availableCourts }}</h3>
            <p>Available Courts</p>
        </div>
    </div>
</div>

<div class="grid grid-cols-2">
    <!-- Upcoming Bookings -->
    <div class="card">
        <div class="card-header">
            <h2 class="card-title"><i class="fas fa-calendar-alt" style="color: var(--primary); margin-right: 0.5rem;"></i> Upcoming Bookings</h2>
            <a href="{{ route('bookings.index') }}" class="btn btn-sm btn-secondary">View All</a>
        </div>
        
        @if($upcomingBookings->count() > 0)
            @foreach($upcomingBookings as $booking)
                <div style="display: flex; align-items: center; gap: 1rem; padding: 1rem; background: var(--bg-card-hover); border-radius: var(--radius-md); margin-bottom: 0.75rem;">
                    <div style="width: 50px; height: 50px; background: var(--gradient-primary); border-radius: var(--radius-md); display: flex; align-items: center; justify-content: center; color: white; font-weight: 700;">
                        {{ $booking->booking_date->format('d') }}
                    </div>
                    <div style="flex: 1;">
                        <div style="font-weight: 600;">{{ $booking->court->court_name }}</div>
                        <div style="font-size: 0.875rem; color: var(--text-secondary);">
                            {{ $booking->booking_date->format('D, M d') }} • {{ \Carbon\Carbon::parse($booking->start_time)->format('g:i A') }} - {{ \Carbon\Carbon::parse($booking->end_time)->format('g:i A') }}
                        </div>
                    </div>
                    <span class="badge badge-{{ $booking->status }}">{{ ucfirst($booking->status) }}</span>
                </div>
            @endforeach
        @else
            <div class="empty-state">
                <i class="fas fa-calendar-xmark"></i>
                <p>No upcoming bookings</p>
                <a href="{{ route('bookings.create') }}" class="btn btn-primary btn-sm" style="margin-top: 1rem;">Book a Court</a>
            </div>
        @endif
    </div>
    
    <!-- Quick Actions -->
    <div>
        <div class="card" style="margin-bottom: 1.5rem;">
            <div class="card-header">
                <h2 class="card-title"><i class="fas fa-bolt" style="color: var(--accent); margin-right: 0.5rem;"></i> Quick Actions</h2>
            </div>
            
            <div style="display: grid; gap: 1rem;">
                <a href="{{ route('bookings.create') }}" class="btn btn-primary" style="justify-content: flex-start;">
                    <i class="fas fa-plus-circle"></i> New Booking
                </a>
                <a href="{{ route('courts.index') }}" class="btn btn-secondary" style="justify-content: flex-start;">
                    <i class="fas fa-table-tennis-paddle-ball"></i> Browse Courts
                </a>
                <a href="{{ route('profile.show') }}" class="btn btn-secondary" style="justify-content: flex-start;">
                    <i class="fas fa-user"></i> My Profile
                </a>
            </div>
        </div>
        
        <!-- Recent Activity -->
        <div class="card">
            <div class="card-header">
                <h2 class="card-title"><i class="fas fa-history" style="color: var(--secondary); margin-right: 0.5rem;"></i> Recent Activity</h2>
            </div>
            
            @if($recentBookings->count() > 0)
                @foreach($recentBookings->take(4) as $booking)
                    <div style="display: flex; align-items: center; gap: 0.75rem; padding: 0.75rem 0; border-bottom: 1px solid var(--border-color);">
                        <span class="badge badge-{{ $booking->status }}" style="min-width: 80px; justify-content: center;">{{ ucfirst($booking->status) }}</span>
                        <div style="flex: 1; font-size: 0.875rem;">
                            <span style="font-weight: 500;">{{ $booking->court->court_name }}</span>
                            <span style="color: var(--text-muted);"> - {{ $booking->booking_date->format('M d') }}</span>
                        </div>
                    </div>
                @endforeach
            @else
                <p style="color: var(--text-secondary); text-align: center; padding: 1rem;">No recent activity</p>
            @endif
        </div>
    </div>
</div>
@endsection

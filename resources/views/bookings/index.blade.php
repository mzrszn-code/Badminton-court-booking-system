@extends('layouts.app')

@section('title', 'My Bookings')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
    <div>
        <h1 class="page-title">My Bookings</h1>
        <p class="page-subtitle">View and manage your court reservations.</p>
    </div>
    <a href="{{ route('bookings.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> New Booking
    </a>
</div>

<div class="card">
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Booking ID</th>
                    <th>Court</th>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Status</th>
                    <th>QR Code</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($bookings as $booking)
                    <tr>
                        <td style="font-weight: 600;">#{{ $booking->id }}</td>
                        <td>
                            <div style="font-weight: 500;">{{ $booking->court->court_name }}</div>
                            <div style="font-size: 0.75rem; color: var(--text-secondary);">{{ $booking->court->court_type }}</div>
                        </td>
                        <td>{{ $booking->booking_date->format('D, M d, Y') }}</td>
                        <td>{{ \Carbon\Carbon::parse($booking->start_time)->format('g:i A') }} - {{ \Carbon\Carbon::parse($booking->end_time)->format('g:i A') }}</td>
                        <td><span class="badge badge-{{ $booking->status }}">{{ ucfirst($booking->status) }}</span></td>
                        <td>
                            @if($booking->status === 'approved' && $booking->checkin)
                                <a href="{{ route('bookings.show', $booking) }}" class="btn btn-sm btn-secondary">
                                    <i class="fas fa-qrcode"></i> View
                                </a>
                            @elseif($booking->status === 'pending')
                                <span style="color: var(--text-muted); font-size: 0.75rem;">Awaiting approval</span>
                            @else
                                <span style="color: var(--text-muted); font-size: 0.75rem;">-</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('bookings.show', $booking) }}" class="btn btn-sm btn-secondary">
                                <i class="fas fa-eye"></i> View
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="text-align: center; padding: 3rem;">
                            <i class="fas fa-calendar-xmark" style="font-size: 2rem; color: var(--text-muted); margin-bottom: 1rem; display: block;"></i>
                            <p style="color: var(--text-secondary); margin-bottom: 1rem;">No bookings yet</p>
                            <a href="{{ route('bookings.create') }}" class="btn btn-primary">Make Your First Booking</a>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if($bookings->hasPages())
        <div class="pagination">
            {{ $bookings->links() }}
        </div>
    @endif
</div>
@endsection

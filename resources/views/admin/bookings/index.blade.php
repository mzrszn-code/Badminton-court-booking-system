@extends('layouts.app')

@section('title', 'Manage Bookings')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
    <div>
        <h1 class="page-title">Manage Bookings</h1>
        <p class="page-subtitle">Review, approve, or reject booking requests.</p>
    </div>
    <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Back to Dashboard
    </a>
</div>

<!-- Filters -->
<div class="card" style="margin-bottom: 1.5rem;">
    <form method="GET" action="{{ route('admin.bookings.index') }}" style="display: flex; gap: 1rem; align-items: end;">
        <div class="form-group" style="margin-bottom: 0; flex: 1;">
            <label class="form-label">Status</label>
            <select name="status" class="form-control">
                <option value="all" {{ request('status') === 'all' ? 'selected' : '' }}>All Statuses</option>
                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approved</option>
                <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
                <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
            </select>
        </div>
        <div class="form-group" style="margin-bottom: 0; flex: 1;">
            <label class="form-label">Date</label>
            <input type="date" name="date" class="form-control" value="{{ request('date') }}">
        </div>
        <button type="submit" class="btn btn-primary">
            <i class="fas fa-filter"></i> Filter
        </button>
    </form>
</div>

<div class="card">
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>User</th>
                    <th>Court</th>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Status</th>
                    <th>Created</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($bookings as $booking)
                    <tr>
                        <td style="font-weight: 600;">#{{ $booking->id }}</td>
                        <td>
                            <div style="font-weight: 500;">{{ $booking->user->name }}</div>
                            <div style="font-size: 0.75rem; color: var(--text-secondary);">{{ $booking->user->email }}</div>
                        </td>
                        <td>{{ $booking->court->court_name }}</td>
                        <td>{{ $booking->booking_date->format('M d, Y') }}</td>
                        <td>{{ \Carbon\Carbon::parse($booking->start_time)->format('g:i A') }} - {{ \Carbon\Carbon::parse($booking->end_time)->format('g:i A') }}</td>
                        <td><span class="badge badge-{{ $booking->status }}">{{ ucfirst($booking->status) }}</span></td>
                        <td>{{ $booking->created_at->diffForHumans() }}</td>
                        <td>
                            @if($booking->status === 'pending')
                                <div style="display: flex; gap: 0.5rem;">
                                    <form action="{{ route('admin.bookings.approve', $booking) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-success" title="Approve">
                                            <i class="fas fa-check"></i>
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.bookings.reject', $booking) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-danger" title="Reject">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </form>
                                </div>
                            @else
                                <span style="color: var(--text-muted);">—</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" style="text-align: center; padding: 3rem;">
                            <i class="fas fa-calendar-xmark" style="font-size: 2rem; color: var(--text-muted); margin-bottom: 1rem; display: block;"></i>
                            <p style="color: var(--text-secondary);">No bookings found</p>
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

@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="page-header">
    <h1 class="page-title">Admin Dashboard</h1>
    <p class="page-subtitle">Overview of court bookings and system statistics.</p>
</div>

<!-- Stats Grid -->
<div class="stats-grid" style="grid-template-columns: repeat(6, 1fr);">
    <div class="stat-card">
        <div class="stat-icon primary"><i class="fas fa-users"></i></div>
        <div class="stat-content">
            <h3>{{ $stats['total_users'] }}</h3>
            <p>Users</p>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon secondary"><i class="fas fa-table-tennis-paddle-ball"></i></div>
        <div class="stat-content">
            <h3>{{ $stats['total_courts'] }}</h3>
            <p>Courts</p>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon accent"><i class="fas fa-calendar-check"></i></div>
        <div class="stat-content">
            <h3>{{ $stats['total_bookings'] }}</h3>
            <p>Total Bookings</p>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon" style="background: var(--warning);"><i class="fas fa-clock"></i></div>
        <div class="stat-content">
            <h3>{{ $stats['pending_bookings'] }}</h3>
            <p>Pending</p>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon success"><i class="fas fa-check"></i></div>
        <div class="stat-content">
            <h3>{{ $stats['approved_bookings'] }}</h3>
            <p>Approved</p>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon" style="background: var(--danger);"><i class="fas fa-calendar-day"></i></div>
        <div class="stat-content">
            <h3>{{ $stats['today_bookings'] }}</h3>
            <p>Today</p>
        </div>
    </div>
</div>

<div class="grid grid-cols-3">
    <!-- Quick Access -->
    <div class="card">
        <div class="card-header">
            <h2 class="card-title"><i class="fas fa-bolt" style="color: var(--accent); margin-right: 0.5rem;"></i> Quick Access</h2>
        </div>
        
        <div style="display: grid; gap: 0.75rem;">
            <a href="{{ route('admin.payments.index') }}?status=pending" class="btn btn-primary" style="justify-content: flex-start;">
                <i class="fas fa-credit-card" style="width: 20px;"></i> Pending Payments ({{ $stats['pending_payments'] ?? 0 }})
            </a>
            <a href="{{ route('admin.bookings.index') }}" class="btn btn-secondary" style="justify-content: flex-start;">
                <i class="fas fa-calendar-check" style="width: 20px;"></i> All Bookings
            </a>
            <a href="{{ route('admin.courts.index') }}" class="btn btn-secondary" style="justify-content: flex-start;">
                <i class="fas fa-table-tennis-paddle-ball" style="width: 20px;"></i> Manage Courts
            </a>
            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary" style="justify-content: flex-start;">
                <i class="fas fa-users" style="width: 20px;"></i> Users
            </a>
            <a href="{{ route('admin.activities.index') }}" class="btn btn-secondary" style="justify-content: flex-start;">
                <i class="fas fa-history" style="width: 20px;"></i> Activity Logs
            </a>
        </div>
    </div>
    
    <!-- Peak Hours -->
    <div class="card">
        <div class="card-header">
            <h2 class="card-title"><i class="fas fa-chart-bar" style="color: var(--primary); margin-right: 0.5rem;"></i> Peak Hours</h2>
        </div>
        
        @if($peakHours->count() > 0)
            @foreach($peakHours as $peak)
                <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 0.75rem;">
                    <div style="width: 50px; font-weight: 700; color: var(--text-secondary);">
                        {{ sprintf('%02d:00', $peak->hour) }}
                    </div>
                    <div style="flex: 1; height: 24px; background: var(--bg-card-hover); border-radius: var(--radius-sm); overflow: hidden;">
                        <div style="height: 100%; width: {{ min(100, ($peak->count / max($peakHours->max('count'), 1)) * 100) }}%; background: var(--gradient-primary); border-radius: var(--radius-sm);"></div>
                    </div>
                    <div style="width: 40px; text-align: right; font-weight: 600;">{{ $peak->count }}</div>
                </div>
            @endforeach
        @else
            <p style="color: var(--text-secondary); text-align: center;">No booking data yet</p>
        @endif
    </div>
    
    <!-- Popular Courts -->
    <div class="card">
        <div class="card-header">
            <h2 class="card-title"><i class="fas fa-trophy" style="color: var(--success); margin-right: 0.5rem;"></i> Popular Courts</h2>
        </div>
        
        @if($popularCourts->count() > 0)
            @foreach($popularCourts as $index => $court)
                <div style="display: flex; align-items: center; gap: 1rem; padding: 0.75rem 0; border-bottom: 1px solid var(--border-color);">
                    <div style="width: 32px; height: 32px; background: {{ $index === 0 ? 'var(--gradient-accent)' : 'var(--bg-card-hover)' }}; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 0.875rem; color: {{ $index === 0 ? 'white' : 'var(--text-secondary)' }};">
                        {{ $index + 1 }}
                    </div>
                    <div style="flex: 1;">
                        <div style="font-weight: 600;">{{ $court->court_name }}</div>
                        <div style="font-size: 0.75rem; color: var(--text-secondary);">{{ $court->bookings_count }} bookings</div>
                    </div>
                </div>
            @endforeach
        @else
            <p style="color: var(--text-secondary); text-align: center;">No booking data yet</p>
        @endif
    </div>
</div>

<div class="grid grid-cols-2" style="margin-top: 1.5rem;">
    <!-- Recent Bookings -->
    <div class="card">
        <div class="card-header">
            <h2 class="card-title"><i class="fas fa-calendar" style="color: var(--secondary); margin-right: 0.5rem;"></i> Recent Bookings</h2>
            <a href="{{ route('admin.bookings.index') }}" class="btn btn-sm btn-secondary">View All</a>
        </div>
        
        <div class="table-container" style="border: none;">
            <table>
                <thead>
                    <tr>
                        <th>User</th>
                        <th>Court</th>
                        <th>Date</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentBookings as $booking)
                        <tr>
                            <td style="font-weight: 500;">{{ $booking->user->name }}</td>
                            <td>{{ $booking->court->court_name }}</td>
                            <td>{{ $booking->booking_date->format('M d') }}</td>
                            <td><span class="badge badge-{{ $booking->status }}">{{ ucfirst($booking->status) }}</span></td>
                            <td>
                                @if($booking->status === 'pending')
                                    <form action="{{ route('admin.bookings.approve', $booking) }}" method="POST" style="display: inline;">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-success"><i class="fas fa-check"></i></button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="text-align: center; color: var(--text-secondary);">No bookings yet</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    
    <!-- Active Maintenances -->
    <div class="card">
        <div class="card-header">
            <h2 class="card-title"><i class="fas fa-wrench" style="color: var(--danger); margin-right: 0.5rem;"></i> Active Maintenances</h2>
            <a href="{{ route('admin.courts.index') }}" class="btn btn-sm btn-secondary">Manage</a>
        </div>
        
        @if($activeMaintenances->count() > 0)
            @foreach($activeMaintenances as $maintenance)
                <div style="padding: 1rem; background: var(--bg-card-hover); border-radius: var(--radius-md); margin-bottom: 0.75rem; border-left: 3px solid var(--danger);">
                    <div style="font-weight: 600;">{{ $maintenance->court->court_name }}</div>
                    <div style="font-size: 0.875rem; color: var(--text-secondary); margin: 0.5rem 0;">{{ $maintenance->description }}</div>
                    <div style="font-size: 0.75rem; color: var(--text-muted);">
                        {{ $maintenance->start_date->format('M d') }} - {{ $maintenance->end_date->format('M d, Y') }}
                    </div>
                </div>
            @endforeach
        @else
            <div class="empty-state" style="padding: 2rem;">
                <i class="fas fa-check-circle" style="color: var(--success);"></i>
                <p>No active maintenances</p>
            </div>
        @endif
    </div>
</div>
@endsection

@extends('layouts.app')

@section('title', 'Activity Logs')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
    <div>
        <h1 class="page-title">Activity Logs</h1>
        <p class="page-subtitle">Monitor all user activities in the system.</p>
    </div>
    <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Back
    </a>
</div>

<!-- Filter -->
<div class="card" style="margin-bottom: 1.5rem;">
    <form method="GET" action="{{ route('admin.activities.index') }}" style="display: flex; gap: 1rem; align-items: end;">
        <div class="form-group" style="margin-bottom: 0; flex: 1;">
            <label class="form-label">Activity Type</label>
            <select name="type" class="form-control">
                <option value="all">All Types</option>
                @foreach($activityTypes as $type)
                    <option value="{{ $type }}" {{ request('type') === $type ? 'selected' : '' }}>
                        {{ ucfirst(str_replace('_', ' ', $type)) }}
                    </option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="btn btn-primary">
            <i class="fas fa-filter"></i> Filter
        </button>
    </form>
</div>

<div class="card">
    @forelse($activities as $activity)
        <div style="display: flex; align-items: flex-start; gap: 1rem; padding: 1.25rem; border-bottom: 1px solid var(--border-color);">
            <div style="width: 44px; height: 44px; background: var(--bg-card-hover); border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                @switch($activity->activity_type)
                    @case('login')
                        <i class="fas fa-sign-in-alt" style="color: var(--success);"></i>
                        @break
                    @case('logout')
                        <i class="fas fa-sign-out-alt" style="color: var(--text-muted);"></i>
                        @break
                    @case('booking_created')
                        <i class="fas fa-calendar-plus" style="color: var(--primary);"></i>
                        @break
                    @case('booking_approved')
                        <i class="fas fa-check-circle" style="color: var(--success);"></i>
                        @break
                    @case('booking_rejected')
                        <i class="fas fa-times-circle" style="color: var(--danger);"></i>
                        @break
                    @case('booking_cancelled')
                        <i class="fas fa-calendar-times" style="color: var(--danger);"></i>
                        @break
                    @case('registration')
                        <i class="fas fa-user-plus" style="color: var(--secondary);"></i>
                        @break
                    @default
                        <i class="fas fa-circle-dot" style="color: var(--text-muted);"></i>
                @endswitch
            </div>
            <div style="flex: 1;">
                <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.25rem;">
                    <span style="font-weight: 600;">{{ $activity->user->name ?? 'Unknown' }}</span>
                    <span class="badge" style="background: var(--bg-card-hover); color: var(--text-secondary); font-size: 0.7rem;">
                        {{ ucfirst(str_replace('_', ' ', $activity->activity_type)) }}
                    </span>
                </div>
                @if($activity->description)
                    <div style="color: var(--text-secondary); font-size: 0.875rem;">{{ $activity->description }}</div>
                @endif
                <div style="font-size: 0.75rem; color: var(--text-muted); margin-top: 0.5rem;">
                    {{ $activity->activity_time->format('M d, Y \a\t g:i A') }}
                </div>
            </div>
        </div>
    @empty
        <div class="empty-state">
            <i class="fas fa-history"></i>
            <p>No activity logs found.</p>
        </div>
    @endforelse
    
    @if($activities->hasPages())
        <div class="pagination">
            {{ $activities->links() }}
        </div>
    @endif
</div>
@endsection

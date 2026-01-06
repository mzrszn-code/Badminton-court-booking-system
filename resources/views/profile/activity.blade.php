@extends('layouts.app')

@section('title', 'Activity Log')

@section('content')
<div style="margin-bottom: 1rem;">
    <a href="{{ route('profile.show') }}" style="color: var(--text-secondary); text-decoration: none; font-size: 0.875rem;">
        <i class="fas fa-arrow-left"></i> Back to Profile
    </a>
</div>

<div class="page-header">
    <h1 class="page-title">Activity Log</h1>
    <p class="page-subtitle">Your complete activity history.</p>
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
                    @case('booking_cancelled')
                        <i class="fas fa-calendar-times" style="color: var(--danger);"></i>
                        @break
                    @case('profile_updated')
                        <i class="fas fa-user-edit" style="color: var(--secondary);"></i>
                        @break
                    @default
                        <i class="fas fa-circle-dot" style="color: var(--text-muted);"></i>
                @endswitch
            </div>
            <div style="flex: 1;">
                <div style="font-weight: 600; margin-bottom: 0.25rem;">{{ ucfirst(str_replace('_', ' ', $activity->activity_type)) }}</div>
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
            <p>No activity recorded yet.</p>
        </div>
    @endforelse
    
    @if($activities->hasPages())
        <div class="pagination">
            {{ $activities->links() }}
        </div>
    @endif
</div>
@endsection

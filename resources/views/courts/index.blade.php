@extends('layouts.app')

@section('title', 'Courts')

@section('content')
<!-- Hero Section -->
<section class="courts-hero">
    <h1 class="page-title" style="font-size: 2.5rem;">Available Courts</h1>
    <p class="page-subtitle">Browse and book from our selection of badminton courts.</p>
</section>

<div class="grid grid-cols-3">
    @forelse($courts as $court)
        <div class="card">
            @if($court->image)
                <div style="height: 160px; margin: -1.5rem -1.5rem 1rem -1.5rem; border-radius: var(--radius-lg) var(--radius-lg) 0 0; overflow: hidden;">
                    <img src="{{ asset($court->image) }}" alt="{{ $court->court_name }}" style="width: 100%; height: 100%; object-fit: cover;">
                </div>
            @endif
            <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1rem;">
                <div style="width: 60px; height: 60px; background: {{ $court->status === 'available' ? 'var(--gradient-primary)' : ($court->status === 'maintenance' ? 'var(--danger)' : 'var(--border-color)') }}; border-radius: var(--radius-md); display: flex; align-items: center; justify-content: center; color: white; font-weight: 700; font-size: 1.5rem;">
                    {{ strtoupper(substr($court->court_name, -1)) }}
                </div>
                <div>
                    <h3 style="font-weight: 700; font-size: 1.25rem;">{{ $court->court_name }}</h3>
                    <span class="badge badge-{{ $court->status }}">{{ ucfirst($court->status) }}</span>
                </div>
            </div>
            
            <div style="margin-bottom: 1rem;">
                <p style="color: var(--text-secondary); font-size: 0.875rem; margin-bottom: 0.5rem;">
                    <i class="fas fa-location-dot" style="width: 20px;"></i> {{ $court->location ?? 'Main Building' }}
                </p>
                <p style="color: var(--text-secondary); font-size: 0.875rem; margin-bottom: 0.5rem;">
                    <i class="fas fa-tag" style="width: 20px;"></i> {{ ucfirst($court->court_type) }} Court
                </p>
                <p style="color: var(--text-secondary); font-size: 0.875rem;">
                    <i class="fas fa-calendar-check" style="width: 20px;"></i> {{ $court->bookings_count ?? 0 }} upcoming bookings
                </p>
            </div>
            
            <p style="color: var(--text-secondary); font-size: 0.875rem; margin-bottom: 1.5rem; min-height: 40px;">
                {{ Str::limit($court->description, 100) ?? 'Standard badminton court with quality flooring.' }}
            </p>
            
            <div style="display: flex; justify-content: space-between; align-items: center; padding-top: 1rem; border-top: 1px solid var(--border-color);">
                <span style="font-size: 1.5rem; font-weight: 800; color: var(--primary);">
                    RM {{ number_format($court->hourly_rate, 2) }}
                    <span style="font-size: 0.75rem; color: var(--text-secondary); font-weight: normal;">/hour</span>
                </span>
                @if($court->status === 'available')
                    <a href="{{ route('bookings.create', ['court' => $court->id]) }}" class="btn btn-primary">
                        <i class="fas fa-calendar-plus"></i> Book Now
                    </a>
                @else
                    <span class="btn btn-secondary" style="opacity: 0.5; cursor: not-allowed;">Unavailable</span>
                @endif
            </div>
        </div>
    @empty
        <div class="card" style="grid-column: span 3; text-align: center; padding: 3rem;">
            <i class="fas fa-table-tennis-paddle-ball" style="font-size: 3rem; color: var(--text-muted); margin-bottom: 1rem;"></i>
            <h3 style="margin-bottom: 0.5rem;">No Courts Available</h3>
            <p style="color: var(--text-secondary);">Please check back later.</p>
        </div>
    @endforelse
</div>
@endsection

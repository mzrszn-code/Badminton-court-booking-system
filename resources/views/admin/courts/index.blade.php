@extends('layouts.app')

@section('title', 'Manage Courts')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
    <div>
        <h1 class="page-title">Manage Courts</h1>
        <p class="page-subtitle">Add, edit, or manage court availability.</p>
    </div>
    <div style="display: flex; gap: 1rem;">
        <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back
        </a>
        <a href="{{ route('admin.courts.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Add Court
        </a>
    </div>
</div>

<div class="grid grid-cols-3">
    @forelse($courts as $court)
        <div class="card">
            <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1rem;">
                <div style="width: 50px; height: 50px; background: {{ $court->status === 'available' ? 'var(--gradient-primary)' : ($court->status === 'maintenance' ? 'var(--danger)' : 'var(--border-color)') }}; border-radius: var(--radius-md); display: flex; align-items: center; justify-content: center; color: white; font-weight: 700; font-size: 1.25rem;">
                    {{ strtoupper(substr($court->court_name, -1)) }}
                </div>
                <div>
                    <h3 style="font-weight: 700;">{{ $court->court_name }}</h3>
                    <span class="badge badge-{{ $court->status }}">{{ ucfirst($court->status) }}</span>
                </div>
            </div>
            
            <p style="color: var(--text-secondary); font-size: 0.875rem; margin-bottom: 0.5rem;">
                <i class="fas fa-location-dot" style="width: 16px;"></i> {{ $court->location ?? 'N/A' }}
            </p>
            <p style="color: var(--text-secondary); font-size: 0.875rem; margin-bottom: 0.5rem;">
                <i class="fas fa-tag" style="width: 16px;"></i> {{ ucfirst($court->court_type) }}
            </p>
            <p style="color: var(--text-secondary); font-size: 0.875rem; margin-bottom: 0.5rem;">
                <i class="fas fa-dollar-sign" style="width: 16px;"></i> RM {{ number_format($court->hourly_rate, 2) }}/hour
            </p>
            <p style="color: var(--text-secondary); font-size: 0.875rem; margin-bottom: 1rem;">
                <i class="fas fa-calendar-check" style="width: 16px;"></i> {{ $court->bookings_count }} bookings
            </p>
            
            <div style="display: flex; gap: 0.5rem; padding-top: 1rem; border-top: 1px solid var(--border-color);">
                <a href="{{ route('admin.courts.edit', $court) }}" class="btn btn-sm btn-secondary" style="flex: 1;">
                    <i class="fas fa-edit"></i> Edit
                </a>
                <form action="{{ route('admin.courts.toggle-maintenance', $court) }}" method="POST" style="flex: 1;">
                    @csrf
                    <button type="submit" class="btn btn-sm {{ $court->status === 'maintenance' ? 'btn-success' : 'btn-danger' }}" style="width: 100%;">
                        <i class="fas fa-{{ $court->status === 'maintenance' ? 'check' : 'wrench' }}"></i>
                        {{ $court->status === 'maintenance' ? 'Enable' : 'Disable' }}
                    </button>
                </form>
            </div>
        </div>
    @empty
        <div class="card" style="grid-column: span 3; text-align: center; padding: 3rem;">
            <i class="fas fa-table-tennis-paddle-ball" style="font-size: 3rem; color: var(--text-muted); margin-bottom: 1rem;"></i>
            <p style="color: var(--text-secondary); margin-bottom: 1rem;">No courts found</p>
            <a href="{{ route('admin.courts.create') }}" class="btn btn-primary">Add First Court</a>
        </div>
    @endforelse
</div>
@endsection

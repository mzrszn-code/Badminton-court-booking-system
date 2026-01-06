@extends('layouts.app')

@section('title', 'Court Calendar')

@push('styles')
<link href='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.css' rel='stylesheet' />
<style>
    .fc {
        background: var(--bg-card);
        border-radius: var(--radius-lg);
        padding: 1rem;
    }
    .fc-theme-standard td, .fc-theme-standard th {
        border-color: var(--border-color);
    }
    .fc-theme-standard .fc-scrollgrid {
        border-color: var(--border-color);
    }
    .fc .fc-button-primary {
        background: var(--primary);
        border-color: var(--primary);
    }
    .fc .fc-button-primary:hover {
        background: var(--primary-dark);
        border-color: var(--primary-dark);
    }
    .fc .fc-button-primary:disabled {
        background: var(--text-muted);
        border-color: var(--text-muted);
    }
    .fc .fc-daygrid-day-number {
        color: var(--text-primary);
    }
    .fc .fc-col-header-cell-cushion {
        color: var(--text-secondary);
    }
    .fc-day-today {
        background: rgba(99, 102, 241, 0.1) !important;
    }
    .fc-event {
        cursor: pointer;
        font-size: 0.75rem;
        padding: 2px 4px;
    }
</style>
@endpush

@section('content')
<div class="page-header">
    <h1 class="page-title">Court Schedule</h1>
    <p class="page-subtitle">View all court bookings in calendar format.</p>
</div>

<div class="grid grid-cols-4" style="grid-template-columns: 1fr 280px;">
    <!-- Calendar -->
    <div class="card">
        <div id="calendar"></div>
    </div>
    
    <!-- Filters & Legend -->
    <div>
        <div class="card" style="margin-bottom: 1.5rem;">
            <div class="card-header">
                <h2 class="card-title"><i class="fas fa-filter" style="color: var(--primary); margin-right: 0.5rem;"></i> Filter</h2>
            </div>
            
            <div class="form-group">
                <label class="form-label">Court</label>
                <select id="courtFilter" class="form-control">
                    <option value="">All Courts</option>
                    @foreach($courts as $court)
                        <option value="{{ $court->id }}">{{ $court->court_name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        
        <div class="card" style="margin-bottom: 1.5rem;">
            <div class="card-header">
                <h2 class="card-title"><i class="fas fa-info-circle" style="color: var(--secondary); margin-right: 0.5rem;"></i> Legend</h2>
            </div>
            
            <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                <div style="display: flex; align-items: center; gap: 0.75rem;">
                    <div style="width: 24px; height: 24px; background: #10b981; border-radius: 4px;"></div>
                    <span style="font-size: 0.875rem;">Approved Booking</span>
                </div>
                <div style="display: flex; align-items: center; gap: 0.75rem;">
                    <div style="width: 24px; height: 24px; background: #f59e0b; border-radius: 4px;"></div>
                    <span style="font-size: 0.875rem;">Pending Approval</span>
                </div>
            </div>
        </div>
        
        @auth
            <a href="{{ route('bookings.create') }}" class="btn btn-primary btn-lg" style="width: 100%;">
                <i class="fas fa-plus"></i> New Booking
            </a>
        @else
            <a href="{{ route('login') }}" class="btn btn-primary btn-lg" style="width: 100%;">
                <i class="fas fa-sign-in-alt"></i> Login to Book
            </a>
        @endauth
    </div>
</div>

@push('scripts')
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js'></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const calendarEl = document.getElementById('calendar');
    const courtFilter = document.getElementById('courtFilter');
    
    const calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay'
        },
        events: function(info, successCallback, failureCallback) {
            const courtId = courtFilter.value;
            let url = `/calendar/events?start=${info.startStr}&end=${info.endStr}`;
            if (courtId) {
                url += `&court_id=${courtId}`;
            }
            
            fetch(url)
                .then(response => response.json())
                .then(events => successCallback(events))
                .catch(error => failureCallback(error));
        },
        eventClick: function(info) {
            alert(`Court: ${info.event.extendedProps.court}\nStatus: ${info.event.extendedProps.status.charAt(0).toUpperCase() + info.event.extendedProps.status.slice(1)}`);
        },
        height: 'auto'
    });
    
    calendar.render();
    
    courtFilter.addEventListener('change', function() {
        calendar.refetchEvents();
    });
});
</script>
@endpush
@endsection

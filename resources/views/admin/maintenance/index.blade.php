@extends('layouts.app')

@section('title', 'Maintenance')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
    <div>
        <h1 class="page-title">Court Maintenance</h1>
        <p class="page-subtitle">Schedule and manage court maintenance periods.</p>
    </div>
    <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Back
    </a>
</div>

<div class="grid grid-cols-2" style="grid-template-columns: 400px 1fr;">
    <!-- Schedule Form -->
    <div class="card">
        <div class="card-header">
            <h2 class="card-title"><i class="fas fa-plus" style="color: var(--primary); margin-right: 0.5rem;"></i> Schedule Maintenance</h2>
        </div>
        
        <form method="POST" action="{{ route('admin.maintenance.store') }}">
            @csrf
            
            <div class="form-group">
                <label class="form-label">Court *</label>
                <select name="court_id" class="form-control" required>
                    <option value="">Select a court...</option>
                    @foreach($courts as $court)
                        <option value="{{ $court->id }}">{{ $court->court_name }}</option>
                    @endforeach
                </select>
            </div>
            
            <div class="form-group">
                <label class="form-label">Start Date *</label>
                <input type="date" name="start_date" class="form-control" min="{{ now()->format('Y-m-d') }}" required>
            </div>
            
            <div class="form-group">
                <label class="form-label">End Date *</label>
                <input type="date" name="end_date" class="form-control" min="{{ now()->format('Y-m-d') }}" required>
            </div>
            
            <div class="form-group">
                <label class="form-label">Description *</label>
                <textarea name="description" class="form-control" rows="3" placeholder="Reason for maintenance..." required></textarea>
            </div>
            
            <button type="submit" class="btn btn-primary" style="width: 100%;">
                <i class="fas fa-calendar-plus"></i> Schedule Maintenance
            </button>
        </form>
    </div>
    
    <!-- Maintenance List -->
    <div class="card">
        <div class="card-header">
            <h2 class="card-title"><i class="fas fa-wrench" style="color: var(--danger); margin-right: 0.5rem;"></i> Maintenance History</h2>
        </div>
        
        <div class="table-container" style="border: none;">
            <table>
                <thead>
                    <tr>
                        <th>Court</th>
                        <th>Period</th>
                        <th>Description</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($maintenances as $maintenance)
                        <tr>
                            <td style="font-weight: 500;">{{ $maintenance->court->court_name }}</td>
                            <td>{{ $maintenance->start_date->format('M d') }} - {{ $maintenance->end_date->format('M d, Y') }}</td>
                            <td>{{ Str::limit($maintenance->description, 50) }}</td>
                            <td>
                                @if($maintenance->status === 'completed')
                                    <span class="badge badge-completed">Completed</span>
                                @elseif($maintenance->status === 'in_progress')
                                    <span class="badge badge-pending">In Progress</span>
                                @else
                                    <span class="badge badge-available">Scheduled</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" style="text-align: center; color: var(--text-secondary);">No maintenance records</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($maintenances->hasPages())
            <div class="pagination">
                {{ $maintenances->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

@extends('layouts.app')

@section('title', 'Edit Court')

@section('content')
<div style="margin-bottom: 1rem;">
    <a href="{{ route('admin.courts.index') }}" style="color: var(--text-secondary); text-decoration: none; font-size: 0.875rem;">
        <i class="fas fa-arrow-left"></i> Back to Courts
    </a>
</div>

<div class="page-header">
    <h1 class="page-title">Edit {{ $court->court_name }}</h1>
    <p class="page-subtitle">Update court information and settings.</p>
</div>

<div class="card" style="max-width: 600px;">
    <form method="POST" action="{{ route('admin.courts.update', $court) }}">
        @csrf
        @method('PUT')
        
        <div class="form-group">
            <label class="form-label">Court Name *</label>
            <input type="text" name="court_name" class="form-control" value="{{ old('court_name', $court->court_name) }}" required>
        </div>
        
        <div class="form-group">
            <label class="form-label">Court Type *</label>
            <select name="court_type" class="form-control" required>
                <option value="standard" {{ old('court_type', $court->court_type) === 'standard' ? 'selected' : '' }}>Standard</option>
                <option value="premium" {{ old('court_type', $court->court_type) === 'premium' ? 'selected' : '' }}>Premium</option>
                <option value="vip" {{ old('court_type', $court->court_type) === 'vip' ? 'selected' : '' }}>VIP</option>
            </select>
        </div>
        
        <div class="form-group">
            <label class="form-label">Status *</label>
            <select name="status" class="form-control" required>
                <option value="available" {{ old('status', $court->status) === 'available' ? 'selected' : '' }}>Available</option>
                <option value="booked" {{ old('status', $court->status) === 'booked' ? 'selected' : '' }}>Booked</option>
                <option value="maintenance" {{ old('status', $court->status) === 'maintenance' ? 'selected' : '' }}>Maintenance</option>
            </select>
        </div>
        
        <div class="form-group">
            <label class="form-label">Location</label>
            <input type="text" name="location" class="form-control" value="{{ old('location', $court->location) }}">
        </div>
        
        <div class="form-group">
            <label class="form-label">Hourly Rate (RM) *</label>
            <input type="number" name="hourly_rate" class="form-control" step="0.01" min="0" value="{{ old('hourly_rate', $court->hourly_rate) }}" required>
        </div>
        
        <div class="form-group">
            <label class="form-label">Description</label>
            <textarea name="description" class="form-control" rows="3">{{ old('description', $court->description) }}</textarea>
        </div>
        
        <div style="display: flex; gap: 1rem;">
            <button type="submit" class="btn btn-primary" style="flex: 1;">
                <i class="fas fa-save"></i> Save Changes
            </button>
            <form action="{{ route('admin.courts.delete', $court) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this court?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">
                    <i class="fas fa-trash"></i>
                </button>
            </form>
        </div>
    </form>
</div>
@endsection

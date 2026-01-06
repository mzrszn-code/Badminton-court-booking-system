@extends('layouts.app')

@section('title', 'Add Court')

@section('content')
<div style="margin-bottom: 1rem;">
    <a href="{{ route('admin.courts.index') }}" style="color: var(--text-secondary); text-decoration: none; font-size: 0.875rem;">
        <i class="fas fa-arrow-left"></i> Back to Courts
    </a>
</div>

<div class="page-header">
    <h1 class="page-title">Add New Court</h1>
    <p class="page-subtitle">Create a new badminton court in the system.</p>
</div>

<div class="card" style="max-width: 600px;">
    <form method="POST" action="{{ route('admin.courts.store') }}">
        @csrf
        
        <div class="form-group">
            <label class="form-label">Court Name *</label>
            <input type="text" name="court_name" class="form-control" placeholder="e.g., Court F" value="{{ old('court_name') }}" required>
        </div>
        
        <div class="form-group">
            <label class="form-label">Court Type *</label>
            <select name="court_type" class="form-control" required>
                <option value="standard" {{ old('court_type') === 'standard' ? 'selected' : '' }}>Standard</option>
                <option value="premium" {{ old('court_type') === 'premium' ? 'selected' : '' }}>Premium</option>
                <option value="vip" {{ old('court_type') === 'vip' ? 'selected' : '' }}>VIP</option>
            </select>
        </div>
        
        <div class="form-group">
            <label class="form-label">Location</label>
            <input type="text" name="location" class="form-control" placeholder="e.g., Ground Floor - East Wing" value="{{ old('location') }}">
        </div>
        
        <div class="form-group">
            <label class="form-label">Hourly Rate (RM) *</label>
            <input type="number" name="hourly_rate" class="form-control" placeholder="25.00" step="0.01" min="0" value="{{ old('hourly_rate', '25.00') }}" required>
        </div>
        
        <div class="form-group">
            <label class="form-label">Description</label>
            <textarea name="description" class="form-control" rows="3" placeholder="Describe the court facilities...">{{ old('description') }}</textarea>
        </div>
        
        <button type="submit" class="btn btn-primary" style="width: 100%;">
            <i class="fas fa-plus"></i> Create Court
        </button>
    </form>
</div>
@endsection

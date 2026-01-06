@extends('layouts.app')

@section('title', 'My Profile')

@section('content')
<div class="page-header">
    <h1 class="page-title">My Profile</h1>
    <p class="page-subtitle">Manage your account settings and personal information.</p>
</div>

<div class="grid grid-cols-2" style="grid-template-columns: 1fr 400px;">
    <!-- Profile Form -->
    <div class="card">
        <div class="card-header">
            <h2 class="card-title"><i class="fas fa-user-edit" style="color: var(--primary); margin-right: 0.5rem;"></i> Account Settings</h2>
        </div>
        
        <form method="POST" action="{{ route('profile.update') }}">
            @csrf
            @method('PUT')
            
            <div class="form-group">
                <label class="form-label">Full Name</label>
                <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
            </div>
            
            <div class="form-group">
                <label class="form-label">Email Address</label>
                <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
            </div>
            
            <div class="form-group">
                <label class="form-label">Phone Number</label>
                <input type="tel" name="phone" class="form-control" value="{{ old('phone', $user->phone) }}" placeholder="e.g., 0123456789">
            </div>
            
            <hr style="border-color: var(--border-color); margin: 2rem 0;">
            
            <h3 style="font-weight: 600; margin-bottom: 1rem;">Change Password</h3>
            <p style="color: var(--text-secondary); font-size: 0.875rem; margin-bottom: 1.5rem;">Leave blank if you don't want to change your password.</p>
            
            <div class="form-group">
                <label class="form-label">Current Password</label>
                <input type="password" name="current_password" class="form-control" placeholder="Enter current password">
            </div>
            
            <div class="grid grid-cols-2">
                <div class="form-group">
                    <label class="form-label">New Password</label>
                    <input type="password" name="new_password" class="form-control" placeholder="Enter new password">
                </div>
                
                <div class="form-group">
                    <label class="form-label">Confirm New Password</label>
                    <input type="password" name="new_password_confirmation" class="form-control" placeholder="Confirm new password">
                </div>
            </div>
            
            <button type="submit" class="btn btn-primary" style="width: 100%;">
                <i class="fas fa-save"></i> Save Changes
            </button>
        </form>
    </div>
    
    <!-- Profile Summary & Activity -->
    <div>
        <div class="card" style="text-align: center; margin-bottom: 1.5rem;">
            <div style="width: 100px; height: 100px; background: var(--gradient-primary); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem; font-size: 3rem; font-weight: 800; color: white;">
                {{ strtoupper(substr($user->name, 0, 1)) }}
            </div>
            <h2 style="font-size: 1.5rem; font-weight: 700; margin-bottom: 0.25rem;">{{ $user->name }}</h2>
            <p style="color: var(--text-secondary);">{{ $user->email }}</p>
            <span class="badge badge-{{ $user->role === 'admin' ? 'approved' : 'available' }}" style="margin-top: 0.5rem;">
                {{ ucfirst($user->role) }}
            </span>
            
            <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 1rem; margin-top: 1.5rem; padding-top: 1.5rem; border-top: 1px solid var(--border-color);">
                <div>
                    <div style="font-size: 1.5rem; font-weight: 800; color: var(--primary);">{{ $user->bookings()->count() }}</div>
                    <div style="font-size: 0.75rem; color: var(--text-secondary);">Total Bookings</div>
                </div>
                <div>
                    <div style="font-size: 1.5rem; font-weight: 800; color: var(--success);">{{ $user->bookings()->where('status', 'completed')->count() }}</div>
                    <div style="font-size: 0.75rem; color: var(--text-secondary);">Completed</div>
                </div>
            </div>
        </div>
        
        <div class="card">
            <div class="card-header">
                <h2 class="card-title"><i class="fas fa-history" style="color: var(--secondary); margin-right: 0.5rem;"></i> Recent Activity</h2>
                <a href="{{ route('profile.activity') }}" class="btn btn-sm btn-secondary">View All</a>
            </div>
            
            @forelse($recentActivity as $activity)
                <div style="display: flex; align-items: center; gap: 0.75rem; padding: 0.75rem 0; border-bottom: 1px solid var(--border-color);">
                    <div style="width: 36px; height: 36px; background: var(--bg-card-hover); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-circle-dot" style="color: var(--primary); font-size: 0.75rem;"></i>
                    </div>
                    <div style="flex: 1;">
                        <div style="font-size: 0.875rem; font-weight: 500;">{{ ucfirst(str_replace('_', ' ', $activity->activity_type)) }}</div>
                        <div style="font-size: 0.75rem; color: var(--text-muted);">{{ $activity->activity_time->diffForHumans() }}</div>
                    </div>
                </div>
            @empty
                <p style="color: var(--text-secondary); text-align: center; padding: 1rem;">No recent activity</p>
            @endforelse
        </div>
    </div>
</div>
@endsection

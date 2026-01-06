@extends('layouts.app')

@section('title', 'Register')

@section('content')
<div style="max-width: 500px; margin: 2rem auto;">
    <div class="card glassmorphism" style="padding: 2.5rem;">
        <div style="text-align: center; margin-bottom: 2rem;">
            <div style="width: 70px; height: 70px; background: var(--gradient-primary); border-radius: var(--radius-lg); display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem; font-size: 2rem; color: white;">
                <i class="fas fa-user-plus"></i>
            </div>
            <h1 style="font-size: 1.75rem; font-weight: 800; margin-bottom: 0.5rem;">Create Account</h1>
            <p style="color: var(--text-secondary);">Join us and start booking courts today</p>
        </div>
        
        <form method="POST" action="{{ route('register') }}">
            @csrf
            
            <div class="form-group">
                <label class="form-label">Full Name</label>
                <input type="text" name="name" class="form-control" placeholder="Enter your full name" value="{{ old('name') }}" required autofocus>
            </div>
            
            <div class="form-group">
                <label class="form-label">Email Address</label>
                <input type="email" name="email" class="form-control" placeholder="Enter your email" value="{{ old('email') }}" required>
            </div>
            
            <div class="form-group">
                <label class="form-label">Phone Number <span style="color: var(--text-muted);">(optional)</span></label>
                <input type="tel" name="phone" class="form-control" placeholder="e.g., 0123456789" value="{{ old('phone') }}">
            </div>
            
            <div class="form-group">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" placeholder="Create a password (min 8 characters)" required>
            </div>
            
            <div class="form-group">
                <label class="form-label">Confirm Password</label>
                <input type="password" name="password_confirmation" class="form-control" placeholder="Confirm your password" required>
            </div>
            
            <button type="submit" class="btn btn-primary btn-lg" style="width: 100%; margin-top: 1rem;">
                <i class="fas fa-rocket"></i> Create Account
            </button>
        </form>
        
        <p style="text-align: center; margin-top: 1.5rem; color: var(--text-secondary); font-size: 0.875rem;">
            Already have an account? <a href="{{ route('login') }}" style="color: var(--primary); text-decoration: none; font-weight: 600;">Sign in here</a>
        </p>
    </div>
</div>
@endsection

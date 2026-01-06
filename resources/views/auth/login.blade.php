@extends('layouts.app')

@section('title', 'Login')

@section('content')
<div style="max-width: 450px; margin: 2rem auto;">
    <div class="card glassmorphism" style="padding: 2.5rem;">
        <div style="text-align: center; margin-bottom: 2rem;">
            <div style="width: 70px; height: 70px; background: var(--gradient-primary); border-radius: var(--radius-lg); display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem; font-size: 2rem; color: white;">
                <i class="fas fa-shuttle-space"></i>
            </div>
            <h1 style="font-size: 1.75rem; font-weight: 800; margin-bottom: 0.5rem;">Welcome Back</h1>
            <p style="color: var(--text-secondary);">Sign in to your account to continue</p>
        </div>
        
        <form method="POST" action="{{ route('login') }}">
            @csrf
            
            <div class="form-group">
                <label class="form-label">Email Address</label>
                <input type="email" name="email" class="form-control" placeholder="Enter your email" value="{{ old('email') }}" required autofocus>
            </div>
            
            <div class="form-group">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" placeholder="Enter your password" required>
            </div>
            
            <div class="form-group" style="display: flex; align-items: center; gap: 0.5rem;">
                <input type="checkbox" name="remember" id="remember" style="width: 18px; height: 18px; accent-color: var(--primary);">
                <label for="remember" style="color: var(--text-secondary); font-size: 0.875rem; cursor: pointer;">Remember me</label>
            </div>
            
            <button type="submit" class="btn btn-primary btn-lg" style="width: 100%; margin-top: 1rem;">
                <i class="fas fa-sign-in-alt"></i> Sign In
            </button>
        </form>
        
        <p style="text-align: center; margin-top: 1.5rem; color: var(--text-secondary); font-size: 0.875rem;">
            Don't have an account? <a href="{{ route('register') }}" style="color: var(--primary); text-decoration: none; font-weight: 600;">Register here</a>
        </p>
    </div>
@endsection

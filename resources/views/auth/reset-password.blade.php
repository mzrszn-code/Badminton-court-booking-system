@extends('layouts.app')

@section('title', 'Reset Password')

@section('content')
<div style="max-width: 450px; margin: 2rem auto;">
    <div class="card glassmorphism" style="padding: 2.5rem;">
        <div style="text-align: center; margin-bottom: 2rem;">
            <div style="width: 70px; height: 70px; background: var(--gradient-success); border-radius: var(--radius-lg); display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem; font-size: 2rem; color: white;">
                <i class="fas fa-lock-open"></i>
            </div>
            <h1 style="font-size: 1.75rem; font-weight: 800; margin-bottom: 0.5rem;">Reset Password</h1>
            <p style="color: var(--text-secondary);">Create a new secure password for your account</p>
        </div>
        
        <form method="POST" action="{{ route('password.update') }}">
            @csrf
            
            <!-- Password Reset Token -->
            <input type="hidden" name="token" value="{{ $token }}">
            
            <div class="form-group">
                <label class="form-label">Email Address</label>
                <input type="email" name="email" class="form-control" placeholder="Enter your email" value="{{ old('email', $email ?? '') }}" required autofocus>
            </div>
            
            <div class="form-group">
                <label class="form-label">New Password</label>
                <div style="position: relative;">
                    <input type="password" name="password" id="password" class="form-control" placeholder="Enter new password (min. 8 characters)" required>
                    <button type="button" onclick="togglePassword('password', 'toggleIcon1')" style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%); background: none; border: none; color: var(--text-muted); cursor: pointer; padding: 0;">
                        <i class="fas fa-eye" id="toggleIcon1"></i>
                    </button>
                </div>
            </div>
            
            <div class="form-group">
                <label class="form-label">Confirm Password</label>
                <div style="position: relative;">
                    <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" placeholder="Confirm your new password" required>
                    <button type="button" onclick="togglePassword('password_confirmation', 'toggleIcon2')" style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%); background: none; border: none; color: var(--text-muted); cursor: pointer; padding: 0;">
                        <i class="fas fa-eye" id="toggleIcon2"></i>
                    </button>
                </div>
            </div>
            
            <!-- Password Requirements Info -->
            <div style="background: rgba(99, 102, 241, 0.1); border: 1px solid rgba(99, 102, 241, 0.3); border-radius: var(--radius-sm); padding: 1rem; margin-bottom: 1rem;">
                <p style="font-size: 0.75rem; color: var(--text-secondary); margin-bottom: 0.5rem;">
                    <i class="fas fa-info-circle" style="color: var(--primary);"></i> Password requirements:
                </p>
                <ul style="font-size: 0.75rem; color: var(--text-muted); margin: 0; padding-left: 1.25rem;">
                    <li>At least 8 characters long</li>
                    <li>Both passwords must match</li>
                </ul>
            </div>
            
            <button type="submit" class="btn btn-primary btn-lg" style="width: 100%; margin-top: 1rem;">
                <i class="fas fa-save"></i> Reset Password
            </button>
        </form>
        
        <div style="text-align: center; margin-top: 2rem; padding-top: 1.5rem; border-top: 1px solid var(--border-color);">
            <a href="{{ route('login') }}" style="color: var(--text-secondary); text-decoration: none; font-size: 0.875rem; display: inline-flex; align-items: center; gap: 0.5rem; transition: var(--transition-fast);" onmouseover="this.style.color='var(--primary)'" onmouseout="this.style.color='var(--text-secondary)'">
                <i class="fas fa-arrow-left"></i> Back to Login
            </a>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function togglePassword(inputId, iconId) {
        const passwordInput = document.getElementById(inputId);
        const toggleIcon = document.getElementById(iconId);
        
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            toggleIcon.classList.remove('fa-eye');
            toggleIcon.classList.add('fa-eye-slash');
        } else {
            passwordInput.type = 'password';
            toggleIcon.classList.remove('fa-eye-slash');
            toggleIcon.classList.add('fa-eye');
        }
    }
</script>
@endpush
@endsection

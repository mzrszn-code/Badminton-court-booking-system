@extends('layouts.app')

@section('title', 'Booking Details')

@section('content')
<div style="margin-bottom: 1rem;">
    <a href="{{ route('bookings.index') }}" style="color: var(--text-secondary); text-decoration: none; font-size: 0.875rem;">
        <i class="fas fa-arrow-left"></i> Back to My Bookings
    </a>
</div>

@php
    $hours = \Carbon\Carbon::parse($booking->start_time)->diffInHours(\Carbon\Carbon::parse($booking->end_time));
    $total = $booking->court->hourly_rate * $hours;
    $paymentCompleted = $booking->payment && $booking->payment->payment_status === 'completed';
    $paymentPending = $booking->payment && $booking->payment->payment_status === 'pending';
@endphp

<div class="grid grid-cols-2" style="grid-template-columns: 1fr 350px;">
    <!-- Booking Details -->
    <div class="card">
        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 2rem;">
            <div>
                <h1 style="font-size: 1.75rem; font-weight: 800;">Booking #{{ $booking->id }}</h1>
                <p style="color: var(--text-secondary);">Created {{ $booking->created_at->diffForHumans() }}</p>
            </div>
            <span class="badge badge-{{ $booking->status }}" style="font-size: 1rem; padding: 0.5rem 1rem;">
                {{ ucfirst($booking->status) }}
            </span>
        </div>
        
        <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 1.5rem; margin-bottom: 2rem;">
            <div style="padding: 1.5rem; background: var(--bg-card-hover); border-radius: var(--radius-md);">
                <div style="font-size: 0.75rem; color: var(--text-secondary); text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.5rem;">Court</div>
                <div style="font-size: 1.25rem; font-weight: 700;">{{ $booking->court->court_name }}</div>
                <div style="font-size: 0.875rem; color: var(--text-secondary);">{{ $booking->court->location }}</div>
            </div>
            
            <div style="padding: 1.5rem; background: var(--bg-card-hover); border-radius: var(--radius-md);">
                <div style="font-size: 0.75rem; color: var(--text-secondary); text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.5rem;">Date</div>
                <div style="font-size: 1.25rem; font-weight: 700;">{{ $booking->booking_date->format('l') }}</div>
                <div style="font-size: 0.875rem; color: var(--text-secondary);">{{ $booking->booking_date->format('F d, Y') }}</div>
            </div>
            
            <div style="padding: 1.5rem; background: var(--bg-card-hover); border-radius: var(--radius-md);">
                <div style="font-size: 0.75rem; color: var(--text-secondary); text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.5rem;">Time</div>
                <div style="font-size: 1.25rem; font-weight: 700;">
                    {{ \Carbon\Carbon::parse($booking->start_time)->format('g:i A') }} - {{ \Carbon\Carbon::parse($booking->end_time)->format('g:i A') }}
                </div>
                <div style="font-size: 0.875rem; color: var(--text-secondary);">{{ $hours }} hour(s)</div>
            </div>
            
            <div style="padding: 1.5rem; background: var(--bg-card-hover); border-radius: var(--radius-md);">
                <div style="font-size: 0.75rem; color: var(--text-secondary); text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.5rem;">Total Cost</div>
                <div style="font-size: 1.25rem; font-weight: 700; color: var(--primary);">
                    RM {{ number_format($total, 2) }}
                </div>
                <div style="font-size: 0.875rem; color: var(--text-secondary);">@ RM {{ number_format($booking->court->hourly_rate, 2) }}/hour</div>
            </div>
        </div>
        
        @if($booking->notes)
            <div style="margin-bottom: 2rem;">
                <h3 style="font-weight: 600; margin-bottom: 0.5rem;">Notes</h3>
                <p style="color: var(--text-secondary); background: var(--bg-card-hover); padding: 1rem; border-radius: var(--radius-md);">{{ $booking->notes }}</p>
        </div>
        @endif
        
        <!-- Payment Status Alert -->
        @if($paymentCompleted)
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i>
                <div>
                    <strong>Payment Verified</strong>
                    <p style="font-size: 0.875rem; margin-top: 0.25rem;">Your booking is confirmed!</p>
                </div>
            </div>
        @elseif($paymentPending)
            <div class="alert alert-warning">
                <i class="fas fa-hourglass-half"></i>
                <div>
                    <strong>Payment Pending Verification</strong>
                    <p style="font-size: 0.875rem; margin-top: 0.25rem;">Please wait while admin verifies your payment.</p>
                </div>
            </div>
        @endif
        
        @if($booking->checkin && $booking->checkin->is_checked_in)
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i>
                <div>
                    <strong>Checked In!</strong>
                    <p style="font-size: 0.875rem; margin-top: 0.25rem;">Checked in at {{ $booking->checkin->checkin_time->format('g:i A') }}</p>
                </div>
            </div>
        @endif
    </div>
    
    <!-- QR Code Panel -->
    <div>
        @if($booking->status === 'approved' && $booking->checkin && $paymentCompleted)
            <div class="card" style="text-align: center;">
                <h2 class="card-title" style="margin-bottom: 1.5rem;">
                    <i class="fas fa-qrcode" style="color: var(--primary);"></i> Check-In QR Code
                </h2>
                
                <div style="background: white; padding: 1.5rem; border-radius: var(--radius-md); display: inline-block; margin-bottom: 1.5rem;">
                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data={{ urlencode(route('checkin.verify', $booking->checkin->qr_code)) }}" 
                         alt="QR Code" style="display: block;">
                </div>
                
                <p style="color: var(--text-secondary); font-size: 0.875rem; margin-bottom: 1rem;">
                    Show this QR code at the facility for quick check-in.
                </p>
                
                <div style="background: var(--bg-card-hover); padding: 1rem; border-radius: var(--radius-md);">
                    <div style="font-size: 0.75rem; color: var(--text-muted); margin-bottom: 0.25rem;">Booking Code</div>
                    <div style="font-family: monospace; font-size: 0.875rem; word-break: break-all;">{{ $booking->checkin->qr_code }}</div>
                </div>
            </div>
        @elseif($booking->status === 'approved' && $booking->payment && $booking->payment->payment_status === 'pending')
            <!-- Payment Pending Verification -->
            <div class="card" style="text-align: center;">
                <div style="width: 80px; height: 80px; background: var(--gradient-accent); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem;">
                    <i class="fas fa-hourglass-half" style="font-size: 2rem; color: white;"></i>
                </div>
                <h2 class="card-title" style="margin-bottom: 0.5rem;">Payment Pending</h2>
                <p style="color: var(--text-secondary);">Your payment is being verified by admin. QR code will be available once verified.</p>
                <div style="margin-top: 1rem; padding: 0.75rem; background: var(--bg-card-hover); border-radius: var(--radius-sm);">
                    <div style="font-size: 0.75rem; color: var(--text-muted);">Reference</div>
                    <div style="font-weight: 600;">{{ $booking->payment->reference_number }}</div>
                </div>
            </div>
        @elseif($booking->status === 'approved' && (!$booking->payment || $booking->payment->payment_status === 'rejected'))
            <!-- Payment Required or Rejected -->
            <div class="card" style="text-align: center;">
                <div style="width: 80px; height: 80px; background: var(--gradient-accent); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem;">
                    <i class="fas fa-credit-card" style="font-size: 2rem; color: white;"></i>
                </div>
                @if($booking->payment && $booking->payment->payment_status === 'rejected')
                    <h2 class="card-title" style="margin-bottom: 0.5rem; color: var(--danger);">Payment Rejected</h2>
                    <p style="color: var(--text-secondary);">Please resubmit your payment.</p>
                @else
                    <h2 class="card-title" style="margin-bottom: 0.5rem;">Payment Required</h2>
                    <p style="color: var(--text-secondary);">Complete payment to get your check-in QR code.</p>
                @endif
                <a href="{{ route('payments.show', $booking) }}" class="btn btn-primary" style="margin-top: 1rem; width: 100%;">
                    <i class="fas fa-credit-card"></i> Make Payment
                </a>
            </div>
        @elseif($booking->status === 'pending')
            <div class="card" style="text-align: center;">
                <div style="width: 80px; height: 80px; background: var(--gradient-accent); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem;">
                    <i class="fas fa-clock" style="font-size: 2rem; color: white;"></i>
                </div>
                <h2 class="card-title" style="margin-bottom: 0.5rem;">Awaiting Approval</h2>
                <p style="color: var(--text-secondary);">Your booking is pending admin approval. You'll receive a notification once it's approved.</p>
            </div>
        @elseif($booking->status === 'rejected')
            <div class="card" style="text-align: center;">
                <div style="width: 80px; height: 80px; background: var(--danger); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem;">
                    <i class="fas fa-times" style="font-size: 2rem; color: white;"></i>
                </div>
                <h2 class="card-title" style="margin-bottom: 0.5rem;">Booking Rejected</h2>
                <p style="color: var(--text-secondary);">Unfortunately, this booking was rejected. Please try booking a different time slot.</p>
                <a href="{{ route('bookings.create') }}" class="btn btn-primary" style="margin-top: 1rem;">
                    <i class="fas fa-plus"></i> New Booking
                </a>
            </div>
        @elseif($booking->status === 'cancelled')
            <div class="card" style="text-align: center;">
                <div style="width: 80px; height: 80px; background: var(--text-muted); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem;">
                    <i class="fas fa-ban" style="font-size: 2rem; color: white;"></i>
                </div>
                <h2 class="card-title" style="margin-bottom: 0.5rem;">Booking Cancelled</h2>
                <p style="color: var(--text-secondary);">This booking has been cancelled.</p>
            </div>
        @endif
    </div>
</div>
@endsection

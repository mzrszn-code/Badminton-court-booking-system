@extends('layouts.app')

@section('title', 'Make Payment')

@section('content')
    <div style="margin-bottom: 1rem;">
        <a href="{{ route('bookings.show', $booking) }}"
            style="color: var(--text-secondary); text-decoration: none; font-size: 0.875rem;">
            <i class="fas fa-arrow-left"></i> Back to Booking
        </a>
    </div>

    <div class="page-header">
        <h1 class="page-title">Complete Payment</h1>
        <p class="page-subtitle">Make payment for booking #{{ $booking->id }}</p>
    </div>

    <div class="grid grid-cols-2" style="grid-template-columns: 1fr 400px;">
        <!-- Payment Instructions -->
        <div>
            <!-- Booking Summary -->
            <div class="card" style="margin-bottom: 1.5rem;">
                <div class="card-header">
                    <h2 class="card-title"><i class="fas fa-receipt"
                            style="color: var(--primary); margin-right: 0.5rem;"></i> Booking Summary</h2>
                </div>

                <div style="display: grid; gap: 0.75rem;">
                    <div
                        style="display: flex; justify-content: space-between; padding: 0.75rem; background: var(--bg-card-hover); border-radius: var(--radius-sm);">
                        <span style="color: var(--text-secondary);">Court:</span>
                        <span style="font-weight: 600;">{{ $booking->court->court_name }}</span>
                    </div>
                    <div
                        style="display: flex; justify-content: space-between; padding: 0.75rem; background: var(--bg-card-hover); border-radius: var(--radius-sm);">
                        <span style="color: var(--text-secondary);">Date:</span>
                        <span style="font-weight: 600;">{{ $booking->booking_date->format('D, M d, Y') }}</span>
                    </div>
                    <div
                        style="display: flex; justify-content: space-between; padding: 0.75rem; background: var(--bg-card-hover); border-radius: var(--radius-sm);">
                        <span style="color: var(--text-secondary);">Time:</span>
                        <span style="font-weight: 600;">{{ \Carbon\Carbon::parse($booking->start_time)->format('g:i A') }} -
                            {{ \Carbon\Carbon::parse($booking->end_time)->format('g:i A') }}</span>
                    </div>
                    <div
                        style="display: flex; justify-content: space-between; padding: 1rem; background: var(--gradient-primary); border-radius: var(--radius-sm); color: white;">
                        <span style="font-weight: 600;">Total Amount:</span>
                        <span style="font-size: 1.5rem; font-weight: 800;">RM {{ number_format($total, 2) }}</span>
                    </div>
                </div>
            </div>

            <!-- Payment Methods -->
            <div class="card">
                <div class="card-header">
                    <h2 class="card-title"><i class="fas fa-credit-card"
                            style="color: var(--secondary); margin-right: 0.5rem;"></i> Payment Methods</h2>
                </div>

                <div style="display: grid; gap: 1.5rem;">
                    <!-- Bank Transfer -->
                    <div
                        style="padding: 1.5rem; background: var(--bg-card-hover); border-radius: var(--radius-md); border: 2px solid var(--primary);">
                        <h3 style="font-weight: 700; margin-bottom: 1rem; display: flex; align-items: center; gap: 0.5rem;">
                            <i class="fas fa-building-columns" style="color: var(--primary);"></i> Bank Transfer
                        </h3>
                        <div style="display: grid; gap: 0.5rem;">
                            <div style="display: flex; justify-content: space-between;">
                                <span style="color: var(--text-secondary);">Bank Name:</span>
                                <span style="font-weight: 600;">{{ $bankDetails['bank_name'] }}</span>
                            </div>
                            <div style="display: flex; justify-content: space-between;">
                                <span style="color: var(--text-secondary);">Account Name:</span>
                                <span style="font-weight: 600;">{{ $bankDetails['account_name'] }}</span>
                            </div>
                            <div style="display: flex; justify-content: space-between;">
                                <span style="color: var(--text-secondary);">Account Number:</span>
                                <span
                                    style="font-weight: 700; font-size: 1.25rem; color: var(--primary); font-family: monospace;">{{ $bankDetails['account_number'] }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- QR Payment -->
                    <div style="padding: 1.5rem; background: var(--bg-card-hover); border-radius: var(--radius-md);">
                        <h3 style="font-weight: 700; margin-bottom: 1rem; display: flex; align-items: center; gap: 0.5rem;">
                            <i class="fas fa-qrcode" style="color: var(--success);"></i> QR Payment (DuitNow)
                        </h3>
                        <div style="text-align: center;">
                            <div
                                style="background: white; padding: 1rem; border-radius: var(--radius-md); display: inline-block; margin-bottom: 1rem;">
                                <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data={{ urlencode('00020101021229370016A000000615000001011011001234567890305' . number_format($total, 2) . '5802MY5925Flotilla Badminton Center6304') }}"
                                    alt="DuitNow QR" style="display: block;">
                            </div>
                            <p style="font-size: 0.875rem; color: var(--text-secondary);">Scan this QR code with your
                                banking app</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Payment Proof Form -->
        <div>
            @if($booking->payment && $booking->payment->payment_status === 'pending')
                <div class="alert alert-warning">
                    <i class="fas fa-clock"></i>
                    <div>
                        <strong>Payment Submitted</strong>
                        <p style="font-size: 0.875rem; margin-top: 0.25rem;">Your payment is pending admin verification.</p>
                    </div>
                </div>
            @elseif($booking->payment && $booking->payment->payment_status === 'completed')
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i>
                    <div>
                        <strong>Payment Confirmed!</strong>
                        <p style="font-size: 0.875rem; margin-top: 0.25rem;">Your payment has been verified.</p>
                    </div>
                </div>
            @elseif($booking->payment && $booking->payment->payment_status === 'rejected')
                <div class="alert alert-error" style="margin-bottom: 1rem;">
                    <i class="fas fa-times-circle"></i>
                    <div>
                        <strong>Payment Rejected</strong>
                        @if($booking->payment->admin_notes)
                            <p style="font-size: 0.875rem; margin-top: 0.25rem;">Reason: {{ $booking->payment->admin_notes }}</p>
                        @endif
                    </div>
                </div>
            @endif

            @if(!$booking->payment || $booking->payment->payment_status === 'rejected')
                <div class="card">
                    <div class="card-header">
                        <h2 class="card-title"><i class="fas fa-upload" style="color: var(--accent); margin-right: 0.5rem;"></i>
                            Submit Payment Proof</h2>
                    </div>

                    <form method="POST" action="{{ route('payments.submit', $booking) }}" enctype="multipart/form-data">
                        @csrf

                        <div class="form-group">
                            <label class="form-label">Payment Method *</label>
                            <select name="payment_method" class="form-control" required>
                                <option value="">Select method...</option>
                                <option value="bank_transfer">Bank Transfer</option>
                                <option value="qr_payment">QR Payment (DuitNow)</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Reference Number / Transaction ID *</label>
                            <input type="text" name="reference_number" class="form-control" placeholder="e.g., TXN12345678"
                                required>
                            <p style="font-size: 0.75rem; color: var(--text-muted); margin-top: 0.5rem;">Enter your bank
                                transfer reference or transaction ID</p>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Payment Receipt Screenshot *</label>
                            <input type="file" name="proof_image" class="form-control" accept="image/jpeg,image/png,image/jpg"
                                required>
                            <p style="font-size: 0.75rem; color: var(--text-muted); margin-top: 0.5rem;">Upload screenshot of
                                your payment receipt (JPEG, PNG, max 2MB)</p>
                        </div>

                        <button type="submit" class="btn btn-primary btn-lg" style="width: 100%;">
                            <i class="fas fa-paper-plane"></i> Submit Payment
                        </button>
                    </form>
                </div>
            @endif

            <div class="card" style="margin-top: 1.5rem;">
                <h3 style="font-weight: 600; margin-bottom: 1rem;"><i class="fas fa-info-circle"
                        style="color: var(--secondary); margin-right: 0.5rem;"></i> Important</h3>
                <ul style="color: var(--text-secondary); font-size: 0.875rem; line-height: 1.8; padding-left: 1.25rem;">
                    <li>Make sure to transfer the exact amount: <strong style="color: var(--primary);">RM
                            {{ number_format($total, 2) }}</strong></li>
                    <li>Upload a clear screenshot of your payment receipt</li>
                    <li>Payment verification may take up to 24 hours</li>
                    <li>Once payment is approved, booking cannot be cancelled</li>
                </ul>
            </div>
        </div>
    </div>
@endsection
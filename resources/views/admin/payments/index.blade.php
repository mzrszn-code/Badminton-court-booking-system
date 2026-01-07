@extends('layouts.app')

@section('title', 'Manage Payments')

@section('content')
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <div>
            <h1 class="page-title">Payment Verification</h1>
            <p class="page-subtitle">Review and verify customer payment submissions.</p>
        </div>
        <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>

    <!-- Filters -->
    <div class="card" style="margin-bottom: 1.5rem;">
        <form method="GET" action="{{ route('admin.payments.index') }}" style="display: flex; gap: 1rem; align-items: end;">
            <div class="form-group" style="margin-bottom: 0; flex: 1;">
                <label class="form-label">Status</label>
                <select name="status" class="form-control">
                    <option value="all" {{ request('status') === 'all' ? 'selected' : '' }}>All Statuses</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                    <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
                    <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-filter"></i> Filter
            </button>
        </form>
    </div>

    <div class="card">
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>User</th>
                        <th>Booking</th>
                        <th>Amount</th>
                        <th>Method</th>
                        <th>Reference</th>
                        <th>Status</th>
                        <th>Submitted</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($payments as $payment)
                        <tr>
                            <td style="font-weight: 600;">#{{ $payment->id }}</td>
                            <td>
                                <div style="font-weight: 500;">{{ $payment->booking->user->name }}</div>
                                <div style="font-size: 0.75rem; color: var(--text-secondary);">
                                    {{ $payment->booking->user->email }}</div>
                                <div style="font-size: 0.75rem; color: var(--text-secondary);">
                                    {{ $payment->booking->user->phone ?? 'No phone' }}</div>
                            </td>
                            <td>
                                <div style="font-weight: 500;">#{{ $payment->booking_id }} -
                                    {{ $payment->booking->court->court_name }}</div>
                                <div style="font-size: 0.75rem; color: var(--text-secondary);">
                                    {{ $payment->booking->booking_date->format('D, M d, Y') }}
                                </div>
                                <div style="font-size: 0.75rem; color: var(--text-secondary);">
                                    {{ \Carbon\Carbon::parse($payment->booking->start_time)->format('g:i A') }} -
                                    {{ \Carbon\Carbon::parse($payment->booking->end_time)->format('g:i A') }}
                                    ({{ \Carbon\Carbon::parse($payment->booking->start_time)->diffInHours(\Carbon\Carbon::parse($payment->booking->end_time)) }}hr)
                                </div>
                            </td>
                            <td style="font-weight: 700; color: var(--primary);">RM {{ number_format($payment->amount, 2) }}
                            </td>
                            <td>{{ ucwords(str_replace('_', ' ', $payment->payment_method)) }}</td>
                            <td style="font-family: monospace; font-size: 0.875rem;">{{ $payment->reference_number }}</td>
                            <td>
                                @if($payment->payment_status === 'pending')
                                    <span class="badge badge-pending">Pending</span>
                                @elseif($payment->payment_status === 'completed')
                                    <span class="badge badge-approved">Verified</span>
                                @elseif($payment->payment_status === 'rejected')
                                    <span class="badge badge-rejected">Rejected</span>
                                @elseif($payment->payment_status === 'cancelled')
                                    <span class="badge badge-cancelled">Cancelled</span>
                                @endif
                            </td>
                            <td>{{ $payment->created_at->diffForHumans() }}</td>
                            <td>
                                @if($payment->payment_status === 'pending')
                                    <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
                                        @if($payment->proof_image)
                                            <a href="{{ asset('storage/' . $payment->proof_image) }}" target="_blank"
                                                class="btn btn-sm btn-secondary" title="View Receipt">
                                                <i class="fas fa-image"></i>
                                            </a>
                                        @endif
                                        <form action="{{ route('admin.payments.approve', $payment) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-success" title="Approve">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        </form>
                                        <form action="{{ route('admin.payments.reject', $payment) }}" method="POST"
                                            onsubmit="return confirm('Reject this payment?');">
                                            @csrf
                                            <input type="hidden" name="admin_notes" value="Payment verification failed">
                                            <button type="submit" class="btn btn-sm btn-danger" title="Reject">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </form>
                                    </div>
                                @else
                                    @if($payment->proof_image)
                                        <a href="{{ asset('storage/' . $payment->proof_image) }}" target="_blank"
                                            class="btn btn-sm btn-secondary" title="View Receipt">
                                            <i class="fas fa-image"></i>
                                        </a>
                                    @else
                                        <span style="color: var(--text-muted);">—</span>
                                    @endif
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" style="text-align: center; padding: 3rem;">
                                <i class="fas fa-credit-card"
                                    style="font-size: 2rem; color: var(--text-muted); margin-bottom: 1rem; display: block;"></i>
                                <p style="color: var(--text-secondary);">No payments found</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($payments->hasPages())
            <div class="pagination">
                {{ $payments->links() }}
            </div>
        @endif
    </div>
@endsection
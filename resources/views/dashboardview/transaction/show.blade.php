@extends('layout.admin')

@push('css')
    <link rel="stylesheet" href="{{ asset('css/dashboardview/transaction/show.css') }}">
@endpush

@section('content')
<div class="container-fluid animate-fade">
    <div class="back-link-container">
        <a href="{{ route('transactions.index') }}" class="back-link">
            <div class="back-link-icon">
                <i class="fa-solid fa-arrow-left"></i>
            </div>
            BACK TO TRANSACTIONS
        </a>
    </div>

    <div class="receipt-container glass">
        <div class="receipt-header">
            <div class="receipt-title">
                <i class="fa-solid fa-file-invoice-dollar"></i>
                Transaction Details
            </div>
            <div>
                @php
                    $statusColor = [
                        'Completed' => 'var(--success)',
                        'Pending' => 'var(--warning)',
                        'Failed' => 'var(--danger)'
                    ][$transaction->status] ?? 'var(--text-dim)';
                @endphp
                <span class="status-badge" style="background: {{ $statusColor }}20; color: {{ $statusColor }}; border: 1px solid {{ $statusColor }}40; font-size: 0.9rem; padding: 0.4rem 1rem; border-radius: 50px; font-weight: 600;">
                    {{ $transaction->status }}
                </span>
            </div>
        </div>

        <div class="receipt-grid">
            <!-- General Info -->
            <div class="receipt-section">
                <div class="receipt-section-title">
                    <i class="fa-solid fa-circle-info"></i> General Information
                </div>
                
                <div class="receipt-row">
                    <span class="receipt-label">Transaction ID</span>
                    <span class="receipt-value">#TX-{{ str_pad($transaction->id, 5, '0', STR_PAD_LEFT) }}</span>
                </div>
                <div class="receipt-row">
                    <span class="receipt-label">Date & Time</span>
                    <span class="receipt-value">{{ $transaction->created_at->format('M d, Y h:i A') }}</span>
                </div>
                <div class="receipt-row">
                    <span class="receipt-label">Type</span>
                    <span class="receipt-value">{{ $transaction->type }}</span>
                </div>
                <div class="receipt-row">
                    <span class="receipt-label">Payment Method</span>
                    <span class="receipt-value">{{ $transaction->payment_method }}</span>
                </div>
                @if($transaction->reference_number)
                <div class="receipt-row">
                    <span class="receipt-label">Reference Number</span>
                    <span class="receipt-value">{{ $transaction->reference_number }}</span>
                </div>
                @endif
            </div>

            <!-- Parties Involved -->
            <div class="receipt-section">
                <div class="receipt-section-title">
                    <i class="fa-solid fa-users"></i> Parties Involved
                </div>
                
                @if($transaction->booking_id)
                <div class="receipt-row">
                    <span class="receipt-label">Booking ID</span>
                    <span class="receipt-value primary">Trip #{{ $transaction->booking_id }}</span>
                </div>
                @endif
                <div class="receipt-row">
                    <span class="receipt-label">Driver</span>
                    <span class="receipt-value">{{ $transaction->driver ? $transaction->driver->full_name : 'N/A' }}</span>
                </div>
                <div class="receipt-row">
                    <span class="receipt-label">Customer</span>
                    <span class="receipt-value">{{ $transaction->customer ? $transaction->customer->name : 'N/A' }}</span>
                </div>
            </div>
        </div>

        <!-- Financial Breakdown -->
        <div class="receipt-section" style="margin-bottom: 2rem;">
            <div class="receipt-section-title">
                <i class="fa-solid fa-chart-pie"></i> Financial Breakdown
            </div>
            
            <div class="receipt-row">
                <span class="receipt-label">Total Amount</span>
                <span class="receipt-value">{{ number_format($transaction->amount) }} MMK</span>
            </div>
            <div class="receipt-row">
                <span class="receipt-label">Platform Commission</span>
                <span class="receipt-value success">{{ number_format($transaction->commission_amount) }} MMK</span>
            </div>
            <div class="receipt-row">
                <span class="receipt-label">Driver Earnings</span>
                <span class="receipt-value">{{ number_format($transaction->driver_amount) }} MMK</span>
            </div>
            @if($transaction->note)
            <div class="receipt-row" style="flex-direction: column; text-align: left;">
                <span class="receipt-label" style="margin-bottom: 0.5rem;">Note</span>
                <span class="receipt-value" style="text-align: left; font-weight: normal; color: var(--text-muted);">{{ $transaction->note }}</span>
            </div>
            @endif
        </div>

        <div class="receipt-total">
            <span class="receipt-total-label">Total Payment</span>
            <span class="receipt-total-value">{{ number_format($transaction->amount) }} MMK</span>
        </div>

        <div class="receipt-footer">
            Generated on {{ now()->format('M d, Y h:i A') }} &bull; Official Financial Record
        </div>

        <div class="action-buttons">
            <button onclick="window.print()" class="btn-primary">
                <i class="fa-solid fa-print"></i> Print Receipt
            </button>
        </div>
    </div>
</div>
@endsection

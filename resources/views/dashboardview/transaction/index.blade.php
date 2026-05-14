@extends('layout.admin')

@section('content')
<div class="container-fluid">
    <div class="page-header animate-fade">
        <div>
            <h1 class="page-title">Financial Records</h1>
            <p class="page-subtitle">Track all transactions, commissions, and payouts.</p>
        </div>
        <div class="header-actions style-0ba205">
            <a href="{{ route('reports.transactions.print') }}" target="_blank" class="btn-primary style-b946eb">
                <i class="fa-solid fa-file-pdf"></i> Print PDF
            </a>
            <a href="{{ route('reports.transactions.csv') }}" class="btn-primary style-07b461">
                <i class="fa-solid fa-file-csv"></i> Export CSV
            </a>
        </div>


    </div>

    <!-- Financial Stats Grid -->
    <div class="stats-grid analytics-stats animate-fade">
        <div class="stat-card glass style-1f7c4f">
            <h3 class="stat-label">Total Revenue</h3>
            <p class="stat-value style-a5e3c2">{{ number_format($totalRevenue) }} <span class="style-fb2a71">MMK</span></p>
        </div>
        <div class="stat-card glass style-b66710">
            <h3 class="stat-label">Total Commission</h3>
            <p class="stat-value style-b2fa7f">{{ number_format($totalCommission) }} <span class="style-fb2a71">MMK</span></p>
        </div>
        <div class="stat-card glass style-99ad2f">
            <h3 class="stat-label">Net Payouts</h3>
            <p class="stat-value">{{ number_format($totalPayouts) }} <span class="style-fb2a71">MMK</span></p>
        </div>
        <div class="stat-card glass style-cf6fa0">
            <h3 class="stat-label">Pending Payouts</h3>
            <p class="stat-value">0 <span class="style-fb2a71">MMK</span></p>
        </div>
    </div>

    <!-- Transactions Table -->
    <div class="table-container glass animate-fade style-cf0f3a">
        <table class="premium-table">
            <thead>
                <tr>
                    <th>ID & Date</th>
                    <th>Type</th>
                    <th>Source (Booking)</th>
                    <th>Party (Driver/Customer)</th>
                    <th>Total Amount</th>
                    <th>Commission</th>
                    <th>Driver Share</th>
                    <th>Payment</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($transactions as $tx)
                <tr class="table-row">
                    <td>
                        <div class="primary-text">#TX-{{ str_pad($tx->id, 5, '0', STR_PAD_LEFT) }}</div>
                        <div class="secondary-text">{{ $tx->created_at->format('M d, Y h:i A') }}</div>
                    </td>
                    <td>
                        <div class="status-badge style-bf8224">
                            {{ $tx->type }}
                        </div>
                    </td>
                    <td>
                        @if($tx->booking_id)
                        <div class="primary-text">Trip #{{ $tx->booking_id }}</div>
                        @else
                        <span class="secondary-text">N/A</span>
                        @endif
                    </td>
                    <td>
                        <div class="primary-text">{{ $tx->driver?->full_name ?? 'N/A' }}</div>
                        <div class="secondary-text">{{ $tx->customer?->name ?? 'N/A' }}</div>
                    </td>

                    <td>
                        <div class="primary-text style-1f7302">
                            {{ number_format($tx->amount) }} MMK
                        </div>
                    </td>
                    <td>
                        <div class="primary-text style-b2fa7f">
                            {{ number_format($tx->commission_amount) }} MMK
                        </div>
                    </td>
                    <td>
                        <div class="primary-text style-8ffdae">
                            {{ number_format($tx->driver_amount) }} MMK
                        </div>
                    </td>
                    <td>
                        <div class="primary-text">{{ $tx->payment_method }}</div>
                    </td>
                    <td>
                        @php
                            $statusColor = [
                                'Completed' => 'var(--success)',
                                'Pending' => 'var(--warning)',
                                'Failed' => 'var(--danger)'
                            ][$tx->status] ?? 'var(--text-dim)';
                        @endphp
                        <span class="status-badge " style="background: {{ $statusColor }}20; color: {{ $statusColor }}; border: 1px solid {{ $statusColor }}40;">
                            {{ $tx->status }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="style-9860ba">
                        No financial records found.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($transactions->hasPages())
    <div class="small-pagination">
        {{ $transactions->links() }}
    </div>
    @endif
</div>

<link rel="stylesheet" href="{{ asset('css/dashboardview/transaction/index.css') }}">
@endsection

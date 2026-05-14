<link rel="stylesheet" href="{{ asset('css/driverview/withdrawal_detail.css') }}">
@extends('layout.driver')

@section('content')
<div class="animate-fade">
    <div class="page-header style-ffbeea">
        <div class="style-99129a">
            <a href="{{ route('driver.withdrawals', $driver->id) }}"  class="style-2e72b4"><i class="fa-solid fa-arrow-left"></i></a>
            <div>
                <h1 class="page-title">Receipt Detail</h1>
                <p class="page-subtitle">Transaction ID: #W{{ str_pad($withdrawal->id, 5, '0', STR_PAD_LEFT) }}</p>
            </div>
        </div>
    </div>

    <div class="glass style-57c15d">
        <div class="style-86c435">
            <div style="width: 60px; height: 60px; border-radius: 50%; background: {{ $withdrawal->status == 'approved' ? 'var(--success-light)' : 'var(--warning-light)' }}; display: flex; align-items: center; justify-content: center; margin: 0 auto 15px; color: {{ $withdrawal->status == 'approved' ? 'var(--success)' : 'var(--warning)' }}; border: 1px solid {{ $withdrawal->status == 'approved' ? 'var(--success)' : 'var(--warning)' }};">
                <i class="fa-solid {{ $withdrawal->status == 'approved' ? 'fa-check-double' : 'fa-clock' }} style-fa415b"></i>
            </div>
            <h2 class="style-661890">{{ number_format($withdrawal->amount) }} MMK</h2>
            <p class="style-f1df8c" style="color: {{ $withdrawal->status == 'approved' ? 'var(--success)' : 'var(--warning)' }}">{{ ucfirst($withdrawal->status) }}</p>
        </div>

        <div class="style-9a3f58">
            <div class="style-58e548">
                <span class="style-2e72b4">Method</span>
                <span class="style-db1172">{{ strtoupper($withdrawal->payment_method) }}</span>
            </div>
            <div class="style-58e548">
                <span class="style-2e72b4">Date</span>
                <span class="style-db1172">{{ $withdrawal->created_at->format('M d, Y h:i A') }}</span>
            </div>
            @if($withdrawal->notes)
            <div class="style-d24beb">
                <span class="style-2e72b4">Notes</span>
                <p class="style-1dc958">{{ $withdrawal->notes }}</p>
            </div>
            @endif
        </div>
    </div>

    @if($withdrawal->screenshot)
    <div class="style-ffbeea">
        <h3 class="style-918787">Payment Receipt</h3>
        <div class="glass style-adba0e">
            <img src="{{ asset('storage/' . $withdrawal->screenshot) }}"  alt="Payment Proof" class="style-c1b590">
        </div>
        <div class="style-d8bf3f">
            <a href="{{ asset('storage/' . $withdrawal->screenshot) }}" download class="glass style-606e53" >
                <i class="fa-solid fa-download"></i> Save Receipt
            </a>
        </div>
    </div>
    @elseif($withdrawal->status == 'approved')
    <div class="glass style-9e2ee1">
        <p class="style-e7992d">Payment processed without receipt image.</p>
    </div>
    @endif
</div>
@endsection

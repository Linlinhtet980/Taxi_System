<link rel="stylesheet" href="{{ asset('css/driverview/withdrawals.css') }}">
@extends('layout.driver')

@section('content')
<div class="animate-fade">
    <div class="style-511dfe">
        <div>
            <h2 class="style-7c092f">Withdrawal History</h2>
            <p class="style-6d5c22">Track your payout requests.</p>
        </div>
        @if(!request()->has('show_all') && $withdrawals->total() > 5)
            <a href="{{ route('driver.withdrawals', ['id' => $driver->id, 'show_all' => 1]) }}" class="glass style-e630a6" >
                SEE ALL HISTORY
            </a>
        @endif
    </div>

    <div class="style-c3e0b7">
        @forelse($withdrawals as $w)
        <div class="glass style-32c16d">
            <div class="style-68b0c3">
                <span class="style-d9a249">#WDR-{{ str_pad($w->id, 4, '0', STR_PAD_LEFT) }}</span>
                @php
                    $colors = ['pending' => '#fbbf24', 'approved' => '#4ade80', 'rejected' => '#f43f5e'];
                    $color = $colors[$w->status] ?? '#94a3b8';
                @endphp
                <span class="status-badge " style="background: {{ $color }}20; color: {{ $color }}; border: 1px solid {{ $color }}40;">
                    {{ strtoupper($w->status) }}
                </span>
            </div>

            <div class="style-452fc9">
                <div>
                    <h3 class="style-aa38c0">{{ number_format($w->amount) }} <span class="style-d9f8ae">MMK</span></h3>
                    <p class="style-da0175">Via {{ $w->payment_method }}</p>
                </div>
                <div class="style-7851db">
                    <p class="style-831dee">{{ $w->created_at->format('d M Y') }}</p>
                    <p class="style-fbd666">{{ $w->created_at->format('h:i A') }}</p>
                </div>
            </div>

            @if($w->notes)
            <div class="style-57b2d5">
                <strong>Admin Note:</strong> {{ $w->notes }}
            </div>
            @endif

            @if($w->status == 'approved')
            <div class="style-8d886c">
                <a href="{{ route('driver.withdrawal.detail', ['id' => $driver->id, 'withdrawalId' => $w->id]) }}"  class="style-424587">
                    View Receipt <i class="fa-solid fa-chevron-right"></i>
                </a>
            </div>
            @endif
        </div>
        @empty
        <div class="glass style-d81ea1">
            <p>No withdrawal requests yet.</p>
        </div>
        @endforelse
    </div>

    <div class="style-aa822b">
        {{ $withdrawals->links() }}
    </div>
</div>
@endsection

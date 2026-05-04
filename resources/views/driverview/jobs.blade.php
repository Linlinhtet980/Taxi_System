@extends('layout.driver')

@section('content')
<div class="animate-fade">
    <div class="style-511dfe">
        <div>
            <h2 class="style-7c092f">My Job History</h2>
            <p class="style-6d5c22">Total Rides: {{ $allJobs->total() }}</p>
        </div>
        @if(!request()->has('show_all') && $allJobs->total() > 5)
            <a href="{{ route('driver.jobs', ['id' => $driver->id, 'show_all' => 1]) }}" class="glass style-e630a6" >
                SEE ALL HISTORY
            </a>
        @endif
    </div>

    <div class="style-c3e0b7">
        @forelse($allJobs as $job)
        <div class="glass style-32c16d">
            <div class="style-134370">
                <div>
                    <span class="style-e29558">#TRP-{{ str_pad($job->id, 5, '0', STR_PAD_LEFT) }}</span>
                    <h4 class="style-7cc7b9">{{ $job->customer->name ?? 'Guest' }}</h4>
                </div>
                @php
                    $statusColors = [
                        'pending' => '#fbbf24',
                        'confirmed' => '#60a5fa',
                        'ongoing' => '#a855f7',
                        'completed' => '#4ade80',
                        'cancelled' => '#f43f5e'
                    ];
                    $color = $statusColors[$job->status] ?? '#94a3b8';
                @endphp
                <span class="status-badge " style="background: {{ $color }}20; color: {{ $color }}; border: 1px solid {{ $color }}40;">
                    {{ $job->status }}
                </span>
            </div>

            <div class="style-279943">
                <p><i class="fa-solid fa-circle-dot style-4b2d64"></i> {{ $job->pickup_location }}</p>
                <p class="style-84d7b9"><i class="fa-solid fa-location-dot style-6d9ef5"></i> {{ $job->dropoff_location }}</p>
            </div>

            <div class="style-1984ad">
                <div class="style-d9a249">
                    {{ $job->created_at->format('d M Y, h:i A') }}
                </div>
                <div class="style-1f7302">
                    {{ number_format($job->fare) }} MMK
                </div>
            </div>
        </div>
        @empty
        <div class="glass style-d81ea1">
            <p>No trip records found.</p>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="style-aa822b">
        {{ $allJobs->links() }}
    </div>
</div>

<link rel="stylesheet" href="{{ asset('css/driverview/jobs.css') }}">
@endsection

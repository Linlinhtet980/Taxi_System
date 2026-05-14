<link rel="stylesheet" href="{{ asset('css/driverview/leaderboard.css') }}">
@extends('layout.driver')

@section('content')
<div class="animate-fade">
    <div class="page-header style-ffbeea">
        <div>
            <h1 class="page-title">Leaderboard</h1>
            <p class="page-subtitle">Celebrating our top performing partners this month.</p>
        </div>
        <div class="style-d9eeb3">
            <i class="fa-solid fa-trophy"></i>
        </div>
    </div>

    <div class="glass style-7dc9cf">
        @foreach($topDrivers as $index => $td)
            @php
                $isTop3 = $index < 3;
                $trophyColor = $index == 0 ? '#fbbf24' : ($index == 1 ? '#94a3b8' : '#b45309');
                $isMe = $td->id == $driver->id;
            @endphp
            <div  style="display: flex; align-items: center; padding: 18px 20px; border-bottom: 1px solid var(--card-border); {{ $isMe ? 'background: var(--primary-light);' : '' }} transition: 0.3s;">
                <div class="style-f9cde4" style="display: flex; align-items: center; justify-content: center; color: var(--primary); border: 1px solid var(--card-border);">
                    @if($isTop3)
                        <i class="fa-solid fa-crown " style="color: {{ $trophyColor }}; font-size: 1.1rem;"></i>
                    @else
                        <span class="style-efab36">#{{ $index + 1 }}</span>
                    @endif
                </div>
                
                <div  style="width: 50px; height: 50px; border-radius: 15px; overflow: hidden; background: var(--input-bg); margin-right: 15px; border: 2px solid {{ $isMe ? 'var(--primary)' : ($isTop3 ? $trophyColor : 'transparent') }}; flex-shrink: 0;">
                    <img src="{{ $td->profile_picture ? asset($td->profile_picture) : 'https://ui-avatars.com/api/?name=' . urlencode($td->full_name) . '&background=D4AF37&color=fff' }}"  class="style-7d1fae">
                </div>

                <div class="style-49cdf8">
                    <h4 class="style-8d108f">
                        {{ $td->full_name }}
                        @if($isMe)
                            <span class="style-e16ccf">YOU</span>
                        @endif
                    </h4>
                    <p class="style-4b6fe5">{{ $td->vehicle->license_plate ?? 'Active Driver' }}</p>
                </div>

                <div class="style-7851db">
                    <div  style="font-size: 1.1rem; font-weight: 800; color: {{ $isTop3 ? $trophyColor : 'var(--text-main)' }};">{{ $td->bookings_count }}</div>
                    <div class="style-2bde83">Trips</div>
                </div>
            </div>
        @endforeach
    </div>

    @if(!$topDrivers->contains('id', $driver->id))
    <div class="glass style-75b068">
        <p class="style-47f4aa">
            <i class="fa-solid fa-rocket style-81a4fe"></i>
            You're close to the top! Complete more trips to join the leaderboard.
        </p>
    </div>
    @endif
</div>
@endsection

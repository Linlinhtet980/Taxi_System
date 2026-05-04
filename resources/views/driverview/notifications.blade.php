@extends('layout.driver')

@section('content')
<div class="animate-fade">
    <div class="page-header style-ffbeea">
        <div>
            <h1 class="page-title">Notifications</h1>
            <p class="page-subtitle">Stay updated with the latest system alerts.</p>
        </div>
        <div class="style-3a5f63">
            <i class="fa-solid fa-bell-concierge"></i>
        </div>
    </div>

    <link rel="stylesheet" href="{{ asset('css/driverview/notifications.css') }}">

    <div class="style-c3e0b7">
        @forelse($notifications as $n)
        @if($n->link)
        <a href="{{ $n->link }}"  class="style-2e2127">
        @endif
        <div class="glass notification-item style-747679">
            @php
                $icon = 'fa-circle-info';
                $bg = 'rgba(96, 165, 250, 0.1)';
                $color = '#60a5fa';
                
                if($n->type == 'success') {
                    $icon = 'fa-circle-check'; $bg = 'rgba(74, 222, 128, 0.1)'; $color = '#4ade80';
                } elseif($n->type == 'warning') {
                    $icon = 'fa-circle-exclamation'; $bg = 'rgba(251, 191, 36, 0.1)'; $color = '#fbbf24';
                }
            @endphp
            <div  style="width: 40px; height: 40px; border-radius: 12px; background: {{ $bg }}; display: flex; align-items: center; justify-content: center; color: {{ $color }}; flex-shrink: 0;">
                <i class="fa-solid {{ $icon }} style-e7ec96"></i>
            </div>
            <div class="style-49cdf8">
                <div class="style-1cac1b">
                    <h4 class="style-8e600e">{{ $n->title }}</h4>
                    <span class="style-782f1b">{{ $n->created_at->diffForHumans() }}</span>
                </div>
                <p class="style-a6fb7d">{{ $n->message }}</p>
            </div>
            @if($n->link)
            <div class="style-622e85">
                <i class="fa-solid fa-chevron-right"></i>
            </div>
            @endif
        </div>
        @if($n->link)
        </a>
        @endif
        @empty
        <div class="glass style-2d55c1">
            <i class="fa-regular fa-bell-slash style-35ae2b"></i>
            <p>You're all caught up! No notifications here.</p>
        </div>
        @endforelse
    </div>

    <!-- Custom Pagination -->
    @if($notifications->hasPages())
    <div class="style-04e630">
        @if (!$notifications->onFirstPage())
            <a href="{{ $notifications->previousPageUrl() }}" class="glass style-b35ae7" >
                <i class="fa-solid fa-chevron-left"></i>
            </a>
        @endif

        @foreach ($notifications->getUrlRange(1, $notifications->lastPage()) as $page => $url)
            @if ($page == $notifications->currentPage())
                <div class="glass style-8ea5da">
                    {{ $page }}
                </div>
            @else
                <a href="{{ $url }}" class="glass style-29602b">
                    {{ $page }}
                </a>
            @endif
        @endforeach

        @if ($notifications->hasMorePages())
            <a href="{{ $notifications->nextPageUrl() }}" class="glass style-b35ae7" >
                <i class="fa-solid fa-chevron-right"></i>
            </a>
        @endif
    </div>
    @endif
</div>
@endsection

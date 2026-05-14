@extends('layout.driver')

@section('content')
<div class="animate-fade">
    <div class="page-header" style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 30px;">
        <div>
            <h1 class="page-title">Notifications</h1>
            <p class="page-subtitle">Stay updated with the latest system alerts.</p>
        </div>
        <form action="{{ route('notifications.mark-all-as-read') }}" method="POST">
            @csrf
            <input type="hidden" name="user_id" value="{{ $driver->id }}">
            <input type="hidden" name="user_type" value="{{ get_class($driver) }}">
            <button type="submit" class="glass" style="padding: 10px 20px; border: 1px solid var(--border-color); color: var(--primary); font-size: 11px; font-weight: 800; text-transform: uppercase; letter-spacing: 1px; cursor: pointer; border-radius: 12px;">Mark all read</button>
        </form>
    </div>

    <link rel="stylesheet" href="{{ asset('css/driverview/notifications.css') }}">

    <div style="display: grid; gap: 15px;">
        @forelse($notifications as $n)
        @php
            $is_admin_link = $n->link && (Str::contains($n->link, '/admin') || Str::contains($n->link, '/transactions') || Str::contains($n->link, '/bookings') || Str::contains($n->link, '/dashboard'));
            $is_read = $n->is_read;
        @endphp
        <div class="glass notification-item {{ $is_read ? 'read' : 'unread' }}" 
             onclick="markAsRead(this, {{ $n->id }}, '{{ ($n->link && !$is_admin_link) ? $n->link : '' }}')"
             style="display: flex; gap: 20px; padding: 25px; cursor: pointer; transition: 0.3s; {{ $is_read ? 'opacity: 0.5;' : '' }}">
            @php
                $statusColors = [
                    'success' => ['bg' => 'var(--success-light)', 'color' => 'var(--success)', 'icon' => 'fa-circle-check'],
                    'warning' => ['bg' => 'var(--warning-light)', 'color' => 'var(--warning)', 'icon' => 'fa-circle-exclamation'],
                    'info' => ['bg' => 'var(--primary-light)', 'color' => 'var(--primary)', 'icon' => 'fa-circle-info'],
                    'danger' => ['bg' => 'var(--danger-light)', 'color' => 'var(--danger)', 'icon' => 'fa-circle-xmark']
                ];
                $s = $statusColors[$n->type] ?? $statusColors['info'];
            @endphp
            <div style="width: 40px; height: 40px; border-radius: 12px; background: {{ $s['bg'] }}; display: flex; align-items: center; justify-content: center; color: {{ $s['color'] }}; flex-shrink: 0; border: 1px solid {{ $s['color'] }};">
                <i class="fa-solid {{ $s['icon'] }}"></i>
            </div>
            <div style="flex: 1;">
                <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 5px;">
                    <h4 style="font-size: 16px; font-weight: 700; color: var(--text-main);">{{ $n->title }}</h4>
                    <span style="font-size: 11px; color: var(--text-dim);">{{ $n->created_at->diffForHumans() }}</span>
                </div>
                <p style="font-size: 13px; color: var(--text-dim); line-height: 1.5;">{{ $n->message }}</p>
            </div>
            @if($n->link && !$is_admin_link)
            <div style="color: var(--primary); opacity: 0.5;">
                <i class="fa-solid fa-chevron-right"></i>
            </div>
            @endif
        </div>
        @empty
        <div class="glass" style="padding: 50px; text-align: center; color: var(--text-dim);">
            <i class="fa-regular fa-bell-slash" style="font-size: 40px; margin-bottom: 20px; opacity: 0.2;"></i>
            <p>You're all caught up! No notifications here.</p>
        </div>
        @endforelse
    </div>

    @push('js')
    <script>
        function markAsRead(element, id, link) {
            fetch(`/notifications/${id}/mark-as-read`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    element.style.opacity = '0.5';
                    if (link) {
                        window.location.href = link;
                    }
                }
            })
            .catch(error => console.error('Error:', error));
        }
    </script>
    @endpush

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

@extends('layout.customer')

@section('title', 'My Activities - Taxi')

@push('css')
    <style>
        .activity-card {
            background: var(--card-glass);
            border-radius: 20px;
            padding: 15px;
            margin-bottom: 15px;
            border: 1px solid var(--card-border);
            backdrop-filter: blur(10px);
            transition: 0.3s;
        }
        .activity-card:hover { border-color: var(--primary); transform: translateY(-2px); }

        .card-top { display: flex; justify-content: space-between; margin-bottom: 15px; }
        .card-date { font-size: 12px; color: var(--text-dim); }
        .card-status {
            font-size: 10px;
            font-weight: 700;
            padding: 4px 10px;
            border-radius: 20px;
            text-transform: uppercase;
        }
        .status-completed { background: var(--success-light); color: var(--success); }
        .status-cancelled { background: var(--danger-light); color: var(--danger); }
        .status-active { background: var(--primary-light); color: var(--primary); }

        .card-details { display: flex; gap: 15px; }
        .loc-icons { display: flex; flex-direction: column; align-items: center; gap: 5px; }
        .dot { width: 8px; height: 8px; border-radius: 50%; }
        .line { width: 2px; height: 20px; background: var(--card-border); }

        .loc-texts { flex: 1; }
        .loc-item { margin-bottom: 10px; }
        .loc-item h6 { font-size: 14px; font-weight: 600; color: var(--text-main); }
        .loc-item p { font-size: 12px; color: var(--text-dim); }

        .card-footer {
            margin-top: 15px;
            padding-top: 15px;
            border-top: 1px solid var(--card-border);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .fare { font-weight: 700; color: var(--primary); }

        .notif-card {
            background: var(--card-glass);
            border-radius: 20px;
            padding: 15px;
            margin-bottom: 15px;
            border: 1px solid var(--card-border);
            backdrop-filter: blur(10px);
            transition: 0.3s;
        @media (max-width: 360px) {
            .activity-card, .notif-card { padding: 12px; border-radius: 16px; }
            .card-top { margin-bottom: 10px; }
            .card-date { font-size: 11px; }
            .card-status { font-size: 9px; padding: 3px 8px; }
            .loc-item h6 { font-size: 13px; }
            .loc-item p { font-size: 11px; }
            .card-footer { margin-top: 10px; padding-top: 10px; font-size: 12px; }
            .fare { font-size: 13px; }
            .section-title { font-size: 16px !important; }
        }
    </style>
@endpush

@section('content')
    <div class="animate-fade">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <h2 class="section-title" style="margin: 0; color: var(--text-main); font-size: 18px; font-weight: 700;">အကြောင်းကြားစာများ</h2>
            @if($unreadCount > 0)
            <form action="{{ route('notifications.mark-all-as-read') }}" method="POST">
                @csrf
                <input type="hidden" name="user_id" value="{{ auth()->guard('customer')->id() }}">
                <input type="hidden" name="user_type" value="{{ get_class(auth()->guard('customer')->user()) }}">
                <button type="submit" style="background: none; border: none; color: var(--primary); font-size: 12px; font-weight: 700; cursor: pointer;">Mark all read</button>
            </form>
            @endif
        </div>

        <div style="margin-bottom: 40px;">
            @forelse($latestNotifications as $notif)
            <div class="notif-card" 
                 onclick="markAsRead(this, {{ $notif->id }}, '{{ $notif->link }}')"
                 style="cursor: pointer; border-left: 4px solid {{ $notif->is_read ? 'var(--card-border)' : 'var(--primary)' }}; {{ $notif->is_read ? 'opacity: 0.6;' : '' }}">
                <div style="display: flex; gap: 12px;">
                    @php
                        $icon = $notif->type == 'success' ? 'fa-circle-check' : ($notif->type == 'warning' ? 'fa-circle-exclamation' : 'fa-circle-info');
                        $color = $notif->type == 'success' ? 'var(--success)' : ($notif->type == 'warning' ? 'var(--warning)' : 'var(--info)');
                    @endphp
                    <i class="fa-solid {{ $icon }}" style="color: {{ $color }}; margin-top: 3px;"></i>
                    <div style="flex: 1;">
                        <div style="display: flex; justify-content: space-between;">
                            <h5 style="font-size: 14px; font-weight: 700; color: var(--text-main);">{{ $notif->title }}</h5>
                            <span style="font-size: 10px; color: var(--text-dim);">{{ $notif->created_at->diffForHumans() }}</span>
                        </div>
                        <p style="font-size: 12px; color: var(--text-dim); margin-top: 2px;">{{ $notif->message }}</p>
                    </div>
                </div>
            </div>
            @empty
            <div style="text-align: center; padding: 20px; background: var(--card-glass); border-radius: 20px; color: var(--text-dim); font-size: 12px; border: 1px dashed var(--card-border);">
                အကြောင်းကြားစာ အသစ်မရှိပါ။
            </div>
            @endforelse
        </div>

        <h2 class="section-title" style="margin-bottom: 20px; color: var(--text-main); font-size: 18px; font-weight: 700;">ခရီးစဉ်မှတ်တမ်းများ</h2>

        @forelse($bookings as $booking)
        <div class="activity-card">
            <div class="card-top">
                <span class="card-date">{{ $booking->created_at->format('d M Y, h:i A') }}</span>
                <span class="card-status status-{{ $booking->status == 'completed' ? 'completed' : ($booking->status == 'cancelled' ? 'cancelled' : 'active') }}">
                    {{ ucfirst($booking->status) }}
                </span>
            </div>
            <div class="card-details">
                <div class="loc-icons">
                    <div class="dot" style="background: var(--primary);"></div>
                    <div class="line"></div>
                    <div class="dot" style="background: var(--danger);"></div>
                </div>
                <div class="loc-texts">
                    <div class="loc-item">
                        <p>Pickup</p>
                        <h6>{{ $booking->pickup_location }}</h6>
                    </div>
                    <div class="loc-item">
                        <p>Drop-off</p>
                        <h6>{{ $booking->dropoff_location }}</h6>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <div style="font-size: 13px; color: var(--text-dim);">
                    <i class="fa-solid fa-car"></i> Taxi
                </div>
                <div class="fare">{{ number_format($booking->fare) }} Ks</div>
            </div>
        </div>
        @empty
        <div style="text-align: center; padding: 50px 20px; color: var(--text-dim); background: var(--card-glass); border-radius: 20px; border: 1px solid var(--card-border);">
            <i class="fa-solid fa-calendar-xmark" style="font-size: 40px; margin-bottom: 15px; opacity: 0.3;"></i>
            <p>ခရီးစဉ်မှတ်တမ်း မရှိသေးပါ။</p>
        </div>
        @endforelse

        <div style="margin-top: 20px;">
            {{ $bookings->links() }}
        </div>
    </div>
@endsection

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
                    element.style.opacity = '0.6';
                    element.style.borderLeftColor = 'var(--card-border)';
                    if (link && link !== '#') {
                        window.location.href = link;
                    }
                }
            })
            .catch(error => console.error('Error:', error));
        }
    </script>
@endpush

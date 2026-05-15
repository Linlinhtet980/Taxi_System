@extends('layout.master')

@section('title', 'Taxi | Admin Dashboard')

@push('css')
    <link rel="stylesheet" href="{{ asset('css/dashboardview/layout/admin.css') }}">
    <link rel="stylesheet" href="{{ asset('css/dashboardview/layout/admin-layout.css') }}">
    <link rel="stylesheet" href="{{ asset('css/layout/admin.css') }}">
@endpush

@section('master_content')
    <!-- Floating Expandable Sidebar -->
    <aside class="sidebar glass expanded" id="sidebar">

        <div class="logo-container" id="sidebar-toggle">
            <i class="fa-solid fa-taxi"></i>
            <span class="logo-text">TaxiAdmin</span>
        </div>
        
        <div class="sidebar-content">
            <ul class="nav-links">
                <li class="nav-group-header">Overview</li>
                <li>
                    <a href="{{ route('dashboard.home') }}" class="nav-link {{ request()->routeIs('dashboard.home') ? 'active' : '' }}">
                        <i class="fa-solid fa-house-chimney"></i>
                        <span class="nav-text">Dashboard</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('analytics.index') }}" class="nav-link {{ request()->routeIs('analytics.*') ? 'active' : '' }}">
                        <i class="fa-solid fa-chart-line"></i>
                        <span class="nav-text">Analytics</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('bookings.map') }}" class="nav-link {{ request()->routeIs('bookings.map') ? 'active' : '' }}">
                        <i class="fa-solid fa-map-location-dot"></i>
                        <span class="nav-text">Live Tracking</span>
                    </a>
                </li>

                <li class="nav-group-header">Operations</li>
                <li>
                    <a href="{{ route('bookings.index') }}" class="nav-link {{ request()->routeIs('bookings.index') && !request()->routeIs('bookings.map') ? 'active' : '' }}">
                        <i class="fa-solid fa-list-check"></i>
                        <span class="nav-text">Bookings List</span>
                    </a>
                </li>

                <li class="nav-group-header">Resources</li>
                <li>
                    <a href="{{ route('drivers.index') }}" class="nav-link {{ request()->routeIs('drivers.*') ? 'active' : '' }}">
                        <i class="fa-solid fa-users-gear"></i>
                        <span class="nav-text">Drivers</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('vehicles.index') }}" class="nav-link {{ request()->routeIs('vehicles.*') ? 'active' : '' }}">
                        <i class="fa-solid fa-car-side"></i>
                        <span class="nav-text">Fleet Management</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('point-rewards.index') }}" class="nav-link {{ request()->routeIs('point-rewards.*') ? 'active' : '' }}">
                        <i class="fa-solid fa-gift"></i>
                        <span class="nav-text">Point Rewards</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('customers.index') }}" class="nav-link {{ request()->routeIs('customers.*') ? 'active' : '' }}">
                        <i class="fa-solid fa-user-group"></i>
                        <span class="nav-text">Passengers</span>
                    </a>
                </li>

                <li class="nav-group-header">Finance</li>
                <li class="nav-item">
                    <a href="{{ route('transactions.index') }}" class="nav-link {{ request()->routeIs('transactions.*') ? 'active' : '' }}">
                        <i class="fa-solid fa-file-invoice-dollar"></i>
                        <span class="nav-text">Transactions</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.withdrawals.index') }}" class="nav-link {{ request()->routeIs('admin.withdrawals.*') ? 'active' : '' }}">
                        <i class="fa-solid fa-money-bill-transfer"></i>
                        <span class="nav-text">Withdrawals</span>
                    </a>
                </li>


                @if(!auth()->check() || (auth()->check() && auth()->user()->role === 'super_admin'))
                <li class="nav-group-header">System</li>
                <li>
                    <a href="{{ route('settings.index') }}" class="nav-link {{ request()->routeIs('settings.*') ? 'active' : '' }}">
                        <i class="fa-solid fa-sliders"></i>
                        <span class="nav-text">Settings</span>
                    </a>
                </li>
                @endif

            </ul>

            <div class="logout-link">
                <form action="{{ route('admin.logout') }}" method="POST" id="admin-logout-form" style="display: none;">
                    @csrf
                </form>
                <a href="#" class="nav-link" onclick="event.preventDefault(); document.getElementById('admin-logout-form').submit();">
                    <i class="fa-solid fa-right-from-bracket"></i>
                    <span class="nav-text">Logout</span>
                </a>
            </div>
        </div>

    </aside>
    
    <div class="sidebar-overlay" id="sidebar-overlay"></div>

    <!-- Main Content Wrapper -->
    <div class="main-wrapper expanded" id="main-wrapper">

        <header>
            <div class="mobile-menu-btn" id="mobile-menu-btn">
                <i class="fa-solid fa-bars"></i>
            </div>

            @if(request()->routeIs('bookings.index') || request()->routeIs('drivers.index') || request()->routeIs('vehicles.index') || request()->routeIs('customers.index'))
            <div class="search-bar">
                <i class="fa-solid fa-magnifying-glass"></i>
                <input type="text" id="global-search" placeholder="Search...">
            </div>
            @else
            <div class="search-bar-spacer"></div>
            @endif


            <div class="user-area">
                <button class="theme-toggle" id="theme-toggle">
                    <i class="fa-solid fa-moon" id="theme-icon"></i>
                </button>
                
                <div class="notifications-wrapper">
                    <div class="notifications-btn" id="notif-btn">
                        <i class="fa-regular fa-bell"></i>
                        @if($unreadCount > 0)
                            <span class="notif-badge">
                                {{ $unreadCount }}
                            </span>
                        @endif
                    </div>

                    <!-- Notification Dropdown -->
                    <div id="notif-dropdown" class="glass dropdown-menu">
                        <div class="notif-header">
                            <h4 class="notif-header-title">Notifications</h4>
                            @if(auth()->check())
                            <form action="{{ route('notifications.mark-all-as-read') }}" method="POST">
                                @csrf
                                <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
                                <input type="hidden" name="user_type" value="{{ get_class(auth()->user()) }}">
                                <button type="submit" class="notif-mark-read-btn">Mark all read</button>
                            </form>
                            @endif
                        </div>
                        <div class="notif-list">
                            @forelse($latestNotifications as $notif)
                            <div class="notif-item notif-item-container {{ $notif->is_read ? 'read' : 'unread' }}" 
                                 onclick="markAsRead(this, {{ $notif->id }}, '{{ $notif->link }}')">
                                <div class="notif-content notif-item-content">
                                    @php
                                        $icon = $notif->type == 'success' ? 'fa-circle-check' : ($notif->type == 'warning' ? 'fa-circle-exclamation' : 'fa-circle-info');
                                        $iconColor = $notif->type == 'success' ? 'var(--success)' : ($notif->type == 'warning' ? 'var(--warning)' : 'var(--info)');
                                    @endphp
                                    <i class="fa-solid {{ $icon }} notif-icon-margin" style="color: {{ $iconColor }};"></i>
                                    <div>
                                        <div class="notif-title notif-item-title">{{ $notif->title }}</div>
                                        <div class="notif-message notif-item-message">{{ $notif->message }}</div>
                                        <div class="notif-time notif-item-time">{{ $notif->created_at->diffForHumans() }}</div>
                                    </div>
                                </div>
                            </div>
                            @empty
                            <div class="notif-empty notif-empty-state">No notifications.</div>
                            @endforelse
                        </div>
                    </div>
                </div>

                
                <div class="user-card">
                    <p class="user-name">{{ auth()->user()->name ?? 'James Radcliffe' }}</p>
                    <p class="user-role" style="text-transform: capitalize; color: var(--primary);">
                        {{ str_replace('_', ' ', auth()->user()->role ?? 'Super Admin') }}
                    </p>
                </div>
                
                <div class="user-avatar-box">
                    <i class="fa-solid fa-user-tie"></i>
                </div>
            </div>
        </header>

        @if(session('success'))
            <div class="glass animate-fade alert-box">
                <p class="alert-text">✨ {{ session('success') }}</p>
            </div>
        @endif

        @yield('content')
    </div>
@endsection

@push('js')
    <!-- Interactivity Script -->
    <script src="{{ asset('js/dashboardview/layout/admin-layout.js') }}"></script>

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
                    element.classList.remove('unread');
                    element.classList.add('read');
                    element.style.opacity = '0.5';
                    if (link && link !== '#') {
                        window.location.href = link;
                    }
                }
            })
            .catch(error => console.error('Error:', error));
        }
    </script>
@endpush

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Taxi | Admin Dashboard</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Fonts & Icons -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- CSS -->
    <link rel="stylesheet" href="{{ asset('css/dashboardview/layout/admin.css') }}">
    <link rel="stylesheet" href="{{ asset('css/dashboardview/layout/admin-layout.css') }}">
    @stack('css')
    <script src="{{ asset('js/dashboardview/layout/theme-init.js') }}"></script>

    <style>
        /* Global Admin Pagination Styling (Bootstrap 4 structure) */
        .pagination { 
            display: flex !important;
            list-style: none !important;
            gap: 8px !important;
            padding: 0 !important;
            margin: 20px 0 !important;
            justify-content: flex-start !important;
        }
        
        .page-item { margin: 0; }

        .page-link { 
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            min-width: 38px !important;
            height: 38px !important;
            padding: 0 10px !important;
            border-radius: 10px !important; 
            background: rgba(255, 255, 255, 0.05) !important; 
            border: 1px solid var(--card-border) !important;
            color: var(--text-dim) !important;
            text-decoration: none !important;
            font-size: 0.85rem !important;
            font-weight: 600 !important;
            transition: all 0.3s ease !important;
        }

        .page-item.active .page-link {
            background: var(--accent-purple) !important;
            border-color: var(--accent-purple) !important;
            color: white !important;
            box-shadow: 0 4px 12px rgba(168, 85, 247, 0.3) !important;
        }

        .page-item.disabled .page-link {
            opacity: 0.3 !important;
            background: transparent !important;
        }

        .page-link:hover:not(.active) {
            background: rgba(168, 85, 247, 0.1) !important;
            border-color: var(--accent-purple) !important;
            color: var(--accent-purple) !important;
            transform: translateY(-2px) !important;
        }

        /* Responsive Fix */
        @media (max-width: 768px) {
            .pagination { justify-content: center !important; }
        }
    </style>

    <link rel="stylesheet" href="{{ asset('css/layout/admin.css') }}">
</head>
<body data-theme="dark">

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


                <li class="nav-group-header">System</li>
                <li>
                    <a href="{{ route('settings.index') }}" class="nav-link {{ request()->routeIs('settings.*') ? 'active' : '' }}">
                        <i class="fa-solid fa-sliders"></i>
                        <span class="nav-text">Settings</span>
                    </a>
                </li>

            </ul>

            <div class="logout-link">
                <a href="#" class="nav-link">
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
                <button class="theme-toggle" id="theme-toggle" title="Toggle Theme">
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
                        <div  class="style-acd1a1">
                            <h4  class="style-a88a79">Notifications</h4>
                            <form action="{{ route('notifications.markAllRead') }}" method="POST">
                                @csrf
                                <button type="submit"  class="style-2a00f9">Mark all read</button>
                            </form>
                        </div>
                        <div class="notif-list">
                            @forelse($latestNotifications as $notif)
                            <div class="notif-item {{ $notif->is_read ? 'read' : 'unread' }}">
                                <div class="notif-content">
                                    @php
                                        $icon = $notif->type == 'success' ? 'fa-circle-check' : ($notif->type == 'warning' ? 'fa-circle-exclamation' : 'fa-circle-info');
                                        $iconColor = $notif->type == 'success' ? '#4ade80' : ($notif->type == 'warning' ? '#fbbf24' : '#60a5fa');
                                    @endphp
                                    <i class="fa-solid {{ $icon }} notif-icon style-139515" ></i>
                                    <div>
                                        <div class="notif-title">{{ $notif->title }}</div>
                                        <div class="notif-message">{{ $notif->message }}</div>
                                        <div class="notif-time">{{ $notif->created_at->diffForHumans() }}</div>
                                    </div>
                                </div>
                            </div>
                            @empty
                            <div class="notif-empty">No notifications.</div>
                            @endforelse
                        </div>
                    </div>
                </div>

                
                <div class="user-card">
                    <p class="user-name">James Radcliffe</p>
                    <p class="user-role">Administrator</p>
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

    <!-- Interactivity Script -->
    <script src="{{ asset('js/dashboardview/layout/admin-layout.js') }}"></script>

    @stack('js')
</body>

</html>

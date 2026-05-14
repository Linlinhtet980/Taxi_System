@extends('layout.master')

@section('title', 'Driver Portal - Taxi Luxury')

@push('css')
    <link rel="stylesheet" href="{{ asset('css/root/theme.css') }}">
    <script>
        const savedTheme = localStorage.getItem('taxi_theme') || 'dark';
        document.documentElement.setAttribute('data-theme', savedTheme);
    </script>
    <link rel="stylesheet" href="{{ asset('css/layout/driver.css') }}">
@endpush

@section('master_content')
    <nav class="navbar">
        <a href="{{ route('driver.dashboard', $driver->id) }}" class="nav-logo">TAXI <span class="text-main">DRIVER</span></a>
        <div class="nav-right">
            <div class="nav-stats">
                <div class="nav-stat-item">
                    <i class="fa-solid fa-route text-primary"></i>
                    {{ $completedJobsCount ?? 0 }}
                </div>
                <a href="{{ route('driver.earnings', $driver->id) }}" class="nav-stat-item">
                    <i class="fa-solid fa-money-bill-trend-up text-primary"></i>
                    {{ number_format($todayEarnings ?? 0) }} K
                </a>
            </div>
            
            <button class="theme-toggle nav-action-btn" id="theme-toggle">
                <i class="fa-solid fa-moon" id="theme-icon"></i>
            </button>

            <a href="{{ route('driver.notifications', $driver->id) }}" class="nav-notif-link">
                <i class="fa-regular fa-bell"></i>
                @if(isset($unreadCount) && $unreadCount > 0)
                <span class="nav-notif-badge">
                    {{ $unreadCount }}
                </span>
                @endif
            </a>
            
            <div onclick="toggleSidebar()" class="mobile-menu-toggle">
                <i class="fa-solid fa-bars-staggered"></i>
            </div>
        </div>
    </nav>

    <div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>
    <div class="driver-sidebar" id="sidebar">
        <div class="sidebar-close" onclick="toggleSidebar()">
            <i class="fa-solid fa-xmark"></i>
        </div>
        <div class="sidebar-user-section">
            <div class="sidebar-user-avatar">
                <img src="{{ $driver->profile_picture ? asset($driver->profile_picture) : 'https://ui-avatars.com/api/?name=' . urlencode($driver->full_name) . '&background=D4AF37&color=fff' }}" alt="Avatar" class="w-100 h-100 object-cover">
            </div>
            <div class="overflow-hidden">
                <p class="sidebar-user-name">{{ $driver->full_name }}</p>
                <p class="sidebar-user-plate">{{ $driver->license_plate ?? 'Premium Driver' }}</p>
            </div>
        </div>

        <div class="sidebar-menu-list">
            <p class="nav-label">Main Menu</p>
            <a href="{{ route('driver.dashboard', $driver->id) }}" class="nav-menu-link {{ request()->routeIs('driver.dashboard') ? 'active' : '' }}">
                <i class="fa-solid fa-house"></i> Dashboard
            </a>
            <a href="{{ route('driver.demand.map', $driver->id) }}" class="nav-menu-link {{ request()->routeIs('driver.demand.map') ? 'active' : '' }}">
                <i class="fa-solid fa-map-location-dot"></i> Demand Map
            </a>
            <a href="{{ route('driver.jobs', $driver->id) }}" class="nav-menu-link {{ request()->routeIs('driver.jobs') ? 'active' : '' }}">
                <i class="fa-solid fa-route"></i> Trip History
            </a>
            <a href="{{ route('driver.leaderboard', $driver->id) }}" class="nav-menu-link {{ request()->routeIs('driver.leaderboard') ? 'active' : '' }}">
                <i class="fa-solid fa-trophy"></i> Leaderboard
            </a>
            <a href="{{ route('driver.referrals', $driver->id) }}" class="nav-menu-link {{ request()->routeIs('driver.referrals') ? 'active' : '' }}">
                <i class="fa-solid fa-users-viewfinder"></i> My Referrals
            </a>
            
            <p class="nav-label">Finance</p>
            <a href="{{ route('driver.earnings', $driver->id) }}" class="nav-menu-link {{ request()->routeIs('driver.earnings') ? 'active' : '' }}">
                <i class="fa-solid fa-chart-line"></i> Earnings
            </a>
            <a href="{{ route('driver.withdrawals', $driver->id) }}" class="nav-menu-link {{ request()->routeIs('driver.withdrawals') ? 'active' : '' }}">
                <i class="fa-solid fa-wallet"></i> My Wallet
            </a>

            <p class="nav-label">Settings</p>
            <a href="{{ route('driver.profile', $driver->id) }}" class="nav-menu-link {{ request()->routeIs('driver.profile') ? 'active' : '' }}">
                <i class="fa-solid fa-circle-user"></i> My Profile
            </a>
            <a href="{{ route('driver.vehicle', $driver->id) }}" class="nav-menu-link {{ request()->routeIs('driver.vehicle') ? 'active' : '' }}">
                <i class="fa-solid fa-car-side"></i> Vehicle
            </a>
            
            <div class="sidebar-logout-section">
                <form action="{{ route('driver.logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="nav-menu-link sidebar-logout-btn">
                        <i class="fa-solid fa-right-from-bracket"></i> Logout Account
                    </button>
                </form>
            </div>
        </div>
    </div>

    <main class="container">
        @if(session('success'))
            <div class="alert-success animate-fade">
                <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
            </div>
        @endif

        @yield('content')
    </main>

    <script>
        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('active');
            document.getElementById('sidebarOverlay').classList.toggle('active');
        }

        // Theme initialization for icons (Master handles the attribute and storage)
        const currentTheme = document.documentElement.getAttribute('data-theme') || 'dark';
        const themeIcon = document.getElementById('theme-icon');
        if (themeIcon) {
            if (currentTheme === 'light') {
                themeIcon.classList.replace('fa-moon', 'fa-sun');
            } else {
                themeIcon.classList.replace('fa-sun', 'fa-moon');
            }
        }
    </script>
@endsection

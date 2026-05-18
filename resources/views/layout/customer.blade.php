@extends('layout.master')

@section('title', 'Customer App - Taxi Luxury')

@push('css')
    <link rel="stylesheet" href="{{ asset('css/layout/customer.css') }}">
@endpush

@section('master_content')
    <nav class="navbar">
        <a href="{{ route('customer.dashboard') }}" class="nav-logo">TAXI <span class="text-main">LUXURY</span></a>
        <div class="nav-right">
            <div class="nav-stats">
                <a href="{{ route('customer.wallet.topup') }}" class="nav-stat-item">
                    <i class="fa-solid fa-wallet text-primary"></i>
                    {{ number_format($customer->wallet_balance ?? 0) }} K
                </a>
                <a href="{{ route('customer.points.exchange') }}" class="nav-stat-item">
                    <i class="fa-solid fa-star text-secondary"></i>
                    {{ number_format($customer->loyalty_points ?? 0) }} P
                </a>
            </div>
            
            <button class="theme-toggle nav-action-btn" id="theme-toggle">
                <i class="fa-solid fa-moon" id="theme-icon"></i>
            </button>

            <a href="{{ route('customer.activities') }}" class="nav-notif-link">
                <i class="fa-regular fa-bell"></i>
                @if(($unreadCount ?? 0) > 0)
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
    <div class="customer-sidebar" id="sidebar">
        <div class="sidebar-close" onclick="toggleSidebar()">
            <i class="fa-solid fa-xmark"></i>
        </div>
        <div class="sidebar-user-section">
            <div class="sidebar-user-avatar">
                <i class="fa-solid fa-user sidebar-user-icon"></i>
            </div>
            <div>
                <p class="sidebar-user-name">{{ $customer->name ?? 'Guest' }}</p>
                <div style="display: flex; gap: 10px; margin-top: 4px;">
                    <span style="font-size: 11px; color: var(--primary); font-weight: 700;"><i class="fa-solid fa-wallet"></i> {{ number_format($customer->wallet_balance ?? 0) }} K</span>
                    <span style="font-size: 11px; color: var(--accent-secondary); font-weight: 700;"><i class="fa-solid fa-star"></i> {{ number_format($customer->loyalty_points ?? 0) }} P</span>
                </div>
            </div>
        </div>

        <div class="sidebar-menu-list">
            <a href="{{ route('customer.dashboard') }}" class="nav-menu-link {{ request()->routeIs('customer.dashboard') ? 'active' : '' }}">
                <i class="fa-solid fa-house"></i> Home
            </a>
            <a href="{{ route('customer.booking') }}" class="nav-menu-link {{ request()->routeIs('customer.booking') ? 'active' : '' }}">
                <i class="fa-solid fa-taxi"></i> Book a Ride
            </a>
            <a href="{{ route('customer.activities') }}" class="nav-menu-link {{ request()->routeIs('customer.activities') ? 'active' : '' }}">
                <i class="fa-solid fa-clock-rotate-left"></i> My Activities
            </a>
            <a href="{{ route('customer.wallet.topup') }}" class="nav-menu-link {{ request()->routeIs('customer.wallet.topup') ? 'active' : '' }}">
                <i class="fa-solid fa-wallet"></i> My Wallet
            </a>
            <a href="{{ route('customer.points.exchange') }}" class="nav-menu-link {{ request()->routeIs('customer.points.exchange') ? 'active' : '' }}">
                <i class="fa-solid fa-star"></i> Exchange Points
            </a>
            <a href="{{ route('customer.settings') }}" class="nav-menu-link {{ request()->routeIs('customer.settings') ? 'active' : '' }}">
                <i class="fa-solid fa-gear"></i> Settings
            </a>
            
            <div class="sidebar-logout-section">
                <form action="{{ route('customer.logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="nav-menu-link sidebar-logout-btn">
                        <i class="fa-solid fa-right-from-bracket"></i> Logout Account
                    </button>
                </form>
            </div>
        </div>
    </div>

    <main class="container">
        @yield('content')
    </main>

    <script>
        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('active');
            document.getElementById('sidebarOverlay').classList.toggle('active');
        }
    </script>
@endsection

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Dashboard - Taxi</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #6366f1;
            --secondary: #4f46e5;
            --accent: #f59e0b;
            --bg: #f8fafc;
            --text: #1e293b;
            --text-light: #64748b;
            --danger: #ef4444;
            --success: #10b981;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Outfit', sans-serif;
        }

        body {
            background-color: var(--bg);
            color: var(--text);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .header {
            background: white;
            padding: 15px 25px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 4px 12px rgba(0,0,0,0.03);
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .avatar {
            width: 42px;
            height: 42px;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: white;
            border-radius: 12px;
            display: flex;
            justify-content: center;
            align-items: center;
            font-weight: 700;
            font-size: 18px;
            box-shadow: 0 4px 10px rgba(99, 102, 241, 0.3);
        }

        .welcome-text p {
            font-size: 11px;
            color: var(--text-light);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-weight: 700;
        }

        .welcome-text h2 {
            font-size: 16px;
            font-weight: 700;
        }

        .header-icons {
            display: flex;
            gap: 15px;
        }

        .icon-btn {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            background: #f1f5f9;
            display: flex;
            justify-content: center;
            align-items: center;
            color: var(--text-light);
            font-size: 18px;
            position: relative;
        }

        .badge {
            position: absolute;
            top: -5px;
            right: -5px;
            background: var(--danger);
            color: white;
            font-size: 10px;
            width: 18px;
            height: 18px;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            border: 2px solid white;
        }

        /* Header Stats Pills */
        .header-stats {
            display: flex;
            gap: 8px;
            margin-right: 10px;
        }
        .stat-pill {
            background: #f1f5f9;
            padding: 6px 12px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            gap: 6px;
            text-decoration: none;
            border: 1px solid #e2e8f0;
        }
        .stat-pill i { font-size: 12px; }
        .stat-pill span {
            font-size: 12px;
            font-weight: 700;
            color: var(--text);
        }
        .stat-pill.wallet i { color: var(--primary); }
        .stat-pill.points i { color: var(--accent); }

        .container {
            padding: 20px;
            max-width: 600px;
            margin: 0 auto;
        }

        /* Hero Search Section */
        .hero-search {
            background: linear-gradient(135deg, #0ea5e9, #2563eb);
            border-radius: 32px;
            padding: 40px 25px;
            margin-bottom: 25px;
            color: white;
            box-shadow: 0 15px 35px rgba(37, 99, 235, 0.2);
            position: relative;
            overflow: hidden;
            text-align: center;
        }

        .hero-search::after {
            content: '\f1b9';
            font-family: 'Font Awesome 6 Free';
            font-weight: 900;
            position: absolute;
            right: -10px;
            bottom: -10px;
            font-size: 80px;
            opacity: 0.15;
            transform: rotate(-15deg);
        }

        .hero-search h1 {
            font-size: 24px;
            font-weight: 800;
            margin-bottom: 20px;
            line-height: 1.3;
        }

        .search-trigger {
            background: white;
            padding: 16px 20px;
            border-radius: 18px;
            display: flex;
            align-items: center;
            gap: 12px;
            color: var(--text-light);
            cursor: pointer;
            transition: 0.3s;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        }

        .search-trigger:hover {
            border-color: var(--primary);
            background: white;
            box-shadow: 0 5px 15px rgba(99, 102, 241, 0.1);
            transform: translateY(-2px);
        }

        .search-trigger i {
            color: var(--primary);
            font-size: 20px;
        }

        .search-trigger span {
            font-weight: 700;
            font-size: 15px;
            color: var(--text);
        }

        /* Weather Widget */
        .weather-widget {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: white;
            padding: 15px 20px;
            border-radius: 20px;
            margin-bottom: 20px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.02);
            border: 1px solid #f1f5f9;
        }

        .weather-info {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .weather-icon {
            font-size: 24px;
            color: var(--accent);
        }

        .weather-temp {
            font-size: 18px;
            font-weight: 700;
        }

        .weather-desc {
            font-size: 12px;
            color: var(--text-light);
        }

        /* Active Ride Card */
        .active-ride {
            background: var(--primary);
            color: white;
            border-radius: 24px;
            padding: 20px;
            margin-bottom: 25px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 10px 20px rgba(99, 102, 241, 0.3);
            cursor: pointer;
        }

        .ride-info h3 {
            font-size: 14px;
            opacity: 0.8;
            margin-bottom: 4px;
        }

        .ride-info p {
            font-size: 18px;
            font-weight: 700;
        }

        .ride-icon {
            width: 50px;
            height: 50px;
            background: rgba(255,255,255,0.2);
            border-radius: 15px;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 24px;
        }

        /* Wallet Section */
        .stats-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin-bottom: 25px;
        }

        .stat-card {
            background: white;
            padding: 20px;
            border-radius: 24px;
            border: 1px solid #f1f5f9;
            box-shadow: 0 4px 10px rgba(0,0,0,0.02);
        }

        .stat-label {
            font-size: 12px;
            color: var(--text-light);
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .stat-value {
            font-size: 20px;
            font-weight: 700;
            color: var(--text);
        }

        /* Quick Actions */
        .section-title {
            font-size: 16px;
            font-weight: 700;
            margin-bottom: 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .quick-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 12px;
            margin-bottom: 25px;
        }

        .quick-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 8px;
        }

        .quick-icon {
            width: 60px;
            height: 60px;
            background: white;
            border-radius: 18px;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 22px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.03);
            border: 1px solid #f1f5f9;
            cursor: pointer;
            transition: 0.3s;
        }

        .quick-icon:hover {
            border-color: var(--primary);
            color: var(--primary);
            transform: translateY(-3px);
        }

        .quick-item span {
            font-size: 11px;
            font-weight: 700;
            color: var(--text-light);
        }

        /* Recent Trips */
        .recent-list {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .recent-card {
            background: white;
            padding: 15px;
            border-radius: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border: 1px solid #f1f5f9;
        }

        .recent-main {
            display: flex;
            gap: 15px;
            align-items: center;
        }

        .loc-icon {
            width: 40px;
            height: 40px;
            background: #f1f5f9;
            border-radius: 10px;
            display: flex;
            justify-content: center;
            align-items: center;
            color: var(--text-light);
        }

        .recent-details h5 {
            font-size: 14px;
            font-weight: 700;
            margin-bottom: 2px;
        }

        .recent-details p {
            font-size: 11px;
            color: var(--text-light);
        }

        .btn-rebook {
            padding: 8px 15px;
            background: #f1f5f9;
            border-radius: 10px;
            font-size: 12px;
            font-weight: 700;
            color: var(--primary);
            cursor: pointer;
            border: none;
            transition: 0.3s;
        }

        .btn-rebook:hover {
            background: var(--primary);
            color: white;
        }

        /* Bottom Nav */
        .bottom-nav {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background: white;
            padding: 10px 30px 25px;
            display: flex;
            justify-content: space-between;
            box-shadow: 0 -4px 20px rgba(0,0,0,0.05);
            z-index: 1000;
        }

        .nav-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 4px;
            color: #94a3b8;
            text-decoration: none;
        }

        .nav-item.active { color: var(--primary); }
        .nav-item i { font-size: 22px; }
        .nav-item span { font-size: 10px; font-weight: 700; }

        /* Footer Styles */
        .footer {
            background: #e2e8f0;
            padding: 30px 20px;
            margin-top: auto;
            text-align: center;
            width: 100%;
            border-top: 1px solid #cbd5e1;
        }
        .footer-brand {
            font-size: 20px;
            font-weight: 800;
            color: var(--text);
            margin-bottom: 10px;
            letter-spacing: 1px;
        }
        .footer-links {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }
        .footer-links a {
            text-decoration: none;
            color: var(--text-light);
            font-size: 15px;
            font-weight: 600;
            transition: 0.3s;
        }
        .footer-links a:hover { color: var(--primary); }
        
        .footer-social {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin-bottom: 20px;
        }
        .footer-social a {
            width: 40px;
            height: 40px;
            background: white;
            border-radius: 16px;
            display: flex;
            justify-content: center;
            align-items: center;
            color: var(--text-light);
            font-size: 20px;
            text-decoration: none;
            transition: 0.3s;
            box-shadow: 0 4px 10px rgba(0,0,0,0.05);
        }
        .footer-social a:hover {
            background: var(--primary);
            color: white;
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(99, 102, 241, 0.2);
        }
        
        .copyright {
            font-size: 11px;
            color: var(--text-light);
            padding-top: 20px;
            opacity: 0.8;
            border-top: 1px solid rgba(0,0,0,0.05);
        }

        /* Sidebar Styles */
        .sidebar {
            position: fixed;
            top: 0;
            right: -280px;
            width: 280px;
            height: 100%;
            background: white;
            z-index: 2000;
            transition: 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: -10px 0 30px rgba(0,0,0,0.05);
            padding: 30px 20px;
            display: flex;
            flex-direction: column;
        }
        .sidebar.active { right: 0; }

        .sidebar-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 40px;
        }
        .sidebar-close {
            width: 40px;
            height: 40px;
            background: #f1f5f9;
            border-radius: 12px;
            display: flex;
            justify-content: center;
            align-items: center;
            cursor: pointer;
        }

        .sidebar-menu { display: flex; flex-direction: column; gap: 10px; }
        .sidebar-link {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 16px 20px;
            text-decoration: none;
            color: var(--text);
            font-weight: 600;
            border-radius: 16px;
            transition: 0.3s;
        }
        .sidebar-link i { font-size: 20px; width: 25px; color: var(--text-light); }
        .sidebar-link:hover, .sidebar-link.active {
            background: var(--bg);
            color: var(--primary);
        }
        .sidebar-link:hover i, .sidebar-link.active i { color: var(--primary); }

        .sidebar-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.3);
            backdrop-filter: blur(4px);
            z-index: 1999;
            display: none;
        }
        .sidebar-overlay.active { display: block; }

        @media (max-width: 400px) {
            .quick-grid { grid-template-columns: repeat(2, 1fr); }
            .stats-grid { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>
    <div class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <h3 style="font-weight: 800; color: var(--primary);">MENU</h3>
            <div class="sidebar-close" onclick="toggleSidebar()">
                <i class="fa-solid fa-xmark"></i>
            </div>
        </div>
        <div class="sidebar-menu">
            <a href="{{ route('customer.dashboard') }}" class="sidebar-link {{ request()->routeIs('customer.dashboard') ? 'active' : '' }}">
                <i class="fa-solid fa-house"></i> Home
            </a>
            <a href="{{ route('customer.wallet.topup') }}" class="sidebar-link {{ request()->routeIs('customer.wallet.topup') ? 'active' : '' }}">
                <i class="fa-solid fa-wallet"></i> Top-up Wallet
            </a>
            <a href="{{ route('customer.booking') }}" class="sidebar-link">
                <i class="fa-solid fa-map-location-dot"></i> Book a Ride
            </a>
            <a href="{{ route('customer.activities') }}" class="sidebar-link">
                <i class="fa-solid fa-clock-rotate-left"></i> Activities
            </a>
            <a href="{{ route('customer.settings') }}" class="sidebar-link">
                <i class="fa-solid fa-gear"></i> Settings
            </a>
            <hr style="border: none; border-top: 1px solid #f1f5f9; margin: 10px 0;">
            <form action="{{ route('customer.logout') }}" method="POST">
                @csrf
                <button type="submit" style="width: 100%; text-align: left; background: none; border: none; padding: 0;">
                    <div class="sidebar-link" style="color: var(--danger);">
                        <i class="fa-solid fa-arrow-right-from-bracket" style="color: var(--danger);"></i> Log Out
                    </div>
                </button>
            </form>
        </div>
    </div>
    <header class="header">
        <div class="user-info">
            <div class="avatar">
                @if($customer->profile_picture)
                    <img src="{{ Str::startsWith($customer->profile_picture, 'uploads/') ? asset($customer->profile_picture) : asset('storage/' . $customer->profile_picture) }}" alt="Profile" style="width: 100%; height: 100%; object-fit: cover; border-radius: 12px;">
                @else
                    {{ substr($customer->name, 0, 1) }}
                @endif
            </div>
            <div class="welcome-text">
                <p>{{ $greeting }}</p>
                <h2>{{ $customer->name }}</h2>
            </div>
        </div>
        <div class="header-icons">
            <div class="header-stats">
                <a href="{{ route('customer.wallet.topup') }}" class="stat-pill wallet">
                    <i class="fa-solid fa-wallet"></i>
                    <span>{{ number_format($customer->wallet_balance) }} K</span>
                </a>
                <a href="{{ route('customer.wallet.topup') }}" class="stat-pill points">
                    <i class="fa-solid fa-star"></i>
                    <span>{{ number_format($customer->loyalty_points) }} P</span>
                </a>
            </div>
            <div class="icon-btn" onclick="window.location.href='#'">
                <i class="fa-regular fa-bell"></i>
                @if($unreadNotificationsCount > 0)
                <span class="badge">{{ $unreadNotificationsCount }}</span>
                @endif
            </div>
            <div class="icon-btn" onclick="toggleSidebar()">
                <i class="fa-solid fa-bars"></i>
            </div>
        </div>
    </header>

    <main class="container">
        <!-- Hero Search Section -->
        <div class="hero-search">
            <h1>ဘယ်ကိုသွားမလဲ?</h1>
            <div class="search-trigger" onclick="window.location.href='{{ route('customer.booking') }}'">
                <i class="fa-solid fa-magnifying-glass"></i>
                <span>သွားလိုသည့်နေရာကို ရှာဖွေပါ</span>
            </div>
        </div>

        <!-- Weather & Greeting Widget -->
        <div class="weather-widget">
            <div class="weather-info">
                <i class="fa-solid fa-sun weather-icon"></i>
                <div>
                    <div class="weather-temp">32°C</div>
                    <div class="weather-desc">နေသာသောနေ့လေးပါ</div>
                </div>
            </div>
            <div style="text-align: right;">
                <div style="font-weight: 700; font-size: 14px;">{{ now()->format('h:i A') }}</div>
                <div style="font-size: 12px; color: var(--text-light);">{{ now()->format('D, d M') }}</div>
            </div>
        </div>

        <!-- Active Ride Status (If Any) -->
        @if($activeBooking)
        <div class="active-ride" onclick="window.location.href='{{ route('customer.waiting', $activeBooking->id) }}'">
            <div class="ride-info">
                <h3>လက်ရှိခရီးစဉ်အခြေအနေ</h3>
                <p>
                    @if($activeBooking->status == 'pending') ယာဉ်မောင်းရှာဖွေနေပါသည်...
                    @elseif($activeBooking->status == 'accepted') ယာဉ်မောင်း လာကြိုနေပါပြီ
                    @else ခရီးစဉ် စတင်နေပါပြီ @endif
                </p>
            </div>
            <div class="ride-icon">
                <i class="fa-solid fa-taxi-board"></i>
            </div>
        </div>
        @endif


        <!-- Quick Services -->
        <div class="section-title">အမြန်ဝန်ဆောင်မှုများ</div>
        <div class="quick-grid">
            <div class="quick-item" onclick="window.location.href='{{ route('customer.booking') }}'">
                <div class="quick-icon" style="color: var(--primary);"><i class="fa-solid fa-car"></i></div>
                <span>ကားခေါ်မည်</span>
            </div>
            <div class="quick-item" onclick="window.location.href='{{ route('customer.booking') }}?type=bike'">
                <div class="quick-icon" style="color: var(--success);"><i class="fa-solid fa-motorcycle"></i></div>
                <span>ဆိုင်ကယ်</span>
            </div>
            <div class="quick-item" onclick="window.location.href='{{ route('customer.booking') }}?type=delivery'">
                <div class="quick-icon" style="color: var(--danger);"><i class="fa-solid fa-box"></i></div>
                <span>ပို့ဆောင်ရေး</span>
            </div>
            <div class="quick-item">
                <div class="quick-icon" style="color: var(--accent);"><i class="fa-solid fa-ellipsis"></i></div>
                <span>အခြား</span>
            </div>
        </div>

        <!-- Recent Trips / Re-book -->
        <div class="section-title">
            မကြာသေးမီက ခရီးစဉ်များ
            <a href="{{ route('customer.activities') }}" style="font-size: 12px; color: var(--primary); text-decoration: none;">အားလုံးကြည့်ရန်</a>
        </div>
        <div class="recent-list">
            @forelse($recentBookings as $booking)
            <div class="recent-card">
                <div class="recent-main">
                    <div class="loc-icon"><i class="fa-solid fa-clock-rotate-left"></i></div>
                    <div class="recent-details">
                        <h5>{{ Str::limit($booking->dropoff_location, 25) }}</h5>
                        <p>{{ $booking->created_at->format('d M, Y') }} • {{ number_format($booking->fare) }} Ks</p>
                    </div>
                </div>
                <button class="btn-rebook" onclick="window.location.href='{{ route('customer.booking', ['pickup' => $booking->pickup_location, 'dropoff' => $booking->dropoff_location]) }}'">
                    ပြန်ခေါ်မည်
                </button>
            </div>
            @empty
            <div style="text-align: center; padding: 20px; color: var(--text-light); font-size: 14px;">
                ခရီးစဉ်မှတ်တမ်း မရှိသေးပါ။
            </div>
            @endforelse
        </div>
    </main>

    <footer class="footer">
        <div class="footer-brand">TAXI</div>
        <div class="footer-links">
            <a href="#">About Us</a>
            <a href="#">Terms</a>
            <a href="#">Privacy</a>
            <a href="#">Support</a>
        </div>
        <div class="footer-social">
            <a href="#"><i class="fa-brands fa-facebook-f"></i></a>
            <a href="#"><i class="fa-brands fa-telegram"></i></a>
            <a href="#"><i class="fa-brands fa-viber"></i></a>
        </div>
        <div class="copyright">
            &copy; 2026 Taxi System. All rights reserved.
        </div>
    </footer>

    <script>
        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('active');
            document.getElementById('sidebarOverlay').classList.toggle('active');
        }
    </script>
</body>
</html>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Driver Portal - Taxi Luxury</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --bg-main: #060505ff;
            --bg-card: #060505ff;
            --primary: #f9db79ff;
            --primary-light: rgba(217, 195, 68, 0.1);
            --text-main: #ffffff;
            --text-dim: rgba(255, 255, 255, 0.5);
            --sidebar-width: 280px;
            --header-height: 80px;
            --glass-bg: rgba(105, 94, 94, 0.7);
            --border-color: rgba(255, 196, 0, 0.41);
        }

        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Outfit', sans-serif; }

        body {
            background-color: var(--bg-main);
            color: var(--text-main);
            min-height: 100vh;
            overflow-x: hidden;
            display: flex;
        }

        /* Sidebar Styling */
        .sidebar {
            position: fixed;
            left: 0;
            top: 0;
            width: var(--sidebar-width);
            height: 100vh;
            background: #0a0a0a;
            border-right: 1px solid var(--border-color);
            z-index: 2000;
            transition: 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            padding: 35px 20px;
            display: flex;
            flex-direction: column;
        }

        .sidebar-header {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 40px;
            padding: 0 10px;
        }
        
        .sidebar-logo {
            width: 40px;
            height: 40px;
            background: var(--primary);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #000;
            font-size: 20px;
            box-shadow: 0 0 20px rgba(212, 175, 55, 0.3);
        }

        .sidebar-header h2 { font-size: 20px; font-weight: 800; letter-spacing: 1px; color: #fff; }

        .sidebar-user {
            background: rgba(255,255,255,0.03);
            border-radius: 20px;
            padding: 15px;
            margin-bottom: 30px;
            display: flex;
            align-items: center;
            gap: 12px;
            border: 1px solid rgba(255,255,255,0.05);
        }

        .sidebar-avatar {
            width: 45px;
            height: 45px;
            border-radius: 14px;
            border: 1.5px solid var(--primary);
            object-fit: cover;
        }

        .user-info h4 { font-size: 14px; font-weight: 700; color: #fff; }
        .user-info p { font-size: 11px; color: var(--primary); font-weight: 600; text-transform: uppercase; }

        .nav-menu { 
            flex: 1; 
            list-style: none; 
            overflow-y: auto; 
            scrollbar-width: none; /* Firefox */
            -ms-overflow-style: none;  /* IE and Edge */
        }
        .nav-menu::-webkit-scrollbar {
            display: none; /* Chrome, Safari and Opera */
        }
        .nav-label { font-size: 10px; font-weight: 800; color: var(--text-dim); text-transform: uppercase; letter-spacing: 2px; margin: 25px 10px 15px; }

        .nav-link {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 14px 18px;
            color: var(--text-dim);
            text-decoration: none;
            border-radius: 16px;
            font-weight: 600;
            font-size: 14px;
            transition: none;
            margin-bottom: 5px;
        }
        .nav-link i { font-size: 18px; width: 22px; text-align: center; transition: none; }
        .nav-link:hover, .nav-link.active {
            color: #fff;
            background: var(--primary-light);
            border: 1px solid rgba(212, 175, 55, 0.1);
        }
        .nav-link.active i { color: var(--primary); text-shadow: 0 0 10px var(--primary); }

        .logout-btn {
            margin-top: 20px;
            color: #ff5e5e;
            background: rgba(255, 94, 94, 0.05);
        }
        .logout-btn:hover { background: rgba(255, 94, 94, 0.1); color: #ff5e5e; }

        /* Main Content Wrapper */
        .main-wrapper {
            flex: 1;
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            transition: 0.3s;
        }
        
        /* Top Header */
        header {
            height: var(--header-height);
            padding: 0 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 1000;
            background: rgba(6, 6, 6, 0.8);
            backdrop-filter: blur(15px);
            border-bottom: 1px solid var(--border-color);
        }

        .header-left { display: flex; align-items: center; gap: 20px; }
        .menu-toggle { display: none; cursor: pointer; font-size: 22px; color: var(--primary); }

        .header-right { display: flex; align-items: center; gap: 25px; }

        .header-stat {
            display: flex;
            align-items: center;
            gap: 10px;
            background: rgba(255,255,255,0.03);
            padding: 8px 16px;
            border-radius: 12px;
            border: 1px solid rgba(255,255,255,0.05);
        }
        .header-stat i { color: var(--primary); font-size: 14px; }
        .stat-label { font-size: 10px; font-weight: 700; color: var(--text-dim); text-transform: uppercase; }
        .stat-value { font-size: 14px; font-weight: 700; color: #fff; }

        .notification-btn {
            width: 45px;
            height: 45px;
            border-radius: 14px;
            background: rgba(255,255,255,0.03);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--text-dim);
            font-size: 18px;
            text-decoration: none;
            transition: 0.3s;
            border: 1px solid rgba(255,255,255,0.05);
            position: relative;
        }
        .notification-btn:hover { color: var(--primary); background: var(--primary-light); }
        .notification-btn .badge {
            position: absolute;
            top: 10px;
            right: 10px;
            width: 8px;
            height: 8px;
            background: var(--primary);
            border-radius: 50%;
            box-shadow: 0 0 10px var(--primary);
        }

        .container {
            flex: 1;
            width: 100%;
            max-width: 1400px;
            margin: 0 auto;
            padding: 40px;
        }

        /* Responsive Layout */
        @media (max-width: 1100px) {
            .sidebar { left: -100%; }
            .sidebar.active { left: 0; }
            .main-wrapper { margin-left: 0; }
            .menu-toggle { display: block; }
            header { padding: 0 20px; }
            .container { padding: 25px 20px; }
            .header-stat { display: none; }
        }

        /* Overlay */
        .overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.7);
            backdrop-filter: blur(5px);
            z-index: 1500;
            display: none;
            opacity: 0;
            transition: 0.3s;
        }
        .overlay.active { display: block; opacity: 1; }

        .glass {
            background: rgba(20, 20, 20, 0.6);
            backdrop-filter: blur(20px);
            border: 1px solid var(--border-color);
            border-radius: 24px;
            transition: 0.3s;
        }
        .glass:hover { border-color: rgba(212, 175, 55, 0.3); }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade { animation: fadeIn 0.6s ease-out forwards; }
    </style>
    @stack('css')
</head>
<body>

    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <div class="sidebar-logo">
                <i class="fa-solid fa-taxi"></i>
            </div>
            <h2>LUXURY</h2>
        </div>

        <div class="sidebar-user">
            <img src="{{ $driver->profile_picture ? asset($driver->profile_picture) : 'https://ui-avatars.com/api/?name=' . urlencode($driver->full_name) . '&background=D4AF37&color=fff' }}" alt="Avatar" class="sidebar-avatar">
            <div class="user-info">
                <h4>{{ Str::limit($driver->full_name, 15) }}</h4>
                <p>{{ $driver->license_plate ?? 'Premium Driver' }}</p>
            </div>
        </div>

        <p class="nav-label">Main Menu</p>
        <ul class="nav-menu">
            <li class="nav-item">
                <a href="{{ route('driver.dashboard', $driver->id) }}" class="nav-link {{ request()->routeIs('driver.dashboard') ? 'active' : '' }}">
                    <i class="fa-solid fa-house"></i> Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('driver.demand.map', $driver->id) }}" class="nav-link {{ request()->routeIs('driver.demand.map') ? 'active' : '' }}">
                    <i class="fa-solid fa-map-location-dot"></i> Demand Map
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('driver.jobs', $driver->id) }}" class="nav-link {{ request()->routeIs('driver.jobs') ? 'active' : '' }}">
                    <i class="fa-solid fa-route"></i> Trip History
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('driver.leaderboard', $driver->id) }}" class="nav-link {{ request()->routeIs('driver.leaderboard') ? 'active' : '' }}">
                    <i class="fa-solid fa-trophy"></i> Leaderboard
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('driver.referrals', $driver->id) }}" class="nav-link {{ request()->routeIs('driver.referrals') ? 'active' : '' }}">
                    <i class="fa-solid fa-users-viewfinder"></i> My Referrals
                </a>
            </li>
            
            <p class="nav-label">Finance</p>
            <li class="nav-item">
                <a href="{{ route('driver.earnings', $driver->id) }}" class="nav-link {{ request()->routeIs('driver.earnings') ? 'active' : '' }}">
                    <i class="fa-solid fa-chart-line"></i> Earnings
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('driver.withdrawals', $driver->id) }}" class="nav-link {{ request()->routeIs('driver.withdrawals') ? 'active' : '' }}">
                    <i class="fa-solid fa-wallet"></i> My Wallet
                </a>
            </li>

            <p class="nav-label">Settings</p>
            <li class="nav-item">
                <a href="{{ route('driver.profile', $driver->id) }}" class="nav-link {{ request()->routeIs('driver.profile') ? 'active' : '' }}">
                    <i class="fa-solid fa-circle-user"></i> My Profile
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('driver.vehicle', $driver->id) }}" class="nav-link {{ request()->routeIs('driver.vehicle') ? 'active' : '' }}">
                    <i class="fa-solid fa-car-side"></i> Vehicle
                </a>
            </li>
        </ul>

        <form action="{{ route('driver.logout') }}" method="POST" id="logout-form" style="display: none;">@csrf</form>
        <a href="javascript:void(0)" onclick="document.getElementById('logout-form').submit();" class="nav-link logout-btn">
            <i class="fa-solid fa-arrow-right-from-bracket"></i> Sign Out
        </a>
    </div>

    <!-- Overlay -->
    <div class="overlay" id="overlay"></div>

    <div class="main-wrapper">
        <header>
            <div class="header-left">
                <div class="menu-toggle" id="menuToggle">
                    <i class="fa-solid fa-bars-staggered"></i>
                </div>
                <div style="display: flex; flex-direction: column;">
                    <h4 style="font-weight: 800; font-size: 18px; color: #fff;">Driver Portal</h4>
                    <p style="font-size: 11px; color: var(--primary); font-weight: 700; text-transform: uppercase; letter-spacing: 1px;">Luxury Fleet Services</p>
                </div>
            </div>
            
            <div class="header-right">
                <div class="header-stat">
                    <i class="fa-solid fa-route"></i>
                    <div>
                        <p class="stat-label">Total Jobs</p>
                        <p class="stat-value">{{ $completedJobsCount ?? 0 }}</p>
                    </div>
                </div>
                <div class="header-stat">
                    <i class="fa-solid fa-money-bill-trend-up"></i>
                    <div>
                        <p class="stat-label">Today</p>
                        <p class="stat-value">{{ number_format($todayEarnings ?? 0) }} K</p>
                    </div>
                </div>
                
                <a href="{{ route('driver.notifications', $driver->id) }}" class="notification-btn">
                    <i class="fa-regular fa-bell"></i>
                    <span class="badge"></span>
                </a>
            </div>
        </header>

        <div class="container">
            @if(session('success'))
                <div style="padding: 18px 25px; background: rgba(74, 222, 128, 0.1); border: 1px solid rgba(74, 222, 128, 0.2); border-radius: 20px; margin-bottom: 30px; color: #4ade80; font-size: 14px; font-weight: 600; display: flex; align-items: center; gap: 12px;" class="animate-fade">
                    <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
                </div>
            @endif

            @yield('content')
        </div>
    </div>

    <script>
        const menuToggle = document.getElementById('menuToggle');
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('overlay');

        function toggleSidebar() {
            sidebar.classList.toggle('active');
            overlay.classList.toggle('active');
        }

        menuToggle.addEventListener('click', toggleSidebar);
        overlay.addEventListener('click', toggleSidebar);
    </script>
    @stack('js')
</body>
</html>

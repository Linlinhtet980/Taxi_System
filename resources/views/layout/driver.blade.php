<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Driver Portal - TaxiAdmin</title>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Custom CSS -->
    <style>
        :root {
            --bg-main: #000000;
            --bg-card: #141414;
            --primary: #D4AF37; /* Luxury Gold */
            --primary-dark: #B8860B;
            --text-main: #ffffff;
            --text-dim: #a1a1a1;
            --glass-border: rgba(212, 175, 55, 0.2);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Outfit', sans-serif;
        }

        body {
            background: linear-gradient(135deg, #000000 0%, #1a1a1a 100%);
            color: var(--text-main);
            min-height: 100vh;
            padding-bottom: 90px; /* Space for bottom nav */
        }

        .glass {
            background: var(--bg-card);
            backdrop-filter: blur(12px);
            border: 1px solid var(--glass-border);
            border-radius: 25px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }

        header {
            padding: 25px 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .driver-profile {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .avatar {
            width: 50px;
            height: 50px;
            border-radius: 18px;
            border: 2px solid var(--primary);
            object-fit: cover;
            box-shadow: 0 5px 15px rgba(212, 175, 55, 0.2);
        }

        .welcome-text h2 {
            font-size: 1.2rem;
            font-weight: 700;
            color: var(--white);
        }

        .welcome-text p {
            font-size: 0.85rem;
            color: var(--primary);
            font-weight: 600;
        }

        /* Bottom Navigation */
        .bottom-nav {
            position: fixed;
            bottom: 20px;
            left: 20px;
            right: 20px;
            background: #111;
            border: 1px solid var(--glass-border);
            border-radius: 25px;
            height: 75px;
            display: flex;
            justify-content: space-around;
            align-items: center;
            z-index: 1000;
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.8);
        }

        .nav-item {
            text-decoration: none;
            color: #555;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 6px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .nav-item i {
            font-size: 1.3rem;
        }

        .nav-item span {
            font-size: 0.75rem;
            font-weight: 600;
        }

        .nav-item.active {
            color: var(--primary);
            transform: translateY(-5px);
            text-shadow: 0 0 10px rgba(212, 175, 55, 0.4);
        }

        .status-badge {
            padding: 5px 15px;
            border-radius: 50px;
            font-size: 0.75rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(15px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .animate-fade {
            animation: fadeIn 0.6s ease-out forwards;
        }
    </style>
    @stack('css')
    <link rel="stylesheet" href="{{ asset('css/layout/driver.css') }}">
</head>
<body>
    <div class="container">
        <header>
            <div class="driver-profile">
                <img src="{{ $driver->profile_picture ? asset('storage/' . $driver->profile_picture) : 'https://ui-avatars.com/api/?name=' . urlencode($driver->full_name) . '&background=a855f7&color=fff' }}" alt="Avatar" class="avatar">
                <div class="welcome-text">
                    <h2>Hello, {{ explode(' ', $driver->full_name)[0] }}</h2>
                    <p>{{ $driver->license_no }}</p>
                </div>
            </div>
            <div  class="style-99129a">
                <div  class="style-73e522">
                    <p  class="style-ece134">Today</p>
                    <p  class="style-590e97">{{ number_format($todayEarningsGlobal ?? 0) }}</p>
                </div>
                <a href="{{ route('driver.notifications', $driver->id) }}" class="notifications style-09a36b" >
                    <i class="fa-regular fa-bell style-b28cb1" ></i>
                </a>
            </div>

        </header>

        @if(session('success'))
            <div class="glass animate-fade style-ab29c4" >
                <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="glass animate-fade style-9e5a4a" >
                <ul  class="style-0fe735">
                    @foreach($errors->all() as $error)
                        <li><i class="fa-solid fa-circle-xmark"></i> {{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @yield('content')

    </div>

    <!-- Bottom Navigation -->
    <nav class="bottom-nav">
        <a href="{{ route('driver.dashboard', $driver->id) }}" class="nav-item {{ request()->routeIs('driver.dashboard') ? 'active' : '' }}">
            <i class="fa-solid fa-house"></i>
            <span>Home</span>
        </a>
        <a href="{{ route('driver.earnings', $driver->id) }}" class="nav-item {{ request()->routeIs('driver.earnings') ? 'active' : '' }}">
            <i class="fa-solid fa-chart-line"></i>
            <span>Earnings</span>
        </a>
        <a href="{{ route('driver.withdrawals', $driver->id) }}" class="nav-item {{ request()->routeIs('driver.withdrawals') ? 'active' : '' }}">
            <i class="fa-solid fa-wallet"></i>
            <span>Wallet</span>
        </a>

        <a href="{{ route('driver.demand.map', $driver->id) }}" class="nav-item {{ request()->routeIs('driver.demand.map') ? 'active' : '' }}">
            <i class="fa-solid fa-map-location-dot"></i>
            <span>Demand</span>
        </a>

        <a href="{{ route('driver.profile', $driver->id) }}" class="nav-item {{ request()->routeIs('driver.profile') ? 'active' : '' }}">
            <i class="fa-solid fa-user-gear"></i>
            <span>Profile</span>
        </a>

        <!-- SOS Floating Style Button -->
        <a href="javascript:void(0)" onclick="sendSOS()"  class="style-3e38a8">
            <div  class="style-a9cfc8">
                <i class="fa-solid fa-triangle-exclamation"></i>
            </div>
            <span  class="style-625a6c">SOS</span>
        </a>

    </nav>

    @stack('js')
</body>
</html>

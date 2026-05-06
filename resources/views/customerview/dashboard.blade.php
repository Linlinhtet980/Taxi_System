<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Home - Taxi</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #6366f1;
            --secondary: #4f46e5;
            --accent: #f59e0b;
            --bg: #f8fafc;
            --text: #1e293b;
            --text-light: #64748b;
            --glass: rgba(255, 255, 255, 0.9);
        }

        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Outfit', sans-serif; }
        body { background-color: var(--bg); color: var(--text); overflow-x: hidden; }

        /* Navigation Bar */
        .navbar {
            position: fixed; top: 0; left: 0; right: 0; height: 70px;
            background: rgba(255, 255, 255, 0.8); backdrop-filter: blur(10px);
            display: flex; justify-content: space-between; align-items: center;
            padding: 0 30px; z-index: 1000; border-bottom: 1px solid rgba(0,0,0,0.05);
        }
        .nav-logo { font-size: 20px; font-weight: 800; color: var(--primary); text-decoration: none; }
        .nav-right { display: flex; align-items: center; gap: 20px; }
        .nav-stats { display: flex; gap: 10px; }
        .nav-stat-item {
            padding: 6px 12px; background: #f1f5f9; border-radius: 10px;
            font-size: 13px; font-weight: 700; color: var(--text); text-decoration: none;
            display: flex; align-items: center; gap: 6px;
        }
        .nav-profile {
            width: 40px; height: 40px; border-radius: 12px; background: var(--primary);
            color: white; display: flex; align-items: center; justify-content: center;
            font-weight: 700; text-decoration: none; overflow: hidden;
        }
        .nav-profile img { width: 100%; height: 100%; object-fit: cover; }

        /* Hero Section */
        .hero {
            height: 450px; background: linear-gradient(rgba(0,0,0,0.3), rgba(0,0,0,0.5)), url('{{ asset('images/site/shwedagon_hero.jpg') }}');
            background-size: cover; background-position: center;
            display: flex; flex-direction: column; justify-content: center; align-items: center;
            padding: 20px; color: white; text-align: center; margin-top: 70px;
        }
        .hero h1 { font-size: 32px; font-weight: 800; margin-bottom: 25px; text-shadow: 0 4px 10px rgba(0,0,0,0.3); }

        .search-container {
            width: 100%; max-width: 600px; background: white;
            padding: 10px; border-radius: 24px; box-shadow: 0 20px 40px rgba(0,0,0,0.2);
            display: flex; align-items: center; gap: 15px; cursor: pointer;
        }
        .search-input-box {
            flex: 1; padding: 15px 20px; background: #f8fafc; border-radius: 18px;
            display: flex; align-items: center; gap: 12px; color: var(--text-light);
        }
        .search-input-box span { font-weight: 600; font-size: 16px; }
        .btn-search {
            background: var(--primary); color: white; border: none; padding: 15px 25px;
            border-radius: 18px; font-weight: 700; cursor: pointer; transition: 0.3s;
        }
        .btn-search:hover { background: var(--secondary); transform: scale(1.02); }

        /* Content Container */
        .container { max-width: 1000px; margin: 20px auto 40px; padding: 0 20px; position: relative; z-index: 10; }

        /* Services Grid */
        .services-grid {
            display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; margin-bottom: 30px;
        }
        .service-card {
            background: white; padding: 25px; border-radius: 24px; text-align: center;
            text-decoration: none; color: var(--text); border: 1px solid rgba(0,0,0,0.05);
            box-shadow: 0 4px 15px rgba(0,0,0,0.05); transition: 0.3s;
        }
        .service-card:hover { transform: translateY(-5px); box-shadow: 0 10px 30px rgba(0,0,0,0.1); }
        .service-icon-box {
            width: 60px; height: 60px; border-radius: 20px; margin: 0 auto 15px;
            display: flex; align-items: center; justify-content: center; font-size: 28px;
        }
        .service-card h3 { font-size: 16px; font-weight: 800; margin-bottom: 5px; }
        .service-card p { font-size: 12px; color: var(--text-light); }

        /* Secondary Sections */
        .section-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
        .section-title { font-size: 18px; font-weight: 800; }
        .view-all { font-size: 13px; font-weight: 700; color: var(--primary); text-decoration: none; }

        .recent-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 15px; }
        .recent-card {
            background: white; padding: 15px; border-radius: 20px; border: 1px solid rgba(0,0,0,0.05);
            display: flex; align-items: center; gap: 15px; text-decoration: none; color: var(--text);
        }
        .recent-icon { width: 45px; height: 45px; border-radius: 12px; background: #f1f5f9; display: flex; align-items: center; justify-content: center; color: var(--text-light); }
        .recent-info h4 { font-size: 14px; font-weight: 700; margin-bottom: 2px; }
        .recent-info p { font-size: 12px; color: var(--text-light); }

        /* Active Ride Banner */
        .active-banner {
            background: var(--primary); color: white; padding: 20px; border-radius: 24px;
            margin-bottom: 30px; display: flex; justify-content: space-between; align-items: center;
            text-decoration: none; box-shadow: 0 10px 20px rgba(99, 102, 241, 0.3);
        }

        /* Sidebar (Mobile Only Trigger) */
        .sidebar { position: fixed; top: 0; right: -300px; width: 300px; height: 100%; background: white; z-index: 2000; transition: 0.4s; padding: 40px 25px; box-shadow: -10px 0 30px rgba(0,0,0,0.1); }
        .sidebar.active { right: 0; }
        .sidebar-overlay { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.3); backdrop-filter: blur(8px); z-index: 1999; display: none; }
        .sidebar-overlay.active { display: block; }

        @media (max-width: 768px) {
            .services-grid { grid-template-columns: repeat(2, 1fr); }
            .recent-grid { grid-template-columns: 1fr; }
            .navbar { padding: 0 20px; }
            .nav-stats { display: none; }
            .hero h1 { font-size: 24px; }
        }
    </style>
</head>
<body>

    <nav class="navbar">
        <a href="#" class="nav-logo">TAXI LUXURY</a>
        <div class="nav-right">
            <div class="nav-stats">
                <a href="{{ route('customer.wallet.topup') }}" class="nav-stat-item">
                    <i class="fa-solid fa-wallet" style="color: var(--primary);"></i>
                    {{ number_format($customer->wallet_balance) }} K
                </a>
                <a href="{{ route('customer.points.exchange') }}" class="nav-stat-item">
                    <i class="fa-solid fa-star" style="color: var(--accent);"></i>
                    {{ number_format($customer->loyalty_points) }} P
                </a>
            </div>
            <a href="{{ route('customer.activities') }}" style="font-size: 18px; color: var(--text-light); text-decoration: none;">
                <i class="fa-regular fa-bell"></i>
            </a>
            <a href="{{ route('customer.settings') }}" class="nav-profile">
                @if($customer->profile_picture)
                    <img src="{{ Str::startsWith($customer->profile_picture, 'uploads/') ? asset($customer->profile_picture) : asset('storage/' . $customer->profile_picture) }}" alt="P">
                @else
                    {{ substr($customer->name, 0, 1) }}
                @endif
            </a>
            <div onclick="toggleSidebar()" style="cursor: pointer; font-size: 20px;">
                <i class="fa-solid fa-bars-staggered"></i>
            </div>
        </div>
    </nav>

    <div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>
    <div class="sidebar" id="sidebar">
        <h2 style="margin-bottom: 30px; font-weight: 800; color: var(--primary);">MENU</h2>
        <div style="display: flex; flex-direction: column; gap: 10px;">
            <a href="{{ route('customer.dashboard') }}" style="text-decoration: none; padding: 15px; color: var(--text); font-weight: 700; border-radius: 15px; background: #f1f5f9;"><i class="fa-solid fa-house"></i> Home</a>
            <a href="{{ route('customer.wallet.topup') }}" style="text-decoration: none; padding: 15px; color: var(--text); font-weight: 700;"><i class="fa-solid fa-wallet"></i> Wallet</a>
            <a href="{{ route('customer.activities') }}" style="text-decoration: none; padding: 15px; color: var(--text); font-weight: 700;"><i class="fa-solid fa-clock-rotate-left"></i> Activities</a>
            <a href="{{ route('customer.settings') }}" style="text-decoration: none; padding: 15px; color: var(--text); font-weight: 700;"><i class="fa-solid fa-gear"></i> Settings</a>
            <hr style="margin: 20px 0; border: none; border-top: 1px solid #f1f5f9;">
            <form action="{{ route('customer.logout') }}" method="POST">
                @csrf
                <button type="submit" style="width: 100%; text-align: left; background: none; border: none; padding: 15px; color: #ef4444; font-weight: 700; cursor: pointer;">
                    <i class="fa-solid fa-arrow-right-from-bracket"></i> Log Out
                </button>
            </form>
        </div>
    </div>

    <section class="hero">
        <h1>ဘယ်ကိုသွားမလဲ?</h1>
        <div class="search-container" onclick="window.location.href='{{ route('customer.booking') }}'">
            <div class="search-input-box">
                <i class="fa-solid fa-magnifying-glass"></i>
                <span>သွားလိုသည့်နေရာကို ရှာဖွေပါ...</span>
            </div>
            <button class="btn-search">Search</button>
        </div>
    </section>

    <main class="container">
        @if($activeBooking)
        <a href="{{ route('customer.waiting', $activeBooking->id) }}" class="active-banner">
            <div>
                <p style="font-size: 12px; opacity: 0.8; font-weight: 700;">လက်ရှိခရီးစဉ်</p>
                <h4 style="font-size: 16px; font-weight: 800;">
                    @if($activeBooking->status == 'pending') ယာဉ်မောင်းရှာဖွေနေပါသည်...
                    @elseif($activeBooking->status == 'accepted') ယာဉ်မောင်း လာကြိုနေပါပြီ
                    @else ခရီးစဉ် စတင်နေပါပြီ @endif
                </h4>
            </div>
            <i class="fa-solid fa-chevron-right"></i>
        </a>
        @endif

        <div class="section-header">
            <h2 class="section-title">ကျွန်ုပ်တို့၏ ဝန်ဆောင်မှုများ</h2>
        </div>

        <div class="services-grid">
            <a href="{{ route('customer.booking') }}" class="service-card">
                <div class="service-icon-box" style="background: #e0e7ff; color: var(--primary);"><i class="fa-solid fa-car"></i></div>
                <h3>Elite Taxi</h3>
                <p>လျင်မြန်စွာ ခေါ်ယူပါ</p>
            </a>
            <a href="{{ route('customer.booking') }}?type=bike" class="service-card">
                <div class="service-icon-box" style="background: #dcfce7; color: #10b981;"><i class="fa-solid fa-motorcycle"></i></div>
                <h3>Bike Ride</h3>
                <p>အမြန်ဆုံး ခရီးစဉ်</p>
            </a>
            <a href="{{ route('customer.booking') }}?type=delivery" class="service-card">
                <div class="service-icon-box" style="background: #fee2e2; color: #ef4444;"><i class="fa-solid fa-box"></i></div>
                <h3>Delivery</h3>
                <p>ပစ္စည်း ပို့ဆောင်ရေး</p>
            </a>
            <a href="{{ route('customer.activities') }}" class="service-card">
                <div class="service-icon-box" style="background: #fef3c7; color: var(--accent);"><i class="fa-solid fa-star"></i></div>
                <h3>Rewards</h3>
                <p>အမှတ်လဲလှယ်မည်</p>
            </a>
        </div>

        <div class="section-header">
            <h2 class="section-title">မကြာသေးမီက ခရီးစဉ်များ</h2>
            <a href="{{ route('customer.activities') }}" class="view-all">အားလုံးကြည့်ရန်</a>
        </div>

        <div class="recent-grid">
            @forelse($recentBookings->take(4) as $booking)
            <a href="{{ route('customer.booking', ['dropoff' => $booking->dropoff_location]) }}" class="recent-card">
                <div class="recent-icon"><i class="fa-solid fa-location-dot"></i></div>
                <div class="recent-info">
                    <h4>{{ Str::limit($booking->dropoff_location, 30) }}</h4>
                    <p>{{ $booking->created_at->format('d M, Y') }} • {{ number_format($booking->fare) }} K</p>
                </div>
            </a>
            @empty
            <p style="grid-column: 1/-1; text-align: center; color: var(--text-light); padding: 20px;">ခရီးစဉ်မှတ်တမ်း မရှိသေးပါ။</p>
            @endforelse
        </div>
    </main>

    <script>
        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('active');
            document.getElementById('sidebarOverlay').classList.toggle('active');
        }
    </script>
</body>
</html>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Activities - Taxi</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #6366f1;
            --bg: #f8fafc;
            --text: #1e293b;
            --text-light: #64748b;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Outfit', sans-serif; }
        body { background-color: var(--bg); color: var(--text); padding-bottom: 90px; }

        .header {
            background: white;
            padding: 20px;
            text-align: center;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
            height: 70px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.02);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .back-btn {
            width: 40px;
            height: 40px;
            border-radius: 12px;
            background: #f1f5f9;
            display: flex;
            justify-content: center;
            align-items: center;
            text-decoration: none;
            color: var(--text);
        }

        .container { 
            padding: 90px 20px 30px; 
            max-width: 600px; 
            margin: 0 auto; 
            min-height: 100vh;
        }
        
        .section-title { font-size: 18px; font-weight: 700; margin-bottom: 20px; }

        .activity-card {
            background: white;
            border-radius: 20px;
            padding: 15px;
            margin-bottom: 15px;
            border: 1px solid #f1f5f9;
        }

        .card-top { display: flex; justify-content: space-between; margin-bottom: 15px; }
        .card-date { font-size: 12px; color: var(--text-light); }
        .card-status {
            font-size: 10px;
            font-weight: 700;
            padding: 4px 10px;
            border-radius: 20px;
            text-transform: uppercase;
        }
        .status-completed { background: #dcfce7; color: #166534; }
        .status-cancelled { background: #fee2e2; color: #991b1b; }
        .status-active { background: #e0e7ff; color: #3730a3; }

        .card-details { display: flex; gap: 15px; }
        .loc-icons { display: flex; flex-direction: column; align-items: center; gap: 5px; }
        .dot { width: 8px; height: 8px; border-radius: 50%; }
        .line { width: 2px; height: 20px; background: #e2e8f0; }

        .loc-texts { flex: 1; }
        .loc-item { margin-bottom: 10px; }
        .loc-item h6 { font-size: 14px; font-weight: 600; }
        .loc-item p { font-size: 12px; color: var(--text-light); }

        .card-footer {
            margin-top: 15px;
            padding-top: 15px;
            border-top: 1px solid #f1f5f9;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .fare { font-weight: 700; color: var(--primary); }

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
            <a href="{{ route('customer.dashboard') }}" class="sidebar-link">
                <i class="fa-solid fa-house"></i> Home
            </a>
            <a href="{{ route('customer.wallet.topup') }}" class="sidebar-link">
                <i class="fa-solid fa-wallet"></i> Top-up Wallet
            </a>
            <a href="{{ route('customer.booking') }}" class="sidebar-link">
                <i class="fa-solid fa-map-location-dot"></i> Book a Ride
            </a>
            <a href="{{ route('customer.activities') }}" class="sidebar-link active">
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
        <div style="position: absolute; left: 20px; top: 50%; transform: translateY(-50%); display: flex; align-items: center; gap: 8px;">
            <a href="{{ route('customer.dashboard') }}" class="back-btn" style="position: static; transform: none;">
                <i class="fa-solid fa-chevron-left"></i>
            </a>
            <h2 style="font-size: 18px;">Activities</h2>
        </div>
        <div style="position: absolute; right: 20px; top: 50%; transform: translateY(-50%); display: flex; align-items: center; gap: 10px;">
            <div style="width: 40px; height: 40px; background: #f1f5f9; border-radius: 12px; display: flex; justify-content: center; align-items: center; cursor: pointer;" onclick="toggleSidebar()">
                <i class="fa-solid fa-bars"></i>
            </div>
        </div>
    </header>

    <main class="container">
        <div class="section-title">ခရီးစဉ်မှတ်တမ်းများ</div>

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
                    <div class="dot" style="background: #ef4444;"></div>
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
                <div style="font-size: 13px; color: var(--text-light);">
                    <i class="fa-solid fa-car"></i> Taxi
                </div>
                <div class="fare">{{ number_format($booking->fare) }} Ks</div>
            </div>
        </div>
        @empty
        <div style="text-align: center; padding: 50px 20px; color: var(--text-light);">
            <i class="fa-solid fa-calendar-xmark" style="font-size: 40px; margin-bottom: 15px; opacity: 0.3;"></i>
            <p>ခရီးစဉ်မှတ်တမ်း မရှိသေးပါ။</p>
        </div>
        @endforelse

        <div style="margin-top: 20px;">
            {{ $bookings->links() }}
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

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings - Taxi</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #6366f1;
            --bg: #f8fafc;
            --text: #1e293b;
            --text-light: #64748b;
            --danger: #ef4444;
            --success: #10b981;
            --secondary: #4f46e5;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Outfit', sans-serif; }
        body { background-color: var(--bg); color: var(--text); padding-bottom: 30px; }

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
            position: absolute;
            left: 20px;
            top: 50%;
            transform: translateY(-50%);
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

        .profile-hero {
            background: white;
            padding: 30px 20px;
            border-radius: 24px;
            text-align: center;
            margin-bottom: 25px;
            border: 1px solid #f1f5f9;
        }

        .profile-avatar {
            width: 90px;
            height: 90px;
            background: linear-gradient(135deg, var(--primary), #a855f7);
            border-radius: 30px;
            margin: 0 auto 15px;
            display: flex;
            justify-content: center;
            align-items: center;
            color: white;
            font-size: 32px;
            font-weight: 700;
            box-shadow: 0 10px 20px rgba(99, 102, 241, 0.2);
            overflow: hidden;
            position: relative;
            cursor: pointer;
        }

        .profile-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .avatar-edit-overlay {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background: rgba(0,0,0,0.4);
            padding: 5px 0;
            font-size: 10px;
            color: white;
            opacity: 0;
            transition: 0.3s;
        }
        .profile-avatar:hover .avatar-edit-overlay { opacity: 1; }

        .stats-row {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #f1f5f9;
        }
        .stat-box { text-align: center; }
        .stat-box h6 { font-size: 14px; font-weight: 700; color: var(--primary); }
        .stat-box p { font-size: 11px; color: var(--text-light); text-transform: uppercase; letter-spacing: 0.5px; }

        .profile-hero h2 { font-size: 20px; font-weight: 700; margin-bottom: 5px; }
        .profile-hero p { font-size: 14px; color: var(--text-light); }

        .form-section {
            background: white;
            padding: 25px;
            border-radius: 24px;
            border: 1px solid #f1f5f9;
            margin-bottom: 25px;
        }

        .form-title { font-size: 16px; font-weight: 700; margin-bottom: 20px; color: var(--text); }

        .form-group { margin-bottom: 18px; }
        .form-group label { display: block; font-size: 13px; font-weight: 600; color: var(--text-light); margin-bottom: 8px; }
        .form-group input {
            width: 100%;
            padding: 14px 18px;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            font-size: 15px;
            outline: none;
            transition: 0.3s;
        }
        .form-group input:focus { border-color: var(--primary); background: white; box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1); }

        .btn-update {
            width: 100%;
            padding: 16px;
            background: var(--primary);
            color: white;
            border: none;
            border-radius: 18px;
            font-weight: 700;
            font-size: 15px;
            cursor: pointer;
            transition: 0.3s;
        }
        .btn-update:hover { background: var(--secondary); transform: translateY(-2px); }

        .alert {
            padding: 15px;
            border-radius: 16px;
            margin-bottom: 20px;
            font-size: 14px;
            font-weight: 600;
        }
        .alert-success { background: #dcfce7; color: #166534; }
        .alert-danger { background: #fee2e2; color: #991b1b; }

        .btn-logout {
            width: 100%;
            padding: 18px;
            background: transparent;
            border: 1px solid #fee2e2;
            border-radius: 24px;
            color: var(--danger);
            font-weight: 700;
            font-size: 15px;
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 10px;
            cursor: pointer;
            margin-top: 20px;
        }

        /* Removed Bottom Nav */

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
            <a href="{{ route('customer.activities') }}" class="sidebar-link">
                <i class="fa-solid fa-clock-rotate-left"></i> Activities
            </a>
            <a href="{{ route('customer.settings') }}" class="sidebar-link active">
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
            <h2 style="font-size: 18px;">Settings</h2>
        </div>
        <div style="position: absolute; right: 20px; top: 50%; transform: translateY(-50%); display: flex; align-items: center; gap: 10px;">
            <div style="width: 40px; height: 40px; background: #f1f5f9; border-radius: 12px; display: flex; justify-content: center; align-items: center; cursor: pointer;" onclick="toggleSidebar()">
                <i class="fa-solid fa-bars"></i>
            </div>
        </div>
    </header>

    <main class="container">
        @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if($errors->any())
        <div class="alert alert-danger">
            @foreach($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
        @endif

        <div class="profile-hero">
            <div class="profile-avatar" onclick="document.getElementById('profile_image_input').click()">
                @if($customer->profile_picture)
                    <img src="{{ asset('storage/' . $customer->profile_picture) }}" alt="Profile">
                @else
                    {{ substr($customer->name, 0, 1) }}
                @endif
                <div class="avatar-edit-overlay">CHANGE</div>
            </div>
            <h2>{{ $customer->name }}</h2>
            <p>{{ $customer->phone }}</p>

            <div class="stats-row">
                <div class="stat-box">
                    <h6>{{ number_format($customer->wallet_balance) }} Ks</h6>
                    <p>Wallet</p>
                </div>
                <div class="stat-box">
                    <h6>{{ number_format($customer->loyalty_points) }} Pts</h6>
                    <p>Points</p>
                </div>
            </div>
        </div>

        <div class="form-section">
            <div class="form-title">Edit My Profile</div>
            <form action="{{ route('customer.settings.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="file" name="profile_picture" id="profile_image_input" style="display: none;" onchange="this.form.submit()">
                
                <div class="form-group">
                    <label>Full Name</label>
                    <input type="text" name="name" value="{{ old('name', $customer->name) }}" placeholder="နာမည်အပြည့်အစုံ ရိုက်ပါ">
                </div>
                <div class="form-group">
                    <label>Phone Number</label>
                    <input type="text" name="phone" value="{{ old('phone', $customer->phone) }}" placeholder="ဖုန်းနံပါတ် ရိုက်ပါ">
                </div>
                <button type="submit" class="btn-update">Update Profile</button>
            </form>
        </div>

        <form action="{{ route('customer.logout') }}" method="POST">
            @csrf
            <button type="submit" class="btn-logout">
                <i class="fa-solid fa-arrow-right-from-bracket"></i>
                Log Out
            </button>
        </form>
    </main>

    <script>
        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('active');
            document.getElementById('sidebarOverlay').classList.toggle('active');
        }
    </script>
</body>
</html>

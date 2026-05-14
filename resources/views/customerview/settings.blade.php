@extends('layout.customer')

@section('title', 'Settings - Taxi')

@push('css')
    <style>
        .profile-hero {
            background: var(--card-glass);
            padding: 30px 20px;
            border-radius: 24px;
            text-align: center;
            margin-bottom: 25px;
            border: 1px solid var(--card-border);
            backdrop-filter: blur(10px);
        }

        .profile-avatar {
            width: 90px;
            height: 90px;
            background: linear-gradient(135deg, var(--primary), var(--accent-secondary));
            border-radius: 30px;
            margin: 0 auto 15px;
            display: flex;
            justify-content: center;
            align-items: center;
            color: white;
            font-size: 32px;
            font-weight: 700;
            box-shadow: 0 10px 20px rgba(0,0,0,0.2);
            overflow: hidden;
            position: relative;
            cursor: pointer;
            border: 3px solid var(--card-border);
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
            background: rgba(0,0,0,0.6);
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
            border-top: 1px solid var(--card-border);
        }
        .stat-box { text-align: center; }
        .stat-box h6 { font-size: 14px; font-weight: 700; color: var(--primary); }
        .stat-box p { font-size: 11px; color: var(--text-dim); text-transform: uppercase; letter-spacing: 0.5px; }

        .form-section {
            background: var(--card-glass);
            padding: 25px;
            border-radius: 24px;
            border: 1px solid var(--card-border);
            margin-bottom: 25px;
            backdrop-filter: blur(10px);
        }

        .form-title { font-size: 16px; font-weight: 700; margin-bottom: 20px; color: var(--text-main); }

        .form-group { margin-bottom: 18px; }
        .form-group label { display: block; font-size: 13px; font-weight: 600; color: var(--text-dim); margin-bottom: 8px; }
        .form-group input {
            width: 100%;
            padding: 14px 18px;
            background: var(--input-bg);
            border: 1px solid var(--card-border);
            border-radius: 16px;
            color: var(--text-main);
            font-size: 15px;
            outline: none;
            transition: 0.3s;
        }
        .form-group input:focus { border-color: var(--primary); background: var(--bg-main); box-shadow: 0 0 0 4px var(--primary-light); }

        .btn-update {
            width: 100%;
            padding: 16px;
            background: var(--primary);
            color: var(--bg-main);
            border: none;
            border-radius: 18px;
            font-weight: 700;
            font-size: 15px;
            cursor: pointer;
            transition: 0.3s;
        }
        .btn-update:hover { transform: translateY(-2px); box-shadow: 0 5px 15px var(--primary-light); }

        .alert {
            padding: 15px;
            border-radius: 16px;
            margin-bottom: 20px;
            font-size: 14px;
            font-weight: 600;
        }
        .alert-success { background: var(--success-light); color: var(--success); border: 1px solid var(--success); }
        .alert-danger { background: var(--danger-light); color: var(--danger); border: 1px solid var(--danger); }

        .btn-logout-alt {
            width: 100%;
            padding: 18px;
            background: transparent;
            border: 1px solid var(--danger);
            border-radius: 24px;
            color: var(--danger);
            font-weight: 700;
            font-size: 15px;
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 10px;
            cursor: pointer;
            transition: 0.3s;
        .btn-logout-alt:hover { background: var(--danger-light); }

        .form-group input { box-sizing: border-box; max-width: 100%; }

        @media (max-width: 360px) {
            .profile-hero { padding: 20px 15px; border-radius: 20px; margin-bottom: 20px; }
            .form-section { padding: 15px; border-radius: 20px; margin-bottom: 20px; }
            .form-group input { padding: 12px 14px; font-size: 14px; }
            .btn-update { padding: 14px; font-size: 14px; }
            .btn-logout-alt { padding: 14px; font-size: 14px; border-radius: 20px; }
            .stats-row { gap: 10px; }
        }
    </style>
@endpush

@section('content')
    <div class="animate-fade">
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
                    <img src="{{ Str::startsWith($customer->profile_picture, 'uploads/') ? asset($customer->profile_picture) : asset('storage/' . $customer->profile_picture) }}" alt="Profile">
                @else
                    {{ substr($customer->name, 0, 1) }}
                @endif
                <div class="avatar-edit-overlay">CHANGE</div>
            </div>
            <h2 style="color: var(--text-main);">{{ $customer->name }}</h2>
            <p style="color: var(--text-dim);">{{ $customer->phone }}</p>

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
            <button type="submit" class="btn-logout-alt">
                <i class="fa-solid fa-arrow-right-from-bracket"></i>
                Log Out Account
            </button>
        </form>
    </div>
@endsection

@extends('layout.customer')

@push('css')
    <style>
        /* Hero Section */
        .hero {
            height: 350px; 
            background: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.7)), url('{{ asset('images/site/shwedagon_hero.jpg') }}');
            background-size: cover; background-position: center;
            display: flex; flex-direction: column; justify-content: center; align-items: center;
            padding: 20px; color: white; text-align: center;
            border-radius: 30px;
            margin-bottom: 30px;
            border: 1px solid var(--card-border);
        }
        .hero h1 { font-size: 32px; font-weight: 800; margin-bottom: 25px; text-shadow: 0 4px 15px rgba(0,0,0,0.5); }

        .search-container {
            width: 100%; max-width: 600px; background: var(--card-glass);
            padding: 10px; border-radius: 50px; border: 1px solid var(--card-border);
            display: flex; align-items: center; gap: 15px; cursor: pointer;
            backdrop-filter: blur(10px);
        }
        .search-input-box {
            flex: 1; padding: 12px 20px; background: var(--input-bg); border-radius: 40px;
            display: flex; align-items: center; gap: 12px; color: var(--text-dim);
        }
        .search-input-box span { font-weight: 600; font-size: 15px; }

        /* Services Grid */
        .services-grid {
            display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; margin-bottom: 35px;
        }
        .service-card {
            background: var(--card-glass); padding: 25px; border-radius: 24px; text-align: center;
            text-decoration: none; color: var(--text-main); border: 1px solid var(--card-border);
            transition: 0.3s;
        }
        .service-card:hover { transform: translateY(-5px); border-color: var(--accent-primary); box-shadow: 0 10px 30px rgba(0,0,0,0.2); }
        .service-icon-box {
            width: 60px; height: 60px; border-radius: 20px; margin: 0 auto 15px;
            display: flex; align-items: center; justify-content: center; font-size: 28px;
        }

        /* Secondary Sections */
        .section-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
        .section-title { font-size: 18px; font-weight: 800; color: var(--text-main); }
        .view-all { font-size: 13px; font-weight: 700; color: var(--primary); text-decoration: none; }

        .recent-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 15px; }
        .recent-card {
            background: var(--card-glass); padding: 15px; border-radius: 20px; border: 1px solid var(--card-border);
            display: flex; align-items: center; gap: 15px; text-decoration: none; color: var(--text-main);
            transition: 0.3s;
        }
        .recent-card:hover { border-color: var(--accent-primary); background: var(--primary-light); }
        .recent-icon { width: 45px; height: 45px; border-radius: 12px; background: var(--input-bg); display: flex; align-items: center; justify-content: center; color: var(--primary); }
        
        .active-banner {
            background: var(--primary);
            color: var(--bg-main); padding: 20px 30px; border-radius: 24px;
            margin-bottom: 35px; display: flex; justify-content: space-between; align-items: center;
            text-decoration: none; font-weight: 700;
            box-shadow: 0 10px 25px var(--primary-light);
        }

        @media (max-width: 768px) {
            .services-grid { grid-template-columns: repeat(2, 1fr); }
            .recent-grid { grid-template-columns: 1fr; }
            .hero h1 { font-size: 24px; }
        }

        @media (max-width: 360px) {
            .welcome-section h2 { font-size: 20px !important; }
            .welcome-section p { font-size: 12px !important; }
            .welcome-section > div:last-child { padding: 6px 10px !important; }
            .welcome-section > div:last-child span:last-child { font-size: 11px !important; }
            
            .active-banner { padding: 15px 12px !important; flex-direction: column; align-items: flex-start; gap: 10px; }
            .active-banner h4 { font-size: 14px !important; }
            
            .hero { height: 260px; padding: 15px; margin-bottom: 20px; border-radius: 20px; }
            .hero h1 { font-size: 20px; margin-bottom: 15px; }
            .search-container { padding: 6px; gap: 8px; }
            .search-input-box { padding: 10px 12px; gap: 8px; }
            .search-input-box span { font-size: 12px; }
            .search-container button { padding: 10px 16px !important; font-size: 13px; }
            
            .services-grid { gap: 12px; margin-bottom: 25px; }
            .service-card { padding: 15px 10px; border-radius: 18px; }
            .service-icon-box { width: 45px; height: 45px; font-size: 20px; margin-bottom: 10px; border-radius: 14px; }
            .service-card h3 { font-size: 14px; }
            .service-card p { font-size: 11px; }
            
            .section-title { font-size: 16px; }
            .recent-card { padding: 12px; gap: 10px; border-radius: 16px; }
            .recent-icon { width: 38px; height: 38px; font-size: 14px; }
            .recent-info h4 { font-size: 13px !important; }
            .recent-info p { font-size: 11px !important; }
        }
    </style>
@endpush

@section('content')
    @if($activeBooking)
    <a href="{{ route('customer.waiting', $activeBooking->id) }}" class="active-banner animate-fade" style="margin-bottom: 25px;">
        <div>
            <p style="font-size: 11px; opacity: 0.8; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 4px;"><i class="fa-solid fa-circle-dot"></i> လက်ရှိသွားနေသော ခရီးစဉ်</p>
            <h4 style="font-size: 18px; font-weight: 800;">
                @if($activeBooking->status == 'pending') ယာဉ်မောင်းရှာဖွေနေပါသည်...
                @elseif($activeBooking->status == 'accepted') ယာဉ်မောင်း လာကြိုနေပါပြီ
                @else ခရီးစဉ် စတင်နေပါပြီ @endif
            </h4>
        </div>
        <div style="display: flex; align-items: center; gap: 10px;">
            <span style="font-size: 13px; font-weight: 600; background: rgba(0,0,0,0.2); padding: 6px 12px; border-radius: 12px;">အခြေအနေကြည့်မည်</span>
            <i class="fa-solid fa-chevron-right"></i>
        </div>
    </a>
    @endif

    <div class="welcome-section animate-fade" style="margin-bottom: 25px; display: flex; justify-content: space-between; align-items: center;">
        <div>
            <p style="color: var(--primary); font-weight: 700; font-size: 14px; margin-bottom: 4px;">{{ $greeting }}၊</p>
            <h2 style="font-size: 26px; font-weight: 800; color: var(--text-main); margin: 0;">{{ $customer->name ?? 'ဧည့်သည်' }}</h2>
        </div>
        <div style="background: var(--card-glass); border: 1px solid var(--card-border); padding: 8px 16px; border-radius: 16px; text-align: right;">
            <span style="font-size: 11px; color: var(--text-dim); display: block;">အဆင့်အတန်း</span>
            <span style="font-size: 13px; font-weight: 700; color: var(--primary);">Elite Member</span>
        </div>
    </div>

    <section class="hero animate-fade">
        <h1>ဘယ်ကိုသွားမလဲ?</h1>
        <div class="search-container" onclick="window.location.href='{{ route('customer.booking') }}'">
            <div class="search-input-box">
                <i class="fa-solid fa-magnifying-glass"></i>
                <span>သွားလိုသည့်နေရာကို ရှာဖွေပါ...</span>
            </div>
            <x-button style="padding: 12px 25px;">Search</x-button>
        </div>
    </section>

    <div class="section-header">
        <h2 class="section-title">ကျွန်ုပ်တို့၏ ဝန်ဆောင်မှုများ</h2>
    </div>

    <div class="services-grid animate-fade">
        <a href="{{ route('customer.booking') }}" class="service-card">
            <div class="service-icon-box" style="background: var(--primary-light); color: var(--primary);"><i class="fa-solid fa-car"></i></div>
            <h3>Elite Taxi</h3>
            <p>လျင်မြန်စွာ ခေါ်ယူပါ</p>
        </a>
        <a href="{{ route('customer.booking') }}?type=bike" class="service-card">
            <div class="service-icon-box" style="background: var(--success-light); color: var(--success);"><i class="fa-solid fa-motorcycle"></i></div>
            <h3>Bike Ride</h3>
            <p>အမြန်ဆုံး ခရီးစဉ်</p>
        </a>
        <a href="{{ route('customer.booking') }}?type=delivery" class="service-card">
            <div class="service-icon-box" style="background: var(--danger-light); color: var(--danger);"><i class="fa-solid fa-box"></i></div>
            <h3>Delivery</h3>
            <p>ပစ္စည်း ပို့ဆောင်ရေး</p>
        </a>
        <a href="{{ route('customer.points.exchange') }}" class="service-card">
            <div class="service-icon-box" style="background: var(--primary-light); color: var(--primary);"><i class="fa-solid fa-star"></i></div>
            <h3>Rewards</h3>
            <p>အမှတ်လဲလှယ်မည်</p>
        </a>
    </div>

    <div class="section-header">
        <h2 class="section-title">မကြာသေးမီက ခရီးစဉ်များ</h2>
        <a href="{{ route('customer.activities') }}" class="view-all">အားလုံးကြည့်ရန်</a>
    </div>

    <div class="recent-grid animate-fade">
        @forelse($recentBookings->take(4) as $booking)
        <a href="{{ route('customer.booking', ['dropoff' => $booking->dropoff_location]) }}" class="recent-card">
            <div class="recent-icon"><i class="fa-solid fa-location-dot"></i></div>
            <div class="recent-info">
                <h4 style="font-weight: 700; margin-bottom: 2px;">{{ Str::limit($booking->dropoff_location, 30) }}</h4>
                <p style="font-size: 12px; color: var(--text-dim);">{{ $booking->created_at->format('d M, Y') }} • {{ number_format($booking->fare) }} K</p>
            </div>
        </a>
        @empty
        <p style="grid-column: 1/-1; text-align: center; color: var(--text-dim); padding: 40px; background: var(--card-glass); border-radius: 20px; border: 1px solid var(--card-border);">ခရီးစဉ်မှတ်တမ်း မရှိသေးပါ။</p>
        @endforelse
    </div>
@endsection

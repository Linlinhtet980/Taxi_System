<link rel="stylesheet" href="{{ asset('css/driverview/profile.css') }}">
@extends('layout.driver')

@section('content')
<div class="animate-fade">
    <div class="style-7f5ca6" style="text-align: center; padding: 20px 0;">
        <img src="{{ $driver->profile_picture ? asset($driver->profile_picture) : 'https://ui-avatars.com/api/?name=' . urlencode($driver->full_name) . '&background=D4AF37&color=fff&size=200' }}" alt="Profile" style="width: 100px; height: 100px; border-radius: 25px; border: 3px solid var(--primary); box-shadow: 0 10px 20px rgba(212, 175, 55, 0.3);">
        <h2 style="margin-top: 15px; font-weight: 800; color: #fff;">{{ $driver->full_name }}</h2>
        <p style="color: var(--primary); font-weight: 600; font-size: 0.9rem;">{{ $driver->email }}</p>
    </div>

    <!-- Personal Info -->
    <div class="glass style-f36ddf">
        <h3 class="style-5ba996">Personal Details</h3>
        <div class="style-c3e0b7">
            <div class="style-58e548">
                <span class="style-341204">Phone Number</span>
                <span class="style-d2abfb">{{ $driver->phone_no }}</span>
            </div>
            <div class="style-58e548">
                <span class="style-341204">License No.</span>
                <span class="style-d2abfb">{{ $driver->license_no }}</span>
            </div>
            <div class="style-58e548">
                <span class="style-341204">Gender</span>
                <span class="style-d2abfb">{{ ucfirst($driver->gender) }}</span>
            </div>
            <div class="style-58e548">
                <span class="style-341204">Joined Date</span>
                <span class="style-d2abfb">{{ $driver->created_at->format('d M Y') }}</span>
            </div>
        </div>
    </div>

    <!-- Vehicle Info -->
    @if($driver->vehicle)
    <div class="glass style-02be83">
        <h3 class="style-5ba996">Vehicle Details</h3>
        <div class="style-c3e0b7">
            <div class="style-58e548">
                <span class="style-341204">Model</span>
                <span class="style-d2abfb">{{ $driver->vehicle->model }}</span>
            </div>
            <div class="style-58e548">
                <span class="style-341204">License Plate</span>
                <span class="style-d2abfb">{{ $driver->vehicle->license_plate }}</span>
            </div>
            <div class="style-58e548">
                <span class="style-341204">Brand / Type</span>
                <span class="style-d2abfb">{{ $driver->vehicle->brand }} ({{ $driver->vehicle->vehicle_type }})</span>
            </div>
            
            <div class="style-c93d8c">
                <a href="{{ route('driver.vehicle', $driver->id) }}"  class="style-7edbb4">
                    <span>Vehicle Health & Maintenance</span>
                    <i class="fa-solid fa-chevron-right"></i>
                </a>
            </div>
        </div>
    </div>
    @else
    <div class="glass style-5404b7">
        <p>No vehicle assigned to this driver.</p>
    </div>
    @endif

    <div class="glass style-eab553">
        <div class="style-99129a">
            <i class="fa-solid fa-ranking-star style-c06d9c"></i>
            <span class="style-db1172">Leaderboard</span>
        </div>
        <a href="{{ route('driver.leaderboard', $driver->id) }}" class="glass style-b7f38b" >
            View
        </a>
    </div>

    <div class="glass style-b48995">
        <div class="style-99129a">
            <i class="fa-solid fa-bell style-a5e3c2"></i>
            <span class="style-db1172">Notifications History</span>
        </div>
        <a href="{{ route('driver.notifications', $driver->id) }}" class="glass style-a13e08" >
            View
        </a>
    </div>

    <div class="glass style-b48995">
        <div class="style-99129a">
            <i class="fa-solid fa-gift style-8ffdae"></i>
            <span class="style-db1172">Refer & Earn</span>
        </div>
        <a href="{{ route('driver.referrals', $driver->id) }}" class="glass style-c41009" >
            View
        </a>
    </div>

    <div class="style-08a6b0">
        <p class="style-622e85">Driver ID: #{{ $driver->id }}</p>
    </div>
</div>
@endsection

<link rel="stylesheet" href="{{ asset('css/driverview/vehicle.css') }}">
@extends('layout.driver')

@section('content')
<div class="animate-fade">
    <div class="page-header style-d3297f">
        <div>
            <h1 class="page-title">Vehicle Status</h1>
            <p class="page-subtitle">Manage your tool of trade and stay safe on the road.</p>
        </div>
        <div class="status-badge style-355479">
            {{ $driver->vehicle->inspection_status ?? 'Good' }}
        </div>
    </div>

    <!-- Vehicle Details -->
    <div class="glass style-b6ad2d">
        <div class="style-e49f64">
            <div class="style-bf13c3">
                <img src="{{ $driver->vehicle->vehicle_photo ? asset('storage/' . $driver->vehicle->vehicle_photo) : 'https://images.unsplash.com/photo-1549317661-bd32c8ce0db2?auto=format&fit=crop&q=80&w=200' }}"  class="style-7d1fae">
            </div>
            <div>
                <h2 class="style-aa38c0">{{ $driver->vehicle->brand }} {{ $driver->vehicle->model }}</h2>
                <p class="style-ec6cb4">{{ $driver->vehicle->license_plate }}</p>
                <p class="style-d9a249">Year: {{ $driver->vehicle->year }} | Color: {{ $driver->vehicle->color }}</p>
            </div>
        </div>

        <div class="style-b119ff">
            <div>
                <p class="style-6f84e5">Current Mileage</p>
                <p class="style-be8e63">{{ number_format($driver->vehicle->mileage ?? 0) }} km</p>
            </div>
            <div>
                <p class="style-6f84e5">Vehicle Type</p>
                <p class="style-be8e63">{{ $driver->vehicle->vehicle_type }}</p>
            </div>
        </div>
    </div>

    <!-- Maintenance Timeline -->
    <div class="glass style-57c15d">
        <h3 class="style-58cb47">Maintenance Timeline</h3>
        
        <div class="style-683255">
            <div class="style-933818"></div>
            
            <div class="style-13cf4b">
                <div class="style-dba254"></div>
                <p class="style-668c23">Last Service</p>
                <p class="style-6fdf9a">{{ $driver->vehicle->last_maintenance_at ? \Carbon\Carbon::parse($driver->vehicle->last_maintenance_at)->format('M d, Y') : 'Not Recorded' }}</p>
                <p class="style-d9a249">Oil change & general inspection completed.</p>
            </div>

            <div class="style-d85c4e">
                <div class="style-637f56"></div>
                <p class="style-d4b30b">Next Due</p>
                <p class="style-6fdf9a">{{ $driver->vehicle->next_maintenance_at ? \Carbon\Carbon::parse($driver->vehicle->next_maintenance_at)->format('M d, Y') : 'Schedule Now' }}</p>
                <p class="style-d9a249">Engine tune-up and brake pad replacement recommended.</p>
            </div>
        </div>
    </div>

    <!-- Quick Checklist -->
    <div class="glass style-45ad02">
        <h3 class="style-52f4eb">Safety Checklist</h3>
        <div class="style-578b24">
            <div class="style-948e6d">
                <span class="style-5d8dee">Tire Pressure</span>
                <span class="style-4b5d67"><i class="fa-solid fa-circle-check"></i> Checked</span>
            </div>
            <div class="style-948e6d">
                <span class="style-5d8dee">Brake Fluid</span>
                <span class="style-4b5d67"><i class="fa-solid fa-circle-check"></i> Normal</span>
            </div>
            <div class="style-948e6d">
                <span class="style-5d8dee">Lights & Signals</span>
                <span class="style-de2886"><i class="fa-solid fa-circle-exclamation"></i> Check Left Blinker</span>
            </div>
        </div>
    </div>
</div>
@endsection

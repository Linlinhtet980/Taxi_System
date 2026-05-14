<link rel="stylesheet" href="{{ asset('css/driverview/vehicle.css') }}">
@extends('layout.driver')

@section('content')
<div class="animate-fade">
    <div class="page-header style-d3297f">
        <div>
            <h1 class="page-title">Vehicle Status</h1>
            <p class="page-subtitle">Manage your tool of trade and stay safe on the road.</p>
        </div>
        <div class="status-badge status-completed">
            {{ $driver->vehicle->inspection_status ?? 'Good' }}
        </div>
    </div>

    <!-- Vehicle Details -->
    <div class="glass style-b6ad2d">
        <div class="style-e49f64">
            <div class="style-bf13c3">
                <img src="{{ $driver->vehicle->vehicle_photo ? asset($driver->vehicle->vehicle_photo) : 'https://images.unsplash.com/photo-1549317661-bd32c8ce0db2?auto=format&fit=crop&q=80&w=200' }}"  class="style-7d1fae">
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

    <!-- Vehicle Gallery -->
    <div class="glass" style="padding: 20px; margin-bottom: 20px;">
        <h3 style="color: var(--primary); margin-bottom: 15px; font-size: 1.1rem; font-weight: 800; text-transform: uppercase;">Vehicle Gallery</h3>
        <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 10px;">
            @php
                $photos = [
                    'Front' => $driver->vehicle->front_photo,
                    'Back' => $driver->vehicle->back_photo,
                    'Left' => $driver->vehicle->left_side_photo,
                    'Right' => $driver->vehicle->right_side_photo,
                    'Inside' => $driver->vehicle->interior_photo
                ];
            @endphp
            @foreach($photos as $label => $path)
                @if($path)
                    <div style="text-align: center;">
                        <img src="{{ asset($path) }}" style="width: 100%; height: 80px; object-fit: cover; border-radius: 12px; border: 1px solid var(--card-border);">
                        <span style="font-size: 10px; color: var(--text-dim); display: block; margin-top: 4px;">{{ $label }}</span>
                    </div>
                @endif
            @endforeach
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

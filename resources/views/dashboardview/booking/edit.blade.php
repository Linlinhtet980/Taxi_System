@extends('layout.admin')

@push('css')
@endpush

@section('content')
<div class="animate-fade">
    <div class="page-header">
        <div>
            <a href="{{ route('bookings.index') }}" onmouseover="this.style.opacity='1'" onmouseout="this.style.opacity='0.9'" class="style-1cb386">
                <div class="style-ea0079">
                    <i class="fa-solid fa-arrow-left"></i>
                </div>
                TRIPS LIST
            </a>
            <h2 class="page-title style-27ac6d">Manage Booking</h2>
        </div>
    </div>

    @php
        $isLocked = in_array($booking->status, ['ongoing', 'completed', 'cancelled']);
        $isFullyLocked = in_array($booking->status, ['completed', 'cancelled']);
    @endphp

    @if($isLocked)
        <div class="glass animate-fade style-9814da">
            <i class="fa-solid fa-lock style-ec43a2"></i>
            <div>
                <p class="style-b653a0">Trip Protection Active</p>
                <p class="style-d9a249">
                    @if($booking->status == 'ongoing')
                        This trip is currently in progress. Passenger, Route, and Fare are locked.
                    @else
                        This trip has ended. Data is preserved for audit records.
                    @endif
                </p>
            </div>
        </div>
    @endif

    <form action="{{ route('bookings.update', $booking) }}" method="POST" class="animate-fade">
        @csrf
        @method('PUT')
        
        <div class="style-9a3433">
            <!-- Form Side -->
            <div class="glass style-b293d7">
                <div class="style-b9f9ff">
                    <h3 class="style-4ac43c">
                        <i class="fa-solid fa-edit"></i> Edit Trip #{{ str_pad($booking->id, 5, '0', STR_PAD_LEFT) }}
                    </h3>
                    <span  style="font-size: 0.75rem; background: {{ $booking->status == 'completed' ? '#4ade80' : ($booking->status == 'cancelled' ? '#f43f5e' : '#fbbf24') }}20; color: {{ $booking->status == 'completed' ? '#4ade80' : ($booking->status == 'cancelled' ? '#f43f5e' : '#fbbf24') }}; padding: 4px 12px; border-radius: 50px; font-weight: 700; text-transform: uppercase; border: 1px solid {{ $booking->status == 'completed' ? '#4ade80' : ($booking->status == 'cancelled' ? '#f43f5e' : '#fbbf24') }}40;">
                        {{ $booking->status }}
                    </span>
                </div>

                <div class="style-2ee324">
                    <input type="hidden" id="pickup_lat" value="{{ $booking->pickup_lat }}">
                    <input type="hidden" id="pickup_lng" value="{{ $booking->pickup_lng }}">
                    <input type="hidden" id="dropoff_lat" value="{{ $booking->dropoff_lat }}">
                    <input type="hidden" id="dropoff_lng" value="{{ $booking->dropoff_lng }}">

                    <div class="form-group">
                        <label class="style-d4bdca">Passenger</label>
                        <input type="text" class="glass style-bbef7d" value="{{ $booking->customer->name }} ({{ $booking->customer->phone }})" readonly>
                    </div>

                    <div class="style-c45d59">
                        <div class="form-group">
                            <label class="style-d4bdca">Assign Driver</label>
                            <select name="driver_id" class="glass style-ad5d06" {{ $isFullyLocked ? 'disabled' : '' }}>
                                @foreach($drivers as $driver)
                                    <option value="{{ $driver->id }}" {{ $booking->driver_id == $driver->id ? 'selected' : '' }}>{{ $driver->full_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="style-d4bdca">Vehicle</label>
                            <select name="vehicle_id" class="glass style-ad5d06" {{ $isFullyLocked ? 'disabled' : '' }}>
                                @foreach($vehicles as $vehicle)
                                    <option value="{{ $vehicle->id }}" {{ $booking->vehicle_id == $vehicle->id ? 'selected' : '' }}>{{ $vehicle->license_plate }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="style-d4bdca">Pickup Point</label>
                        <input type="text" class="glass style-bbef7d" value="{{ $booking->pickup_location }}" readonly>
                    </div>

                    <div class="form-group">
                        <label class="style-d4bdca">Destination</label>
                        <input type="text" class="glass style-bbef7d" value="{{ $booking->dropoff_location }}" readonly>
                    </div>

                    <div class="style-c45d59">
                        <div class="form-group">
                            <label class="style-d4bdca">Fare (MMK)</label>
                            <input type="number" name="fare" class="glass style-358939" value="{{ $booking->fare }}" {{ $isLocked ? 'readonly' : '' }}>
                        </div>
                        <div class="form-group">
                            <label class="style-d4bdca">Update Status</label>
                            <select name="status" class="glass style-ad5d06" {{ $isFullyLocked ? 'disabled' : '' }}>
                                <option value="pending" {{ $booking->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="confirmed" {{ $booking->status == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                                <option value="ongoing" {{ $booking->status == 'ongoing' ? 'selected' : '' }}>Ongoing</option>
                                <option value="completed" {{ $booking->status == 'completed' ? 'selected' : '' }}>Completed</option>
                                <option value="cancelled" {{ $booking->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                        </div>
                    </div>

                    @if(!$isFullyLocked)
                        <button type="submit" class="btn-primary style-7cb755">
                            <i class="fa-solid fa-save"></i> UPDATE TRIP RECORDS
                        </button>
                    @endif
                </div>
            </div>

            <!-- Map Side -->
            <div class="glass style-0f6d9e">
                <div id="edit-map" class="style-e2bd5a"></div>
                <div class="style-086c62">
                    <i class="fa-solid fa-route mr-1"></i> Visualized route for this booking.
                </div>
            </div>
        </div>
    </form>
</div>

<!-- Leaflet & Routing Machine -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine@latest/dist/leaflet-routing-machine.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://unpkg.com/leaflet-routing-machine@latest/dist/leaflet-routing-machine.js"></script>

<link rel="stylesheet" href="{{ asset('css/dashboardview/booking/edit.css') }}">

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var pickupLat = {{ $booking->pickup_lat }};
        var pickupLng = {{ $booking->pickup_lng }};
        var dropoffLat = {{ $booking->dropoff_lat }};
        var dropoffLng = {{ $booking->dropoff_lng }};

        var map = L.map('edit-map').setView([pickupLat, pickupLng], 13);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);

        var pickupPos = L.latLng(pickupLat, pickupLng);
        var dropoffPos = L.latLng(dropoffLat, dropoffLng);

        L.marker(pickupPos).addTo(map).bindPopup("Pickup").openPopup();
        L.marker(dropoffPos).addTo(map).bindPopup("Destination");

        L.Routing.control({
            waypoints: [pickupPos, dropoffPos],
            lineOptions: {
                styles: [{ color: '#a855f7', weight: 6, opacity: 0.8 }]
            },
            createMarker: function() { return null; },
            addWaypoints: false,
            routeWhileDragging: false,
            show: false
        }).addTo(map);
    });
</script>

</div>
@endsection

@extends('layout.admin')

@section('content')
<div class="container-fluid">
    <div class="page-header animate-fade">
        <div>
            <h1 class="page-title">Live Fleet Tracking</h1>
            <p class="page-subtitle">Real-time monitoring of all drivers and active bookings.</p>
        </div>
        <div class="header-actions">
            <div class="badge badge-primary px-3 py-2 style-ef2326">
                <i class="fa-solid fa-sync fa-spin"></i> Auto-refreshing
            </div>
        </div>
    </div>

    <!-- Full Width Map -->
    <div class="animate-fade style-257995">
        <div class="table-container glass style-00909f">
            <div class="map-inner-wrapper style-bd7907">
                <div id="map" class="style-adb9a0"></div>
            </div>
        </div>
    </div>

    <!-- Active Trips Table Below -->
    <div class="animate-fade">
        <div class="table-container glass">
            <div class="style-bbc98b">
                <h2 class="style-9cf61f">Active Trips <span class="style-0b704c">(Real-time List)</span></h2>
                <div class="style-0ba205">
                    <span class="secondary-text"><i class="fa-solid fa-circle style-5a518e"></i> Pending</span>
                    <span class="secondary-text"><i class="fa-solid fa-circle style-d65ef7"></i> Confirmed</span>
                    <span class="secondary-text"><i class="fa-solid fa-circle style-3e850f"></i> Ongoing</span>
                </div>
            </div>

            <table class="premium-table">
                <thead>
                    <tr>
                        <th>Trip ID</th>
                        <th>Passenger Info</th>
                        <th>Pickup Location</th>
                        <th>Dropoff Location</th>
                        <th>Fare</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($bookings->whereIn('status', ['pending', 'confirmed', 'ongoing'])->take(15) as $b)
                    <tr class="table-row">
                        <td><span class="primary-text style-fb2a71">#TRP-{{ str_pad($b->id, 5, '0', STR_PAD_LEFT) }}</span></td>
                        <td>
                            <div class="primary-text">{{ $b->customer->name ?? 'Unknown' }}</div>
                            <div class="secondary-text">{{ $b->customer->phone ?? 'No contact' }}</div>
                        </td>
                        <td>
                            <div class="secondary-text style-1a5c8d" title="{{ $b->pickup_location }}">
                                <i class="fa-solid fa-circle-dot style-575259"></i>
                                {{ Str::limit($b->pickup_location, 35) }}
                            </div>
                        </td>
                        <td>
                            <div class="secondary-text style-1a5c8d" title="{{ $b->dropoff_location }}">
                                <i class="fa-solid fa-location-dot style-455477"></i>
                                {{ Str::limit($b->dropoff_location, 35) }}
                            </div>
                        </td>
                        <td>
                            <div class="primary-text style-a5e3c2">{{ number_format($b->fare) }} MMK</div>
                        </td>
                        <td>
                            @php
                                $color = $b->status == 'ongoing' ? '#4ade80' : ($b->status == 'pending' ? '#fbbf24' : '#60a5fa');
                            @endphp
                            <span class="status-badge " style="background: {{ $color }}20; color: {{ $color }}; border: 1px solid {{ $color }}40;">
                                {{ strtoupper($b->status) }}
                            </span>
                        </td>
                        <td>
                            <button class="btn-primary style-aa7031" 
                                    onclick="focusOnRide({{ $b->pickup_lat }}, {{ $b->pickup_lng }}, {{ $b->dropoff_lat ?? 'null' }}, {{ $b->dropoff_lng ?? 'null' }})">
                                <i class="fa-solid fa-location-arrow"></i> Track
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="style-1efb5a">No active trips at the moment.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Leaflet & Routing -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine/dist/leaflet-routing-machine.css" />
<script src="https://unpkg.com/leaflet-routing-machine/dist/leaflet-routing-machine.js"></script>

<link rel="stylesheet" href="{{ asset('css/dashboardview/booking/map.css') }}">

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var map = L.map('map').setView([16.8661, 96.1951], 13);
        
        L.tileLayer('https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png', {
            attribution: '&copy; OpenStreetMap &copy; CARTO'
        }).addTo(map);

        var carIcon = L.icon({
            iconUrl: 'https://cdn-icons-png.flaticon.com/512/744/744465.png',
            iconSize: [35, 35],
            iconAnchor: [17, 17]
        });

        var destIcon = L.icon({
            iconUrl: 'https://cdn-icons-png.flaticon.com/512/2776/2776067.png',
            iconSize: [30, 30],
            iconAnchor: [15, 30]
        });

        var bookings = @json($bookings);
        var routingControl = null;

        bookings.forEach(function(b) {
            if (b.pickup_lat && b.pickup_lng) {
                var m = L.marker([b.pickup_lat, b.pickup_lng], { icon: carIcon }).addTo(map);
                m.bindPopup(`
                    <div class="style-bde3ea">
                        <b class="style-f3ba20">Ride #${b.id}</b><br>
                        <b>Passenger:</b> ${b.customer ? b.customer.name : 'Unknown'}<br>
                        <b>Status:</b> <span class="style-745e0f">${b.status}</span>
                    </div>
                `);
            }
        });

        window.focusOnRide = function(pLat, pLng, dLat, dLng) {
            if (routingControl) {
                map.removeControl(routingControl);
            }

            window.scrollTo({ top: 0, behavior: 'smooth' });

            if (pLat && pLng) {
                // If we have both points, fit the map to show both
                if (dLat && dLng) {
                    var bounds = L.latLngBounds([
                        [pLat, pLng],
                        [dLat, dLng]
                    ]);
                    map.fitBounds(bounds, { padding: [50, 50], maxZoom: 16 });

                    routingControl = L.Routing.control({
                        waypoints: [
                            L.latLng(pLat, pLng),
                            L.latLng(dLat, dLng)
                        ],
                        routeWhileDragging: false,
                        addWaypoints: false,
                        show: false,
                        createMarker: function(i, wp) {
                            return L.marker(wp.latLng, {
                                icon: i === 0 ? carIcon : destIcon
                            });
                        },
                        lineOptions: {
                            styles: [{ color: '#a855f7', opacity: 0.8, weight: 6 }]
                        }
                    }).addTo(map);
                } else {
                    // Only pickup available
                    map.setView([pLat, pLng], 16);
                }
            }
        };

    });
</script>
@endsection

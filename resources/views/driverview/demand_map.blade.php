@extends('layout.driver')

@push('css')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine@latest/dist/leaflet-routing-machine.css" />
<link rel="stylesheet" href="{{ asset('css/driverview/demand_map.css') }}">
<style>
/* Full Screen Map Override */
    @if($activeBooking)
    body { padding-bottom: 0 !important; overflow: hidden; }
    .container { max-width: none !important; padding: 0 !important; margin: 0 !important; height: 100vh; width: 100vw; }
    header, footer, .page-header, .bottom-nav { display: none !important; }
    #demandMap { width: 100vw; height: 100vh; z-index: 1; }
    @else
    #demandMap { width: 100%; height: 60vh; border-radius: 24px; z-index: 1; }
    @endif
</style>
@endpush

@section('content')
@if($activeBooking)
    <div id="demandMap"></div>

    <div class="nav-floating-top animate-fade">
        <div class="style-b512c9">
            <div class="style-11704a">
                <i class="fa-solid fa-user style-541dcc"></i>
            </div>
            <div>
                <p class="style-d1844e">{{ $activeBooking->customer->name ?? 'Passenger' }}</p>
                <p class="style-0d665b">Active Trip #{{ $activeBooking->id }}</p>
            </div>
        </div>
        <a href="{{ route('driver.dashboard', $driver->id) }}"  class="style-1a76a4">EXIT</a>
    </div>

    <div class="nav-floating-bottom">
        <div class="address-card animate-fade">
            <div class="style-d7bb5f">
                <div class="style-b13125">
                    <i class="fa-solid fa-location-dot style-238f0a"></i>
                    <p class="style-1eac26">{{ $activeBooking->pickup_location }}</p>
                </div>
                <div class="style-b13125">
                    <i class="fa-solid fa-flag-checkered style-7e9c59"></i>
                    <p class="style-1eac26">{{ $activeBooking->dropoff_location }}</p>
                </div>
            </div>
        </div>

        @if($activeBooking->status == 'confirmed')
        <form action="{{ route('driver.trip.update', [$driver->id, $activeBooking->id]) }}" method="POST">
            @csrf
            <input type="hidden" name="status" value="ongoing">
            <button type="submit" class="btn-nav-action btn-nav-start">START TRIP</button>
        </form>
        @elseif($activeBooking->status == 'ongoing')
        <button onclick="showPaymentSelection()" class="btn-nav-action btn-nav-finish">FINISH TRIP</button>
        @endif
    </div>

    <div id="payment-modal" class="style-a71f4a">
        <div class="style-6a205a">
            <div class="style-7f5ca6">
                <h2 class="style-5264ef">Payment Settlement</h2>
                
                <div class="glass style-3d4b41">
                    <div class="style-3a34b0">
                        <span class="style-2e72b4">Total Fare</span>
                        <span class="style-121ea8">{{ number_format($activeBooking->fare) }} MMK</span>
                    </div>
                    <div class="style-a8e133">
                        @php $commission = $activeBooking->fare * 0.15; @endphp
                        <span class="style-2e72b4">Commission (15%)</span>
                        <span class="style-a1c609">-{{ number_format($commission) }} MMK</span>
                    </div>
                    <div class="style-948e6d">
                        <span class="style-32d019">Your Earnings</span>
                        <span class="style-e76106">{{ number_format($activeBooking->fare - $commission) }} MMK</span>
                    </div>
                </div>
            </div>

            <div class="style-0000c8">
                <div onclick="setPaymentMethod('Cash')" id="opt-cash" class="glass style-ece2e4">
                    <i class="fa-solid fa-money-bill-wave style-893807"></i>
                    <p class="style-e678dc">CASH</p>
                </div>
                <div onclick="setPaymentMethod('Digital')" id="opt-digital" class="glass style-ece2e4">
                    <i class="fa-solid fa-mobile-screen-button style-5d5bcf"></i>
                    <p class="style-e678dc">DIGITAL</p>
                </div>
            </div>

            <!-- Payment Hint Box -->
            <div id="payment-hint" class="glass style-394c46">
                <p id="hint-text" class="style-a0538b"></p>
            </div>

            <form action="{{ route('driver.trip.update', [$driver->id, $activeBooking->id]) }}" method="POST">
                @csrf
                <input type="hidden" name="status" value="completed">
                <input type="hidden" name="payment_method" id="final-method" value="Cash">
                <button type="submit" id="submit-btn" class="btn-nav-action btn-nav-finish">COMPLETE</button>
                <button type="button" onclick="closePaymentModal()" class="style-89882f">Cancel</button>
            </form>
        </div>
    </div>
@else
    <div class="style-32c16d">
        <div class="page-header style-6deeba">
            <div>
                <h1 class="page-title">Demand Heatmap</h1>
                <p class="page-subtitle">Locate high-traffic pickup zones.</p>
            </div>
            <div class="stat-badge glass">
                <span id="point-count" class="style-9ceb8b">0</span> Active Zones
            </div>
        </div>
        <div class="glass style-063f2e">
            <div id="demandMap"></div>
            <button onclick="refreshData()" class="glass style-c89a77">
                <i class="fa-solid fa-rotate"></i> Refresh
            </button>
        </div>
    </div>
@endif

@push('js')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://leaflet.github.io/Leaflet.heat/dist/leaflet-heat.js"></script>
<script src="https://unpkg.com/leaflet-routing-machine@latest/dist/leaflet-routing-machine.js"></script>

<script>
    let map, heatmapLayer;
    const isActive = {{ $activeBooking ? 'true' : 'false' }};

    function initMap() {
        map = L.map('demandMap', { zoomControl: true, attributionControl: false }).setView([16.8661, 96.1951], 13);
        L.tileLayer('https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png', { maxZoom: 19 }).addTo(map);

        if (isActive) {
            const pLat = {{ $activeBooking->pickup_lat ?? '16.8409' }};
            const pLng = {{ $activeBooking->pickup_lng ?? '96.1735' }};
            const dLat = {{ $activeBooking->dropoff_lat ?? '16.8660' }};
            const dLng = {{ $activeBooking->dropoff_lng ?? '96.1951' }};

            L.Routing.control({
                waypoints: [L.latLng(pLat, pLng), L.latLng(dLat, dLng)],
                lineOptions: { styles: [{ color: '#6366f1', opacity: 0.8, weight: 6 }] },
                createMarker: function(i, wp) {
                    const iconHtml = (i === 0) 
                        ? '<div class="style-f62853"><i class="fa-solid fa-location-dot style-a5e3c2"></i></div>'
                        : '<div class="style-f62853"><i class="fa-solid fa-flag-checkered style-3f909e"></i></div>';
                    return L.marker(wp.latLng, { icon: L.divIcon({ html: iconHtml, className: 'c-icon', iconSize: [30, 30], iconAnchor: [15, 30] }) });
                },
                addWaypoints: false, fitSelectedRoutes: true
            }).addTo(map);
        } else {
            fetchDemandData();
        }
    }

    async function fetchDemandData() {
        try {
            const res = await fetch("{{ route('driver.api.demand') }}");
            const data = await res.json();
            document.getElementById('point-count').innerText = data.length;
            const points = data.map(p => [parseFloat(p.pickup_lat), parseFloat(p.pickup_lng), 0.5]);
            if (heatmapLayer) map.removeLayer(heatmapLayer);
            heatmapLayer = L.heatLayer(points, { radius: 25, blur: 15 }).addTo(map);
            if (data.length > 0) map.fitBounds(new L.LatLngBounds(data.map(p => [p.pickup_lat, p.pickup_lng])), { padding: [50, 50] });
        } catch (e) { console.error(e); }
    }

    function refreshData() { fetchDemandData(); }
    function showPaymentSelection() { document.getElementById('payment-modal').style.display = 'flex'; setPaymentMethod('Cash'); }
    function closePaymentModal() { document.getElementById('payment-modal').style.display = 'none'; }
    function setPaymentMethod(m) {
        document.getElementById('final-method').value = m;
        document.getElementById('opt-cash').style.borderColor = m === 'Cash' ? '#4ade80' : 'transparent';
        document.getElementById('opt-digital').style.borderColor = m === 'Digital' ? '#60a5fa' : 'transparent';
        
        const btn = document.getElementById('submit-btn');
        const hintText = document.getElementById('hint-text');
        
        const fare = {{ $activeBooking->fare ?? 0 }};
        const commission = fare * 0.15;
        const earnings = fare - commission;

        if (m === 'Cash') {
            btn.style.background = '#4ade80';
            btn.innerText = 'COLLECT ' + fare.toLocaleString() + ' MMK';
            hintText.innerHTML = '<i class="fa-solid fa-circle-info style-0f5418"></i> Collect full fare from passenger. Commission (' + commission.toLocaleString() + ') will be deducted from your wallet.';
        } else {
            btn.style.background = '#60a5fa';
            btn.innerText = 'DISMISS & COMPLETE';
            hintText.innerHTML = '<i class="fa-solid fa-circle-info style-3b5f25"></i> Passenger paid online. Your share (' + earnings.toLocaleString() + ') will be added to your wallet.';
        }
    }

    document.addEventListener('DOMContentLoaded', initMap);
</script>
@endpush
@endsection

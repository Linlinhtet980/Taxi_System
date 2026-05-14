@extends('layout.driver')

@push('css')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine@latest/dist/leaflet-routing-machine.css" />
<link rel="stylesheet" href="{{ asset('css/driverview/demand_map.css') }}">
<style>
/* Full Screen Map Override */
    @if($activeBooking)
    body { padding-bottom: 0 !important; overflow: hidden; }
    .container { padding: 30px !important; max-width: 1600px !important; margin: 0 auto !important; }
    .animate-fade { padding: 0 !important; }
    .main-wrapper { height: 100vh; overflow: hidden; background: var(--bg-main); }
    .map-layout-wrapper {
        height: calc(100vh - 140px); /* 80px header + 60px vertical padding */
        border-radius: 24px;
        overflow: hidden;
        border: 1px solid var(--card-border);
        box-shadow: 0 10px 40px rgba(0,0,0,0.1);
    }
    .container-map {
        height: 100%;
        position: relative;
        background: var(--bg-main);
    }
    #demandMap { width: 100% !important; height: 100% !important; z-index: 1; }
    @else
    #demandMap { width: 100%; height: 65vh; border-radius: 24px; z-index: 1; }
    @endif
    .user-avatar-small {
        width: 45px;
        height: 45px;
        background: var(--primary);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--bg-main);
        font-size: 20px;
    }
    .loc-icon.pickup { background: var(--success-light); color: var(--success); border: 1px solid var(--success); }
    .loc-icon.dropoff { background: var(--danger-light); color: var(--danger); border: 1px solid var(--danger); }
    .btn-nav-start { 
        background: #c59114; 
        color: white; 
        box-shadow: 0 8px 20px rgba(197, 145, 20, 0.2); 
    }
    
    .btn-nav-finish { 
        background: #10b981; 
        color: white; 
        box-shadow: 0 8px 20px rgba(16, 185, 129, 0.2); 
    }

    .btn-exit-map {
        text-align: center;
        text-decoration: none;
        font-size: 13px;
        font-weight: 700;
        color: #71717a;
        padding: 12px;
        border-radius: 12px;
        border: 1px solid #e4e4e7;
        transition: 0.3s;
        background: white;
    }
</style>
@endpush

@section('content')
@if($activeBooking)
    <div class="map-layout-wrapper">
        <div class="container-map">
            <div id="demandMap"></div>
        </div>

        <aside class="right-info-sidebar animate-fade">
            <!-- Customer Info -->
            <div class="sidebar-section">
                <div class="user-card-mini">
                    <div class="user-avatar-small">
                        <i class="fa-solid fa-user"></i>
                    </div>
                    <div>
                        <h4 class="customer-name">{{ $activeBooking->customer->name ?? 'Passenger' }}</h4>
                        <p class="trip-id">Active Trip #{{ $activeBooking->id }}</p>
                    </div>
                </div>
            </div>


            <!-- Route Info -->
            <div class="sidebar-section">
                <h5 class="section-label">Trip Details</h5>
                <div class="location-item">
                    <div class="loc-icon pickup"><i class="fa-solid fa-location-dot"></i></div>
                    <div class="loc-details">
                        <p class="loc-label">Pickup Location</p>
                        <p class="loc-value">{{ $activeBooking->pickup_location }}</p>
                    </div>
                </div>
                <div class="location-item">
                    <div class="loc-icon dropoff"><i class="fa-solid fa-flag-checkered"></i></div>
                    <div class="loc-details">
                        <p class="loc-label">Dropoff Location</p>
                        <p class="loc-value">{{ $activeBooking->dropoff_location }}</p>
                    </div>
                </div>
            </div>

            <div class="sidebar-footer">
                @if($activeBooking->status == 'confirmed')
                <form action="{{ route('driver.trip.update', [$driver->id, $activeBooking->id]) }}" method="POST">
                    @csrf
                    <input type="hidden" name="status" value="ongoing">
                    <button type="submit" class="btn-nav-action btn-nav-start">START TRIP</button>
                </form>
                @elseif($activeBooking->status == 'ongoing')
                <button onclick="showPaymentSelection()" class="btn-nav-action btn-nav-finish">FINISH TRIP</button>
                @endif
                
                <a href="{{ route('driver.dashboard', $driver->id) }}" class="btn-exit-map">EXIT MAP</a>
            </div>
        </aside>
    </div>

    <div id="payment-modal" class="payment-modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title">Payment Settlement</h2>
                
                <div class="modal-card">
                    <div class="fare-row">
                        <span class="label">Total Fare</span>
                        <span class="fare-total">{{ number_format($activeBooking->fare) }} MMK</span>
                    </div>
                    <div class="commission-row">
                        @php $commission = $activeBooking->fare * 0.15; @endphp
                        <span class="label">Commission (15%)</span>
                        <span class="commission-value">-{{ number_format($commission) }} MMK</span>
                    </div>
                    <div class="earnings-row">
                        <span class="label-large">Your Earnings</span>
                        <span class="earnings-value">{{ number_format($activeBooking->fare - $commission) }} MMK</span>
                    </div>
                </div>
            </div>

            <div class="payment-methods">
                @if(in_array(strtolower($activeBooking->payment_method ?? 'cash'), ['wallet', 'digital']))
                    <div class="method-card active">
                        <i class="fa-solid fa-mobile-screen-button" style="color: var(--primary); font-size: 1.5rem;"></i>
                        <p style="color: var(--text-main); margin-top: 10px; font-weight: 700;">DIGITAL PAYMENT RECEIVED</p>
                    </div>
                @else
                    <div class="method-card active" style="border: 1px solid var(--success); background: var(--success-light);">
                        <i class="fa-solid fa-money-bill-wave" style="color: var(--success); font-size: 1.5rem;"></i>
                        <p style="color: var(--text-main); margin-top: 10px; font-weight: 700;">CASH PAYMENT</p>
                    </div>
                @endif
            </div>

            <!-- Payment Hint Box -->
            <div id="payment-hint" class="modal-card" style="padding: 15px; margin-bottom: 20px; font-size: 0.85rem;">
                <p id="hint-text" style="color: var(--text-dim); line-height: 1.5; margin: 0;"></p>
            </div>

            <form action="{{ route('driver.trip.update', [$driver->id, $activeBooking->id]) }}" method="POST">
                @csrf
                <input type="hidden" name="status" value="completed">
                <input type="hidden" name="payment_method" id="final-method" value="{{ in_array(strtolower($activeBooking->payment_method ?? 'cash'), ['wallet', 'digital']) ? 'Digital' : 'Cash' }}">
                <button type="submit" id="submit-btn" class="btn-nav-action btn-nav-finish">
                    {{ in_array(strtolower($activeBooking->payment_method ?? 'cash'), ['wallet', 'digital']) ? 'CONFIRM & COMPLETE' : 'COLLECT & COMPLETE' }}
                </button>
                <button type="button" onclick="closePaymentModal()" class="btn-exit-map" style="width: 100%; margin-top: 10px;">Cancel</button>
            </form>
        </div>
    </div>
@else
    <div class="idle-map-container" style="padding: 30px;">
        <div class="page-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px;">
            <div>
                <h1 class="page-title" style="font-size: 24px; font-weight: 800; color: var(--text-main);">Demand Heatmap</h1>
                <p class="page-subtitle" style="color: var(--text-dim); font-size: 13px;">Locate high-traffic pickup zones.</p>
            </div>
            <div class="stat-badge glass" style="padding: 8px 16px; border-radius: 12px; background: var(--primary-light); border: 1px solid var(--card-border); color: var(--text-main); font-weight: 700;">
                <span id="point-count" style="color: var(--primary); font-weight: 800;">0</span> Active Zones
            </div>
        </div>
        
        <div class="glass map-card" style="border-radius: 24px; overflow: hidden; position: relative; border: 1px solid var(--card-border); height: 65vh;">
            <div id="demandMap" style="width: 100%; height: 100%;"></div>
            <button onclick="refreshData()" class="glass" style="position: absolute; top: 20px; right: 20px; z-index: 1000; padding: 12px 20px; border-radius: 12px; cursor: pointer; color: var(--text-main); background: var(--card-glass); border: 1px solid var(--card-border); font-weight: 700; display: flex; align-items: center; gap: 8px;">
                <i class="fa-solid fa-rotate"></i> Refresh
            </button>
        </div>
    </div>
@if(isset($incomingRequest) && $incomingRequest)
    <div id="incoming-modal" style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.85); z-index: 4000; display: flex; align-items: center; justify-content: center; backdrop-filter: blur(15px);">
        <div class="glass animate-fade" style="width: 100%; max-width: 450px; padding: 35px; border-radius: 30px; text-align: center; border: 1px solid var(--primary);">
            <div style="width: 70px; height: 70px; background: var(--primary-light); color: var(--primary); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 30px; margin: 0 auto 20px; box-shadow: 0 0 20px var(--primary-light);">
                <i class="fa-solid fa-car-side"></i>
            </div>
            <h3 style="color: var(--text-main); font-size: 22px; font-weight: 800; margin-bottom: 5px;">New Ride Request!</h3>
            <p style="color: var(--text-dim); font-size: 13px; margin-bottom: 25px;">A passenger is requesting a premium cab nearby.</p>
            
            <div style="background: var(--input-bg); border-radius: 16px; padding: 15px; text-align: left; margin-bottom: 25px; border: 1px solid var(--card-border);">
                <div style="display: flex; gap: 12px; margin-bottom: 12px;">
                    <i class="fa-solid fa-location-dot" style="color: var(--success); margin-top: 3px;"></i>
                    <div>
                        <span style="font-size: 10px; color: var(--text-dim); display: block; font-weight: 800; text-transform: uppercase;">Pickup</span>
                        <span style="font-size: 13px; color: var(--text-main); font-weight: 600;">{{ Str::limit($incomingRequest->pickup_location, 35) }}</span>
                    </div>
                </div>
                <div style="display: flex; gap: 12px;">
                    <i class="fa-solid fa-flag-checkered" style="color: var(--danger); margin-top: 3px;"></i>
                    <div>
                        <span style="font-size: 10px; color: var(--text-dim); display: block; font-weight: 800; text-transform: uppercase;">Dropoff</span>
                        <span style="font-size: 13px; color: var(--text-main); font-weight: 600;">{{ Str::limit($incomingRequest->dropoff_location, 35) }}</span>
                    </div>
                </div>
                <div style="margin-top: 15px; padding-top: 12px; border-top: 1px dashed var(--card-border); display: flex; justify-content: space-between; align-items: center;">
                    <span style="font-size: 11px; color: var(--text-dim); font-weight: 800; text-transform: uppercase;">Estimated Fare</span>
                    <span style="font-size: 16px; color: var(--primary); font-weight: 800;">{{ number_format($incomingRequest->fare) }} MMK</span>
                </div>
            </div>

            <div style="display: flex; gap: 15px;">
                <form action="{{ route('driver.trip.accept', [$driver->id, $incomingRequest->id]) }}" method="POST" style="flex: 1;">
                    @csrf
                    <button type="submit" style="width: 100%; background: var(--primary); color: var(--bg-main); padding: 15px; border-radius: 14px; font-weight: 900; border: none; cursor: pointer; box-shadow: 0 10px 20px var(--primary-light);">ACCEPT</button>
                </form>
                <form action="{{ route('driver.trip.decline', [$driver->id, $incomingRequest->id]) }}" method="POST" style="flex: 1;">
                    @csrf
                    <button type="submit" style="width: 100%; background: var(--input-bg); color: var(--text-main); padding: 15px; border-radius: 14px; font-weight: 700; border: 1px solid var(--card-border); cursor: pointer;">DECLINE</button>
                </form>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const audio = new Audio('https://actions.google.com/sounds/v1/alarms/digital_watch_alarm_long.ogg');
            audio.play().catch(() => {});
        });
    </script>
@endif
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

        setTimeout(() => { map.invalidateSize(); }, 200);

        if (isActive) {
            const pLat = {{ $activeBooking->pickup_lat ?? '16.8409' }};
            const pLng = {{ $activeBooking->pickup_lng ?? '96.1735' }};
            const dLat = {{ $activeBooking->dropoff_lat ?? '16.8660' }};
            const dLng = {{ $activeBooking->dropoff_lng ?? '96.1951' }};

            const primaryColor = getComputedStyle(document.documentElement).getPropertyValue('--primary').trim() || '#D4AF37';
            L.Routing.control({
                waypoints: [L.latLng(pLat, pLng), L.latLng(dLat, dLng)],
                lineOptions: { styles: [{ color: primaryColor, opacity: 0.8, weight: 6 }] },
                createMarker: function(i, wp) {
                    const iconHtml = (i === 0) 
                        ? '<div class="style-f62853"><i class="fa-solid fa-location-dot" style="color: var(--success);"></i></div>'
                        : '<div class="style-f62853"><i class="fa-solid fa-flag-checkered" style="color: var(--primary);"></i></div>';
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
    function showPaymentSelection() { 
        document.getElementById('payment-modal').style.display = 'flex'; 
        initPaymentInfo();
    }
    function closePaymentModal() { document.getElementById('payment-modal').style.display = 'none'; }
    function initPaymentInfo() {
        const m = "{{ strtolower($activeBooking->payment_method ?? 'cash') }}";
        const hintText = document.getElementById('hint-text');
        const fare = {{ $activeBooking->fare ?? 0 }};
        const commission = fare * 0.15;
        const earnings = fare - commission;
        
        if (m === 'wallet' || m === 'digital') {
            hintText.innerHTML = '<i class="fa-solid fa-circle-info" style="color: var(--primary);"></i> Passenger paid online. Your share (' + earnings.toLocaleString() + ') will be added to your wallet.';
        } else {
            hintText.innerHTML = '<i class="fa-solid fa-circle-info" style="color: var(--success);"></i> Collect full fare from passenger. Commission (' + commission.toLocaleString() + ') will be deducted from your wallet.';
        }
    }

    document.addEventListener('DOMContentLoaded', initMap);

    // Real-Time Auto-polling to detect incoming ride requests while on Heatmap
    setInterval(() => {
        fetch(window.location.href)
            .then(r => r.text())
            .then(html => {
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');
                const hasIncoming = doc.getElementById('incoming-modal');
                const currentHasIncoming = document.getElementById('incoming-modal');
                if (hasIncoming && !currentHasIncoming) {
                    const audio = new Audio('https://actions.google.com/sounds/v1/alarms/digital_watch_alarm_long.ogg');
                    audio.play().catch(() => {});
                    window.location.reload();
                }
            })
            .catch(() => {});
    }, 5000);
</script>
@endpush
@endsection

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Live Trip - Taxi</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine@latest/dist/leaflet-routing-machine.css" />
    <style>
        :root {
            --primary: #6366f1;
            --bg: #f8fafc;
            --card-bg: rgba(255, 255, 255, 0.95);
            --text: #1e293b;
            --accent: #10b981;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Outfit', sans-serif; }
        body { background: var(--bg); color: var(--text); height: 100vh; overflow: hidden; display: flex; }

        #map { flex: 1; height: 100%; z-index: 1; }

        .sidebar {
            width: 350px; height: 100%; background: var(--card-bg); 
            backdrop-filter: blur(20px); border-left: 1px solid rgba(0,0,0,0.05);
            z-index: 10; display: flex; flex-direction: column; padding: 30px;
            box-shadow: -10px 0 30px rgba(0,0,0,0.05);
        }

        .trip-header { margin-bottom: 20px; }
        .trip-status {
            display: inline-flex; align-items: center; gap: 8px;
            background: rgba(16, 185, 129, 0.1); color: var(--accent);
            padding: 8px 16px; border-radius: 20px; font-size: 11px; font-weight: 800;
            margin-bottom: 15px; text-transform: uppercase; letter-spacing: 1px;
        }
        .pulse { width: 8px; height: 8px; background: var(--accent); border-radius: 50%; animation: pulse 2s infinite; }
        @keyframes pulse { 0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7); } 70% { transform: scale(1); box-shadow: 0 0 0 10px rgba(16, 185, 129, 0); } 100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(16, 185, 129, 0); } }

        .dest-label { font-size: 11px; font-weight: 800; color: #64748b; text-transform: uppercase; margin-bottom: 5px; }
        .dest-name { font-size: 16px; font-weight: 800; color: var(--text); line-height: 1.4; }

        .trip-meta {
            display: flex; gap: 10px; margin-top: 15px;
        }
        .meta-pill {
            flex: 1; background: #f1f5f9; padding: 10px; border-radius: 12px;
            text-align: center; font-size: 13px; font-weight: 700; color: var(--text);
            display: flex; flex-direction: column; gap: 2px;
        }
        .meta-pill span:first-child { font-size: 10px; text-transform: uppercase; color: #64748b; }

        .divider { height: 1px; background: rgba(0,0,0,0.05); margin: 25px 0; }

        .driver-card {
            background: #fff; padding: 20px; border-radius: 20px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.03); margin-bottom: 20px;
        }
        .driver-info { display: flex; align-items: center; gap: 15px; margin-bottom: 15px; }
        .driver-avatar { width: 50px; height: 50px; border-radius: 15px; background: var(--primary); display: flex; align-items: center; justify-content: center; font-size: 20px; font-weight: 800; color: #fff; overflow: hidden; }
        .driver-avatar img { width: 100%; height: 100%; object-fit: cover; }
        .driver-name { font-size: 15px; font-weight: 800; }
        .car-info { font-size: 11px; color: #64748b; font-weight: 600; }

        .fare-info { display: flex; justify-content: space-between; align-items: center; background: #f8fafc; padding: 12px; border-radius: 12px; }
        .fare-label { font-size: 11px; font-weight: 700; color: #64748b; }
        .fare-val { font-size: 16px; font-weight: 800; color: var(--primary); }

        .actions { margin-top: auto; display: grid; gap: 10px; }
        .btn-action {
            display: flex; align-items: center; justify-content: center; gap: 10px;
            padding: 14px; border-radius: 15px; border: none; font-weight: 800; font-size: 14px;
            cursor: pointer; transition: 0.3s; text-decoration: none;
        }
        .btn-call { background: var(--primary); color: #fff; }
        .btn-cancel { background: #fee2e2; color: #ef4444; }

        /* Marker Styles */
        .map-marker { width: 30px; height: 30px; border-radius: 50% 50% 50% 0; border: 2px solid #fff; transform: rotate(-45deg); display: flex; align-items: center; justify-content: center; }
        .map-marker i { transform: rotate(45deg); font-size: 10px; color: #fff; }
        .marker-pickup { background: var(--primary); }
        .marker-dropoff { background: #f43f5e; }

        /* Hide Default Routing UI */
        .leaflet-routing-container { display: none !important; }

        @media (max-width: 768px) {
            body { flex-direction: column; }
            .sidebar { width: 100%; height: auto; border-left: none; border-top: 1px solid rgba(0,0,0,0.05); padding: 20px; }
            #map { height: 50%; }
        }
    </style>
</head>
<body>

    <div id="map"></div>

    <div class="sidebar">
        <div class="trip-header">
            <div class="trip-status">
                <div class="pulse"></div>
                @if($booking->status == 'pending') Searching Driver
                @elseif($booking->status == 'accepted') Driver is Coming
                @else Ongoing Trip
                @endif
            </div>
            <p class="dest-label">Destination</p>
            <h2 class="dest-name">{{ $booking->dropoff_location }}</h2>
            
            <div class="trip-meta">
                <div class="meta-pill">
                    <span>Distance</span>
                    <b id="trip-dist">-- km</b>
                </div>
                <div class="meta-pill">
                    <span>Est. Time</span>
                    <b id="trip-time">-- min</b>
                </div>
            </div>
        </div>

        <div class="divider"></div>

        <div class="driver-card">
            <div class="driver-info">
                <div class="driver-avatar">
                    @if($booking->driver && $booking->driver->profile_picture)
                        <img src="{{ Str::startsWith($booking->driver->profile_picture, 'uploads/') ? asset($booking->driver->profile_picture) : asset('storage/' . $booking->driver->profile_picture) }}" alt="">
                    @else
                        {{ substr($booking->driver->full_name ?? 'D', 0, 1) }}
                    @endif
                </div>
                <div>
                    <h4 class="driver-name">{{ $booking->driver->full_name ?? 'Assigning...' }}</h4>
                    <p class="car-info">{{ $booking->driver->vehicle->model ?? 'Standard Taxi' }}</p>
                    <p class="car-info" style="color: var(--primary);">{{ $booking->driver->vehicle->license_plate ?? '' }}</p>
                </div>
            </div>

            <div class="fare-info">
                <span class="fare-label">Total Fare</span>
                <span class="fare-val">{{ number_format($booking->fare) }} MMK</span>
            </div>
        </div>

        <div class="actions">
            <a href="tel:{{ $booking->driver->phone ?? '' }}" class="btn-action btn-call">
                <i class="fa-solid fa-phone"></i> CALL DRIVER
            </a>
            @if($booking->status == 'pending')
            <button class="btn-action btn-cancel" onclick="alert('Trip cancellation coming soon')">
                <i class="fa-solid fa-xmark"></i> CANCEL RIDE
            </button>
            @endif
        </div>
    </div>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://unpkg.com/leaflet-routing-machine@latest/dist/leaflet-routing-machine.js"></script>
    <script>
        const map = L.map('map', {zoomControl: false}).setView([{{ $booking->pickup_lat }}, {{ $booking->pickup_lng }}], 15);
        
        L.tileLayer('https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png', {
            attribution: '&copy; OpenStreetMap'
        }).addTo(map);

        const pickupIcon = L.divIcon({
            className: 'custom-div-icon',
            html: '<div class="map-marker marker-pickup"><i class="fa-solid fa-car"></i></div>',
            iconSize: [30, 30], iconAnchor: [15, 30]
        });

        const dropoffIcon = L.divIcon({
            className: 'custom-div-icon',
            html: '<div class="map-marker marker-dropoff"><i class="fa-solid fa-location-dot"></i></div>',
            iconSize: [30, 30], iconAnchor: [15, 30]
        });

        const pickup = L.latLng({{ $booking->pickup_lat }}, {{ $booking->pickup_lng }});
        const dropoff = L.latLng({{ $booking->dropoff_lat }}, {{ $booking->dropoff_lng }});

        L.marker(pickup, {icon: pickupIcon}).addTo(map);
        L.marker(dropoff, {icon: dropoffIcon}).addTo(map);

        const routingControl = L.Routing.control({
            waypoints: [pickup, dropoff],
            lineOptions: { styles: [{ color: '#6366f1', opacity: 0.6, weight: 6 }] },
            createMarker: function() { return null; },
            addWaypoints: false,
            routeWhileDragging: false,
            draggableWaypoints: false,
            fitSelectedRoutes: true,
            show: false // Explicitly hide directions
        }).addTo(map);

        routingControl.on('routesfound', function(e) {
            const routes = e.routes;
            const summary = routes[0].summary;
            
            // Update UI with distance and time
            const dist = (summary.totalDistance / 1000).toFixed(1);
            const time = Math.round(summary.totalTime / 60);
            
            document.getElementById('trip-dist').innerText = dist + ' km';
            document.getElementById('trip-time').innerText = time + ' min';
        });

        // Optimized Status Checker (No flickering)
        let checkStatusInterval = setInterval(() => {
            fetch(window.location.href)
                .then(r => r.text())
                .then(html => {
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');
                    const newStatus = doc.querySelector('.trip-status').innerText.trim();
                    const oldStatus = document.querySelector('.trip-status').innerText.trim();
                    
                    // Only reload if the status has actually changed (e.g. from Ongoing to Completed)
                    if (newStatus !== oldStatus) {
                        window.location.reload();
                    }
                })
                .catch(err => console.log('Status check failed'));
        }, 10000); // Check every 10 seconds to save battery and reduce flicker
    </script>
</body>
</html>

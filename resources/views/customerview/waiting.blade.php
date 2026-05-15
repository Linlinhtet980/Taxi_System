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
    <link rel="stylesheet" href="{{ asset('css/root/theme.css') }}">
    <script>
        const savedTheme = localStorage.getItem('taxi_theme') || 'dark';
        document.documentElement.setAttribute('data-theme', savedTheme);
    </script>
    <style>
        :root {
            --bg: var(--bg-main);
            --card-bg: var(--card-glass);
            --text: var(--text-main);
            --accent: var(--accent-secondary);
        }

        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Outfit', sans-serif; }
        body { background: var(--bg); color: var(--text); height: 100vh; overflow: hidden; display: flex; }

        #map { flex: 1; height: 100%; z-index: 1; }

        .sidebar {
            width: 350px; height: 100%; background: var(--card-bg); 
            backdrop-filter: blur(20px); border-left: 1px solid var(--card-border);
            z-index: 10; display: flex; flex-direction: column; padding: 30px;
            box-shadow: -10px 0 30px var(--primary-light);
        }

        .trip-header { margin-bottom: 20px; }
        .trip-status {
            display: inline-flex; align-items: center; gap: 8px;
            background: var(--success-light); color: var(--success);
            padding: 8px 16px; border-radius: 20px; font-size: 11px; font-weight: 800;
            margin-bottom: 15px; text-transform: uppercase; letter-spacing: 1px;
        }
        .pulse { width: 8px; height: 8px; background: var(--success); border-radius: 50%; animation: pulse 2s infinite; }
        @keyframes pulse { 0% { transform: scale(0.95); box-shadow: 0 0 0 0 var(--success-light); } 70% { transform: scale(1); box-shadow: 0 0 0 10px rgba(16, 185, 129, 0); } 100% { transform: scale(0.95); box-shadow: 0 0 0 0 var(--success-light); } }

        .dest-label { font-size: 11px; font-weight: 800; color: var(--text-dim); text-transform: uppercase; margin-bottom: 5px; }
        .dest-name { font-size: 16px; font-weight: 800; color: var(--text); line-height: 1.4; }

        .trip-meta {
            display: flex; gap: 10px; margin-top: 15px;
        }
        .meta-pill {
            flex: 1; background: var(--input-bg); padding: 10px; border-radius: 12px;
            text-align: center; font-size: 13px; font-weight: 700; color: var(--text-main);
            display: flex; flex-direction: column; gap: 2px;
            border: 1px solid var(--card-border);
        }
        .meta-pill span:first-child { font-size: 10px; text-transform: uppercase; color: var(--text-dim); }

        .divider { height: 1px; background: var(--card-border); margin: 25px 0; }

        .driver-card {
            background: var(--input-bg); padding: 20px; border-radius: 20px;
            box-shadow: 0 4px 15px var(--primary-light); margin-bottom: 20px;
            border: 1px solid var(--card-border);
        }
        .driver-info { display: flex; align-items: center; gap: 15px; margin-bottom: 15px; }
        .driver-avatar { width: 50px; height: 50px; border-radius: 15px; background: var(--primary); display: flex; align-items: center; justify-content: center; font-size: 20px; font-weight: 800; color: #fff; overflow: hidden; }
        .driver-avatar img { width: 100%; height: 100%; object-fit: cover; }
        .driver-name { font-size: 15px; font-weight: 800; }
        .car-info { font-size: 11px; color: var(--text-dim); font-weight: 600; }

        .fare-info { display: flex; justify-content: space-between; align-items: center; background: var(--bg-main); padding: 12px; border-radius: 12px; border: 1px solid var(--card-border); }
        .fare-label { font-size: 11px; font-weight: 700; color: var(--text-dim); }
        .fare-val { font-size: 16px; font-weight: 800; color: var(--primary); }

        .actions { margin-top: auto; display: grid; gap: 10px; }
        .btn-action {
            display: flex; align-items: center; justify-content: center; gap: 10px;
            padding: 14px; border-radius: 15px; border: none; font-weight: 800; font-size: 14px;
            cursor: pointer; transition: 0.3s; text-decoration: none;
        }
        .btn-call { background: var(--primary); color: var(--bg-main); }
        .btn-cancel { background: var(--danger-light); color: var(--danger); }

        /* Marker Styles */
        .map-marker { width: 30px; height: 30px; border-radius: 50% 50% 50% 0; border: 2px solid #fff; transform: rotate(-45deg); display: flex; align-items: center; justify-content: center; }
        .map-marker i { transform: rotate(45deg); font-size: 10px; color: #fff; }
        .marker-pickup { background: var(--primary); }
        .marker-dropoff { background: var(--danger); }

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

        <div class="actions" style="display: flex; gap: 10px; flex-wrap: wrap;">
            <button onclick="toggleChatDrawer()" class="btn-action" style="flex: 1; background: var(--card-glass); color: var(--primary); border: 1px solid var(--primary); font-weight: 800; cursor: pointer;">
                <i class="fa-solid fa-comments"></i> LIVE CHAT
            </button>
            <a href="tel:{{ $booking->driver->phone ?? '' }}" class="btn-action btn-call" style="flex: 1;">
                <i class="fa-solid fa-phone"></i> CALL
            </a>
            @if($booking->status == 'pending')
            <form action="{{ route('customer.booking.cancel', $booking->id) }}" method="POST" style="flex: 1; min-width: 100%;">
                @csrf
                <button type="submit" class="btn-action btn-cancel" style="width: 100%; border: none; cursor: pointer;">
                    <i class="fa-solid fa-xmark"></i> CANCEL RIDE
                </button>
            </form>
            @endif
        </div>
    </div>

    <!-- Live Chat Drawer Overlay -->
    <div id="chat-drawer" style="display: none; position: fixed; bottom: 0; left: 0; width: 100%; height: 75vh; background: rgba(15, 23, 42, 0.95); z-index: 10000; border-top-left-radius: 30px; border-top-right-radius: 30px; border-top: 1px solid var(--primary); box-shadow: 0 -10px 30px rgba(0,0,0,0.5); backdrop-filter: blur(20px); flex-direction: column;">
        <div style="padding: 20px; border-bottom: 1px solid var(--card-border); display: flex; justify-content: space-between; align-items: center;">
            <div style="display: flex; align-items: center; gap: 10px;">
                <div style="width: 10px; height: 10px; border-radius: 50%; background: var(--success); animation: pulseGlow 1.5s infinite;"></div>
                <h4 style="color: var(--text-main); font-weight: 800; font-size: 15px;">Chat with Driver</h4>
            </div>
            <button onclick="toggleChatDrawer()" style="background: none; border: none; color: var(--text-dim); font-size: 20px; cursor: pointer;"><i class="fa-solid fa-chevron-down"></i></button>
        </div>
        <div id="chat-messages-container" style="flex: 1; overflow-y: auto; padding: 20px; display: flex; flex-direction: column; gap: 12px;">
            <!-- Rendered Live -->
        </div>
        <div style="padding: 15px; border-top: 1px solid var(--card-border); display: flex; gap: 10px; background: var(--bg-main);">
            <input type="text" id="chat-input-msg" onkeypress="if(event.key === 'Enter') sendChatMessage()" placeholder="Type message..." style="flex: 1; background: var(--input-bg); border: 1px solid var(--card-border); padding: 12px 18px; border-radius: 20px; color: var(--text-main); outline: none;">
            <button onclick="sendChatMessage()" style="background: var(--primary); border: none; color: var(--bg-main); width: 45px; height: 45px; border-radius: 50%; cursor: pointer; display: flex; align-items: center; justify-content: center; font-size: 16px;">
                <i class="fa-solid fa-paper-plane"></i>
            </button>
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
            lineOptions: { styles: [{ color: getComputedStyle(document.documentElement).getPropertyValue('--primary').trim() || '#D4AF37', opacity: 0.6, weight: 6 }] },
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

        // Optimized Status Checker (Detects backend redirects seamlessly)
        let checkStatusInterval = setInterval(() => {
            const drawer = document.getElementById('chat-drawer');
            if (drawer && drawer.style.display !== 'none') return; // Pause auto-reload while chatting

            fetch(window.location.href)
                .then(r => {
                    if (r.redirected) {
                        window.location.href = r.url;
                        return null;
                    }
                    return r.text();
                })
                .then(html => {
                    if (!html) return;
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');
                    const statusEl = doc.querySelector('.trip-status');
                    if (!statusEl) {
                        window.location.reload();
                        return;
                    }
                    const newStatus = statusEl.innerText.trim();
                    const oldStatus = document.querySelector('.trip-status').innerText.trim();
                    
                    if (newStatus !== oldStatus) {
                        window.location.reload();
                    }
                })
                .catch(err => console.log('Status check failed'));
        }, 5000); // Check every 5 seconds for fast real-time demonstration

        // === Live Chat JS Functions ===
        let chatInterval = null;
        const targetBookingId = {{ $booking->id }};

        function toggleChatDrawer() {
            const drawer = document.getElementById('chat-drawer');
            if (drawer.style.display === 'none') {
                drawer.style.display = 'flex';
                fetchChatThread();
                chatInterval = setInterval(fetchChatThread, 3000);
            } else {
                drawer.style.display = 'none';
                if (chatInterval) clearInterval(chatInterval);
            }
        }

        function fetchChatThread() {
            fetch(`/chat/messages/${targetBookingId}`)
                .then(r => r.json())
                .then(data => {
                    if (data && data.success) {
                        const container = document.getElementById('chat-messages-container');
                        const isScrolledToBottom = container.scrollHeight - container.clientHeight <= container.scrollTop + 50;
                        
                        container.innerHTML = data.messages.map(m => `
                            <div style="display: flex; flex-direction: column; align-items: ${m.sender_type === 'customer' ? 'flex-end' : 'flex-start'};">
                                <div style="background: ${m.sender_type === 'customer' ? '#fac000' : 'var(--card-glass)'}; color: ${m.sender_type === 'customer' ? '#000000' : 'var(--text-main)'}; padding: 10px 16px; border-radius: 18px; max-width: 80%; font-size: 13px; font-weight: 800; border: 1px solid ${m.sender_type === 'customer' ? '#fac000' : 'var(--card-border)'}; box-shadow: 0 4px 15px rgba(250,192,0,0.2);">
                                    ${m.message}
                                </div>
                                <span style="font-size: 9px; color: var(--text-dim); margin-top: 4px; padding: 0 6px;">${m.time}</span>
                            </div>
                        `).join('');

                        if (isScrolledToBottom) container.scrollTop = container.scrollHeight;
                    }
                }).catch(() => {});
        }

        function sendChatMessage() {
            const input = document.getElementById('chat-input-msg');
            const msg = input.value.trim();
            if (!msg) return;

            input.value = '';
            fetch(`/chat/messages/${targetBookingId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ sender_type: 'customer', message: msg })
            }).then(r => r.json())
              .then(data => {
                  if (data.success) fetchChatThread();
              });
        }
    </script>
</body>
</html>

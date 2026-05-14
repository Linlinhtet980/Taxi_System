<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Taxi - Premium Booking</title>
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&display=swap" rel="stylesheet">
    <!-- Leaflet & CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine@latest/dist/leaflet-routing-machine.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <link rel="stylesheet" href="{{ asset('css/root/theme.css') }}">
    <link rel="stylesheet" href="{{ asset('css/customerview/booking.css') }}">
    <script>
        const savedTheme = localStorage.getItem('taxi_theme') || 'dark';
        document.documentElement.setAttribute('data-theme', savedTheme);
    </script>
</head>
<body>
    @if(session('error') || session('warning') || session('success') || $errors->any())
        <div style="position: fixed; top: 20px; left: 50%; transform: translateX(-50%); z-index: 9999; width: 90%; max-width: 400px; text-align: center;">
            @if(session('error'))
                <div style="background: var(--danger); color: white; padding: 12px 20px; border-radius: 12px; margin-bottom: 10px; font-size: 13px; font-weight: 600; box-shadow: 0 4px 12px rgba(0,0,0,0.3);">
                    {{ session('error') }}
                </div>
            @endif
            @if(session('warning'))
                <div style="background: var(--warning); color: var(--bg-main); padding: 12px 20px; border-radius: 12px; margin-bottom: 10px; font-size: 13px; font-weight: 700; box-shadow: 0 4px 12px rgba(0,0,0,0.3);">
                    {{ session('warning') }}
                </div>
            @endif
            @if(session('success'))
                <div style="background: var(--success); color: white; padding: 12px 20px; border-radius: 12px; margin-bottom: 10px; font-size: 13px; font-weight: 600; box-shadow: 0 4px 12px rgba(0,0,0,0.3);">
                    {{ session('success') }}
                </div>
            @endif
            @if($errors->any())
                <div style="background: var(--danger); color: white; padding: 12px 20px; border-radius: 12px; margin-bottom: 10px; font-size: 13px; font-weight: 600; box-shadow: 0 4px 12px rgba(0,0,0,0.3);">
                    {{ $errors->first() }}
                </div>
            @endif
        </div>
    @endif

    <div id="map"></div>
    
    <div class="header-overlay">
        <a href="{{ route('customer.dashboard') }}" class="back-btn-floating animate-fade">
            <i class="fa-solid fa-chevron-left"></i>
        </a>
        <div class="search-card animate-fade">
            <div class="location-input">
                <div class="dot-icon pickup"></div>
                <div class="flex-relative">
                    <input type="text" class="input-field" id="pickup_addr" placeholder="Where to pick you up?" autocomplete="off">
                    <div id="pickup-results" class="search-results-list"></div>
                </div>
                <button type="button" onclick="getCurrentLocation()" class="btn-location">
                    <i class="fa-solid fa-location-crosshairs"></i>
                </button>
            </div>
            <div class="divider"></div>
            <div class="location-input">
                <div class="dot-icon dropoff"></div>
                <div class="flex-relative">
                    <input type="text" class="input-field" id="dropoff_addr" placeholder="Where are you going?" autocomplete="off">
                    <div id="dropoff-results" class="search-results-list"></div>
                </div>
            </div>
        </div>
    </div>


    <div class="map-status-pill" id="map-status">Tap map or search to pick location</div>

    <div class="bottom-sheet" id="booking-sheet">
        <div class="sheet-handle"></div>
        <div class="sheet-title">
            <span>Select Vehicle</span>
            <span id="fare-display" class="fare-display">0 MMK</span>
        </div>

        <div class="driver-list">
            @foreach($availableDrivers as $driver)
            <div class="driver-card" onclick="selectDriver({{ $driver->id }})" id="driver-{{ $driver->id }}">
                <div class="car-avatar">
                    <i class="fa-solid fa-car-side"></i>
                </div>
                <div class="driver-info">
                    <div class="driver-name">{{ $driver->full_name }}</div>
                    <div class="car-model">{{ $driver->vehicle->model ?? 'Standard Taxi' }} • {{ $driver->vehicle->license_plate ?? 'N/A' }}</div>
                </div>
                <div class="rating">
                    <i class="fa-solid fa-star star-icon"></i>
                    <span class="rating-text">4.9</span>
                </div>
            </div>
            @endforeach
        </div>

        <div class="section-title" style="margin-top: 15px; font-size: 14px;">Payment Method</div>
        <div class="payment-grid">
            <div class="payment-card active" onclick="selectPayment('cash')" id="pay-cash">
                <i class="fa-solid fa-money-bill-1"></i>
                <span>Cash</span>
            </div>
            <div class="payment-card" onclick="selectPayment('wallet')" id="pay-wallet">
                <i class="fa-solid fa-wallet"></i>
                <div class="pay-info">
                    <span>Wallet</span>
                    <small>{{ number_format($customer->wallet_balance) }} Ks</small>
                </div>
            </div>
        </div>
        <div id="balance-warning" style="display: none; color: var(--danger); font-size: 11px; font-weight: 600; margin-top: 8px; text-align: center;">
            <i class="fa-solid fa-circle-exclamation"></i> လက်ကျန်ငွေ မလုံလောက်ပါ။ ငွေဖြည့်ပေးပါ။
        </div>

        <form action="{{ route('customer.booking.store') }}" method="POST" id="booking-form" onsubmit="prepareLocationValues()">
            @csrf
            <input type="hidden" name="customer_id" value="{{ $customer->id }}">
            <input type="hidden" name="driver_id" id="selected_driver">
            <input type="hidden" name="fare" id="fare_val">
            <input type="hidden" name="pickup_location" id="pickup_loc_val">
            <input type="hidden" name="dropoff_location" id="dropoff_loc_val">
            <input type="hidden" name="payment_method" id="payment_method_val" value="cash">
            
            <!-- Coordinates -->
            <input type="hidden" name="pickup_lat" id="pickup_lat">
            <input type="hidden" name="pickup_lng" id="pickup_lng">
            <input type="hidden" name="dropoff_lat" id="dropoff_lat">
            <input type="hidden" name="dropoff_lng" id="dropoff_lng">

            <button type="submit" class="confirm-btn" id="confirm-btn" disabled>
                Confirm Ride <i class="fa-solid fa-arrow-right"></i>
            </button>
        </form>
    </div>

    <!-- Scripts -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://unpkg.com/leaflet-routing-machine@latest/dist/leaflet-routing-machine.js"></script>
    
    <script>
        const map = L.map('map', {zoomControl: false}).setView([16.8409, 96.1735], 13);
        L.tileLayer('https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors &copy; <a href="https://carto.com/attributions">CARTO</a>',
            subdomains: 'abcd',
            maxZoom: 20
        }).addTo(map);

        let pickupMarker = null;
        let dropoffMarker = null;
        let routingControl = null;

        function getCurrentLocation() {
            if (navigator.geolocation) {
                document.getElementById('map-status').innerText = "Locating you...";
                navigator.geolocation.getCurrentPosition(position => {
                    const latlng = { lat: position.coords.latitude, lng: position.coords.longitude };
                    map.setView(latlng, 15);
                    setPickupMarker(latlng);
                }, () => {
                    alert("Could not get your location. Please check permissions.");
                    document.getElementById('map-status').innerText = "Tap map or search to pick location";
                });
            }
        }

        function setPickupMarker(latlng) {
            if (pickupMarker) map.removeLayer(pickupMarker);
            pickupMarker = L.marker(latlng, {
                icon: L.divIcon({
                    className: 'custom-marker',
                    html: '<div class="map-marker pickup"></div>'
                })
            }).addTo(map);
            
            document.getElementById('pickup_lat').value = latlng.lat;
            document.getElementById('pickup_lng').value = latlng.lng;
            
            reverseGeocode(latlng, 'pickup');
            
            if (!dropoffMarker) {
                document.getElementById('map-status').innerText = "Now pick destination";
            } else {
                calculateRoute();
            }
        }

        function setDropoffMarker(latlng) {
            if (dropoffMarker) map.removeLayer(dropoffMarker);
            dropoffMarker = L.marker(latlng, {
                icon: L.divIcon({
                    className: 'custom-marker',
                    html: '<div class="map-marker dropoff"></div>'
                })
            }).addTo(map);
            
            document.getElementById('dropoff_lat').value = latlng.lat;
            document.getElementById('dropoff_lng').value = latlng.lng;
            
            reverseGeocode(latlng, 'dropoff');
            
            if (pickupMarker) {
                calculateRoute();
                document.getElementById('map-status').style.display = 'none';
            }
        }

        map.on('click', function(e) {
            if (!pickupMarker) {
                setPickupMarker(e.latlng);
            } else if (!dropoffMarker) {
                setDropoffMarker(e.latlng);
            }
        });

        // Autocomplete Logic
        function setupAutocomplete(inputId, resultsId, type) {
            const input = document.getElementById(inputId);
            const results = document.getElementById(resultsId);
            let timeout = null;

            input.addEventListener('input', (e) => {
                clearTimeout(timeout);
                const query = e.target.value;
                if (query.length < 3) {
                    results.style.display = 'none';
                    return;
                }

                timeout = setTimeout(() => {
                    fetch(`https://nominatim.openstreetmap.org/search?q=${encodeURIComponent(query)}&format=json&limit=5&countrycodes=mm`)
                        .then(r => r.json())
                        .then(data => {
                            results.innerHTML = '';
                            if (data.length > 0) {
                                results.style.display = 'block';
                                data.forEach(item => {
                                    const div = document.createElement('div');
                                    div.className = 'search-result-item';
                                    div.innerText = item.display_name.split(',').slice(0,3).join(',');
                                    div.onclick = () => {
                                        const latlng = { lat: parseFloat(item.lat), lng: parseFloat(item.lon) };
                                        map.setView(latlng, 15);
                                        if (type === 'pickup') setPickupMarker(latlng);
                                        else setDropoffMarker(latlng);
                                        results.style.display = 'none';
                                        input.value = div.innerText;
                                    };
                                    results.appendChild(div);
                                });
                            } else {
                                results.style.display = 'none';
                            }
                        });
                }, 500);
            });

            // Close results when clicking outside
            document.addEventListener('click', (e) => {
                if (!input.contains(e.target) && !results.contains(e.target)) {
                    results.style.display = 'none';
                }
            });
        }

        setupAutocomplete('pickup_addr', 'pickup-results', 'pickup');
        setupAutocomplete('dropoff_addr', 'dropoff-results', 'dropoff');

        function prepareLocationValues() {
            const pVal = document.getElementById('pickup_loc_val');
            const dVal = document.getElementById('dropoff_loc_val');
            if (!pVal.value) pVal.value = document.getElementById('pickup_addr').value || "Current Pickup Location";
            if (!dVal.value) dVal.value = document.getElementById('dropoff_addr').value || "Selected Destination";
        }

        function reverseGeocode(latlng, type) {
            fetch(`https://nominatim.openstreetmap.org/reverse?lat=${latlng.lat}&lon=${latlng.lng}&format=json`)
                .then(r => r.json())
                .then(data => {
                    let addr = "Location (" + latlng.lat.toFixed(4) + ", " + latlng.lng.toFixed(4) + ")";
                    if (data && data.display_name) {
                        addr = data.display_name.split(',').slice(0, 2).join(', ');
                    }
                    if (type === 'pickup') {
                        document.getElementById('pickup_addr').value = addr;
                        document.getElementById('pickup_loc_val').value = addr;
                    } else {
                        document.getElementById('dropoff_addr').value = addr;
                        document.getElementById('dropoff_loc_val').value = addr;
                    }
                }).catch(() => {
                    const fallbackAddr = "Location (" + latlng.lat.toFixed(4) + ", " + latlng.lng.toFixed(4) + ")";
                    if (type === 'pickup') {
                        document.getElementById('pickup_addr').value = fallbackAddr;
                        document.getElementById('pickup_loc_val').value = fallbackAddr;
                    } else {
                        document.getElementById('dropoff_addr').value = fallbackAddr;
                        document.getElementById('dropoff_loc_val').value = fallbackAddr;
                    }
                });
        }

        function calculateRoute() {
            if (routingControl) map.removeControl(routingControl);

            routingControl = L.Routing.control({
                waypoints: [pickupMarker.getLatLng(), dropoffMarker.getLatLng()],
                router: L.Routing.osrmv1({ serviceUrl: 'https://router.project-osrm.org/route/v1' }),
                lineOptions: { styles: [{ color: getComputedStyle(document.documentElement).getPropertyValue('--primary').trim() || '#D4AF37', opacity: 0.8, weight: 6 }] },
                createMarker: function() { return null; }
            }).addTo(map);

            routingControl.on('routesfound', function(e) {
                let distance = e.routes[0].summary.totalDistance / 1000;
                // Round distance to 1 decimal place (e.g. 5.983 -> 6.0) to match user expectations
                distance = Math.round(distance * 10) / 10;
                
                const baseFare = {{ \App\Models\Core\Setting::get('base_fare', 2500) }};
                const pricePerKm = {{ \App\Models\Core\Setting::get('price_per_km', 800) }};
                const fare = Math.round(baseFare + (distance * pricePerKm));
                
                document.getElementById('fare-display').innerText = fare.toLocaleString() + ' MMK';
                document.getElementById('fare_val').value = fare;
                document.getElementById('booking-sheet').classList.add('active');
                checkValidation();
            });
        }

        let selectedPaymentMethod = 'cash';
        const userBalance = {{ $customer->wallet_balance }};

        function selectPayment(method) {
            selectedPaymentMethod = method;
            document.getElementById('payment_method_val').value = method;
            
            document.getElementById('pay-cash').classList.remove('active');
            document.getElementById('pay-wallet').classList.remove('active');
            document.getElementById('pay-' + method).classList.add('active');
            
            checkValidation();
        }

        function checkValidation() {
            const driverSelected = document.getElementById('selected_driver').value;
            const fare = parseFloat(document.getElementById('fare_val').value) || 0;
            const confirmBtn = document.getElementById('confirm-btn');
            const warning = document.getElementById('balance-warning');
            
            let isValid = driverSelected !== '';
            
            if (selectedPaymentMethod === 'wallet') {
                if (userBalance < fare) {
                    isValid = false;
                    warning.style.display = 'block';
                } else {
                    warning.style.display = 'none';
                }
            } else {
                warning.style.display = 'none';
            }
            
            confirmBtn.disabled = !isValid;
        }

        function selectDriver(id) {
            document.querySelectorAll('.driver-card').forEach(el => el.classList.remove('active'));
            document.getElementById('driver-' + id).classList.add('active');
            document.getElementById('selected_driver').value = id;
            checkValidation();
        }
    </script>
</body>
</html>

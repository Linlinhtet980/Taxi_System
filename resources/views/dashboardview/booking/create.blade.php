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
            <h2 class="page-title style-27ac6d">New Booking</h2>
        </div>
    </div>

    <form action="{{ route('bookings.store') }}" method="POST" class="animate-fade">
        @csrf
        <div class="style-9a3433">
            <!-- Form Side -->
            <div class="glass style-b293d7">
                <div class="style-257995">
                    <h3 class="style-4ac43c">
                        <i class="fa-solid fa-map-location-dot"></i> Dispatch Trip Details
                    </h3>
                </div>

                <div class="style-2ee324">
                    <input type="hidden" name="pickup_lat" id="pickup_lat">
                    <input type="hidden" name="pickup_lng" id="pickup_lng">
                    <input type="hidden" name="dropoff_lat" id="dropoff_lat">
                    <input type="hidden" name="dropoff_lng" id="dropoff_lng">

                    <div class="style-c45d59">
                        <div class="form-group">
                            <label class="style-d4bdca">Select Passenger</label>
                            <select name="customer_id" class="glass style-ad5d06" required>
                                <option value="">-- Choose Passenger --</option>
                                @foreach($customers as $customer)
                                    <option value="{{ $customer->id }}">{{ $customer->name }} ({{ $customer->phone }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="style-d4bdca">Assign Vehicle</label>
                            <select name="vehicle_id" class="glass style-ad5d06">
                                <option value="">-- Choose Vehicle --</option>
                                @foreach($vehicles as $vehicle)
                                    <option value="{{ $vehicle->id }}">{{ $vehicle->license_plate }} - {{ $vehicle->driver->full_name ?? 'No Driver' }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="style-d4bdca">Pickup Point</label>
                        <div class="style-d85c4e">
                            <i class="fa-solid fa-location-dot style-2d90a7"></i>
                            <input type="text" id="pickup_location" name="pickup_location" class="glass style-09e273" placeholder="Search pickup..." required onchange="searchLocation('pickup')">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="style-d4bdca">Destination</label>
                        <div class="style-d85c4e">
                            <i class="fa-solid fa-flag-checkered style-4b7518"></i>
                            <input type="text" id="dropoff_location" name="dropoff_location" class="glass style-09e273" placeholder="Search destination..." required onchange="searchLocation('dropoff')">
                        </div>
                    </div>

                    <div class="style-c45d59">
                        <div class="form-group">
                            <label class="style-d4bdca">Fare (MMK)</label>
                            <input type="number" name="fare" id="fare" class="glass style-358939">
                        </div>
                        <div class="form-group">
                            <label class="style-d4bdca">Initial Status</label>
                            <select name="status" class="glass style-ad5d06">
                                <option value="pending">Pending</option>
                                <option value="confirmed">Confirmed</option>
                                <option value="ongoing">Ongoing</option>
                            </select>
                        </div>
                    </div>

                    <button type="submit" class="btn-primary style-7cb755">
                        <i class="fa-solid fa-paper-plane"></i> CREATE TRIP & DISPATCH
                    </button>
                </div>
            </div>


            <!-- Map Side -->
            <div class="glass style-0f6d9e">
                <div id="create-map" class="style-e7e64e"></div>
                <div class="style-49faa5">
                    <i class="fa-solid fa-circle-info mr-1"></i> Type an address and press Tab/Enter to find it on the map.
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

<link rel="stylesheet" href="{{ asset('css/dashboardview/booking/create.css') }}">

<script>
    var map = L.map('create-map').setView([16.8661, 96.1951], 12);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);

    var pickupMarker, dropoffMarker, routingControl;
    var settingType = 'pickup';

    map.on('click', function(e) {
        updateLocationData(settingType, e.latlng.lat, e.latlng.lng);
        settingType = (settingType === 'pickup') ? 'dropoff' : 'pickup';
    });

    function searchLocation(type) {
        var query = document.getElementById(type + '_location').value;
        if (query.length < 3) return;
        fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${query}`)
            .then(res => res.json())
            .then(data => {
                if (data.length > 0) updateLocationData(type, data[0].lat, data[0].lon, false);
            });
    }

    function updateLocationData(type, lat, lng, updateInput = true) {
        document.getElementById(type + '_lat').value = lat;
        document.getElementById(type + '_lng').value = lng;

        if (updateInput) {
            fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}`)
                .then(res => res.json())
                .then(data => {
                    if (data.display_name) document.getElementById(type + '_location').value = data.display_name;
                });
        }
        updateMarkers(type, lat, lng);
    }

    function updateMarkers(type, lat, lon) {
        var pos = L.latLng(lat, lon);
        if (type === 'pickup') {
            if (pickupMarker) map.removeLayer(pickupMarker);
            pickupMarker = L.marker(pos, {draggable: true}).addTo(map).bindPopup("Pickup").openPopup();
            pickupMarker.on('dragend', function(e) { updateLocationData('pickup', e.target.getLatLng().lat, e.target.getLatLng().lng); });
        } else {
            if (dropoffMarker) map.removeLayer(dropoffMarker);
            dropoffMarker = L.marker(pos, {draggable: true}).addTo(map).bindPopup("Destination").openPopup();
            dropoffMarker.on('dragend', function(e) { updateLocationData('dropoff', e.target.getLatLng().lat, e.target.getLatLng().lng); });
        }
        drawRoute();
    }

    var baseFare = {{ $baseFare }};
    var pricePerKm = {{ $pricePerKm }};

    function drawRoute() {
        if (pickupMarker && dropoffMarker) {
            if (routingControl) map.removeControl(routingControl);

            routingControl = L.Routing.control({
                waypoints: [
                    pickupMarker.getLatLng(),
                    dropoffMarker.getLatLng()
                ],
                lineOptions: {
                    styles: [{ color: '#22d3ee', weight: 6, opacity: 0.8 }]
                },
                createMarker: function() { return null; },
                addWaypoints: false,
                routeWhileDragging: false,
                show: false
            }).addTo(map);

            routingControl.on('routesfound', function(e) {
                var routes = e.routes;
                var distance = (routes[0].summary.totalDistance / 1000); // KM
                
                // Calculate Fare: Base + (Distance * PricePerKM)
                var calculatedFare = Math.round(baseFare + (distance * pricePerKm));
                
                // Update Fare input field
                document.getElementsByName('fare')[0].value = calculatedFare;
                
                console.log("Distance: " + distance.toFixed(2) + " km");
                console.log("Calculated Fare: " + calculatedFare + " MMK");
            });
        }
    }

</script>
</div>
@endsection

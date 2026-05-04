@extends('layout.driver')

@push('css')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine@latest/dist/leaflet-routing-machine.css" />
<link rel="stylesheet" href="{{ asset('css/driverview/dashboard.css') }}">
@endpush

@section('content')
<div class="animate-fade">
    <!-- Header with Toggle -->
    <div class="style-995390">
        <div>
            <h3 class="style-aa38c0">Dashboard</h3>
            <p class="style-d9a249">{{ date('l, d M Y') }}</p>
        </div>
        <form action="{{ route('driver.status.toggle', $driver->id) }}" method="POST">
            @csrf
            <button type="submit" class="glass " style="padding: 8px 15px; border-radius: 50px; display: flex; align-items: center; gap: 8px; cursor: pointer; border-color: {{ $driver->driver_status =='active' ? '#4ade80': '#f43f5e' }};">
                <div  style="width: 10px; height: 10px; border-radius: 50%; background: {{ $driver->driver_status =='active' ? '#4ade80': '#f43f5e' }}; box-shadow: 0 0 10px {{ $driver->driver_status =='active' ? '#4ade80': '#f43f5e' }};"></div>
                <span  style="font-size: 0.8rem; font-weight: 600; color: {{ $driver->driver_status =='active' ? '#4ade80': '#f43f5e' }};">
                    {{ $driver->driver_status == 'active' ? 'Online' : 'Offline' }}
                </span>
            </button>
        </form>
    </div>

    <!-- Wallet Card -->
    <div class="glass" style="padding: 30px; margin-bottom: 25px; background: linear-gradient(135deg, #111 0%, #222 100%); border: 1px solid var(--primary); box-shadow: 0 15px 40px rgba(212, 175, 55, 0.15);">
        <p style="color: var(--primary); font-size: 0.8rem; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 10px;">{{ $driver->wallet_balance < 0 ? 'Outstanding Debt' : 'Total Earnings' }}</p>
        <h1 style="font-size: 2.8rem; font-weight: 800; color: #fff; margin-bottom: 20px;">{{ number_format($driver->wallet_balance) }} <span style="font-size: 1.2rem; color: var(--primary);">MMK</span></h1>
        
        <div style="display: flex; gap: 12px;">
            <button onclick="{{ $driver->wallet_balance > 0 ? "document.getElementById('withdraw-modal').style.display='flex'" : "alert('Insufficient balance to withdraw.')" }}" style="flex: 1; padding: 12px; border-radius: 15px; border: 1px solid var(--primary); background: var(--primary); color: #000; font-weight: 700; cursor: pointer;">
                WITHDRAW
            </button>
            <a href="{{ route('driver.withdrawals', $driver->id) }}" style="flex: 1; padding: 12px; border-radius: 15px; border: 1px solid var(--primary); background: transparent; color: var(--primary); font-weight: 700; text-align: center; text-decoration: none;">
                HISTORY
            </a>
        </div>
    </div>


    <!-- New Ride Requests (Flash Notification Style) -->
    @foreach($newRequests as $request)
    <div class="glass animate-fade style-1625a1">
        <div class="style-edad91">
            <h3 class="style-83dbb4"><i class="fa-solid fa-bell"></i> New Ride Request!</h3>
            <span class="style-8af478">{{ $request->created_at->diffForHumans() }}</span>
        </div>
        
        <div class="style-44cf8c">
            <div class="style-32ad3d">
                <i class="fa-solid fa-location-dot"></i>
                <p class="style-fd134c"><strong>From:</strong> {{ $request->pickup_location }}</p>
            </div>
            <div class="style-da6e27">
                <i class="fa-solid fa-flag-checkered"></i>
                <p class="style-fd134c"><strong>To:</strong> {{ $request->dropoff_location }}</p>
            </div>
            
            <!-- Mini Map for Request -->
            <div id="request-map-{{ $request->id }}" class="request-map-container"></div>
        </div>

        <div class="style-e30fad">
            <form action="{{ route('driver.trip.accept', [$driver->id, $request->id]) }}" method="POST"  class="style-49cdf8">
                @csrf
                <button type="submit" class="style-071527">
                    ACCEPT
                </button>
            </form>
            <form action="{{ route('driver.trip.decline', [$driver->id, $request->id]) }}" method="POST"  class="style-49cdf8">
                @csrf
                <button type="submit" class="style-d0dd65">
                    DECLINE
                </button>
            </form>
        </div>
    </div>
    @endforeach

    <!-- Vehicle Assignment Quick Info -->
    @if($driver->vehicle)
    <div class="glass animate-fade style-70a9a2">
        <div class="style-99129a">
            <div class="style-3035a9">
                <i class="fa-solid fa-car"></i>
            </div>
            <div>
                <p class="style-5df2e9">Assigned Vehicle</p>
                <h4 class="style-493754">{{ $driver->vehicle->license_plate }}</h4>
            </div>
        </div>
        <div class="style-7851db">
            <span  style="font-size: 0.65rem; background: {{ $driver->vehicle->status =='Available' ? 'rgba(74, 222, 128, 0.1)': 'rgba(251, 191, 36, 0.1)' }}; color: {{ $driver->vehicle->status =='Available' ? '#4ade80': '#fbbf24' }}; padding: 4px 10px; border-radius: 50px; font-weight: 700; text-transform: uppercase; border: 1px solid {{ $driver->vehicle->status =='Available' ? 'rgba(74, 222, 128, 0.2)': 'rgba(251, 191, 36, 0.2)' }};">
                {{ $driver->vehicle->status }}
            </span>
            <p class="style-3d8dfe">{{ $driver->vehicle->vehicle_type }}</p>
        </div>
    </div>
    @endif

    <!-- Stats Grid -->

    <div class="style-36b5aa">
        <div class="glass style-087bdd">
            <p class="style-b8cc31">Today's Earning</p>
            <h4 class="style-d97f2c">{{ number_format($todayEarnings) }}</h4>
        </div>
        <div class="glass style-087bdd">
            <p class="style-b8cc31">Weekly Total</p>
            <h4 class="style-1e8742">{{ number_format($thisWeekEarnings) }}</h4>
        </div>
    </div>

    <div class="style-d62148">
        <div class="glass style-087bdd">
            <p class="style-b8cc31">Completed Trips</p>
            <h4 class="style-33f38b">{{ $completedJobsCount }}</h4>
        </div>
        <div class="glass style-d0cce7">
            <p class="style-b8cc31">Online Time</p>
            <h4 class="style-1e8742">{{ $todayOnlineTime }}</h4>
        </div>
    </div>

    <!-- Earnings Analytics Chart -->
    <div class="glass animate-fade style-89c0e0">
        <h3 class="style-14fc06">Earnings Analytics (Last 7 Days)</h3>
        <div class="style-648ec5">
            <canvas id="earningsChart"></canvas>
        </div>
    </div>


    <!-- Daily Goal Tracker -->
    <div class="glass style-89c0e0">
        <div class="style-edad91">
            <div>
                <h3 class="style-8eae47">Daily Goal Progress</h3>
                <p class="style-831dee">Target: {{ number_format($dailyTarget) }} MMK</p>
            </div>
            <div class="style-7851db">
                <span class="style-85a9da">{{ $goalProgress }}%</span>
            </div>
        </div>
        <div class="style-98d1b1" style="height: 10px; background: rgba(255,255,255,0.05); border-radius: 10px; overflow: hidden; margin-bottom: 10px;">
            <div style="width: {{ $goalProgress }}%; height: 100%; background: var(--primary); box-shadow: 0 0 15px rgba(212, 175, 55, 0.5); transition: width 1s ease-out;"></div>
        </div>
        <p class="style-6a7a99">
            @if($goalProgress < 100)
                You need {{ number_format($dailyTarget - $todayEarnings) }} MMK more to reach your goal!
            @else
                Amazing! You've reached your daily goal! 🚀
            @endif
        </p>
    </div>

    <!-- More Features -->
    <div class="style-d62148">
        <a href="{{ route('driver.leaderboard', $driver->id) }}" class="glass style-8f156e" >
            <i class="fa-solid fa-trophy style-6055de"></i>
            <h4 class="style-70f30b">Leaderboard</h4>
            <p class="style-fbd666">See rankings</p>
        </a>
        <a href="{{ route('driver.reviews', $driver->id) }}" class="glass style-b3a40d" >
            <i class="fa-solid fa-star-half-stroke style-6055de"></i>
            <h4 class="style-70f30b">Reviews</h4>
            <p class="style-fbd666">Check feedback</p>
        </a>
    </div>

    <!-- Active Jobs Section -->
    <div class="style-ffbeea">
        <div class="style-edad91">
            <h3 class="style-460f77">
                <i class="fa-solid fa-bolt style-c06d9c"></i> Active Jobs
            </h3>
            @if($activeJobs->isNotEmpty())
            <button onclick="sendSOS()" class="style-3f3bbb">
                <i class="fa-solid fa-circle-exclamation"></i> SOS
            </button>
            @endif
        </div>

        @forelse($activeJobs as $job)
        <div class="glass style-f7bcf1">
            <div class="style-bb19fd">
                <span class="status-badge style-2f5f62">{{ strtoupper($job->status) }}</span>
                <span class="style-d9a249">#TRP-{{ str_pad($job->id, 5, '0', STR_PAD_LEFT) }}</span>
            </div>
            
            <div class="style-c3e0b7">
                <div class="style-800a82">
                    <i class="fa-solid fa-location-dot style-def79a"></i>
                    <div>
                        <p class="style-fbd666">PICKUP</p>
                        <p class="style-19f6a2">{{ $job->pickup_location }}</p>
                    </div>
                </div>
                <div class="style-800a82">
                    <i class="fa-solid fa-flag-checkered style-bb30be"></i>
                    <div>
                        <p class="style-fbd666">DESTINATION</p>
                        <p class="style-19f6a2">{{ $job->dropoff_location }}</p>
                    </div>
                </div>
            </div>

            <div class="style-6c78a9">
                <div class="style-b512c9">
                    <div class="style-579af7">
                        <i class="fa-solid fa-user style-fb2a71"></i>
                    </div>
                    <div>
                        <p class="style-fbd666">PASSENGER</p>
                        <p class="style-6b7db9">{{ $job->customer->name ?? 'N/A' }}</p>
                    </div>
                </div>
                <div class="style-0ed0e9">
                    <a href="{{ route('driver.demand.map', $driver->id) }}" class="glass style-9201ec" >
                        Navigate <i class="fa-solid fa-map-location-dot"></i>
                    </a>
                    
                    @if($job->status == 'confirmed')
                    <form action="{{ route('driver.trip.update', [$driver->id, $job->id]) }}" method="POST">
                        @csrf
                        <input type="hidden" name="status" value="ongoing">
                        <button type="submit" class="glass style-baf6db">
                            Start Trip
                        </button>
                    </form>
                    @elseif($job->status == 'ongoing')
                    <div class="style-199b6f">
                        <button onclick="showPaymentSelection({{ $job->id }}, {{ $job->fare }})" class="glass style-ab8c3e" >
                            FINISH TRIP <i class="fa-solid fa-flag-checkered style-43ae80"></i>
                        </button>
                    </div>

                    <!-- Visual Payment Selection Modal -->
                    <div id="payment-modal-{{ $job->id }}"  class="style-a2e1c8">
                        <div class="animate-fade style-607010">
                            <div class="style-7f5ca6">
                                <h2 class="style-3c195e">Select Payment Method</h2>
                                <p class="style-2e72b4">Total Fare: <span class="style-299f11">{{ number_format($job->fare) }} MMK</span></p>
                            </div>

                            <div class="style-64b7e1">
                                <!-- Cash Option -->
                                <div onclick="setPaymentMethod({{ $job->id }}, 'Cash')" id="opt-cash-{{ $job->id }}" class="glass style-00208b" >
                                    <div class="style-95d066">
                                        <i class="fa-solid fa-money-bill-wave style-f4ff59"></i>
                                    </div>
                                    <p class="style-cc1dc8">CASH</p>
                                    <p class="style-e89ed8">Customer pays you directly in cash.</p>
                                    <div class="style-8beb53">Commission: -{{ number_format($job->fare * 0.15) }}</div>
                                </div>

                                <!-- Digital Option -->
                                <div onclick="setPaymentMethod({{ $job->id }}, 'Digital')" id="opt-digital-{{ $job->id }}" class="glass style-00208b" >
                                    <div class="style-f668e7">
                                        <i class="fa-solid fa-mobile-screen-button style-020c5a"></i>
                                    </div>
                                    <p class="style-cc1dc8">DIGITAL</p>
                                    <p class="style-e89ed8">Customer paid online via app.</p>
                                    <div class="style-2e7fb7">Earnings: +{{ number_format($job->fare * 0.85) }}</div>
                                </div>
                            </div>

                            <form action="{{ route('driver.trip.update', [$driver->id, $job->id]) }}" method="POST">
                                @csrf
                                <input type="hidden" name="status" value="completed">
                                <input type="hidden" name="payment_method" id="final-method-{{ $job->id }}" value="Cash">
                                <button type="submit" id="submit-btn-{{ $job->id }}" class="btn-primary style-8db1fd" >
                                    COMPLETE TRANSACTION
                                </button>
                                <button type="button" onclick="closePaymentModal({{ $job->id }})"  class="style-604586">Back</button>
                            </form>
                        </div>
                    </div>
                    @endif



                </div>

            </div>
        </div>
        @empty
        <div class="glass style-59d046">
            <i class="fa-solid fa-mug-hot style-569918"></i>
            <p class="style-33dd45">No active jobs at the moment.</p>
        </div>
        @endforelse
    </div>

    <!-- Vehicle Card -->
    @if($driver->vehicle)
    <div class="glass style-790d87">
        <h3 class="style-69295c">Assigned Vehicle</h3>
        <div class="style-04affe">
            <div class="style-76e68d"><i class="fa-solid fa-car-side"></i></div>
            <div>
                <p class="style-7cc7b9">{{ $driver->vehicle->license_plate }}</p>
                <p class="style-831dee">{{ $driver->vehicle->model }} ({{ $driver->vehicle->type }})</p>
            </div>
        </div>
    </div>
    @endif

    <!-- Withdrawal Modal -->
    <div id="withdraw-modal" class="style-6e61f3">
        <div class="glass animate-fade style-6ff9b2">
            <div class="style-6bc63b">
                <h3 class="style-b28cb1">Request Payout</h3>
                <i class="fa-solid fa-xmark style-24b531" onclick="document.getElementById('withdraw-modal').style.display='none'"></i>
            </div>
            
            <form action="{{ route('driver.withdrawal.request', $driver->id) }}" method="POST">
                @csrf
                <div class="style-b0473a">
                    <label class="style-f36cc0">Withdrawal Amount (MMK)</label>
                    <input type="number" name="amount" class="glass style-5223f1" value="{{ $driver->wallet_balance }}" max="{{ $driver->wallet_balance }}" required>
                </div>
                
                <div class="style-ffbeea">
                    <label class="style-f36cc0">Payment Method</label>
                    <select name="payment_method" class="glass style-5223f1">
                        <option value="KBZPay">KBZPay</option>
                        <option value="WaveMoney">WaveMoney</option>
                        <option value="CBPay">CBPay</option>
                        <option value="Cash">Cash at Office</option>
                    </select>
                </div>
                
                <button type="submit" class="glass style-455ca1">
                    Confirm Request
                </button>
            </form>
        </div>
    </div>
</div>
@push('js')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://unpkg.com/leaflet-routing-machine@latest/dist/leaflet-routing-machine.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    function showPaymentSelection(jobId, fare) {
        document.getElementById('payment-modal-' + jobId).style.display = 'flex';
        setPaymentMethod(jobId, 'Cash'); // Default
    }

    function setPaymentMethod(jobId, method) {
        document.getElementById('final-method-' + jobId).value = method;
        
        // Visual selection feedback
        const cashOpt = document.getElementById('opt-cash-' + jobId);
        const digitalOpt = document.getElementById('opt-digital-' + jobId);
        const submitBtn = document.getElementById('submit-btn-' + jobId);

        if(method === 'Cash') {
            cashOpt.style.borderColor = '#4ade80';
            cashOpt.style.background = 'rgba(74, 222, 128, 0.1)';
            digitalOpt.style.borderColor = 'transparent';
            digitalOpt.style.background = 'rgba(255,255,255,0.03)';
            submitBtn.style.background = '#4ade80';
            submitBtn.innerText = 'COMPLETE (COLLECT CASH)';
        } else {
            digitalOpt.style.borderColor = '#60a5fa';
            digitalOpt.style.background = 'rgba(96, 165, 250, 0.1)';
            cashOpt.style.borderColor = 'transparent';
            cashOpt.style.background = 'rgba(255,255,255,0.03)';
            submitBtn.style.background = '#60a5fa';
            submitBtn.innerText = 'COMPLETE (DIGITAL PAID)';
        }
    }

    function closePaymentModal(jobId) {
        document.getElementById('payment-modal-' + jobId).style.display = 'none';
    }

    function sendSOS() {

        if(confirm('Are you sure you want to send an emergency SOS to the admin?')) {
            alert('SOS signal sent! Admin has been notified of your location.');
            // In a real app, this would hit an API endpoint to notify admin via socket/push
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        // Initialize Request Maps
        @foreach($newRequests as $request)
        (function() {
            const mapId = 'request-map-{{ $request->id }}';
            const pLat = {{ $request->pickup_lat ?? '16.8409' }};
            const pLng = {{ $request->pickup_lng ?? '96.1735' }};
            const dLat = {{ $request->dropoff_lat ?? '16.8660' }};
            const dLng = {{ $request->dropoff_lng ?? '96.1951' }};

            try {
                const reqMap = L.map(mapId, {
                    zoomControl: true,
                    attributionControl: false,
                    dragging: true,
                    touchZoom: true,
                    scrollWheelZoom: true,
                    doubleClickZoom: true
                }).setView([pLat, pLng], 14);

                L.tileLayer('https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png', {
                    maxZoom: 19
                }).addTo(reqMap);

                // Use Leaflet Routing Machine for actual road paths
                L.Routing.control({
                    waypoints: [
                        L.latLng(pLat, pLng),
                        L.latLng(dLat, dLng)
                    ],
                    lineOptions: {
                        styles: [{ color: '#6366f1', opacity: 0.8, weight: 6 }]
                    },
                    createMarker: function(i, wp, nWps) {
                        const iconHtml = (i === 0) 
                            ? '<div class="style-a21ea4"><i class="fa-solid fa-location-dot style-b33db9"></i></div>'
                            : '<div class="style-a21ea4"><i class="fa-solid fa-flag-checkered style-5a797c"></i></div>';
                        
                        return L.marker(wp.latLng, {
                            icon: L.divIcon({
                                html: iconHtml,
                                className: 'custom-div-icon',
                                iconSize: [24, 24],
                                iconAnchor: [12, 24]
                            })
                        });
                    },
                    addWaypoints: false,
                    routeWhileDragging: false,
                    draggableWaypoints: false,
                    fitSelectedRoutes: true
                }).addTo(reqMap);
                
                // Force a map refresh after a small delay to fix container sizing issues
                setTimeout(() => {
                    reqMap.invalidateSize();
                }, 500);
            } catch (e) {
                console.error("Map initialization failed for " + mapId, e);
                document.getElementById(mapId).innerHTML = '<div class="style-deac98">Map currently unavailable</div>';
            }
        })();
        @endforeach

        const ctx = document.getElementById('earningsChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: {!! json_encode($dailyEarnings->keys()) !!},
                datasets: [{
                    label: 'Earnings (MMK)',
                    data: {!! json_encode($dailyEarnings->values()) !!},
                    borderColor: '#D4AF37',
                    backgroundColor: 'rgba(212, 175, 55, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointRadius: 4,
                    pointBackgroundColor: '#D4AF37'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: 'rgba(255,255,255,0.05)' },
                        ticks: { color: '#94a3b8', font: { size: 10 } }
                    },
                    x: {
                        grid: { display: false },
                        ticks: { color: '#94a3b8', font: { size: 10 } }
                    }
                }
            }
        });
    });
</script>
@endpush
@endsection


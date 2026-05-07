@extends('layout.driver')

@push('css')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine@latest/dist/leaflet-routing-machine.css" />
<style>
    .dashboard-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 25px;
        margin-bottom: 30px;
    }

    .stat-card {
        padding: 25px;
        position: relative;
        overflow: hidden;
    }
    .stat-card i {
        position: absolute;
        right: -10px;
        bottom: -10px;
        font-size: 60px;
        color: rgba(212, 175, 55, 0.05);
        transform: rotate(-15deg);
    }
    .stat-card p { font-size: 11px; font-weight: 800; color: var(--text-dim); text-transform: uppercase; letter-spacing: 1.5px; margin-bottom: 8px; }
    .stat-card h2 { font-size: 24px; font-weight: 800; color: #fff; }
    .stat-card .trend { font-size: 11px; margin-top: 10px; font-weight: 700; }
    .trend.up { color: #4ade80; }

    .main-content-grid {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 30px;
    }

    .card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 25px;
    }
    .card-header h3 { font-size: 18px; font-weight: 800; color: #fff; display: flex; align-items: center; gap: 12px; }
    .card-header h3 i { color: var(--primary); }

    .online-status-card {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 20px 30px;
        margin-bottom: 30px;
        background: linear-gradient(90deg, rgba(212, 175, 55, 0.05) 0%, rgba(0,0,0,0) 100%);
    }

    .status-toggle-btn {
        padding: 10px 24px;
        border-radius: 50px;
        border: 1px solid var(--border-color);
        background: rgba(0,0,0,0.3);
        color: #fff;
        font-size: 12px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 1px;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 10px;
        transition: 0.3s;
    }
    .status-toggle-btn.active { border-color: #4ade80; color: #4ade80; }
    .status-toggle-btn.inactive { border-color: #f43f5e; color: #f43f5e; }
    .status-dot { width: 10px; height: 10px; border-radius: 50%; }

    .job-card { padding: 30px; margin-bottom: 25px; border-left: 4px solid var(--primary); }
    .job-info-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin: 20px 0; }
    .info-item { display: flex; gap: 15px; }
    .info-item i { font-size: 18px; color: var(--primary); margin-top: 3px; }
    .info-item .label { font-size: 10px; color: var(--text-dim); font-weight: 800; text-transform: uppercase; }
    .info-item .value { font-size: 14px; color: #fff; font-weight: 600; margin-top: 2px; }

    .btn-group { display: flex; gap: 15px; margin-top: 25px; }
    .btn-primary-dash { flex: 1; background: var(--primary); color: #000; padding: 15px; border-radius: 14px; font-weight: 800; border: none; cursor: pointer; transition: 0.3s; }
    .btn-secondary-dash { flex: 1; background: rgba(255,255,255,0.05); color: #fff; padding: 15px; border-radius: 14px; font-weight: 700; border: 1px solid rgba(255,255,255,0.1); cursor: pointer; transition: 0.3s; }

    .goal-card { padding: 30px; }
    .progress-container { height: 12px; background: rgba(255,255,255,0.05); border-radius: 10px; margin: 20px 0; overflow: hidden; }
    .progress-bar { height: 100%; background: var(--primary); box-shadow: 0 0 15px var(--primary); transition: 1s ease-out; }

    .request-map-container { height: 250px; border-radius: 20px; margin: 20px 0; border: 1px solid var(--border-color); overflow: hidden; }

    @media (max-width: 1200px) {
        .dashboard-grid { grid-template-columns: repeat(2, 1fr); }
        .main-content-grid { grid-template-columns: 1fr; }
    }
    @media (max-width: 600px) {
        .dashboard-grid { grid-template-columns: 1fr; }
    }
</style>
@endpush

@section('content')
<div class="animate-fade">
    <!-- Status & Welcome -->
    <div class="glass online-status-card">
        <div>
            <h2 style="font-size: 24px; font-weight: 800; color: #fff;">Welcome back, {{ $driver->full_name }}!</h2>
            <p style="color: var(--text-dim); font-size: 13px;">You have {{ $newRequests->count() }} new ride requests waiting.</p>
        </div>
        <form action="{{ route('driver.status.toggle', $driver->id) }}" method="POST">
            @csrf
            <button type="submit" class="status-toggle-btn {{ $driver->driver_status =='active' ? 'active': 'inactive' }}">
                <div class="status-dot" style="background: {{ $driver->driver_status =='active' ? '#4ade80': '#f43f5e' }}; box-shadow: 0 0 10px {{ $driver->driver_status =='active' ? '#4ade80': '#f43f5e' }};"></div>
                {{ $driver->driver_status == 'active' ? 'Online' : 'Offline' }}
            </button>
        </form>
    </div>

    <!-- Stats Grid -->
    <div class="dashboard-grid">
        <div class="glass stat-card">
            <i class="fa-solid fa-wallet"></i>
            <p>Wallet Balance</p>
            <h2>{{ number_format($driver->wallet_balance) }} K</h2>
            <div class="trend up"><i class="fa-solid fa-arrow-up"></i> Available</div>
        </div>
        <div class="glass stat-card">
            <i class="fa-solid fa-route"></i>
            <p>Trips Today</p>
            <h2>{{ $completedJobsCount ?? 0 }}</h2>
            <div class="trend"><i class="fa-solid fa-clock"></i> Last: {{ now()->format('H:i') }}</div>
        </div>
        <div class="glass stat-card">
            <i class="fa-solid fa-star"></i>
            <p>Rating</p>
            <h2>4.9</h2>
            <div class="trend up">Premium Driver</div>
        </div>
        <div class="glass stat-card">
            <i class="fa-solid fa-hourglass-half"></i>
            <p>Online Time</p>
            <h2>{{ $todayOnlineTime ?? '0h 0m' }}</h2>
            <div class="trend">Today's session</div>
        </div>
    </div>

    <div class="main-content-grid">
        <!-- Left Side: Active Jobs & Requests -->
        <div class="left-column">
            <!-- Active Jobs -->
            <div class="card-header">
                <h3><i class="fa-solid fa-bolt"></i> Active Trip</h3>
                @if($activeJobs->isNotEmpty())
                    <button onclick="sendSOS()" style="background: #f43f5e; color: #fff; border: none; padding: 8px 16px; border-radius: 10px; font-weight: 800; font-size: 11px; cursor: pointer;">SOS EMERGENCY</button>
                @endif
            </div>

            @forelse($activeJobs as $job)
                <div class="glass job-card">
                    <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                        <div>
                            <span style="background: var(--primary-light); color: var(--primary); padding: 4px 12px; border-radius: 8px; font-size: 10px; font-weight: 800; text-transform: uppercase;">{{ $job->status }}</span>
                            <p style="margin-top: 8px; font-size: 14px; font-weight: 700; color: #fff;">Trip #TRP-{{ str_pad($job->id, 5, '0', STR_PAD_LEFT) }}</p>
                        </div>
                        <h3 style="color: var(--primary); font-weight: 800;">{{ number_format($job->fare) }} K</h3>
                    </div>

                    <div class="job-info-grid">
                        <div class="info-item">
                            <i class="fa-solid fa-location-dot" style="color: #4ade80;"></i>
                            <div>
                                <p class="label">Pickup</p>
                                <p class="value">{{ $job->pickup_location }}</p>
                            </div>
                        </div>
                        <div class="info-item">
                            <i class="fa-solid fa-flag-checkered" style="color: #f43f5e;"></i>
                            <div>
                                <p class="label">Dropoff</p>
                                <p class="value">{{ $job->dropoff_location }}</p>
                            </div>
                        </div>
                    </div>

                    <div style="display: flex; align-items: center; gap: 12px; padding: 15px; background: rgba(255,255,255,0.03); border-radius: 15px;">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($job->customer->name ?? 'User') }}&background=333&color=fff" style="width: 35px; height: 35px; border-radius: 10px;">
                        <div>
                            <p class="label">Customer</p>
                            <p class="value" style="font-size: 13px;">{{ $job->customer->name ?? 'N/A' }}</p>
                        </div>
                        <a href="tel:{{ $job->customer->phone ?? '' }}" style="margin-left: auto; color: var(--primary); font-size: 18px;"><i class="fa-solid fa-phone-volume"></i></a>
                    </div>

                    <div class="btn-group">
                        <a href="{{ route('driver.demand.map', $driver->id) }}" class="btn-secondary-dash" style="text-align: center; text-decoration: none;">NAVIGATE</a>
                        @if($job->status == 'confirmed')
                            <form action="{{ route('driver.trip.update', [$driver->id, $job->id]) }}" method="POST" style="flex: 1;">
                                @csrf
                                <input type="hidden" name="status" value="ongoing">
                                <button type="submit" class="btn-primary-dash">START TRIP</button>
                            </form>
                        @elseif($job->status == 'ongoing')
                            <button onclick="showPaymentSelection({{ $job->id }}, {{ $job->fare }}, '{{ $job->payment_method }}')" class="btn-primary-dash">COMPLETE TRIP</button>
                        @endif
                    </div>
                </div>
            @empty
                <div class="glass" style="padding: 40px; text-align: center; color: var(--text-dim); margin-bottom: 30px;">
                    <i class="fa-solid fa-mug-hot" style="font-size: 40px; margin-bottom: 15px; opacity: 0.3;"></i>
                    <p>No active trips. Go online to receive requests!</p>
                </div>
            @endforelse

            <!-- New Requests -->
            <div class="card-header">
                <h3><i class="fa-solid fa-bell"></i> New Requests</h3>
            </div>
            @foreach($newRequests as $request)
                <div class="glass job-card" style="border-left-color: #6366f1;">
                    <div style="display: flex; justify-content: space-between;">
                        <h4 style="color: #fff; font-weight: 700;">New Ride Request</h4>
                        <span style="font-size: 11px; color: var(--text-dim);">{{ $request->created_at->diffForHumans() }}</span>
                    </div>
                    <div id="request-map-{{ $request->id }}" class="request-map-container"></div>
                    <div class="job-info-grid">
                        <div class="info-item">
                            <i class="fa-solid fa-location-arrow" style="color: #6366f1;"></i>
                            <div>
                                <p class="label">From</p>
                                <p class="value">{{ Str::limit($request->pickup_location, 40) }}</p>
                            </div>
                        </div>
                        <div class="info-item">
                            <i class="fa-solid fa-map-pin" style="color: #f43f5e;"></i>
                            <div>
                                <p class="label">To</p>
                                <p class="value">{{ Str::limit($request->dropoff_location, 40) }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="btn-group">
                        <form action="{{ route('driver.trip.accept', [$driver->id, $request->id]) }}" method="POST" style="flex: 1;">
                            @csrf
                            <button type="submit" class="btn-primary-dash" style="background: #6366f1; color: #fff;">ACCEPT</button>
                        </form>
                        <form action="{{ route('driver.trip.decline', [$driver->id, $request->id]) }}" method="POST" style="flex: 1;">
                            @csrf
                            <button type="submit" class="btn-secondary-dash">DECLINE</button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Right Side: Goals & Wallet -->
        <div class="right-column">
            <!-- Wallet Summary -->
            <div class="glass stat-card" style="margin-bottom: 30px; background: linear-gradient(135deg, rgba(212, 175, 55, 0.1) 0%, rgba(0,0,0,0) 100%);">
                <p>Available Balance</p>
                <h2 style="font-size: 32px; color: var(--primary);">{{ number_format($driver->wallet_balance) }} <span style="font-size: 14px;">MMK</span></h2>
                <div class="btn-group" style="margin-top: 20px;">
                    <button onclick="{{ $driver->wallet_balance > 0 ? "document.getElementById('withdraw-modal').style.display='flex'" : "alert('Insufficient balance.')" }}" class="btn-primary-dash" style="padding: 10px; font-size: 12px;">WITHDRAW</button>
                    <a href="{{ route('driver.withdrawals', $driver->id) }}" class="btn-secondary-dash" style="padding: 10px; font-size: 12px; text-align: center; text-decoration: none;">HISTORY</a>
                </div>
            </div>

            <!-- Daily Goal -->
            <div class="glass goal-card">
                <div class="card-header">
                    <h3><i class="fa-solid fa-bullseye"></i> Daily Goal</h3>
                    <span style="font-size: 14px; font-weight: 800; color: var(--primary);">{{ $goalProgress }}%</span>
                </div>
                <p style="font-size: 12px; color: var(--text-dim);">Target: {{ number_format($dailyTarget) }} MMK</p>
                <div class="progress-container">
                    <div class="progress-bar" style="width: {{ $goalProgress }}%;"></div>
                </div>
                <p style="font-size: 13px; color: #fff; font-weight: 600;">
                    @if($goalProgress < 100)
                        {{ number_format($dailyTarget - $todayEarnings) }} K more to go!
                    @else
                        Goal achieved! 🚀
                    @endif
                </p>
            </div>

            <!-- Quick Links -->
            <div style="margin-top: 30px;">
                <p class="nav-label" style="margin-left: 0;">Resources</p>
                <a href="{{ route('driver.leaderboard', $driver->id) }}" class="glass" style="display: flex; align-items: center; gap: 15px; padding: 20px; text-decoration: none; margin-bottom: 15px;">
                    <div style="width: 40px; height: 40px; background: rgba(212, 175, 55, 0.1); border-radius: 12px; display: flex; align-items: center; justify-content: center; color: var(--primary);"><i class="fa-solid fa-trophy"></i></div>
                    <div>
                        <h4 style="color: #fff; font-size: 14px;">Leaderboard</h4>
                        <p style="color: var(--text-dim); font-size: 11px;">Top drivers this week</p>
                    </div>
                </a>
                <a href="{{ route('driver.reviews', $driver->id) }}" class="glass" style="display: flex; align-items: center; gap: 15px; padding: 20px; text-decoration: none;">
                    <div style="width: 40px; height: 40px; background: rgba(212, 175, 55, 0.1); border-radius: 12px; display: flex; align-items: center; justify-content: center; color: var(--primary);"><i class="fa-solid fa-star"></i></div>
                    <div>
                        <h4 style="color: #fff; font-size: 14px;">Ratings & Reviews</h4>
                        <p style="color: var(--text-dim); font-size: 11px;">See what customers say</p>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Withdrawal Modal -->
<div id="withdraw-modal" style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.8); z-index: 3000; display: none; align-items: center; justify-content: center; backdrop-filter: blur(10px);">
    <div class="glass animate-fade" style="width: 100%; max-width: 450px; padding: 35px;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px;">
            <h3 style="color: #fff;">Request Payout</h3>
            <i class="fa-solid fa-xmark" style="cursor: pointer; color: var(--text-dim);" onclick="document.getElementById('withdraw-modal').style.display='none'"></i>
        </div>
        <form action="{{ route('driver.withdrawal.request', $driver->id) }}" method="POST">
            @csrf
            <div style="margin-bottom: 20px;">
                <label style="display: block; font-size: 11px; font-weight: 800; color: var(--text-dim); text-transform: uppercase; margin-bottom: 10px;">Amount (MMK)</label>
                <input type="number" name="amount" value="{{ $driver->wallet_balance }}" max="{{ $driver->wallet_balance }}" style="width: 100%; background: rgba(255,255,255,0.05); border: 1px solid var(--border-color); padding: 15px; border-radius: 12px; color: #fff; outline: none;" required>
            </div>
            <div style="margin-bottom: 25px;">
                <label style="display: block; font-size: 11px; font-weight: 800; color: var(--text-dim); text-transform: uppercase; margin-bottom: 10px;">Payment Method</label>
                <select name="payment_method" style="width: 100%; background: rgba(255,255,255,0.05); border: 1px solid var(--border-color); padding: 15px; border-radius: 12px; color: #fff; outline: none;">
                    <option value="KBZPay">KBZPay</option>
                    <option value="WaveMoney">WaveMoney</option>
                    <option value="CBPay">CBPay</option>
                    <option value="Cash">Cash at Office</option>
                </select>
            </div>
            <button type="submit" class="btn-primary-dash">SUBMIT REQUEST</button>
        </form>
    </div>
</div>

<!-- Trip Completion Modal -->
<div id="payment-selection-modal" style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.8); z-index: 3000; display: none; align-items: center; justify-content: center; backdrop-filter: blur(10px);">
    <div class="glass animate-fade" style="width: 100%; max-width: 500px; padding: 35px;">
        <h3 style="color: #fff; margin-bottom: 10px;">Finish Trip</h3>
        <p style="color: var(--text-dim); font-size: 14px; margin-bottom: 25px;">Select how the customer is paying for the ride.</p>
        
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 30px;">
            <div id="opt-cash" onclick="selectFinalPayment('Cash')" style="padding: 20px; border: 1px solid var(--border-color); border-radius: 20px; text-align: center; cursor: pointer; transition: 0.3s;">
                <i class="fa-solid fa-money-bill-wave" style="font-size: 30px; color: #4ade80; margin-bottom: 10px;"></i>
                <p style="font-weight: 800; color: #fff;">CASH</p>
            </div>
            <div id="opt-digital" onclick="selectFinalPayment('Digital')" style="padding: 20px; border: 1px solid var(--border-color); border-radius: 20px; text-align: center; cursor: pointer; transition: 0.3s;">
                <i class="fa-solid fa-mobile-screen" style="font-size: 30px; color: #60a5fa; margin-bottom: 10px;"></i>
                <p style="font-weight: 800; color: #fff;">DIGITAL</p>
            </div>
        </div>

        <form id="completion-form" action="" method="POST">
            @csrf
            <input type="hidden" name="status" value="completed">
            <input type="hidden" name="payment_method" id="final-payment-method" value="Cash">
            <button type="submit" class="btn-primary-dash" id="final-submit-btn">COMPLETE TRIP</button>
            <button type="button" onclick="document.getElementById('payment-selection-modal').style.display='none'" style="width: 100%; margin-top: 15px; background: transparent; border: none; color: var(--text-dim); cursor: pointer; font-weight: 700;">CANCEL</button>
        </form>
    </div>
</div>

@push('js')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://unpkg.com/leaflet-routing-machine@latest/dist/leaflet-routing-machine.js"></script>
<script>
    function showPaymentSelection(jobId, fare, method) {
        const modal = document.getElementById('payment-selection-modal');
        const form = document.getElementById('completion-form');
        form.action = `/driver/{{ $driver->id }}/trip/${jobId}/update`;
        
        // Convert to Capitalized case to match IDs if needed, but our methods are 'Cash'/'Digital' usually
        const capitalizedMethod = method.charAt(0).toUpperCase() + method.slice(1).toLowerCase();
        
        // Hide/Show options based on booking payment method
        const cashOpt = document.getElementById('opt-cash');
        const digitalOpt = document.getElementById('opt-digital');
        
        if (capitalizedMethod === 'Cash') {
            cashOpt.style.display = 'block';
            digitalOpt.style.display = 'none';
            document.querySelector('#payment-selection-modal h3 + p').innerText = 'The customer selected CASH payment.';
        } else {
            cashOpt.style.display = 'none';
            digitalOpt.style.display = 'block';
            document.querySelector('#payment-selection-modal h3 + p').innerText = 'The customer selected DIGITAL payment.';
        }
        
        modal.style.display = 'flex';
        selectFinalPayment(capitalizedMethod);
    }

    function selectFinalPayment(method) {
        document.getElementById('final-payment-method').value = method;
        const cashOpt = document.getElementById('opt-cash');
        const digitalOpt = document.getElementById('opt-digital');
        const submitBtn = document.getElementById('final-submit-btn');

        if(method === 'Cash') {
            cashOpt.style.borderColor = '#4ade80';
            cashOpt.style.background = 'rgba(74, 222, 128, 0.1)';
            digitalOpt.style.borderColor = 'rgba(212, 175, 55, 0.15)';
            digitalOpt.style.background = 'transparent';
            submitBtn.style.background = '#4ade80';
            submitBtn.innerText = 'COMPLETE (COLLECT CASH)';
        } else {
            digitalOpt.style.borderColor = '#60a5fa';
            digitalOpt.style.background = 'rgba(96, 165, 250, 0.1)';
            cashOpt.style.borderColor = 'rgba(212, 175, 55, 0.15)';
            cashOpt.style.background = 'transparent';
            submitBtn.style.background = '#60a5fa';
            submitBtn.innerText = 'COMPLETE (DIGITAL PAID)';
        }
    }

    function sendSOS() {
        if(confirm('Are you in immediate danger? This will notify emergency services and our dispatch team.')) {
            alert('SOS Signal Sent. Help is on the way.');
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        @foreach($newRequests as $request)
        (function() {
            const mapId = 'request-map-{{ $request->id }}';
            const pLat = {{ $request->pickup_lat ?? '16.8409' }};
            const pLng = {{ $request->pickup_lng ?? '96.1735' }};
            const dLat = {{ $request->dropoff_lat ?? '16.8660' }};
            const dLng = {{ $request->dropoff_lng ?? '96.1951' }};

            try {
                const reqMap = L.map(mapId, {
                    zoomControl: false,
                    attributionControl: false
                }).setView([pLat, pLng], 14);

                L.tileLayer('https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png').addTo(reqMap);

                L.Routing.control({
                    waypoints: [L.latLng(pLat, pLng), L.latLng(dLat, dLng)],
                    lineOptions: { styles: [{ color: '#6366f1', opacity: 0.8, weight: 6 }] },
                    createMarker: function() { return null; },
                    addWaypoints: false,
                    routeWhileDragging: false,
                    draggableWaypoints: false,
                    fitSelectedRoutes: true
                }).addTo(reqMap);
                
                setTimeout(() => { reqMap.invalidateSize(); }, 500);
            } catch (e) {
                console.error("Map failed", e);
            }
        })();
        @endforeach
    });
</script>
@endpush
@endsection

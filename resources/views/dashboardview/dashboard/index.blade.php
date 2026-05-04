<link rel="stylesheet" href="{{ asset('css/dashboardview/dashboard/index.css') }}">
@extends('layout.admin')

@push('css')
<link rel="stylesheet" href="{{ asset('css/dashboardview/dashboard.css') }}">
@endpush

@section('content')
<div class="container-fluid">
    <div class="page-header animate-fade">
        <div>
            <h1 class="page-title">Management Dashboard</h1>
            <p class="page-subtitle">Real-time overview of your fleet performance and revenue.</p>
        </div>
        <div class="dashboard-header">
            <div class="glass system-status">
                <div class="status-dot"></div>
                <span class="status-text">System Online</span>
            </div>
            <button class="btn-primary" onclick="window.location.href='{{ route('bookings.map') }}'">
                <i class="fa-solid fa-satellite"></i> Live Tracking
            </button>
        </div>
    </div>

    <!-- Stats Grid (Always 4 columns on desktop) -->
    <div class="stats-grid animate-fade dashboard-stats-grid">
        <div class="stat-card glass style-2fc629">
            <div class="stat-card-inner">
                <div>
                    <h3 class="stat-label">Daily Revenue</h3>
                    <p class="stat-value style-a5e3c2">{{ number_format($todayRevenue) }} <span class="style-fb2a71">MMK</span></p>
                </div>
                <div class="stat-icon-box style-82cb4d">
                    <i class="fa-solid fa-coins fa-lg"></i>
                </div>
            </div>
            <div class="stat-update-info style-a5e3c2">
                <i class="fa-solid fa-arrow-trend-up"></i> Updated just now
            </div>
        </div>

        <div class="stat-card glass style-0cda32">
            <div class="stat-card-inner">
                <div>
                    <h3 class="stat-label">Active Drivers</h3>
                    <p class="stat-value style-b2fa7f">{{ number_format($activeDrivers) }}</p>
                </div>
                <div class="stat-icon-box style-7e310d">
                    <i class="fa-solid fa-id-card fa-lg"></i>
                </div>
            </div>
            <div class="stat-update-info style-2e72b4">
                Available for dispatch
            </div>
        </div>

        <div class="stat-card glass style-3f5f82">
            <div class="stat-card-inner">
                <div>
                    <h3 class="stat-label">Total Rides</h3>
                    <p class="stat-value style-524f3f">{{ number_format($totalBookings) }}</p>
                </div>
                <div class="stat-icon-box style-804922">
                    <i class="fa-solid fa-taxi fa-lg"></i>
                </div>
            </div>
            <div class="stat-update-info style-2e72b4">
                Cumulative bookings
            </div>
        </div>

        <div class="stat-card glass style-1f578e">
            <div class="stat-card-inner">
                <div>
                    <h3 class="stat-label">Passengers</h3>
                    <p class="stat-value style-462d35">{{ number_format($totalCustomers) }}</p>
                </div>
                <div class="stat-icon-box style-9fcb8c">
                    <i class="fa-solid fa-users fa-lg"></i>
                </div>
            </div>
            <div class="stat-update-info style-2e72b4">
                Registered accounts
            </div>
        </div>
    </div>

    <!-- Middle Row: Charts & Quick Actions -->
    <div class="animate-fade dashboard-middle-row style-967544">
        <!-- Status Chart Card -->
        <div class="table-container glass chart-card style-9489cc">
            <h2 class="chart-title">Booking Status</h2>
            <div class="chart-container style-ced3a3">
                <canvas id="statusChart"></canvas>
            </div>
            <div class="chart-legend style-64348a">
                @foreach($statusCounts as $status => $count)
                <div class="legend-item style-37e43d">
                    <div class="legend-dot " style="width: 8px; height: 8px; border-radius: 50%; background: {{ ['pending'=>'#fbbf24','confirmed'=>'#60a5fa','ongoing'=>'#a855f7','completed'=>'#4ade80','cancelled'=>'#f43f5e'][$status] ?? '#94a3b8' }};"></div>
                    <span>{{ ucfirst($status) }}</span>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Revenue Trend Chart Card -->
        <div class="table-container glass chart-card style-9489cc">
            <h2 class="chart-title">Revenue Trend (Last 6 Months)</h2>
            <div class="chart-container style-8a1f03">
                <canvas id="revenueChart"></canvas>
            </div>
        </div>

        <!-- Quick Launch Panel -->
        <div class="table-container glass launch-panel style-9489cc">
            <h2 class="chart-title">Quick Actions</h2>
            <div class="launch-grid style-e4ed45">
                <a href="{{ route('bookings.create') }}" class="action-tile glass style-ae2f99">
                    <i class="fa-solid fa-plus-circle style-b2fa7f"></i>
                    <span>New Ride</span>
                </a>
                <a href="{{ route('drivers.create') }}" class="action-tile glass style-ae2f99">
                    <i class="fa-solid fa-user-plus style-8ffdae"></i>
                    <span>Add Driver</span>
                </a>
                <a href="{{ route('reports.transactions.print') }}" target="_blank" class="action-tile glass style-ae2f99">
                    <i class="fa-solid fa-file-pdf style-524f3f"></i>
                    <span>Print PDF</span>
                </a>
                <a href="{{ route('settings.index') }}" class="action-tile glass style-ae2f99">
                    <i class="fa-solid fa-gears style-c06d9c"></i>
                    <span>Settings</span>
                </a>
            </div>
        </div>
    </div>


    <!-- Bottom Row: Full Width Table -->
    <div class="animate-fade live-stream-card">
        <div class="table-container glass style-67b87e">
            <div class="live-stream-header">
                <h2 class="stream-title">Live Booking Stream</h2>
                <a href="{{ route('bookings.index') }}" class="btn-reset view-history-btn">View Full History</a>
            </div>
            <table class="premium-table">
                <thead>
                    <tr>
                        <th>Trip ID</th>
                        <th>Passenger</th>
                        <th>Status</th>
                        <th>Fare</th>
                        <th>Booked Time</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentBookings as $booking)
                    <tr class="table-row">
                        <td><span class="primary-text trip-id-text">#TRP-{{ str_pad($booking->id, 5, '0', STR_PAD_LEFT) }}</span></td>
                        <td>
                            <div class="primary-text">{{ $booking->customer->name ?? 'N/A' }}</div>
                        </td>
                        <td>
                            @php
                                $statusColors = [
                                    'pending' => '#fbbf24',
                                    'confirmed' => '#60a5fa',
                                    'ongoing' => '#a855f7',
                                    'completed' => '#4ade80',
                                    'cancelled' => '#f43f5e'
                                ];
                                $color = $statusColors[$booking->status] ?? '#94a3b8';
                            @endphp
                            <span class="status-badge " style="background: {{ $color }}20; color: {{ $color }}; border: 1px solid {{ $color }}40; font-size: 0.7rem;">
                                {{ strtoupper($booking->status) }}
                            </span>
                        </td>
                        <td><span class="primary-text fare-text">{{ number_format($booking->fare) }} MMK</span></td>
                        <td><span class="secondary-text">{{ $booking->created_at->format('Y-m-d H:i') }} ({{ $booking->created_at->diffForHumans() }})</span></td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="style-a47a8c">No active bookings found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('js')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="{{ asset('js/dashboardview/dashboard/status-chart.js') }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        initStatusChart({!! json_encode($statusCounts) !!});
        
        // Revenue Trend Chart
        const ctxRevenue = document.getElementById('revenueChart').getContext('2d');
        new Chart(ctxRevenue, {
            type: 'line',
            data: {
                labels: {!! json_encode($monthlyRevenue->keys()) !!},
                datasets: [{
                    label: 'Revenue (MMK)',
                    data: {!! json_encode($monthlyRevenue->values()) !!},
                    borderColor: '#a855f7',
                    backgroundColor: 'rgba(168, 85, 247, 0.1)',
                    fill: true,
                    tension: 0.4,
                    borderWidth: 3,
                    pointBackgroundColor: '#a855f7'
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
                        ticks: { color: '#94a3b8' }
                    },
                    x: {
                        grid: { display: false },
                        ticks: { color: '#94a3b8' }
                    }
                }
            }
        });
    });
</script>

@endpush
@endsection

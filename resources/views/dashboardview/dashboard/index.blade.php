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
            <x-button onclick="window.location.href='{{ route('bookings.map') }}'">
                <i class="fa-solid fa-satellite"></i> Live Tracking
            </x-button>
        </div>
    </div>

    <!-- Stats Grid (Always 4 columns on desktop) -->
    <div class="stats-grid animate-fade dashboard-stats-grid">
        <x-stat-card 
            label="Daily Revenue" 
            value="{{ number_format($todayRevenue) }} MMK" 
            icon="fa-solid fa-coins"
        />

        <x-stat-card 
            label="Active Drivers" 
            value="{{ number_format($activeDrivers) }}" 
            icon="fa-solid fa-id-card"
        />

        <x-stat-card 
            label="Total Rides" 
            value="{{ number_format($totalBookings) }}" 
            icon="fa-solid fa-taxi"
        />

        <x-stat-card 
            label="Passengers" 
            value="{{ number_format($totalCustomers) }}" 
            icon="fa-solid fa-users"
        />
    </div>

    <!-- Quick Actions Row -->
    <div class="quick-actions-row animate-fade">
        <a href="{{ route('bookings.create') }}" class="action-tile-col glass">
            <i class="fa-solid fa-plus-circle style-b2fa7f"></i>
            <span class="primary-text">New Ride</span>
        </a>
        <a href="{{ route('drivers.create') }}" class="action-tile-col glass">
            <i class="fa-solid fa-user-plus style-8ffdae"></i>
            <span class="primary-text">Add Driver</span>
        </a>
        <a href="{{ route('reports.transactions.print') }}" target="_blank" class="action-tile-col glass">
            <i class="fa-solid fa-file-pdf style-524f3f"></i>
            <span class="primary-text">Print PDF</span>
        </a>
        <a href="{{ route('settings.index') }}" class="action-tile-col glass">
            <i class="fa-solid fa-gears style-c06d9c"></i>
            <span class="primary-text">Settings</span>
        </a>
    </div>

    <!-- Middle Row: Charts -->
    <div class="animate-fade dashboard-middle-row charts-row">
        <!-- Status Chart Card -->
        <div class="table-container glass chart-card style-9489cc">
            <h2 class="chart-title">Booking Status</h2>
            <div class="chart-container style-ced3a3">
                <canvas id="statusChart"></canvas>
            </div>
            <div class="chart-legend style-64348a">
                @php
                    $statusVarMap = [
                        'pending' => 'var(--warning)',
                        'confirmed' => 'var(--info)',
                        'ongoing' => 'var(--primary)',
                        'completed' => 'var(--success)',
                        'cancelled' => 'var(--danger)'
                    ];
                @endphp
                @foreach($statusCounts as $status => $count)
                <div class="legend-item style-37e43d">
                    <div class="legend-dot" style="width: 8px; height: 8px; border-radius: 50%; background: {{ $statusVarMap[$status] ?? 'var(--text-dim)' }};"></div>
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
                            <span class="status-badge status-{{ $booking->status }}">
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
                    borderColor: getComputedStyle(document.documentElement).getPropertyValue('--primary').trim() || '#D4AF37',
                    backgroundColor: getComputedStyle(document.documentElement).getPropertyValue('--primary-light').trim() || 'rgba(212, 175, 55, 0.1)',
                    fill: true,
                    tension: 0.4,
                    borderWidth: 3,
                    pointBackgroundColor: getComputedStyle(document.documentElement).getPropertyValue('--primary').trim() || '#D4AF37'
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
                        grid: { 
                            color: getComputedStyle(document.documentElement).getPropertyValue('--card-border').trim() || 'rgba(255,255,255,0.05)' 
                        },
                        ticks: { 
                            color: getComputedStyle(document.documentElement).getPropertyValue('--text-dim').trim() || '#94a3b8' 
                        }
                    },
                    x: {
                        grid: { display: false },
                        ticks: { 
                            color: getComputedStyle(document.documentElement).getPropertyValue('--text-dim').trim() || '#94a3b8' 
                        }
                    }
                }
            }
        });
    });
</script>

@endpush
@endsection

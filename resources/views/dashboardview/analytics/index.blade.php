@extends('layout.admin')

@push('css')
    <link rel="stylesheet" href="{{ asset('css/dashboardview/analytics/index.css') }}">
@endpush

@section('content')
<div class="animate-fade">
    <!-- Header -->
    <div class="page-header">
        <div>
            <h2 class="page-title">Analytics & Reports</h2>
            <p class="page-subtitle">Visual overview of fleet performance and revenue trends.</p>
        </div>
        <button class="btn-primary" onclick="window.print()">
            <i class="fa-solid fa-file-export"></i> Export Report
        </button>
    </div>

    <!-- Quick Stats -->
    <div class="stats-grid analytics-stats">
        <div class="stat-card glass style-1f7c4f">
            <h3 class="stat-label">Total Revenue</h3>
            <p class="stat-value style-a5e3c2">{{ number_format($totalRevenue) }} <span class="style-fb2a71">MMK</span></p>
        </div>
        <div class="stat-card glass style-b66710">
            <h3 class="stat-label">Total Bookings</h3>
            <p class="stat-value">{{ $totalBookings }}</p>
        </div>
        <div class="stat-card glass style-99ad2f">
            <h3 class="stat-label">Completed Rides</h3>
            <p class="stat-value">{{ $completedBookings }}</p>
        </div>
        <div class="stat-card glass style-cf6fa0">
            <h3 class="stat-label">Active Drivers</h3>
            <p class="stat-value">{{ $activeDrivers }}</p>
        </div>
    </div>



    <!-- Charts Row -->
    <div class="analytics-grid">
        <div class="glass chart-card">
            <h3 class="section-title style-d3297f">
                <i class="fa-solid fa-chart-line style-200979"></i> Revenue Trend
            </h3>
            <div class="chart-container">
                <canvas id="revenueChart"></canvas>
            </div>
        </div>
        <div class="glass chart-card">
            <h3 class="section-title style-d3297f">
                <i class="fa-solid fa-chart-pie style-b2fa7f"></i> Booking Status
            </h3>
            <div class="chart-container">
                <canvas id="statusChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Rankings Row -->
    <div class="analytics-grid style-a43474">
        <div class="glass ranking-card">
            <h3 class="section-title">
                <i class="fa-solid fa-trophy style-462d35"></i> Top Drivers
            </h3>
            <div class="style-988c5f">
                @foreach($topDrivers as $index => $driver)
                <div class="ranking-item">
                    <div class="driver-rank-info">
                        <span class="rank-number">#{{ $index + 1 }}</span>
                        <div>
                            <p class="style-3a4f71">{{ $driver->full_name }}</p>
                            <p class="style-fbd666">{{ $driver->license_no }}</p>
                        </div>
                    </div>
                    <div class="style-7851db">
                        <p class="style-c8c51c">{{ $driver->bookings_count }}</p>
                        <p class="style-fbd666">Rides</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <div class="glass ranking-card">
            <h3 class="section-title">
                <i class="fa-solid fa-money-bill-trend-up style-a5e3c2"></i> High Value Trips
            </h3>
            <div class="style-988c5f">
                @foreach($highValueTrips as $trip)
                <div class="ranking-item">
                    <div>
                        <p class="style-3a4f71">{{ $trip->customer->name }}</p>
                        <p class="style-fbd666">Trip #{{ $trip->id }}</p>
                    </div>
                    <div class="style-7851db">
                        <p class="revenue-value">{{ number_format($trip->fare) }}</p>
                        <p class="style-fbd666">MMK</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Revenue Chart
        const revCtx = document.getElementById('revenueChart').getContext('2d');
        const revenueTrendData = {!! json_encode($revenueTrend->pluck('total')) !!};
        const revenueTrendLabels = {!! json_encode($revenueTrend->pluck('date')) !!};

        new Chart(revCtx, {
            type: 'line',
            data: {
                labels: revenueTrendLabels.length ? revenueTrendLabels : ['No Data'],
                datasets: [{
                    label: 'Revenue',
                    data: revenueTrendData.length ? revenueTrendData : [0],
                    borderColor: getComputedStyle(document.documentElement).getPropertyValue('--primary').trim() || '#D4AF37',
                    backgroundColor: getComputedStyle(document.documentElement).getPropertyValue('--primary-light').trim() || 'rgba(212, 175, 55, 0.1)',
                    fill: true,
                    tension: 0.4,
                    borderWidth: 3,
                    pointBackgroundColor: getComputedStyle(document.documentElement).getPropertyValue('--primary').trim() || '#D4AF37',
                    pointBorderColor: 'rgba(255,255,255,0.5)',
                    pointHoverRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { 
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: 'rgba(15, 23, 42, 0.9)',
                        titleColor: '#22d3ee',
                        bodyColor: '#fff',
                        borderColor: 'rgba(34, 211, 238, 0.2)',
                        borderWidth: 1,
                        padding: 12,
                        displayColors: false
                    }
                },
                scales: {
                    y: { 
                        beginAtZero: true,
                        grid: { color: getComputedStyle(document.documentElement).getPropertyValue('--card-border').trim() || 'rgba(0,0,0,0.1)' }, 
                        ticks: { color: getComputedStyle(document.documentElement).getPropertyValue('--text-dim').trim() || '#6b6b6b', font: { family: 'Outfit' } } 
                    },
                    x: { 
                        grid: { display: false }, 
                        ticks: { color: getComputedStyle(document.documentElement).getPropertyValue('--text-dim').trim() || '#6b6b6b', font: { family: 'Outfit' } } 
                    }
                }
            }
        });

        // Status Chart
        const statusCtx = document.getElementById('statusChart').getContext('2d');
        const statusCounts = {!! json_encode($statusCounts) !!};
        const statusLabels = Object.keys(statusCounts);
        const statusValues = Object.values(statusCounts);

        new Chart(statusCtx, {
            type: 'pie',
            data: {
                labels: statusLabels.length ? statusLabels.map(s => s.toUpperCase()) : ['NO DATA'],
                datasets: [{
                    data: statusValues.length ? statusValues : [1],
                    backgroundColor: statusValues.length ? [
                        getComputedStyle(document.documentElement).getPropertyValue('--warning').trim() || '#fbbf24',
                        getComputedStyle(document.documentElement).getPropertyValue('--info').trim() || '#60a5fa',
                        getComputedStyle(document.documentElement).getPropertyValue('--primary').trim() || '#D4AF37',
                        getComputedStyle(document.documentElement).getPropertyValue('--success').trim() || '#4ade80',
                        getComputedStyle(document.documentElement).getPropertyValue('--danger').trim() || '#f43f5e'
                    ] : ['rgba(255,255,255,0.05)'],
                    borderWidth: 0,
                    hoverOffset: 15
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {

                    legend: {
                        position: 'bottom',
                        labels: { 
                            color: getComputedStyle(document.documentElement).getPropertyValue('--text-dim').trim() || '#6b6b6b', 
                            usePointStyle: true, 
                            padding: 20,
                            font: { family: 'Outfit', size: 11 }
                        }
                    }
                }
            }
        });

    });
</script>
@endpush

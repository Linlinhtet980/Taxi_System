<link rel="stylesheet" href="{{ asset('css/driverview/earnings.css') }}">
@extends('layout.driver')

@section('content')
<div class="animate-fade">
    <div class="page-header style-d3297f">
        <div>
            <h1 class="page-title">Earnings Analytics</h1>
            <p class="page-subtitle">Track your performance and financial growth.</p>
        </div>
    </div>

    <!-- Overview Stats -->
    <div class="style-d62148">
        <div class="glass style-a9a25b">
            <p class="style-af391f">Monthly Net</p>
            <h2 class="style-3c0b40">{{ number_format($currentMonthEarnings) }} <span class="style-fb2a71">MMK</span></h2>
        </div>
        <div class="glass style-45ad02">
            <p class="style-af391f">Comm. Paid</p>
            <h2 class="style-ab71b2">{{ number_format($currentMonthCommission) }} <span class="style-fb2a71">MMK</span></h2>
        </div>
    </div>

    <!-- Main Chart Section -->
    <div class="glass style-57c15d">
        <div class="style-995390">
            <h3 class="style-c63345">Earnings Trend (30D)</h3>
            <div class="style-0ed0e9">
                <span class="style-fbd666"><i class="fa-solid fa-circle style-d268a8"></i> Daily Net</span>
            </div>
        </div>
        <div class="style-c40e32">
            <canvas id="detailedEarningsChart"></canvas>
        </div>
    </div>

    <!-- Stats Breakdown -->
    <div class="style-c3e0b7">
        <h3 class="style-4f6f68">Performance Summary</h3>
        
        <div class="glass style-c8a5af">
            <div class="style-99129a">
                <div class="style-8fd29b">
                    <i class="fa-solid fa-bolt style-a5e3c2"></i>
                </div>
                <div>
                    <p class="style-2a880b">Average Daily</p>
                    <p class="style-831dee">Based on last 30 days</p>
                </div>
            </div>
            <p class="style-d41bb0">{{ number_format($currentMonthEarnings / 30) }} MMK</p>
        </div>

        <div class="glass style-c8a5af">
            <div class="style-99129a">
                <div class="style-4d1a80">
                    <i class="fa-solid fa-car style-8ffdae"></i>
                </div>
                <div>
                    <p class="style-2a880b">Trip Efficiency</p>
                    <p class="style-831dee">Net per trip average</p>
                </div>
            </div>
            @php $avgPerTrip = $driver->bookings_count > 0 ? $currentMonthEarnings / $driver->bookings_count : 0; @endphp
            <p class="style-d41bb0">{{ number_format($avgPerTrip) }} MMK</p>
        </div>
    </div>
</div>

@push('js')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('detailedEarningsChart').getContext('2d');
        
        const labels = {!! json_encode($dailyData->pluck('date')) !!};
        const data = {!! json_encode($dailyData->pluck('total')) !!};

        const gradient = ctx.createLinearGradient(0, 0, 0, 250);
        gradient.addColorStop(0, 'rgba(212, 175, 55, 0.4)');
        gradient.addColorStop(1, 'rgba(212, 175, 55, 0)');

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Daily Net',
                    data: data,
                    borderColor: '#D4AF37',
                    borderWidth: 3,
                    fill: true,
                    backgroundColor: gradient,
                    tension: 0.4,
                    pointBackgroundColor: '#D4AF37',
                    pointRadius: 0,
                    pointHoverRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    x: {
                        grid: { display: false },
                        ticks: { color: 'rgba(255,255,255,0.4)', font: { size: 10 } }
                    },
                    y: {
                        grid: { color: 'rgba(255,255,255,0.05)' },
                        ticks: { 
                            color: 'rgba(255,255,255,0.4)', 
                            font: { size: 10 },
                            callback: function(value) { return value.toLocaleString(); }
                        }
                    }
                }
            }
        });
    });
</script>
@endpush
@endsection

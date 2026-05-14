<link rel="stylesheet" href="{{ asset('css/dashboardview/driver/index.css') }}">
@extends('layout.admin')

@push('css')
    <link rel="stylesheet" href="{{ asset('css/dashboardview/driver/index.css') }}">
@endpush

@section('content')
<div class="animate-fade">
    <!-- Header Section -->
    <div class="page-header">
        <div>
            <h2 class="page-title">Drivers Oversight</h2>
            <p class="page-subtitle">Ongoing status and management of the taxi fleet.</p>
        </div>
        <a href="{{ route('drivers.create') }}" class="btn-primary no-decoration">
            <i class="fa-solid fa-plus"></i> Register New Driver
        </a>
    </div>

    <!-- Stats summary -->
    <div class="stats-grid driver-stats-grid">
        <x-stat-card label="Total Drivers" :value="$drivers->total()" icon="fa-solid fa-car-side" color="primary" />
        <x-stat-card label="Active Service" :value="$totalActive" icon="fa-solid fa-circle-check" color="success" />
        <x-stat-card label="Pending" :value="$totalPending" icon="fa-solid fa-clock-rotate-left" color="warning" />
        @php
            $totalDue = \App\Models\Auth\Driver::query()->where('wallet_balance', '<', 0)->sum('wallet_balance');
        @endphp
        <x-stat-card label="Total Comm. Due" :value="number_format(abs($totalDue)) . ' MMK'" icon="fa-solid fa-hand-holding-dollar" color="danger" />
    </div>

    <h3 class="section-title">
        <i class="fa-solid fa-list-ul"></i> Ongoing Status
    </h3>

    <!-- Filter Bar -->
    <div class="filter-controls animate-fade">
        <select id="statusFilter" class="filter-select">
            <option value="all">All Status</option>
            <option value="active">Active</option>
            <option value="inactive">Inactive</option>
            <option value="pending">Pending</option>
        </select>
        <button id="resetFilters" class="btn-reset">
            <i class="fa-solid fa-rotate-left"></i> Reset
        </button>
    </div>

    <div class="glass table-container">
        <table class="premium-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Driver Profile</th>
                    <th>Vehicle Details</th>
                    <th>License No</th>
                    <th>Status</th>
                    <th>Wallet Balance</th>
                    <th>Join Date</th>
                    <th class="text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($drivers as $driver)
                <tr class="table-row row-hover">
                    <td>
                        <span class="style-5af919">
                            DRV-{{ str_pad($driver->id, 4, '0', STR_PAD_LEFT) }}
                        </span>
                    </td>

                    <td>
                        <div class="driver-profile-cell">
                            @if($driver->profile_picture)
                                <img src="{{ asset($driver->profile_picture) }}" class="avatar-img">
                            @else
                                <div class="avatar-placeholder">
                                    {{ substr($driver->full_name, 0, 1) }}
                                </div>
                            @endif
                            <div>
                                <p class="passenger-name">{{ $driver->full_name }}</p>
                                <p class="passenger-phone">{{ $driver->phone_no }}</p>
                            </div>
                        </div>
                    </td>
                    <td>
                        <p class="vehicle-plate">{{ $driver->vehicle->license_plate ?? 'N/A' }}</p>
                        <p class="passenger-phone">{{ $driver->vehicle->vehicle_type ?? 'Generic' }}</p>
                    </td>
                    <td>
                        <span class="license-text">{{ $driver->license_no }}</span>
                    </td>
                    <td>
                        @if($driver->driver_status == 'active')
                            <div class="style-bd9db1">
                                <div class="style-755eff"></div>
                                <span class="status-badge style-355479">Online</span>
                            </div>
                        @else
                            <div class="style-bd9db1">
                                <div class="style-806bc5"></div>
                                <span class="status-badge style-44cf61">Offline</span>
                            </div>
                        @endif
                    </td>
                    <td>
                        @if($driver->wallet_balance < 0)
                            <div class="style-a1c609">
                                <span class="style-a77d57">Owes Comm.</span>
                                -{{ number_format(abs($driver->wallet_balance)) }} MMK
                            </div>
                        @else
                            <div class="style-299f11">
                                <span class="style-a77d57">Available</span>
                                {{ number_format($driver->wallet_balance) }} MMK
                            </div>
                        @endif
                    </td>

                    <td class="date-text">
                        {{ $driver->created_at->format('M d, Y') }}
                    </td>
                    <td>
                        <div class="action-btn-group">
                            <a href="{{ route('drivers.show', $driver) }}" class="btn-action-show">
                                <i class="fa-solid fa-id-badge"></i>
                            </a>
                            <a href="{{ route('drivers.edit', $driver) }}" class="btn-action-edit">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </a>
                            <form action="{{ route('drivers.destroy', $driver) }}" method="POST" onsubmit="return confirm('Archive driver records?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-action-delete">
                                    <i class="fa-solid fa-trash-can"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="empty-table-cell">
                        No driver data available.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if($drivers->hasPages())
    <div class="small-pagination">
        {{ $drivers->links() }}
    </div>
    @endif
</div>

@endsection

@push('js')
    <script src="{{ asset('js/dashboardview/drivers/search.js') }}"></script>
@endpush

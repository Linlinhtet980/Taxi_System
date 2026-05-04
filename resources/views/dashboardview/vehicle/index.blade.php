@extends('layout.admin')

@push('css')
    <link rel="stylesheet" href="{{ asset('css/dashboardview/vehicles/index.css') }}">
@endpush

@section('content')
<div class="animate-fade">
    <!-- Header Section -->
    <div class="page-header">
        <div>
            <h2 class="page-title">Fleet Oversight</h2>
            <p class="page-subtitle">Manage all taxi vehicles, status, and details in your fleet.</p>
        </div>
        <a href="{{ route('vehicles.create') }}" class="btn-primary no-decoration">
            <i class="fa-solid fa-plus"></i> Register New Vehicle
        </a>
    </div>

    <!-- Stats summary -->
    <div class="stats-grid">
        <div class="stat-card glass">
            <h3 class="stat-label"><i class="fa-solid fa-car-side purple-icon"></i> Total Vehicles</h3>
            <p class="stat-value">{{ $totalVehicles }}</p>
        </div>
        <div class="stat-card glass">
            <h3 class="stat-label"><i class="fa-solid fa-circle-check green-icon"></i> Available</h3>
            <p class="stat-value">{{ $totalAvailable }}</p>
        </div>
        <div class="stat-card glass">
            <h3 class="stat-label"><i class="fa-solid fa-screwdriver-wrench orange-icon"></i> Maintenance</h3>
            <p class="stat-value">{{ $totalMaintenance }}</p>
        </div>
    </div>

    <h3 class="section-title">
        <i class="fa-solid fa-list-ul purple-icon"></i> Fleet Status
    </h3>

    <!-- Filter Bar -->
    <div class="filter-controls animate-fade">
        <select id="statusFilter" class="filter-select">
            <option value="all">All Status</option>
            <option value="available">Available</option>
            <option value="on ride">On Ride</option>
            <option value="maintenance">Maintenance</option>
        </select>
        <button id="resetFilters" class="btn-reset">
            <i class="fa-solid fa-rotate-left"></i> Reset
        </button>
    </div>

    <div class="glass table-container">
        <table class="premium-table">
            <thead>
                <tr>
                    <th>License Plate</th>
                    <th>Make & Model</th>
                    <th>Type & Color</th>
                    <th>Status</th>
                    <th>Registered Year</th>
                    <th class="text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($vehicles as $vehicle)
                <tr class="table-row row-hover">
                    <td>
                        <span class="plate-badge">
                            {{ $vehicle->license_plate }}
                        </span>
                    </td>
                    <td>
                        <div class="driver-profile-cell">
                            @if($vehicle->vehicle_photo)
                                <img src="{{ asset('storage/' . $vehicle->vehicle_photo) }}" alt="Vehicle" class="vehicle-img-thumb">
                            @else
                                <div class="vehicle-icon-placeholder">
                                    <i class="fa-solid fa-car sub-text"></i>
                                </div>
                            @endif
                            <div>
                                <p class="passenger-name">{{ $vehicle->brand }}</p>
                                <p class="passenger-phone">{{ $vehicle->model }}</p>
                            </div>
                        </div>
                    </td>
                    <td>
                        <p class="vehicle-plate">{{ $vehicle->vehicle_type }}</p>
                        <p class="passenger-phone">{{ $vehicle->color ?? 'Unspecified' }}</p>
                    </td>
                    <td>
                        <span class="status-badge status-{{ str_replace(' ', '.', $vehicle->status) }}">
                            {{ $vehicle->status }}
                        </span>
                    </td>
                    <td class="date-text">
                        {{ $vehicle->year ?? 'N/A' }}
                    </td>
                    <td>
                        <div class="action-btn-group">
                            <a href="{{ route('vehicles.show', $vehicle) }}" class="btn-action-view" title="View Profile">
                                <i class="fa-solid fa-eye"></i>
                            </a>
                            <a href="{{ route('vehicles.edit', $vehicle) }}" class="btn-action-edit" title="Edit Vehicle">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </a>
                            <form action="{{ route('vehicles.destroy', $vehicle) }}" method="POST" onsubmit="return confirm('Remove vehicle {{ $vehicle->license_plate }} from fleet?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-action-delete" title="Remove">
                                    <i class="fa-solid fa-trash-can"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="empty-table-cell">
                        No vehicle data available in fleet.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if($vehicles->hasPages())
    <div class="small-pagination">
        {{ $vehicles->links() }}
    </div>
    @endif
</div>

@endsection

@push('js')
    <script src="{{ asset('js/dashboardview/vehicles/search.js') }}"></script>
@endpush

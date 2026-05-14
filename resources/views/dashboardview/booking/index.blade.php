@extends('layout.admin')

@push('css')
    <link rel="stylesheet" href="{{ asset('css/dashboardview/booking/index.css') }}">
@endpush

@section('content')
<div class="animate-fade">
    <!-- Header Section -->
    <div class="page-header">
        <div>
            <h2 class="page-title">Trip Bookings</h2>
            <p class="page-subtitle">Track and manage all ride requests and active trips.</p>
        </div>
        <a href="{{ route('bookings.create') }}" class="btn-primary no-decoration">
            <i class="fa-solid fa-plus"></i> Dispatch New Ride
        </a>
    </div>

    <!-- Stats summary -->
    <div class="stats-grid">
        <div class="stat-card glass">
            <h3 class="stat-label"><i class="fa-solid fa-receipt purple-icon"></i> Total Bookings</h3>
            <p class="stat-value">{{ $totalBookings }}</p>
        </div>
        <div class="stat-card glass">
            <h3 class="stat-label"><i class="fa-solid fa-route green-icon"></i> Ongoing Trips</h3>
            <p class="stat-value">{{ $ongoingTrips }}</p>
        </div>
    </div>

    <h3 class="section-title">
        <i class="fa-solid fa-clock-rotate-left purple-icon"></i> Recent Activity
    </h3>

    <!-- Filter Bar -->
    <div class="filter-controls animate-fade">
        <select id="statusFilter" class="filter-select">
            <option value="all">All Status</option>
            <option value="pending">Pending</option>
            <option value="confirmed">Confirmed</option>
            <option value="ongoing">Ongoing</option>
            <option value="completed">Completed</option>
            <option value="cancelled">Cancelled</option>
        </select>
        <button id="resetFilters" class="btn-reset">
            <i class="fa-solid fa-rotate-left"></i> Reset
        </button>
    </div>

    <div class="glass table-container">
        <table class="premium-table">
            <thead>
                <tr>
                    <th>Trip ID</th>
                    <th>Passenger</th>
                    <th>Vehicle/Driver</th>
                    <th>Route</th>
                    <th>Fare</th>
                    <th>Status</th>
                    <th class="text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($bookings as $booking)
                <tr class="table-row row-hover">
                    <td>
                        <span class="trip-id">
                            #TRP-{{ str_pad($booking->id, 5, '0', STR_PAD_LEFT) }}
                        </span>
                        <p class="sub-text">{{ $booking->created_at->format('d M, H:i') }}</p>
                    </td>
                    <td>
                        <p class="passenger-name">{{ $booking->customer->name }}</p>
                        <p class="passenger-phone">{{ $booking->customer->phone }}</p>
                    </td>
                    <td>
                        @if($booking->vehicle)
                            <p class="vehicle-plate">{{ $booking->vehicle->license_plate }}</p>
                            <p class="passenger-phone">{{ $booking->vehicle->driver->full_name ?? 'No Driver' }}</p>
                        @else
                            <span class="unassigned-text">Unassigned</span>
                        @endif
                    </td>
                    <td>
                        <div class="route-info">
                            <p class="route-text"><i class="fa-solid fa-location-dot route-icon green-icon"></i> {{ $booking->pickup_location }}</p>
                            <p class="route-text"><i class="fa-solid fa-flag-checkered route-icon red-icon"></i> {{ $booking->dropoff_location }}</p>
                        </div>
                    </td>
                    <td>
                        <p class="fare-value">{{ number_format($booking->fare) }} <span class="fare-currency">MMK</span></p>
                    </td>
                    <td>
                        <span class="status-badge status-{{ $booking->status }}">
                            {{ ucfirst($booking->status) }}
                        </span>
                    </td>
                    <td>
                        <div class="action-btn-group flex-end-gap">
                            <!-- Status Transitions -->
                            @if($booking->status == 'pending')
                                <form action="{{ route('bookings.status.update', $booking) }}" method="POST">
                                    @csrf @method('PATCH')
                                    <input type="hidden" name="status" value="confirmed">
                                    <button type="submit" class="btn-status-update btn-confirm">
                                        <i class="fa-solid fa-check"></i> Confirm
                                    </button>
                                </form>
                            @elseif($booking->status == 'confirmed')
                                <form action="{{ route('bookings.status.update', $booking) }}" method="POST">
                                    @csrf @method('PATCH')
                                    <input type="hidden" name="status" value="ongoing">
                                    <button type="submit" class="btn-status-update btn-start">
                                        <i class="fa-solid fa-play"></i> Start
                                    </button>
                                </form>
                            @elseif($booking->status == 'ongoing')
                                <form action="{{ route('bookings.status.update', $booking) }}" method="POST">
                                    @csrf @method('PATCH')
                                    <input type="hidden" name="status" value="completed">
                                    <button type="submit" class="btn-status-update btn-complete">
                                        <i class="fa-solid fa-flag-checkered"></i> Finish
                                    </button>
                                </form>
                            @endif

                            @if(in_array($booking->status, ['pending', 'confirmed']))
                                <form action="{{ route('bookings.status.update', $booking) }}" method="POST">
                                    @csrf @method('PATCH')
                                    <input type="hidden" name="status" value="cancelled">
                                    <button type="submit" class="btn-status-update btn-cancel">
                                        <i class="fa-solid fa-xmark"></i> Cancel
                                    </button>
                                </form>
                            @endif

                            <div class="vertical-divider"></div>

                            <a href="{{ route('bookings.edit', $booking) }}" class="btn-action-edit">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </a>
                            <form action="{{ route('bookings.destroy', $booking) }}" method="POST" onsubmit="return confirm('Cancel/Delete this booking record?')">
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
                    <td colspan="7" class="empty-table-cell">
                        No bookings found. Click "Dispatch New Ride" to start.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if($bookings->hasPages())
    <div class="small-pagination">
        {{ $bookings->links() }}
    </div>
    @endif
</div>
@endsection

@push('js')
    <script src="{{ asset('js/dashboardview/bookings/search.js') }}"></script>
@endpush


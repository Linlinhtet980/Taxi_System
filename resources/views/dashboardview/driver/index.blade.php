@extends('layout.admin')

@push('css')
    <link rel="stylesheet" href="{{ asset('css/drivers/index.css') }}">
@endpush

@section('content')
<div class="animate-fade">
    <!-- Header Section -->
    <div class="page-header">
        <div>
            <h2 class="page-title">Drivers Oversight</h2>
            <p class="page-subtitle">Ongoing status and management of the taxi fleet.</p>
        </div>
        <a href="{{ route('drivers.create') }}" class="btn-primary" style="text-decoration: none;">
            <i class="fa-solid fa-plus"></i> Register New Driver
        </a>
    </div>

    <!-- Stats summary -->
    <div class="stats-grid">
        <div class="stat-card glass">
            <h3 class="stat-label"><i class="fa-solid fa-car-side" style="color: var(--accent-purple); margin-right: 0.5rem;"></i> Total Drivers</h3>
            <p class="stat-value">{{ $drivers->total() }}</p>
        </div>
        <div class="stat-card glass">
            <h3 class="stat-label"><i class="fa-solid fa-circle-check" style="color: #22c55e; margin-right: 0.5rem;"></i> Active Service</h3>
            <p class="stat-value">{{ $totalActive }}</p>
        </div>
        <div class="stat-card glass">
            <h3 class="stat-label"><i class="fa-solid fa-clock-rotate-left" style="color: var(--accent-yellow); margin-right: 0.5rem;"></i> Pending</h3>
            <p class="stat-value">{{ $totalPending }}</p>
        </div>
    </div>

    <h3 class="section-title">
        <i class="fa-solid fa-list-ul" style="color: var(--accent-purple);"></i> Ongoing Status
    </h3>

    <div class="glass table-container">
        <table class="premium-table">
            <thead>
                <tr>
                    <th>Driver Profile</th>
                    <th>Vehicle Details</th>
                    <th>License No</th>
                    <th>Status</th>
                    <th>Join Date</th>
                    <th style="text-align: right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($drivers as $driver)
                <tr class="table-row row-hover">
                    <td>
                        <div class="driver-profile-cell">
                            @if($driver->profile_picture)
                                <img src="{{ asset('storage/' . $driver->profile_picture) }}" class="avatar-img">
                            @else
                                <div class="avatar-placeholder">
                                    {{ substr($driver->full_name, 0, 1) }}
                                </div>
                            @endif
                            <div>
                                <p style="font-weight: 600; font-size: 0.95rem;">{{ $driver->full_name }}</p>
                                <p style="font-size: 0.75rem; color: var(--text-dim);">{{ $driver->phone_no }}</p>
                            </div>
                        </div>
                    </td>
                    <td>
                        <p style="font-weight: 500; font-size: 0.9rem;">{{ $driver->vehicle_no ?? 'N/A' }}</p>
                        <p style="font-size: 0.75rem; color: var(--text-dim);">{{ $driver->vehicle_type ?? 'Generic' }}</p>
                    </td>
                    <td>
                        <span style="font-family: monospace; color: var(--accent-yellow);">{{ $driver->license_no }}</span>
                    </td>
                    <td>
                        <span class="status-badge status-{{ $driver->driver_status }}">
                            {{ $driver->driver_status }}
                        </span>
                    </td>
                    <td style="color: var(--text-dim); font-size: 0.85rem;">
                        {{ $driver->created_at->format('M d, Y') }}
                    </td>
                    <td>
                        <div class="action-btn-group">
                            <a href="{{ route('drivers.edit', $driver) }}" class="btn-action-edit" title="Edit Profile">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </a>
                            <form action="{{ route('drivers.destroy', $driver) }}" method="POST" onsubmit="return confirm('Archive driver records?')">
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
                    <td colspan="6" style="padding: 4rem; text-align: center; color: var(--text-dim);">
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

<script>
    // Real-time Search Logic
    const searchInput = document.getElementById('global-search');
    if (searchInput) {
        searchInput.addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase();
            document.querySelectorAll('.table-row').forEach(row => {
                row.style.display = row.textContent.toLowerCase().includes(searchTerm) ? '' : 'none';
            });
        });
    }
</script>
@endsection
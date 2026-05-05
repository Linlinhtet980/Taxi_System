@extends('layout.admin')

@push('css')
    <link rel="stylesheet" href="{{ asset('css/dashboardview/customers/index.css') }}">
@endpush

@section('content')
<div class="animate-fade">
    <!-- Header Section -->
    <div class="page-header">
        <div>
            <h2 class="page-title">Passenger Management</h2>
            <p class="page-subtitle">Manage registered customers and their account status.</p>
        </div>
        <a href="{{ route('customers.create') }}" class="btn-primary no-decoration">
            <i class="fa-solid fa-user-plus"></i> Add New Passenger
        </a>
    </div>

    <!-- Stats summary -->
    <div class="stats-grid">
        <div class="stat-card glass">
            <h3 class="stat-label"><i class="fa-solid fa-users purple-icon"></i> Total Passengers</h3>
            <p class="stat-value">{{ $totalCustomers }}</p>
        </div>
        <div class="stat-card glass">
            <h3 class="stat-label"><i class="fa-solid fa-user-check green-icon"></i> Active Accounts</h3>
            <p class="stat-value">{{ $activeCustomers }}</p>
        </div>
    </div>

    <h3 class="section-title">
        <i class="fa-solid fa-address-book purple-icon"></i> Customer Directory
    </h3>

    <!-- Filter Bar -->
    <div class="filter-controls animate-fade">
        <select id="statusFilter" class="filter-select">
            <option value="all">All Status</option>
            <option value="active">Active</option>
            <option value="blocked">Blocked</option>
        </select>
        <button id="resetFilters" class="btn-reset">
            <i class="fa-solid fa-rotate-left"></i> Reset
        </button>
    </div>

    <div class="glass table-container">
        <table class="premium-table">
            <thead>
                <tr>
                    <th>Passenger</th>
                    <th>Contact Info</th>
                    <th>Address</th>
                    <th>Status</th>
                    <th class="text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($customers as $customer)
                <tr class="table-row row-hover">
                    <td>
                        <div class="driver-profile-cell">
                            @if($customer->profile_picture)
                                <img src="{{ Str::startsWith($customer->profile_picture, 'uploads/') ? asset($customer->profile_picture) : asset('storage/' . $customer->profile_picture) }}" alt="Avatar" class="avatar-img">
                            @else
                                <div class="avatar-placeholder">
                                    {{ substr($customer->name, 0, 1) }}
                                </div>
                            @endif
                            <div>
                                <p class="passenger-name">{{ $customer->name }}</p>
                                <p class="passenger-phone">ID: #CST-{{ str_pad($customer->id, 4, '0', STR_PAD_LEFT) }}</p>
                            </div>
                        </div>
                    </td>
                    <td>
                        <p class="passenger-name"><i class="fa-solid fa-phone contact-icon"></i> {{ $customer->phone }}</p>
                        <p class="passenger-phone"><i class="fa-solid fa-envelope contact-icon"></i> {{ $customer->email ?? 'N/A' }}</p>
                    </td>
                    <td class="address-cell">
                        <p class="date-text truncate-text" title="{{ $customer->address }}">
                            {{ $customer->address ?? 'No address provided' }}
                        </p>
                    </td>
                    <td>
                        <span class="status-badge status-{{ $customer->status }}">
                            {{ ucfirst($customer->status) }}
                        </span>
                    </td>
                    <td>
                        <div class="action-btn-group">
                            <a href="{{ route('customers.edit', $customer) }}" class="btn-action-edit" title="Edit Customer">
                                <i class="fa-solid fa-user-pen"></i>
                            </a>
                            <form action="{{ route('customers.destroy', $customer) }}" method="POST" onsubmit="return confirm('Permanently remove this customer?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-action-delete" title="Delete">
                                    <i class="fa-solid fa-trash-can"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="empty-table-cell">
                        No passengers registered yet.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if($customers->hasPages())
    <div class="small-pagination">
        {{ $customers->links() }}
    </div>
    @endif
</div>
@endsection

@push('js')
    <script src="{{ asset('js/dashboardview/customers/search.js') }}"></script>
@endpush

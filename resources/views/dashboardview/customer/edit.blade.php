<link rel="stylesheet" href="{{ asset('css/dashboardview/customer/edit.css') }}">
@extends('layout.admin')

@push('css')
    <link rel="stylesheet" href="{{ asset('css/dashboardview/customer/form.css') }}">
@endpush

@section('content')
<div class="animate-fade">
    <div class="page-header">
        <div>
            <a href="{{ route('customers.index') }}" onmouseover="this.style.opacity='1'" onmouseout="this.style.opacity='0.9'" class="style-1cb386">
                <div class="style-ea0079">
                    <i class="fa-solid fa-arrow-left"></i>
                </div>
                PASSENGER LIST
            </a>
            <h2 class="page-title style-27ac6d">Manage Account: {{ $customer->name }}</h2>
            <p class="page-subtitle">Review passenger profile and manage account access.</p>
        </div>
    </div>

    <div class="glass style-951c26">
        <div class="style-e4862d">
            <!-- Profile Info Card (Read Only) -->
            <div class="style-88a1f9">
                <div class="style-b7ae15">
                    @if($customer->profile_picture)
                        <img src="{{ Str::startsWith($customer->profile_picture, 'uploads/') ? asset($customer->profile_picture) : asset('storage/' . $customer->profile_picture) }}" alt="Profile"  class="style-70a872">
                    @else
                        <div class="style-aeaced">
                            {{ substr($customer->name, 0, 1) }}
                        </div>
                    @endif
                    <h3 class="style-18f431">{{ $customer->name }}</h3>
                    <p class="style-34a298">PASSENGER ACCOUNT</p>
                </div>

                <div class="style-f48bc2">
                    <div class="style-f5c1ba">
                        <p class="style-294785">Primary Phone</p>
                        <p class="style-465024">{{ $customer->phone }}</p>
                    </div>
                    <div class="style-f5c1ba">
                        <p class="style-294785">Email Address</p>
                        <p class="style-3a4f71">{{ $customer->email ?? 'Not Linked' }}</p>
                    </div>
                    <div class="style-f5c1ba">
                        <p class="style-294785">Registration Date</p>
                        <p class="style-3a4f71">{{ $customer->created_at->format('M d, Y') }}</p>
                    </div>
                </div>
            </div>

            <!-- Management Actions (Editable) -->
            <div class="style-c64088">
                <h4 class="style-b8b25c">Account Management</h4>
                
                <form action="{{ route('customers.update', $customer) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="form-group style-257995">
                        <label class="style-f73efd">Update Passenger Status</label>
                        
                        <div class="style-7ad851">
                            <label  style="display: flex; align-items: center; gap: 1rem; padding: 1.25rem; background: {{ $customer->status == 'active' ? 'rgba(74, 222, 128, 0.1)' : 'rgba(255,255,255,0.03)' }}; border: 1px solid {{ $customer->status == 'active' ? '#4ade80' : 'var(--glass-border)' }}; border-radius: 16px; cursor: pointer; transition: 0.3s;">
                                <input type="radio" name="status" value="active" {{ $customer->status == 'active' ? 'checked' : '' }}  class="style-a71b21">
                                <div>
                                    <p class="style-1f7302">Active Account</p>
                                    <p class="style-831dee">Full access to booking services.</p>
                                </div>
                            </label>

                            <label  style="display: flex; align-items: center; gap: 1rem; padding: 1.25rem; background: {{ $customer->status == 'inactive' ? 'rgba(244, 63, 94, 0.1)' : 'rgba(255,255,255,0.03)' }}; border: 1px solid {{ $customer->status == 'inactive' ? '#f43f5e' : 'var(--glass-border)' }}; border-radius: 16px; cursor: pointer; transition: 0.3s;">
                                <input type="radio" name="status" value="inactive" {{ $customer->status == 'inactive' ? 'checked' : '' }}  class="style-08cbd0">
                                <div>
                                    <p class="style-9ac58e">Suspended / Blocked</p>
                                    <p class="style-831dee">Restrict user from booking rides.</p>
                                </div>
                            </label>
                        </div>
                    </div>

                    <button type="submit" class="btn-primary style-857deb">
                        <i class="fa-solid fa-save"></i> SAVE ACCOUNT CHANGES
                    </button>
                </form>

                <div class="style-6539ad">
                    <p class="style-48134b">
                        <i class="fa-solid fa-info-circle style-7cbe9f"></i>
                        Account settings modified here will take effect immediately. For security reasons, personal data updates (like password) must be initiated by the customer through the mobile application.
                    </p>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection


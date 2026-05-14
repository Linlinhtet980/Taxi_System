<link rel="stylesheet" href="{{ asset('css/dashboardview/customer/create.css') }}">
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
            <h2 class="page-title style-27ac6d">New Customer Registration</h2>
            <p class="page-subtitle">Add a new customer account to the system.</p>
        </div>
    </div>

    <form action="{{ route('customers.store') }}" method="POST" enctype="multipart/form-data" class="animate-fade">
        @csrf
        <div class="glass style-1efce1">
            <div class="style-257995">
                <h3 class="style-4ac43c">
                    <i class="fa-solid fa-user-plus"></i> Passenger Registration
                </h3>
            </div>

            <div class="style-c45d59">
                <div class="form-group">
                    <label class="style-367f5d">Full Name *</label>
                    <input type="text" name="name" class="glass style-ad5d06" placeholder="John Doe" required>
                </div>
                <div class="form-group">
                    <label class="style-367f5d">Phone Number *</label>
                    <input type="text" name="phone" class="glass style-ad5d06" placeholder="09..." required>
                </div>
                <div class="form-group">
                    <label class="style-367f5d">Email Address</label>
                    <input type="email" name="email" class="glass style-ad5d06" placeholder="john@example.com">
                </div>
                <div class="form-group">
                    <label class="style-367f5d">Initial Status</label>
                    <select name="status" class="glass style-ad5d06">
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>
            </div>

            <div class="form-group style-185d79">
                <label class="style-367f5d">Residential Address</label>
                <textarea name="address" class="glass style-ad5d06" rows="3" placeholder="Enter address..."></textarea>
            </div>

            <div class="form-group style-185d79">
                <label class="style-367f5d">Profile Picture</label>
                <input type="file" name="profile_picture" class="glass style-e19da8">
            </div>

            <div class="style-dd0058">
                <button type="button" onclick="history.back()" class="btn-secondary style-54f933">Cancel</button>
                <button type="submit" class="btn-primary style-853a9b">
                    <i class="fa-solid fa-save"></i> REGISTER NOW
                </button>
            </div>
        </div>
    </form>

    </form>
</div>
@endsection


@extends('layout.admin')

@push('css')
    <link rel="stylesheet" href="{{ asset('css/drivers/form.css') }}">
@endpush

@section('content')
<div class="animate-fade form-container">
    <div class="page-header">
        <div>
            <a href="{{ route('drivers.index') }}" style="color: var(--accent-purple); text-decoration: none; font-weight: 600; font-size: 0.9rem;">
                <i class="fa-solid fa-arrow-left" style="margin-right: 0.5rem;"></i> Back to Oversight
            </a>
            <h2 class="page-title" style="margin-top: 1rem;">Register New Driver</h2>
        </div>
        <div class="user-card">
            <p class="user-role">Step 1 of 1</p>
            <p class="user-name">Full Profile Creation</p>
        </div>
    </div>

    @if($errors->any())
        <div class="glass" style="padding: 1.5rem; border-left: 4px solid var(--accent-pink); margin-bottom: 2.5rem;">
            <p style="color: var(--accent-pink); font-weight: 700; margin-bottom: 0.5rem;"><i class="fa-solid fa-circle-exclamation"></i> Action Required:</p>
            <ul style="color: var(--text-dim); font-size: 0.9rem; list-style-position: inside;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('drivers.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        
        <div class="form-grid-3">
            
            <!-- Column 1: Identity -->
            <div class="glass form-section-card">
                <h3 class="form-section-title" style="color: var(--accent-purple);">
                    <i class="fa-solid fa-id-card-clip" style="margin-right: 0.75rem;"></i> 01. Identity
                </h3>
                
                <div class="form-group">
                    <label class="form-label" style="margin-bottom: 0.75rem; display: block;">Full Legal Name *</label>
                    <input type="text" name="full_name" value="{{ old('full_name') }}" class="input-glass" required placeholder="Enter full name">
                </div>
                
                <div class="form-group-row">
                    <div class="form-group">
                        <label class="form-label" style="margin-bottom: 0.75rem; display: block;">Gender</label>
                        <select name="gender" class="input-glass">
                            <option value="">Select</option>
                            <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
                            <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
                            <option value="other" {{ old('gender') == 'other' ? 'selected' : '' }}>Other</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label" style="margin-bottom: 0.75rem; display: block;">Date of Birth</label>
                        <input type="date" name="date_of_birth" value="{{ old('date_of_birth') }}" class="input-glass">
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label" style="margin-bottom: 0.75rem; display: block;">Email address</label>
                    <input type="email" name="email" value="{{ old('email') }}" class="input-glass" placeholder="email@example.com">
                </div>

                <div class="form-group">
                    <label class="form-label" style="margin-bottom: 0.75rem; display: block;">Identity Card No (NRIC)</label>
                    <input type="text" name="identity_card_no" value="{{ old('identity_card_no') }}" class="input-glass" placeholder="e.g. 12/YAKANA(N)123456">
                </div>
            </div>

            <!-- Column 2: Professional & Contact -->
            <div class="glass form-section-card">
                <h3 class="form-section-title" style="color: var(--accent-yellow);">
                    <i class="fa-solid fa-briefcase" style="margin-right: 0.75rem;"></i> 02. Professional
                </h3>

                <div class="form-group-row">
                    <div class="form-group">
                        <label class="form-label" style="margin-bottom: 0.75rem; display: block;">Primary Phone *</label>
                        <input type="text" name="phone_no" value="{{ old('phone_no') }}" class="input-glass" required placeholder="+95 9...">
                    </div>
                    <div class="form-group">
                        <label class="form-label" style="margin-bottom: 0.75rem; display: block;">Emergency Contact</label>
                        <input type="text" name="emergency_contact_no" value="{{ old('emergency_contact_no') }}" class="input-glass" placeholder="Kin contact">
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label" style="margin-bottom: 0.75rem; display: block;">Residential Address</label>
                    <input type="text" name="address" value="{{ old('address') }}" class="input-glass" placeholder="City, Township, Street">
                </div>

                <div class="form-group-row">
                    <div class="form-group">
                        <label class="form-label" style="margin-bottom: 0.75rem; display: block;">License No *</label>
                        <input type="text" name="license_no" value="{{ old('license_no') }}" class="input-glass" required placeholder="LN-XXXXXX">
                    </div>
                    <div class="form-group">
                        <label class="form-label" style="margin-bottom: 0.75rem; display: block;">Status</label>
                        <select name="driver_status" class="input-glass">
                            <option value="pending" {{ old('driver_status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="active" {{ old('driver_status') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ old('driver_status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label" style="margin-bottom: 0.75rem; display: block;">Vehicle Plate & Type</label>
                    <div style="display: flex; gap: 0.5rem;">
                        <input type="text" name="vehicle_no" value="{{ old('vehicle_no') }}" class="input-glass" placeholder="Plate" style="flex: 1;">
                        <input type="text" name="vehicle_type" value="{{ old('vehicle_type') }}" class="input-glass" placeholder="Type" style="flex: 1;">
                    </div>
                </div>
            </div>

            <!-- Column 3: Documents -->
            <div class="glass form-section-card">
                <h3 class="form-section-title" style="color: var(--accent-pink);">
                    <i class="fa-solid fa-file-shield" style="margin-right: 0.75rem;"></i> 03. Documents
                </h3>

                <div class="photo-preview-group">
                    <div class="form-group">
                        <label class="form-label" style="margin-bottom: 0.5rem; display: block;">Portrait Photo</label>
                        <div class="photo-upload-box glass" onclick="document.getElementById('profile_picture').click()">
                            <div id="preview-profile" class="photo-placeholder">
                                <i class="fa-solid fa-camera-retro" style="font-size: 1.25rem; margin-bottom: 0.25rem;"></i><br>
                                <span style="font-size: 0.65rem;">Portrait</span>
                            </div>
                            <img id="img-profile" class="photo-preview-img" style="display:none;">
                        </div>
                        <input type="file" name="profile_picture" id="profile_picture" style="display:none;" onchange="previewImage(this, 'profile')">
                    </div>

                    <div class="form-group-row">
                        <div class="form-group">
                            <label class="user-role" style="margin-bottom: 0.5rem; display: block;">License Scan</label>
                            <div class="photo-upload-box glass" onclick="document.getElementById('license_photo').click()">
                                <div id="preview-license" class="photo-placeholder">
                                    <i class="fa-solid fa-id-card" style="font-size: 1.25rem; margin-bottom: 0.25rem;"></i><br>
                                    <span style="font-size: 0.65rem;">License</span>
                                </div>
                                <img id="img-license" class="photo-preview-img" style="display:none;">
                            </div>
                            <input type="file" name="license_photo" id="license_photo" style="display:none;" onchange="previewImage(this, 'license')">
                        </div>

                        <div class="form-group">
                            <label class="user-role" style="margin-bottom: 0.5rem; display: block;">NRIC Scan</label>
                            <div class="photo-upload-box glass" onclick="document.getElementById('nric_photo').click()">
                                <div id="preview-nric" class="photo-placeholder">
                                    <i class="fa-solid fa-address-card" style="font-size: 1.25rem; margin-bottom: 0.25rem;"></i><br>
                                    <span style="font-size: 0.65rem;">NRIC</span>
                                </div>
                                <img id="img-nric" class="photo-preview-img" style="display:none;">
                            </div>
                            <input type="file" name="nric_photo" id="nric_photo" style="display:none;" onchange="previewImage(this, 'nric')">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div style="margin-top: 3rem; display: flex; justify-content: flex-end; gap: 1.5rem;">
            <button type="button" onclick="history.back()" class="btn-secondary">Discard Changes</button>
            <button type="submit" class="btn-primary" style="padding: 1rem 3rem;">Complete Registration</button>
        </div>
    </form>
</div>

<script>
    function previewImage(input, type) {
        const preview = document.getElementById('preview-' + type);
        const img = document.getElementById('img-' + type);
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                img.src = e.target.result;
                img.style.display = 'block';
                preview.style.display = 'none';
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endsection

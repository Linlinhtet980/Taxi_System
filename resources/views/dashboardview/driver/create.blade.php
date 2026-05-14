<link rel="stylesheet" href="{{ asset('css/dashboardview/driver/create.css') }}">
@extends('layout.admin')

@push('css')
    <link rel="stylesheet" href="{{ asset('css/dashboardview/driver/form.css') }}">
@endpush

@section('content')
<div class="animate-fade form-container">
    <div class="page-header">
        <div>
            <a href="{{ route('drivers.index') }}" onmouseover="this.style.opacity='1'" onmouseout="this.style.opacity='0.9'" class="style-1cb386">
                <div class="style-ea0079">
                    <i class="fa-solid fa-arrow-left"></i>
                </div>
                BACK TO DRIVERS LIST
            </a>
            <h2 class="page-title style-27ac6d">Register New Driver</h2>
        </div>
        <div class="user-card">
            <p class="user-role">Step 1 of 1</p>
            <p class="user-name">Full Profile Creation</p>
        </div>
    </div>

    @if($errors->any())
        <div class="glass style-35984a">
            <p class="style-40a99b"><i class="fa-solid fa-circle-exclamation"></i> Action Required:</p>
            <ul class="style-e6de14">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('drivers.store') }}" method="POST" enctype="multipart/form-data" class="animate-fade">
        @csrf
        <div class="style-95d58c">
            
            <!-- Column 1: Identity -->
            <div class="glass style-fe9f1d">
                <h3 class="style-3b1ce4">
                    <i class="fa-solid fa-id-card"></i> 01. Identity
                </h3>
                
                <div class="form-group style-d3297f">
                    <label class="style-367f5d">Full Legal Name *</label>
                    <input type="text" name="full_name" class="glass style-ad5d06" placeholder="Full Name" required>
                </div>
                
                <div class="style-61da27">
                    <div class="form-group">
                        <label class="style-367f5d">Gender</label>
                        <select name="gender" class="glass style-ad5d06">
                            <option value="male">Male</option>
                            <option value="female">Female</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="style-367f5d">DOB</label>
                        <input type="date" name="date_of_birth" class="glass style-ad5d06">
                    </div>
                </div>

                <div class="form-group style-d3297f">
                    <label class="style-367f5d">Identity Card No (NRIC)</label>
                    <input type="text" name="identity_card_no" class="glass style-ad5d06" placeholder="NRIC Number">
                </div>
            </div>

            <!-- Column 2: Professional -->
            <div class="glass style-fe9f1d">
                <h3 class="style-0eb9c5">
                    <i class="fa-solid fa-briefcase"></i> 02. Professional
                </h3>

                <div class="form-group style-d3297f">
                    <label class="style-367f5d">Primary Phone *</label>
                    <input type="text" name="phone_no" class="glass style-ad5d06" placeholder="+95 9..." required>
                </div>

                <div class="form-group style-d3297f">
                    <label class="style-367f5d">License No *</label>
                    <input type="text" name="license_no" class="glass style-ad5d06" placeholder="License Number" required>
                </div>

                <div class="form-group style-d3297f">
                    <label class="style-367f5d">Current Status</label>
                    <select name="driver_status" class="glass style-ad5d06">
                        <option value="pending">Pending Review</option>
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>

                <div class="form-group style-d3297f">
                    <label class="style-367f5d">Custom Commission Rate (%)</label>
                    <input type="number" name="commission_rate" min="0" max="100" class="glass style-ad5d06" placeholder="e.g. 10 (Leave blank for default)">
                </div>

                <div class="style-85f502">
                    <div class="form-group">
                        <label class="style-367f5d">Vehicle Assignment</label>
                        <input type="text" name="vehicle_no" class="glass style-ad5d06" placeholder="Vehicle Plate">
                    </div>
                    <div class="form-group">
                        <label class="style-367f5d">Vehicle Type</label>
                        <select name="vehicle_type" class="glass style-ad5d06">
                            <option value="">Select Type</option>
                            @php
                                $types = explode(',', \App\Models\Core\Setting::get('vehicle_types', 'Sedan,SUV,Minivan,Hatchback'));
                            @endphp
                            @foreach($types as $type)
                                @php $t = trim($type); @endphp
                                <option value="{{ $t }}">{{ $t }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <!-- Column 3: Documents -->
            <div class="glass style-fe9f1d">
                <h3 class="style-fee314">
                    <i class="fa-solid fa-file-shield"></i> 03. Verification
                </h3>

                <div class="style-268fb7">
                    <div class="form-group">
                        <label class="style-aab719">Profile Portrait</label>
                        <div class="photo-upload-box glass style-0aeda0" onclick="document.getElementById('profile_picture').click()">
                            <div id="preview-profile" class="style-cdd8ca">
                                <i class="fa-solid fa-camera style-76e68d"></i>
                            </div>
                            <img id="img-profile" class="style-ddb81d">
                        </div>
                        <input type="file" name="profile_picture" id="profile_picture" onchange="previewImage(this, 'profile')" class="style-93b8ea">
                    </div>

                    <div class="style-85f502">
                        <div class="form-group">
                            <label class="style-aab719">License Scan</label>
                            <div class="photo-upload-box glass style-8684cb" onclick="document.getElementById('license_photo').click()">
                                <div id="preview-license" class="style-cdd8ca">
                                    <i class="fa-solid fa-id-badge style-ed4950"></i>
                                </div>
                                <img id="img-license" class="style-008297">
                            </div>
                            <input type="file" name="license_photo" id="license_photo" onchange="previewImage(this, 'license')" class="style-93b8ea">
                        </div>
                        <div class="form-group">
                            <label class="style-aab719">NRIC Scan</label>
                            <div class="photo-upload-box glass style-8684cb" onclick="document.getElementById('nric_photo').click()">
                                <div id="preview-nric" class="style-cdd8ca">
                                    <i class="fa-solid fa-address-card style-ed4950"></i>
                                </div>
                                <img id="img-nric" class="style-008297">
                            </div>
                            <input type="file" name="nric_photo" id="nric_photo" onchange="previewImage(this, 'nric')" class="style-93b8ea">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="style-70b365">
            <button type="button" onclick="history.back()" class="btn-secondary style-54f933">Discard</button>
            <button type="submit" class="btn-primary style-853a9b">
                <i class="fa-solid fa-save"></i> REGISTER DRIVER
            </button>
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


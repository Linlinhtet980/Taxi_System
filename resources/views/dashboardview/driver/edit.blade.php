<link rel="stylesheet" href="{{ asset('css/dashboardview/driver/edit.css') }}">
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
            <h2 class="page-title style-27ac6d">Update Driver: {{ $driver->full_name }}</h2>
        </div>
        <div class="user-card">
            <p class="user-role">ID: {{ str_pad($driver->id, 5, '0', STR_PAD_LEFT) }}</p>
            <p class="user-name">Profile Management</p>
        </div>
    </div>

    @if($errors->any())
        <div class="glass style-35984a">
            <p class="style-40a99b"><i class="fa-solid fa-circle-exclamation"></i> Verification Errors:</p>
            <ul class="style-e6de14">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('drivers.update', $driver) }}" method="POST" enctype="multipart/form-data" class="animate-fade">
        @csrf
        @method('PUT')
        <div class="style-95d58c">
            
            <!-- Column 1: Identity -->
            <div class="glass style-fe9f1d">
                <h3 class="style-3b1ce4">
                    <i class="fa-solid fa-id-card"></i> 01. Identity
                </h3>
                
                <div class="form-group style-d3297f">
                    <label class="style-367f5d">Full Legal Name *</label>
                    <input type="text" name="full_name" class="glass style-ad5d06" value="{{ $driver->full_name }}" required>
                </div>
                
                <div class="style-61da27">
                    <div class="form-group">
                        <label class="style-367f5d">Gender</label>
                        <select name="gender" class="glass style-ad5d06">
                            <option value="male" {{ $driver->gender == 'male' ? 'selected' : '' }}>Male</option>
                            <option value="female" {{ $driver->gender == 'female' ? 'selected' : '' }}>Female</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="style-367f5d">DOB</label>
                        <input type="date" name="date_of_birth" class="glass style-ad5d06" value="{{ $driver->date_of_birth }}">
                    </div>
                </div>

                <div class="form-group style-d3297f">
                    <label class="style-367f5d">Identity Card No (NRIC)</label>
                    <input type="text" name="identity_card_no" class="glass style-ad5d06" value="{{ $driver->identity_card_no }}">
                </div>
            </div>

            <!-- Column 2: Professional -->
            <div class="glass style-fe9f1d">
                <h3 class="style-0eb9c5">
                    <i class="fa-solid fa-briefcase"></i> 02. Professional
                </h3>

                <div class="form-group style-d3297f">
                    <label class="style-367f5d">Primary Phone *</label>
                    <input type="text" name="phone_no" class="glass style-ad5d06" value="{{ $driver->phone_no }}" required>
                </div>

                <div class="form-group style-d3297f">
                    <label class="style-367f5d">License No *</label>
                    <input type="text" name="license_no" class="glass style-ad5d06" value="{{ $driver->license_no }}" required>
                </div>

                <div class="form-group style-d3297f">
                    <label class="style-367f5d">Current Status</label>
                    <select name="driver_status" class="glass style-ad5d06">
                        <option value="pending" {{ $driver->driver_status == 'pending' ? 'selected' : '' }}>Pending Review</option>
                        <option value="active" {{ $driver->driver_status == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ $driver->driver_status == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>

                <div class="form-group style-d3297f">
                    <label class="style-367f5d">Custom Commission Rate (%)</label>
                    <input type="number" name="commission_rate" min="0" max="100" class="glass style-ad5d06" value="{{ $driver->commission_rate }}" placeholder="e.g. 10 (Leave blank for default)">
                </div>

                <div class="style-85f502">
                    <div class="form-group">
                        <label class="style-367f5d">Vehicle Assignment</label>
                        <input type="text" name="vehicle_no" class="glass style-ad5d06" value="{{ $driver->vehicle->license_plate ?? '' }}" placeholder="Vehicle Plate">
                    </div>
                    <div class="form-group">
                        <label class="style-367f5d">Vehicle Type</label>
                        <select name="vehicle_type" class="glass style-ad5d06">
                            <option value="">Select Type</option>
                            @php
                                $types = explode(',', \App\Models\Core\Setting::get('vehicle_types', 'Sedan,SUV,Minivan,Hatchback'));
                                $currentType = $driver->vehicle->vehicle_type ?? '';
                            @endphp
                            @foreach($types as $type)
                                @php $t = trim($type); @endphp
                                <option value="{{ $t }}" {{ $currentType == $t ? 'selected' : '' }}>{{ $t }}</option>
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
                        <div class="photo-upload-box glass style-104050" onclick="document.getElementById('profile_picture').click()">
                            @if($driver->profile_picture)
                                <img src="{{ asset($driver->profile_picture) }}" id="img-profile"  class="style-7d1fae">
                            @else
                                <div id="preview-profile" class="style-cdd8ca">
                                    <i class="fa-solid fa-camera style-76e68d"></i>
                                </div>
                                <img id="img-profile" class="style-0f43f4">
                            @endif
                        </div>
                        <input type="file" name="profile_picture" id="profile_picture" onchange="previewImage(this, 'profile')" class="style-93b8ea">
                    </div>

                    <div class="style-85f502">
                        <div class="form-group">
                            <label class="style-aab719">License Scan</label>
                            <div class="photo-upload-box glass style-bdc9eb" onclick="document.getElementById('license_photo').click()">
                                @if($driver->license_photo)
                                    <img src="{{ asset($driver->license_photo) }}" id="img-license"  class="style-7d1fae">
                                @else
                                    <div id="preview-license" class="style-cdd8ca">
                                        <i class="fa-solid fa-id-badge style-ed4950"></i>
                                    </div>
                                    <img id="img-license" class="style-0f43f4">
                                @endif
                            </div>
                            <input type="file" name="license_photo" id="license_photo" onchange="previewImage(this, 'license')" class="style-93b8ea">
                        </div>
                        <div class="form-group">
                            <label class="style-aab719">NRIC Scan</label>
                            <div class="photo-upload-box glass style-bdc9eb" onclick="document.getElementById('nric_photo').click()">
                                @if($driver->nric_photo)
                                    <img src="{{ asset($driver->nric_photo) }}" id="img-nric"  class="style-7d1fae">
                                @else
                                    <div id="preview-nric" class="style-cdd8ca">
                                        <i class="fa-solid fa-address-card style-ed4950"></i>
                                    </div>
                                    <img id="img-nric" class="style-0f43f4">
                                @endif
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
                <i class="fa-solid fa-save"></i> UPDATE DRIVER PROFILE
            </button>
        </div>
    </form>

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
                if(preview) preview.style.display = 'none';
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endsection


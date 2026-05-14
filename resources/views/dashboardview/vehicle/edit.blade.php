<link rel="stylesheet" href="{{ asset('css/dashboardview/vehicle/edit.css') }}">
@extends('layout.admin')

@push('css')
    <link rel="stylesheet" href="{{ asset('css/dashboardview/vehicle/form.css') }}">
@endpush

@section('content')
<div class="animate-fade">
    <div class="page-header">
        <div>
            <a href="{{ route('vehicles.index') }}" onmouseover="this.style.opacity='1'" onmouseout="this.style.opacity='0.9'" class="style-1cb386">
                <div class="style-ea0079">
                    <i class="fa-solid fa-arrow-left"></i>
                </div>
                FLEET LIST
            </a>
            <h2 class="page-title style-27ac6d">Update Vehicle: {{ $vehicle->license_plate }}</h2>
        </div>
    </div>

    @if ($errors->any())
        <div class="glass style-2b5e13">
            <p class="style-775447"><i class="fa-solid fa-circle-exclamation"></i> Error</p>
            <ul class="style-271000">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('vehicles.update', $vehicle) }}" method="POST" enctype="multipart/form-data" class="animate-fade">
        @csrf
        @method('PUT')
        <div class="glass style-b293d7">
            <div class="style-b9f9ff">
                <h3 class="style-4ac43c">
                    <i class="fa-solid fa-car"></i> Edit Vehicle Records
                </h3>
                <span class="status-badge " style="background: {{ $vehicle->status == 'Available' ? '#4ade80' : '#fbbf24' }}20; color: {{ $vehicle->status == 'Available' ? '#4ade80' : '#fbbf24' }}; border: 1px solid {{ $vehicle->status == 'Available' ? '#4ade80' : '#fbbf24' }}40;">
                    {{ strtoupper($vehicle->status) }}
                </span>
            </div>

            <div class="style-c45d59">
                <div class="form-group">
                    <label class="style-367f5d">License Plate No *</label>
                    <input type="text" name="license_plate" class="glass style-ad5d06" value="{{ $vehicle->license_plate }}" required>
                </div>
                <div class="form-group">
                    <label class="style-367f5d">Brand / Make *</label>
                    <input type="text" name="brand" class="glass style-ad5d06" value="{{ $vehicle->brand }}" required>
                </div>
                <div class="form-group">
                    <label class="style-367f5d">Model *</label>
                    <input type="text" name="model" class="glass style-ad5d06" value="{{ $vehicle->model }}" required>
                </div>
                <div class="form-group">
                    <label class="style-367f5d">Vehicle Type *</label>
                    <select name="vehicle_type" class="glass style-ad5d06" required>
                        @php
                            $types = explode(',', \App\Models\Core\Setting::get('vehicle_types', 'Sedan,SUV,Minivan,Hatchback'));
                        @endphp
                        @foreach($types as $type)
                            @php $t = trim($type); @endphp
                            <option value="{{ $t }}" {{ $vehicle->vehicle_type == $t ? 'selected' : '' }}>{{ $t }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label class="style-367f5d">Color</label>
                    <input type="text" name="color" class="glass style-ad5d06" value="{{ $vehicle->color }}">
                </div>
                <div class="form-group">
                    <label class="style-367f5d">Status</label>
                    <select name="status" class="glass style-ad5d06">
                        <option value="Available" {{ $vehicle->status == 'Available' ? 'selected' : '' }}>Available</option>
                        <option value="On Ride" {{ $vehicle->status == 'On Ride' ? 'selected' : '' }}>On Ride</option>
                        <option value="Maintenance" {{ $vehicle->status == 'Maintenance' ? 'selected' : '' }}>Maintenance</option>
                    </select>
                </div>
            </div>

            <div class="style-d6f41e">
                <h4 class="style-76e28f">Inspection & Gallery</h4>
                <div class="style-117f1a">
                    @foreach(['front', 'back', 'left_side', 'right_side', 'interior'] as $pos)
                    @php $photoField = $pos . '_photo'; @endphp
                    <div class="form-group">
                        <label class="style-bb948d">{{ str_replace('_', ' ', $pos) }}</label>
                        <div class="photo-upload-box glass style-738f98" onclick="document.getElementById('{{ $pos }}_photo').click()">
                            @if($vehicle->$photoField)
                                <img src="{{ asset($vehicle->$photoField) }}" id="img-{{ $pos }}"  class="style-7d1fae">
                            @else
                                <div id="preview-{{ $pos }}" class="style-cdd8ca">
                                    <i class="fa-solid fa-camera style-ed4950"></i>
                                </div>
                                <img id="img-{{ $pos }}" class="style-0f43f4">
                            @endif
                        </div>
                        <input type="file" name="{{ $pos }}_photo" id="{{ $pos }}_photo" onchange="previewImage(this, '{{ $pos }}')" class="style-93b8ea">
                    </div>
                    @endforeach
                </div>
            </div>

            <div class="style-70b365">
                <button type="button" onclick="history.back()" class="btn-secondary style-54f933">Cancel</button>
                <button type="submit" class="btn-primary style-853a9b">
                    <i class="fa-solid fa-save"></i> UPDATE VEHICLE RECORDS
                </button>
            </div>
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


<link rel="stylesheet" href="{{ asset('css/dashboardview/vehicle/create.css') }}">
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
            <h2 class="page-title style-27ac6d">Register New Vehicle</h2>
            <p class="page-subtitle">Add a new vehicle to the taxi fleet.</p>
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

    <form action="{{ route('vehicles.store') }}" method="POST" enctype="multipart/form-data" class="animate-fade">
        @csrf
        <div class="glass style-b293d7">
            <div class="style-257995">
                <h3 class="style-4ac43c">
                    <i class="fa-solid fa-car"></i> Fleet Registration
                </h3>
            </div>

            <div class="style-c45d59">
                <div class="form-group">
                    <label class="style-367f5d">License Plate No *</label>
                    <input type="text" name="license_plate" class="glass style-ad5d06" placeholder="YGN-1234" required>
                </div>
                <div class="form-group">
                    <label class="style-367f5d">Brand / Make *</label>
                    <input type="text" name="brand" class="glass style-ad5d06" placeholder="Toyota" required>
                </div>
                <div class="form-group">
                    <label class="style-367f5d">Model *</label>
                    <input type="text" name="model" class="glass style-ad5d06" placeholder="Probox" required>
                </div>
                <div class="form-group">
                    <label class="style-367f5d">Vehicle Type *</label>
                    <select name="vehicle_type" class="glass style-ad5d06" required>
                        @php
                            $types = explode(',', \App\Models\Core\Setting::get('vehicle_types', 'Sedan,SUV,Minivan,Hatchback'));
                        @endphp
                        @foreach($types as $type)
                            <option value="{{ trim($type) }}">{{ trim($type) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label class="style-367f5d">Color</label>
                    <input type="text" name="color" class="glass style-ad5d06" placeholder="White">
                </div>
                <div class="form-group">
                    <label class="style-367f5d">Status</label>
                    <select name="status" class="glass style-ad5d06">
                        <option value="Available">Available</option>
                        <option value="Maintenance">Maintenance</option>
                    </select>
                </div>
            </div>

            <div class="style-d6f41e">
                <h4 class="style-76e28f">Vehicle Inspection Photos</h4>
                <div class="style-117f1a">
                    @foreach(['front', 'back', 'left_side', 'right_side', 'interior'] as $pos)
                    <div class="form-group">
                        <label class="style-bb948d">{{ str_replace('_', ' ', $pos) }}</label>
                        <div class="photo-upload-box glass style-e62607" onclick="document.getElementById('{{ $pos }}_photo').click()">
                            <div id="preview-{{ $pos }}" class="style-cdd8ca">
                                <i class="fa-solid fa-camera style-ed4950"></i>
                            </div>
                            <img id="img-{{ $pos }}" class="style-ddb81d">
                        </div>
                        <input type="file" name="{{ $pos }}_photo" id="{{ $pos }}_photo" onchange="previewImage(this, '{{ $pos }}')" class="style-93b8ea">
                    </div>
                    @endforeach
                </div>
            </div>

            <div class="style-70b365">
                <button type="button" onclick="history.back()" class="btn-secondary style-54f933">Discard</button>
                <button type="submit" class="btn-primary style-853a9b">
                    <i class="fa-solid fa-save"></i> REGISTER VEHICLE
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


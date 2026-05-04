<link rel="stylesheet" href="{{ asset('css/dashboardview/vehicle/show.css') }}">
@extends('layout.admin')

@push('css')
    <link rel="stylesheet" href="{{ asset('css/dashboardview/vehicles/show.css?v=5') }}">
@endpush

@section('content')
<div class="animate-fade profile-container">
    <div class="page-header">
        <div>
            <h2 class="page-title">Vehicle Profile</h2>
            <p class="page-subtitle">Detailed information and inspection records.</p>
        </div>
        <div class="style-465b2a">
            <a href="{{ route('vehicles.index') }}" onmouseover="this.style.opacity='1'" onmouseout="this.style.opacity='0.9'" class="style-1cb386">
                <div class="style-ea0079">
                    <i class="fa-solid fa-arrow-left"></i>
                </div>
                FLEET LIST
            </a>
            <a href="{{ route('vehicles.edit', $vehicle) }}" class="btn-secondary style-18e8a5">
                <i class="fa-solid fa-pen-to-square"></i> Edit
            </a>
        </div>
    </div>

    <div class="glass style-a13c9c">
        <div class="style-5702eb">
            <!-- Left Side: Hero & Status -->
            <div class="style-b02fdb">
                <div class="style-407972">
                    @if($vehicle->vehicle_photo)
                        <img src="{{ asset('storage/' . $vehicle->vehicle_photo) }}" id="main-vehicle-image"  onclick="openLightbox()" class="style-803650">
                    @else
                        <div id="no-photo-placeholder" class="style-8acfcc">
                            <i class="fa-solid fa-car style-b2178e"></i>
                            <p>No primary photo</p>
                        </div>
                        <img id="main-vehicle-image" onclick="openLightbox()" class="style-964eba">
                    @endif
                    
                    <div class="style-d2e2f5">
                        <div class="style-452fc9">
                            <div>
                                <div class="style-a52a1b">
                                    {{ $vehicle->license_plate }}
                                </div>
                                <div class="style-b512c9">
                                    <span  style="width: 10px; height: 10px; border-radius: 50%; background: {{ $vehicle->status == 'Available' ? '#4ade80' : '#fbbf24' }}; box-shadow: 0 0 10px {{ $vehicle->status == 'Available' ? '#4ade80' : '#fbbf24' }};"></span>
                                    <span class="style-d31629">{{ $vehicle->status }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="style-c233d6">
                    @foreach(['front', 'back', 'left_side', 'right_side', 'interior'] as $pos)
                        @php $photoField = $pos . '_photo'; @endphp
                        <div onclick="switchMainImage('{{ $pos }}', '{{ asset('storage/' . $vehicle->$photoField) }}')"  onmouseover="this.style.borderColor='var(--accent-purple)'" onmouseout="this.style.borderColor='var(--glass-border)'" class="style-71ffb8">
                            @if($vehicle->$photoField)
                                <img src="{{ asset('storage/' . $vehicle->$photoField) }}"  class="style-7d1fae">
                            @else
                                <i class="fa-solid fa-camera style-8c191c"></i>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Right Side: Technical Specs & Driver -->
            <div class="style-502951">
                <h3 class="style-b3de61">
                    <i class="fa-solid fa-microchip"></i> Technical Specifications
                </h3>

                <div class="style-2ee324">
                    <div class="style-85f502">
                        <div class="glass style-34ec1e">
                            <p class="style-7eef84">Brand / Make</p>
                            <p class="style-32102a">{{ $vehicle->brand }}</p>
                        </div>
                        <div class="glass style-34ec1e">
                            <p class="style-7eef84">Model</p>
                            <p class="style-32102a">{{ $vehicle->model }}</p>
                        </div>
                    </div>

                    <div class="style-85f502">
                        <div class="glass style-34ec1e">
                            <p class="style-7eef84">Type</p>
                            <p class="style-32102a">{{ $vehicle->vehicle_type }}</p>
                        </div>
                        <div class="glass style-34ec1e">
                            <p class="style-7eef84">Color</p>
                            <p class="style-32102a">{{ $vehicle->color ?? 'N/A' }}</p>
                        </div>
                    </div>

                    <div class="glass style-e67d3e">
                        <p class="style-f3780a">Assigned Personnel</p>
                        @if($vehicle->driver)
                            <div class="style-ee8a4b">
                                @if($vehicle->driver->profile_picture)
                                    <img src="{{ asset('storage/' . $vehicle->driver->profile_picture) }}"  class="style-a54e88">
                                @else
                                    <div class="style-1de599">
                                        <i class="fa-solid fa-user"></i>
                                    </div>
                                @endif
                                <div>
                                    <p class="style-6cdad5">{{ $vehicle->driver->full_name }}</p>
                                    <p class="style-6d4167">Verified Driver Account</p>
                                </div>
                            </div>
                        @else
                            <div class="style-fd0deb">
                                <div class="style-c6e720">
                                    <i class="fa-solid fa-user-slash"></i>
                                </div>
                                <p class="style-4f38c3">No Driver Assigned</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<!-- Simple Lightbox Modal -->
<div id="lightbox" class="style-b2e6dc">
    <span onclick="document.getElementById('lightbox').style.display='none'" class="style-d922ea">&times;</span>
    <img id="lightbox-img" class="style-058322">
</div>

<script>
function switchMainImage(id) {
    const thumbImg = document.getElementById('img-' + id);
    const mainImg = document.getElementById('main-vehicle-image');
    const placeholder = document.getElementById('no-photo-placeholder');
    const overlay = document.getElementById('main-hero-overlay');

    if (thumbImg && mainImg) {
        // Change source
        mainImg.src = thumbImg.src;
        
        // Show the image if it was hidden (in case no primary photo was initially there)
        mainImg.style.display = 'block';
        if (placeholder) placeholder.style.display = 'none';
        if (overlay) overlay.style.display = 'block';

        // Add a small fade-in animation
        mainImg.style.opacity = '0';
        setTimeout(() => {
            mainImg.style.transition = 'opacity 0.3s ease';
            mainImg.style.opacity = '1';
        }, 10);
    }
}

function openLightbox() {
    const mainImg = document.getElementById('main-vehicle-image');
    if (mainImg && mainImg.src) {
        document.getElementById('lightbox-img').src = mainImg.src;
        document.getElementById('lightbox').style.display = 'flex';
    }
}
</script>
@endsection

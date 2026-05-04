<link rel="stylesheet" href="{{ asset('css/dashboardview/driver/show.css') }}">
@extends('layout.admin')

@push('css')
    <link rel="stylesheet" href="{{ asset('css/dashboardview/drivers/show.css') }}">
@endpush

@section('content')
<div class="animate-fade">
    <div class="style-257995">
        <a href="{{ route('drivers.index') }}" onmouseover="this.style.opacity='1'" onmouseout="this.style.opacity='0.9'" class="style-1cb386">
            <div class="style-ea0079">
                <i class="fa-solid fa-arrow-left"></i>
            </div>
            BACK TO DRIVERS LIST
        </a>
    </div>

    <div class="style-c9c22e">
        <div class="glass style-cc786a">
            <!-- Top Decoration -->
            <div class="style-36da62">
                <div class="style-59da4c">
                    <i class="fa-solid fa-taxi"></i> SMART TAXI FLEET
                </div>
                <div class="style-f39d7d">
                    DRIVER PASS
                </div>
            </div>

            <!-- Profile Info Overlay -->
            <div class="style-d5fbb6">
                <div class="style-b546cd">
                    <div class="style-61a045">
                        @if($driver->profile_picture)
                            <img src="{{ asset('storage/' . $driver->profile_picture) }}"  class="style-7d1fae">
                        @else
                            <div class="style-a1bd68">
                                {{ substr($driver->full_name, 0, 1) }}
                            </div>
                        @endif
                    </div>

                    <h2 class="style-9c0434">{{ $driver->full_name }}</h2>
                    <p class="style-7d64ee">Verified Professional</p>
                </div>

                <div class="style-6ac99d">
                    <div>
                        <p class="style-b3fd52">Driver ID</p>
                        <p class="style-944c77">DRV-{{ str_pad($driver->id, 5, '0', STR_PAD_LEFT) }}</p>
                    </div>
                    <div>
                        <p class="style-b3fd52">Joined Date</p>
                        <p class="style-944c77">{{ $driver->created_at->format('M Y') }}</p>
                    </div>
                    <div>
                        <p class="style-b3fd52">License No</p>
                        <p class="style-944c77">{{ $driver->license_no ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="style-b3fd52">Phone</p>
                        <p class="style-944c77">{{ $driver->phone_no }}</p>
                    </div>
                </div>

                <div class="style-a77c4c">
                    <div>
                        <p class="style-b693d7">Vehicle Assignment</p>
                        <p class="style-c8c51c">{{ $driver->vehicle->license_plate ?? 'UNASSIGNED' }}</p>
                    </div>
                    <div class="style-dd1f2e">
                        <i class="fa-solid fa-qrcode"></i>
                    </div>
                </div>
            </div>

            <!-- Footer Badge -->
            <div class="style-44a760">
                OFFICIAL COMPANY IDENTITY CARD
            </div>
        </div>

        <div class="style-e7105d">
            <a href="{{ route('drivers.edit', $driver) }}" class="btn-primary style-9997f5">
                <i class="fa-solid fa-pen"></i> Edit Profile
            </a>
            <button onclick="window.print()" class="btn-secondary style-9997f5">
                <i class="fa-solid fa-print"></i> Print ID
            </button>
        </div>
    </div>

</div>
@endsection

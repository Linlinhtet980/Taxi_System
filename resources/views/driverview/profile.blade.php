<link rel="stylesheet" href="{{ asset('css/driverview/profile.css') }}">
@extends('layout.driver')

@section('content')
<div class="animate-fade">
    <div class="style-7f5ca6" style="text-align: center; padding: 20px 0; position: relative;">
        <button id="editToggle" style="position: absolute; right: 10px; top: 10px; background: var(--primary-light); border: 1px solid var(--primary); color: var(--primary); width: 35px; height: 35px; border-radius: 10px; cursor: pointer; transition: 0.3s;">
            <i class="fa-solid fa-pen-to-square"></i>
        </button>

        <img id="profilePreview" src="{{ $driver->profile_picture ? asset($driver->profile_picture) : 'https://ui-avatars.com/api/?name=' . urlencode($driver->full_name) . '&background=D4AF37&color=fff&size=200' }}" alt="Profile" style="width: 100px; height: 100px; border-radius: 25px; border: 3px solid var(--primary); box-shadow: 0 10px 20px var(--shadow-color);">
        <h2 id="viewName" style="margin-top: 15px; font-weight: 800; color: var(--text-main);">{{ $driver->full_name }}</h2>
        <p id="viewEmail" style="color: var(--primary); font-weight: 600; font-size: 0.9rem;">{{ $driver->email }}</p>
    </div>

    @if(session('success'))
        <div class="glass" style="margin: 0 15px 15px; padding: 12px; color: var(--success); border: 1px solid var(--success); border-radius: 15px; text-align: center; font-size: 0.9rem;">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="glass" style="margin: 0 15px 15px; padding: 12px; color: var(--error); border: 1px solid var(--error); border-radius: 15px; font-size: 0.8rem;">
            @foreach($errors->all() as $error)
                <p>• {{ $error }}</p>
            @endforeach
        </div>
    @endif

    <!-- View Mode -->
    <div id="viewMode">
        <!-- Personal Info -->
        <div class="glass style-f36ddf">
            <h3 class="style-5ba996">Personal Details</h3>
            <div class="style-c3e0b7">
                <div class="style-58e548">
                    <span class="style-341204">Phone Number</span>
                    <span class="style-d2abfb">{{ $driver->phone_no }}</span>
                </div>
                <div class="style-58e548">
                    <span class="style-341204">License No.</span>
                    <span class="style-d2abfb">{{ $driver->license_no }}</span>
                </div>
                <div class="style-58e548">
                    <span class="style-341204">Address</span>
                    <span class="style-d2abfb">{{ $driver->address ?: 'Not set' }}</span>
                </div>
                <div class="style-58e548">
                    <span class="style-341204">Joined Date</span>
                    <span class="style-d2abfb">{{ $driver->created_at->format('d M Y') }}</span>
                </div>
            </div>
        </div>

        <!-- Vehicle Info -->
        @if($driver->vehicle)
        <div class="glass style-02be83">
            <h3 class="style-5ba996">Vehicle Details</h3>
            <div class="style-c3e0b7">
                <div class="style-58e548">
                    <span class="style-341204">Model</span>
                    <span class="style-d2abfb">{{ $driver->vehicle->model }}</span>
                </div>
                <div class="style-58e548">
                    <span class="style-341204">License Plate</span>
                    <span class="style-d2abfb">{{ $driver->vehicle->license_plate }}</span>
                </div>
                <div class="style-58e548">
                    <span class="style-341204">Brand / Type</span>
                    <span class="style-d2abfb">{{ $driver->vehicle->brand }} ({{ $driver->vehicle->vehicle_type }})</span>
                </div>
            </div>
        </div>
        @endif
    </div>

    <!-- Edit Mode (Hidden by default) -->
    <div id="editMode" style="display: none;">
        <form action="{{ route('driver.profile.update', $driver->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="glass" style="margin: 0 15px 20px; padding: 20px; border-radius: 20px;">
                <div style="margin-bottom: 15px;">
                    <label style="display: block; color: var(--text-dim); font-size: 0.8rem; margin-bottom: 8px;">Full Name</label>
                    <input type="text" name="full_name" value="{{ $driver->full_name }}" class="input-glass" style="padding: 12px; border-radius: 12px;">
                </div>

                <div style="margin-bottom: 15px;">
                    <label style="display: block; color: var(--text-dim); font-size: 0.8rem; margin-bottom: 8px;">Phone Number</label>
                    <input type="text" name="phone_no" value="{{ $driver->phone_no }}" class="input-glass" style="padding: 12px; border-radius: 12px;">
                </div>

                <div style="margin-bottom: 15px;">
                    <label style="display: block; color: var(--text-dim); font-size: 0.8rem; margin-bottom: 8px;">Address</label>
                    <textarea name="address" rows="3" class="input-glass" style="padding: 12px; border-radius: 12px; resize: none;">{{ $driver->address }}</textarea>
                </div>

                <div style="margin-bottom: 15px;">
                    <label style="display: block; color: var(--text-dim); font-size: 0.8rem; margin-bottom: 8px;">Change Profile Picture</label>
                    <input type="file" name="profile_picture" accept="image/*" style="color: var(--text-dim); font-size: 0.8rem;">
                </div>

                <div style="display: flex; gap: 10px; margin-top: 20px;">
                    <button type="submit" style="flex: 1; background: var(--primary); color: var(--bg-main); border: none; padding: 12px; border-radius: 12px; font-weight: 700; cursor: pointer;">Save Changes</button>
                    <button type="button" id="cancelEdit" style="flex: 1; background: var(--input-bg); color: var(--text-main); border: 1px solid var(--card-border); padding: 12px; border-radius: 12px; font-weight: 700; cursor: pointer;">Cancel</button>
                </div>
            </div>
        </form>
    </div>

    <!-- Navigation Options -->
    <div class="glass style-eab553">
        <div class="style-99129a">
            <i class="fa-solid fa-ranking-star style-c06d9c"></i>
            <span class="style-db1172">Leaderboard</span>
        </div>
        <a href="{{ route('driver.leaderboard', $driver->id) }}" class="glass style-b7f38b" >
            View
        </a>
    </div>

    <div class="glass style-b48995">
        <div class="style-99129a">
            <i class="fa-solid fa-bell style-a5e3c2"></i>
            <span class="style-db1172">Notifications History</span>
        </div>
        <a href="{{ route('driver.notifications', $driver->id) }}" class="glass style-a13e08" >
            View
        </a>
    </div>

    <div class="glass style-b48995">
        <div class="style-99129a">
            <i class="fa-solid fa-gift style-8ffdae"></i>
            <span class="style-db1172">Refer & Earn</span>
        </div>
        <a href="{{ route('driver.referrals', $driver->id) }}" class="glass style-c41009" >
            View
        </a>
    </div>

    <div class="style-08a6b0">
        <p class="style-622e85">Driver ID: #{{ $driver->id }}</p>
    </div>
</div>

<script>
    const editToggle = document.getElementById('editToggle');
    const cancelEdit = document.getElementById('cancelEdit');
    const viewMode = document.getElementById('viewMode');
    const editMode = document.getElementById('editMode');

    editToggle.addEventListener('click', () => {
        viewMode.style.display = 'none';
        editMode.style.display = 'block';
        editToggle.style.display = 'none';
    });

    cancelEdit.addEventListener('click', () => {
        viewMode.style.display = 'block';
        editMode.style.display = 'none';
        editToggle.style.display = 'block';
    });
</script>
@endsection

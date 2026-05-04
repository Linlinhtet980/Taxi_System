<link rel="stylesheet" href="{{ asset('css/driverview/referrals.css') }}">
@extends('layout.driver')

@section('content')
<div class="animate-fade">
    <div class="page-header style-d3297f">
        <div>
            <h1 class="page-title">Refer & Earn</h1>
            <p class="page-subtitle">Invite fellow drivers and earn rewards for every signup.</p>
        </div>
    </div>

    <!-- Referral Code Card -->
    <div class="glass style-866e46">
        <p class="style-1a609f">Your Unique Code</p>
        <h2 class="style-c20dba">{{ $driver->referral_code }}</h2>
        
        <div class="style-4647c7">
            <button onclick="copyCode()" class="glass style-2be34d">
                <i class="fa-regular fa-copy"></i> Copy Code
            </button>
            <button onclick="shareCode()" class="glass style-8da937">
                <i class="fa-solid fa-share-nodes"></i> Share
            </button>
        </div>
    </div>

    <!-- Stats Row -->
    <div class="style-d62148">
        <div class="glass style-ce639d">
            <p class="style-6f84e5">Total Referrals</p>
            <h3 class="style-af3cfd">{{ count($referredDrivers) }}</h3>
        </div>
        <div class="glass style-ce639d">
            <p class="style-6f84e5">Rewards Earned</p>
            <h3 class="style-fa8412">0 <span class="style-fb2a71">MMK</span></h3>
        </div>
    </div>

    <!-- Referred List -->
    <div class="glass style-2ee8fb">
        <h3 class="style-58cb47">Your Network</h3>
        
        @if(count($referredDrivers) > 0)
            <div class="style-c3e0b7">
                @foreach($referredDrivers as $ref)
                    <div class="style-f03d68">
                        <div class="style-1c20bb">
                            <img src="{{ $ref->profile_picture ? asset('storage/' . $ref->profile_picture) : 'https://ui-avatars.com/api/?name=' . urlencode($ref->full_name) }}"  class="style-679a4b">
                            <div>
                                <p class="style-8c5fd0">{{ $ref->full_name }}</p>
                                <p class="style-fbd666">Joined {{ $ref->created_at->format('M d, Y') }}</p>
                            </div>
                        </div>
                        <span class="style-b6bd95">ACTIVE</span>
                    </div>
                @endforeach
            </div>
        @else
            <div class="style-452e4a">
                <i class="fa-solid fa-users style-d6a97b"></i>
                <p>No referrals yet. Start inviting!</p>
            </div>
        @endif
    </div>
</div>

@push('js')
<script>
    function copyCode() {
        const code = "{{ $driver->referral_code }}";
        navigator.clipboard.writeText(code);
        alert('Referral code copied to clipboard!');
    }
    function shareCode() {
        const text = "Join me on TaxiAdmin! Use my referral code {{ $driver->referral_code }} to get started.";
        if (navigator.share) {
            navigator.share({ title: 'TaxiAdmin Referral', text: text, url: window.location.href });
        } else {
            copyCode();
        }
    }
</script>
@endpush
@endsection

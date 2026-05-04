<link rel="stylesheet" href="{{ asset('css/driverview/reviews.css') }}">
@extends('layout.driver')

@section('content')
<div class="animate-fade">
    <div class="style-60802f">
        <div>
            <h2 class="style-7c092f">Ratings & Reviews</h2>
            <p class="style-6d5c22">What passengers are saying about you.</p>
        </div>
        <div class="glass style-af3cbb">
            <div class="style-8e097e">{{ number_format($averageRating, 1) }}</div>
            <div class="style-ece134">Avg Rating</div>
        </div>
    </div>

    <div class="style-c3e0b7">
        @forelse($reviews as $r)
        <div class="glass style-32c16d">
            <div class="style-edbb86">
                <div class="style-b512c9">
                    <div class="style-dd8405">
                        {{ substr($r->customer->name, 0, 1) }}
                    </div>
                    <div>
                        <h4 class="style-8c5fd0">{{ $r->customer->name }}</h4>
                        <p class="style-fbd666">{{ $r->updated_at->diffForHumans() }}</p>
                    </div>
                </div>
                <div class="style-422dac">
                    @for($i = 1; $i <= 5; $i++)
                        <i class="fa-{{ $i <= $r->rating ? 'solid' : 'regular' }} fa-star style-fb2a71" ></i>
                    @endfor
                </div>
            </div>
            <p class="style-0558c4">
                "{{ $r->review }}"
            </p>
            <div class="style-6b30b8">
                Trip #{{ str_pad($r->id, 5, '0', STR_PAD_LEFT) }} • {{ $r->pickup_location }} to {{ $r->dropoff_location }}
            </div>
        </div>
        @empty
        <div class="glass style-d81ea1">
            <i class="fa-solid fa-comment-slash style-ac7b72"></i>
            <p>No reviews yet. Complete more trips to get rated!</p>
        </div>
        @endforelse
    </div>

    <div class="style-bc9cee">
        {{ $reviews->links() }}
    </div>
</div>
@endsection

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Taxi - Request Status</title>
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <link rel="stylesheet" href="{{ asset('css/customerview/waiting.css') }}">
    <meta http-equiv="refresh" content="5">
</head>
<body>
    <div class="status-container">
        @if($booking->status == 'pending')
            <div class="pulse-container">
                <div class="pulse-ring"></div>
                <div class="pulse-ring"></div>
                <div class="pulse-ring"></div>
                <div class="pulse-core">
                    <i class="fa-solid fa-satellite-dish"></i>
                </div>
            </div>
            <h2 class="finding-title">Finding Driver</h2>
            <p class="dim-text">Connecting you with {{ $booking->driver->full_name }}...</p>
            <form action="{{ route('customer.booking.cancel', $booking->id) }}" method="POST">
                @csrf
                <button type="submit" class="btn-cancel">Cancel Request</button>
            </form>
        @else
            <div class="status-card">
                @if($booking->status == 'confirmed')
                    <i class="fa-solid fa-check-circle status-icon success"></i>
                    <h2 class="status-title">On the way!</h2>
                    <p class="dim-text">Your driver is coming to get you.</p>
                @elseif($booking->status == 'ongoing')
                    <i class="fa-solid fa-car-side status-icon info"></i>
                    <h2 class="status-title">On your way</h2>
                    <p class="dim-text">Headed to {{ $booking->dropoff_location }}</p>
                @elseif($booking->status == 'completed')
                    <i class="fa-solid fa-flag-checkered status-icon success"></i>
                    <h2 class="status-title">Arrived!</h2>
                    <p class="dim-text">Hope you enjoyed your ride.</p>
                @elseif($booking->status == 'cancelled')
            <div class="status-card error">
                <i class="fa-solid fa-circle-xmark status-icon error"></i>
                <h2 class="error-title">Ride Cancelled</h2>
                <p class="dim-text mb-25">The driver has declined or the ride was cancelled.</p>
                
                <div class="driver-profile error">
                    <div class="avatar error"><i class="fa-solid fa-user-slash"></i></div>
                    <div class="flex-1">
                        <p class="driver-name error">Declined</p>
                        <p class="driver-detail">Request Not Accepted</p>
                    </div>
                </div>

                <a href="{{ route('customer.booking') }}" class="btn-cancel">TRY ANOTHER RIDE</a>
            </div>
        @endif


                <div class="driver-profile">
                    <div class="avatar"><i class="fa-solid fa-user"></i></div>
                    <div class="flex-1">
                        <p class="driver-name">{{ $booking->driver->full_name }}</p>
                        <p class="driver-detail">{{ $booking->driver->vehicle->license_plate ?? 'Premium Taxi' }}</p>
                    </div>
                    <div class="text-right">
                        <p class="fare-amount">{{ number_format($booking->fare) }}</p>
                        <p class="fare-currency">MMK</p>
                    </div>
                </div>

                @if($booking->status == 'completed')
                    <a href="{{ route('customer.booking') }}" class="book-again-link">BOOK AGAIN</a>
                @endif
            </div>
        @endif
    </div>
</body>
</html>

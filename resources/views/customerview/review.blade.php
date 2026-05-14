@extends('layout.customer')

@push('css')
    <style>
        .review-container {
            max-width: 500px;
            margin: 20px auto 40px;
            background: var(--card-glass);
            backdrop-filter: blur(20px);
            border: 1px solid var(--card-border);
            border-radius: 30px;
            padding: 35px 25px;
            text-align: center;
            box-shadow: 0 20px 50px rgba(0,0,0,0.3);
        }

        .success-checkmark {
            width: 80px;
            height: 80px;
            background: var(--success-light);
            color: var(--success);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 35px;
            margin: 0 auto 20px;
            box-shadow: 0 0 30px var(--success-light);
            animation: scaleIn 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }

        @keyframes scaleIn {
            0% { transform: scale(0); opacity: 0; }
            100% { transform: scale(1); opacity: 1; }
        }

        .review-title {
            font-size: 24px;
            font-weight: 800;
            color: var(--text-main);
            margin-bottom: 8px;
        }

        .review-subtitle {
            font-size: 13px;
            color: var(--text-dim);
            margin-bottom: 25px;
            line-height: 1.5;
        }

        .driver-preview {
            background: var(--input-bg);
            border: 1px solid var(--card-border);
            padding: 15px;
            border-radius: 20px;
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 30px;
            text-align: left;
        }

        .driver-avatar {
            width: 50px;
            height: 50px;
            border-radius: 15px;
            object-fit: cover;
        }

        .driver-info h4 { font-size: 15px; font-weight: 800; color: var(--text-main); margin-bottom: 2px; }
        .driver-info p { font-size: 12px; color: var(--primary); font-weight: 600; }

        /* Star Rating System */
        .stars-wrapper {
            display: flex;
            flex-direction: row-reverse;
            justify-content: center;
            gap: 10px;
            margin-bottom: 25px;
        }

        .stars-wrapper input { display: none; }

        .stars-wrapper label {
            font-size: 32px;
            color: var(--card-border);
            cursor: pointer;
            transition: 0.2s;
        }

        .stars-wrapper label:hover,
        .stars-wrapper label:hover ~ label,
        .stars-wrapper input:checked ~ label {
            color: var(--primary);
            text-shadow: 0 0 15px var(--accent-glow);
        }

        .form-group {
            margin-bottom: 25px;
            text-align: left;
        }

        .form-group label {
            display: block;
            font-size: 11px;
            font-weight: 800;
            color: var(--text-dim);
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 10px;
        }

        .form-control-area {
            width: 100%;
            background: var(--input-bg);
            border: 1px solid var(--card-border);
            padding: 15px;
            border-radius: 16px;
            color: var(--text-main);
            font-size: 14px;
            outline: none;
            resize: none;
            height: 100px;
            transition: 0.3s;
        }

        .form-control-area:focus {
            border-color: var(--primary);
            background: var(--input-focus-bg, var(--input-bg));
        }

        .btn-submit-review {
            width: 100%;
            background: var(--primary);
            color: var(--bg-main);
            border: none;
            padding: 16px;
            border-radius: 16px;
            font-weight: 900;
            font-size: 15px;
            cursor: pointer;
            transition: 0.3s;
            box-shadow: 0 10px 25px var(--primary-light);
        }

        .btn-submit-review:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 35px rgba(212, 175, 55, 0.4);
        }

        .btn-skip {
            display: inline-block;
            margin-top: 15px;
            color: var(--text-dim);
            font-size: 13px;
            font-weight: 700;
            text-decoration: none;
            transition: 0.2s;
        }

        .btn-skip:hover { color: var(--text-main); }

        @media (max-width: 360px) {
            .review-container { padding: 25px 15px; margin: 10px auto; border-radius: 20px; }
            .success-checkmark { width: 60px; height: 60px; font-size: 28px; margin-bottom: 15px; }
            .review-title { font-size: 20px; }
            .stars-wrapper label { font-size: 26px; gap: 6px; }
            .driver-preview { padding: 10px; gap: 10px; margin-bottom: 20px; }
            .driver-avatar { width: 40px; height: 40px; }
            .btn-submit-review { padding: 14px; font-size: 14px; }
        }
    </style>
@endpush

@section('content')
    <div class="review-container animate-fade">
        <div class="success-checkmark">
            <i class="fa-solid fa-check"></i>
        </div>

        <h2 class="review-title">ခရီးစဉ် ပြီးဆုံးပါပြီ</h2>
        <p class="review-subtitle">သင့်၏ ခရီးစဉ်အတွေ့အကြုံကို အဆင့်သတ်မှတ်ပေးရန်နှင့် မှတ်ချက်ပေးရန် ဖိတ်ခေါ်အပ်ပါသည်။</p>

        <div class="driver-preview">
            <img src="{{ $booking->driver->profile_picture ? asset($booking->driver->profile_picture) : 'https://ui-avatars.com/api/?name='.urlencode($booking->driver->full_name ?? 'Driver').'&background=D4AF37&color=000000' }}" class="driver-avatar" alt="Driver">
            <div class="driver-info">
                <h4>{{ $booking->driver->full_name ?? 'Elite Driver' }}</h4>
                <p>{{ $booking->driver->vehicle->license_plate ?? 'Premium Cab' }}</p>
            </div>
        </div>

        <form action="{{ route('customer.booking.review', $booking->id) }}" method="POST">
            @csrf
            
            <div class="stars-wrapper">
                <input type="radio" name="rating" id="star5" value="5" required checked>
                <label for="star5" title="5 stars"><i class="fa-solid fa-star"></i></label>
                
                <input type="radio" name="rating" id="star4" value="4">
                <label for="star4" title="4 stars"><i class="fa-solid fa-star"></i></label>
                
                <input type="radio" name="rating" id="star3" value="3">
                <label for="star3" title="3 stars"><i class="fa-solid fa-star"></i></label>
                
                <input type="radio" name="rating" id="star2" value="2">
                <label for="star2" title="2 stars"><i class="fa-solid fa-star"></i></label>
                
                <input type="radio" name="rating" id="star1" value="1">
                <label for="star1" title="1 star"><i class="fa-solid fa-star"></i></label>
            </div>

            <div class="form-group">
                <label>မှတ်ချက်ရေးသားရန် (Review)</label>
                <textarea name="review" class="form-control-area" placeholder="ယာဉ်မောင်း၏ ဝန်ဆောင်မှုအပေါ် သင့်အမြင်ကို မျှဝေပေးပါ..."></textarea>
            </div>

            <button type="submit" class="btn-submit-review">
                အတည်ပြုမည် <i class="fa-solid fa-arrow-right ml-2"></i>
            </button>
        </form>

        <a href="{{ route('customer.dashboard') }}" class="btn-skip">
            အဆင့်မသတ်မှတ်ဘဲ ကျော်သွားမည်
        </a>
    </div>
@endsection

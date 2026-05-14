@extends('layout.customer')

@section('title', 'Exchange Points - Taxi Luxury')

@push('css')
    <style>
        .points-hero-card {
            background: linear-gradient(135deg, var(--accent-primary), var(--accent-secondary));
            padding: 30px;
            border-radius: 24px;
            color: black;
            text-align: center;
            margin-bottom: 30px;
            box-shadow: 0 10px 30px var(--primary-light);
            border: 1px solid var(--card-border);
        }

        .points-hero-card p {
            font-size: 14px;
            opacity: 0.8;
            margin-bottom: 8px;
            font-weight: 700;
        }

        .points-hero-card h1 {
            font-size: 40px;
            font-weight: 800;
            margin: 0;
        }

        .section-title {
            font-size: 16px;
            font-weight: 700;
            margin-bottom: 20px;
            color: var(--text-main);
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .reward-card {
            background: var(--card-glass);
            border-radius: 20px;
            padding: 20px;
            margin-bottom: 15px;
            border: 1px solid var(--card-border);
            display: flex;
            justify-content: space-between;
            align-items: center;
            transition: 0.3s;
            backdrop-filter: blur(10px);
        }

        .reward-card:hover {
            transform: translateY(-3px);
            border-color: var(--primary);
            box-shadow: 0 10px 25px var(--primary-light);
        }

        .reward-info h3 {
            font-size: 17px;
            font-weight: 800;
            color: var(--text-main);
            margin-bottom: 4px;
        }

        .reward-info p {
            font-size: 13px;
            color: var(--text-dim);
            margin-bottom: 8px;
        }

        .reward-points {
            font-size: 13px;
            font-weight: 700;
            color: var(--primary);
            background: var(--input-bg);
            padding: 4px 10px;
            border-radius: 12px;
            border: 1px solid var(--card-border);
            display: inline-block;
        }

        .btn-exchange {
            padding: 12px 24px;
            background: var(--primary);
            color: black;
            border: none;
            border-radius: 16px;
            font-weight: 800;
            font-size: 14px;
            cursor: pointer;
            transition: 0.3s;
            box-shadow: 0 5px 15px var(--primary-light);
        }

        .btn-exchange:hover:not(:disabled) {
            transform: scale(1.05);
            filter: brightness(1.1);
        }

        .btn-exchange:disabled {
            background: var(--input-bg);
            color: var(--text-dim);
            border: 1px solid var(--card-border);
            cursor: not-allowed;
            box-shadow: none;
        }

        .alert-box {
            padding: 15px;
            border-radius: 16px;
            margin-bottom: 20px;
            font-size: 13px;
            font-weight: 600;
        }

        .alert-success-custom {
            background: rgba(16, 185, 129, 0.1);
            color: #10b981;
            border: 1px solid rgba(16, 185, 129, 0.2);
        }

        .alert-danger-custom {
            background: rgba(239, 68, 68, 0.1);
            color: #ef4444;
            border: 1px solid rgba(239, 68, 68, 0.2);
        }

        @media (max-width: 360px) {
            .points-hero-card {
                padding: 20px 15px;
                margin-bottom: 20px;
                border-radius: 20px;
            }

            .points-hero-card h1 {
                font-size: 28px;
            }

            .points-hero-card p {
                font-size: 12px;
            }

            .reward-card {
                padding: 15px 12px;
                flex-direction: column;
                align-items: flex-start;
                gap: 12px;
                border-radius: 16px;
            }

            .reward-info h3 {
                font-size: 15px;
            }

            .reward-info p {
                font-size: 11px;
            }

            .reward-points {
                font-size: 11px;
                padding: 3px 8px;
            }

            .reward-card form,
            .reward-card button {
                width: 100%;
            }

            .btn-exchange {
                padding: 10px;
                font-size: 13px;
            }
        }
    </style>
@endpush

@section('content')
    <div class="animate-fade">
        @if(session('success'))
            <div class="alert-box alert-success-custom">
                <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert-box alert-danger-custom">
                <i class="fa-solid fa-circle-exclamation"></i> {{ session('error') }}
            </div>
        @endif

        <div class="points-hero-card">
            <p>လက်ကျန် အမှတ်များ</p>
            <h1>{{ number_format($customer->loyalty_points) }}</h1>
            <p style="margin-top: 5px; font-size: 12px; margin-bottom: 0;">Points Available</p>
        </div>

        <div class="section-title">လဲလှယ်ရန် ရွေးချယ်ပါ</div>

        @forelse($rewards as $reward)
            <div class="reward-card">
                <div class="reward-info">
                    <h3>{{ $reward->title }}</h3>
                    <p>{{ $reward->description }}</p>
                    <span class="reward-points"><i class="fa-solid fa-star"></i> {{ number_format($reward->points_required) }}
                        Points</span>
                </div>
                <form action="{{ route('customer.points.exchange.submit', $reward->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn-exchange" {{ $customer->loyalty_points < $reward->points_required ? 'disabled' : '' }}>
                        Exchange
                    </button>
                </form>
            </div>
        @empty
            <div
                style="text-align: center; padding: 50px 20px; color: var(--text-dim); background: var(--card-glass); border-radius: 20px; border: 1px solid var(--card-border);">
                <i class="fa-solid fa-gift" style="font-size: 40px; margin-bottom: 15px; opacity: 0.3;"></i>
                <p>လဲလှယ်ရန် ပစ္စည်းများ မရှိသေးပါ။</p>
            </div>
        @endforelse
    </div>
@endsection
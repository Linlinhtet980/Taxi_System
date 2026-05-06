<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exchange Points - Taxi</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #6366f1;
            --bg: #f8fafc;
            --text: #1e293b;
            --text-light: #64748b;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Outfit', sans-serif; }
        body { background-color: var(--bg); color: var(--text); }

        .header {
            background: white; padding: 20px; display: flex; align-items: center; justify-content: center;
            position: fixed; top: 0; left: 0; right: 0; z-index: 1000; height: 70px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.02);
        }
        .back-btn {
            position: absolute; left: 20px; top: 50%; transform: translateY(-50%);
            width: 40px; height: 40px; border-radius: 12px; background: #f1f5f9;
            display: flex; justify-content: center; align-items: center;
            text-decoration: none; color: var(--text);
        }

        .container { padding: 90px 20px 30px; max-width: 600px; margin: 0 auto; }

        .points-hero {
            background: linear-gradient(135deg, var(--primary), #a855f7);
            padding: 30px; border-radius: 24px; color: white; text-align: center;
            margin-bottom: 25px; box-shadow: 0 10px 20px rgba(99, 102, 241, 0.2);
        }
        .points-hero h1 { font-size: 36px; font-weight: 800; }
        .points-hero p { font-size: 14px; opacity: 0.9; margin-top: 5px; font-weight: 600; }

        .section-title { font-size: 18px; font-weight: 700; margin-bottom: 20px; color: var(--text); }

        .reward-card {
            background: white; border-radius: 20px; padding: 20px; margin-bottom: 15px;
            border: 1px solid #f1f5f9; display: flex; justify-content: space-between; align-items: center;
            transition: 0.3s;
        }
        .reward-card:hover { transform: translateY(-3px); box-shadow: 0 8px 20px rgba(0,0,0,0.05); }

        .reward-info h3 { font-size: 16px; font-weight: 700; margin-bottom: 4px; }
        .reward-info p { font-size: 12px; color: var(--text-light); }
        .reward-points { font-size: 14px; font-weight: 700; color: var(--primary); margin-top: 8px; display: block; }

        .btn-exchange {
            padding: 12px 20px; background: var(--primary); color: white; border: none;
            border-radius: 15px; font-weight: 700; font-size: 14px; cursor: pointer;
            transition: 0.3s;
        }
        .btn-exchange:disabled { background: #e2e8f0; color: #94a3b8; cursor: not-allowed; }

        .alert {
            padding: 15px; border-radius: 16px; margin-bottom: 20px; font-size: 14px; font-weight: 600;
        }
        .alert-success { background: #dcfce7; color: #166534; }
        .alert-danger { background: #fee2e2; color: #991b1b; }
    </style>
</head>
<body>

    <header class="header">
        <a href="{{ route('customer.dashboard') }}" class="back-btn">
            <i class="fa-solid fa-chevron-left"></i>
        </a>
        <h2 style="font-size: 18px;">Exchange Points</h2>
    </header>

    <main class="container">
        @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <div class="points-hero">
            <p>လက်ကျန် အမှတ်</p>
            <h1>{{ number_format($customer->loyalty_points) }}</h1>
            <p>Points Available</p>
        </div>

        <div class="section-title">လဲလှယ်ရန် ရွေးချယ်ပါ</div>

        @forelse($rewards as $reward)
        <div class="reward-card">
            <div class="reward-info">
                <h3>{{ $reward->title }}</h3>
                <p>{{ $reward->description }}</p>
                <span class="reward-points">{{ number_format($reward->points_required) }} Points</span>
            </div>
            <form action="{{ route('customer.points.exchange.submit', $reward->id) }}" method="POST">
                @csrf
                <button type="submit" class="btn-exchange" {{ $customer->loyalty_points < $reward->points_required ? 'disabled' : '' }}>
                    Exchange
                </button>
            </form>
        </div>
        @empty
        <div style="text-align: center; padding: 50px 20px; color: var(--text-light);">
            <p>လဲလှယ်ရန် ပစ္စည်းများ မရှိသေးပါ။</p>
        </div>
        @endforelse
    </main>

</body>
</html>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Information - Taxi</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #6366f1;
            --primary-dark: #4f46e5;
            --bg: #f8fafc;
            --text: #1e293b;
            --text-light: #64748b;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Outfit', sans-serif;
        }

        body {
            background-color: #c9d6ff;
            background: linear-gradient(to right, #e2e2e2, #c9d6ff);
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            overflow: hidden;
        }

        .container {
            width: 90%;
            max-width: 400px;
            height: 550px;
            background: white;
            padding: 30px;
            display: flex;
            flex-direction: column;
            box-shadow: 0 20px 50px rgba(0,0,0,0.1);
            border-radius: 40px;
            text-align: center;
        }

        .header {
            margin-bottom: 30px;
        }

        .header h1 {
            font-size: 24px;
            color: var(--text);
            margin-bottom: 10px;
        }

        .header p {
            color: var(--text-light);
            font-size: 14px;
            line-height: 1.5;
        }

        .payment-flow {
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 20px;
            justify-content: center;
        }

        .flow-item {
            display: flex;
            align-items: center;
            gap: 15px;
            text-align: left;
            padding: 15px;
            background: #f8fafc;
            border-radius: 20px;
            transition: 0.3s;
        }

        .icon-box {
            width: 45px;
            height: 45px;
            border-radius: 12px;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 20px;
            flex-shrink: 0;
        }

        .info h3 {
            font-size: 15px;
            color: var(--text);
            margin-bottom: 2px;
        }

        .info p {
            font-size: 12px;
            color: var(--text-light);
            line-height: 1.4;
        }

        .footer {
            margin-top: 20px;
        }

        .btn-finish {
            width: 100%;
            padding: 16px;
            background: var(--primary);
            color: white;
            border: none;
            border-radius: 16px;
            font-size: 18px;
            font-weight: 600;
            cursor: pointer;
            transition: 0.3s;
        }

        .btn-finish:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(99, 102, 241, 0.4);
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>ငွေပေးချေမှုစနစ်များ</h1>
            <p>ခရီးစဉ်များအတွက် အောက်ပါနည်းလမ်းများဖြင့် လွယ်ကူစွာ ငွေပေးချေနိုင်ပါသည်။</p>
        </div>

        <div class="payment-flow">
            <div class="flow-item">
                <div class="icon-box" style="background: #ecfdf5; color: #10b981;">
                    <i class="fa-solid fa-money-bill-wave"></i>
                </div>
                <div class="info">
                    <h3>လက်ငင်းငွေချေစနစ် (Cash)</h3>
                    <p>ခရီးစဉ်အပြီးတွင် ယာဉ်မောင်းထံသို့ လက်ငင်းပေးချေနိုင်ပါသည်။</p>
                </div>
            </div>

            <div class="flow-item">
                <div class="icon-box" style="background: #eff6ff; color: #3b82f6;">
                    <i class="fa-solid fa-mobile-screen"></i>
                </div>
                <div class="info">
                    <h3>KPay ဖြင့်ပေးချေခြင်း</h3>
                    <p>KBZPay အသုံးပြု၍ အလွယ်တကူ Scan ဖတ်ကာ ငွေလွှဲပေးချေနိုင်ပါသည်။</p>
                </div>
            </div>

            <div class="flow-item">
                <div class="icon-box" style="background: #fff7ed; color: #f59e0b;">
                    <i class="fa-solid fa-money-check-dollar"></i>
                </div>
                <div class="info">
                    <h3>WavePay ဖြင့်ပေးချေခြင်း</h3>
                    <p>WavePay အသုံးပြုသူများအတွက်လည်း စိတ်ချလက်ချ ပေးချေနိုင်ပါသည်။</p>
                </div>
            </div>
        </div>

        <div class="footer">
            <button class="btn-finish" onclick="window.location.href='{{ route('customer.dashboard') }}'">
                စတင်အသုံးပြုမည်
            </button>
        </div>
    </div>
</body>
</html>

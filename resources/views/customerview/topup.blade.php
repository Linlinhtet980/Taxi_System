<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Top-up Wallet - Taxi</title>
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
        body { background-color: var(--bg); color: var(--text); padding-bottom: 50px; }

        .header {
            background: white;
            padding: 20px;
            text-align: center;
            position: relative;
            box-shadow: 0 2px 10px rgba(0,0,0,0.02);
            margin-bottom: 25px;
        }

        .back-btn {
            position: absolute;
            left: 20px;
            top: 50%;
            transform: translateY(-50%);
            width: 40px;
            height: 40px;
            border-radius: 12px;
            background: #f1f5f9;
            display: flex;
            justify-content: center;
            align-items: center;
            text-decoration: none;
            color: var(--text);
        }

        .container { padding: 0 20px; max-width: 500px; margin: 0 auto; }

        .balance-card {
            background: linear-gradient(135deg, var(--primary), #4f46e5);
            padding: 30px;
            border-radius: 24px;
            color: white;
            text-align: center;
            margin-bottom: 30px;
            box-shadow: 0 15px 35px rgba(99, 102, 241, 0.25);
        }
        .balance-card p { font-size: 14px; opacity: 0.9; margin-bottom: 8px; font-weight: 500; }
        .balance-card h2 { font-size: 32px; font-weight: 800; }

        .section-title { font-size: 16px; font-weight: 700; margin-bottom: 20px; color: var(--text); }

        .amount-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 12px;
            margin-bottom: 20px;
        }
        .amount-btn {
            background: white;
            border: 2px solid #e2e8f0;
            padding: 15px 10px;
            border-radius: 16px;
            font-size: 14px;
            font-weight: 700;
            color: var(--text);
            cursor: pointer;
            transition: 0.3s;
            text-align: center;
        }
        .amount-btn:hover { border-color: var(--primary); color: var(--primary); }

        .input-wrapper { position: relative; margin-bottom: 30px; }
        .input-wrapper input {
            width: 100%;
            padding: 16px 20px;
            padding-left: 45px;
            background: white;
            border: 2px solid #e2e8f0;
            border-radius: 16px;
            font-size: 18px;
            font-weight: 700;
            outline: none;
            transition: 0.3s;
        }
        .input-wrapper input:focus { border-color: var(--primary); }
        .input-wrapper i { position: absolute; left: 20px; top: 50%; transform: translateY(-50%); color: var(--text-light); font-size: 18px; }

        /* Method Selection */
        .method-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
            margin-bottom: 30px;
        }
        .method-card {
            background: white;
            border: 2px solid #e2e8f0;
            padding: 20px;
            border-radius: 20px;
            text-align: center;
            cursor: pointer;
            transition: 0.3s;
        }
        .method-card.active {
            border-color: var(--primary);
            background: #f5f3ff;
        }
        .method-card img {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            margin-bottom: 10px;
        }
        .method-card span { display: block; font-size: 14px; font-weight: 700; }

        /* Admin Details */
        #adminDetails {
            display: none;
            background: #f1f5f9;
            padding: 20px;
            border-radius: 20px;
            margin-bottom: 30px;
            border: 1px dashed #cbd5e1;
        }
        .detail-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }
        .detail-label { font-size: 12px; color: var(--text-light); }
        .detail-value { font-size: 14px; font-weight: 700; }

        /* File Upload */
        .file-upload {
            border: 2px dashed #e2e8f0;
            padding: 30px 20px;
            border-radius: 20px;
            text-align: center;
            cursor: pointer;
            background: white;
            margin-bottom: 30px;
        }
        .file-upload i { font-size: 30px; color: var(--text-light); margin-bottom: 10px; }
        .file-upload p { font-size: 13px; color: var(--text-light); font-weight: 500; }

        .btn-submit {
            width: 100%;
            padding: 18px;
            background: var(--primary);
            color: white;
            border: none;
            border-radius: 20px;
            font-size: 16px;
            font-weight: 700;
            cursor: pointer;
            box-shadow: 0 10px 20px rgba(99, 102, 241, 0.2);
        }

        .info-banner {
            background: #eff6ff;
            padding: 15px;
            border-radius: 16px;
            display: flex;
            gap: 12px;
            margin-top: 30px;
        }
        .info-banner i { color: #2563eb; }
        .info-banner p { font-size: 12px; color: #1e40af; line-height: 1.5; }

        /* Points Exchange UI */
        .points-card {
            background: white;
            padding: 20px;
            border-radius: 24px;
            border: 1px solid #f1f5f9;
            margin-bottom: 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .points-info { display: flex; align-items: center; gap: 15px; }
        .points-icon { 
            width: 50px; 
            height: 50px; 
            background: #fffbeb; 
            border-radius: 15px; 
            display: flex; 
            justify-content: center; 
            align-items: center; 
            color: #fbbf24; 
            font-size: 24px;
        }
        .points-text p { font-size: 12px; color: var(--text-light); margin-bottom: 2px; }
        .points-text h4 { font-size: 20px; font-weight: 800; }
        .btn-exchange-trigger {
            background: #fffbeb;
            color: #d97706;
            border: 1px solid #fef3c7;
            padding: 10px 15px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 700;
            cursor: pointer;
        }

        #exchangeFormContainer {
            display: none;
            background: white;
            padding: 20px;
            border-radius: 24px;
            border: 1px solid #f1f5f9;
            margin-bottom: 30px;
            animation: slideDown 0.3s ease-out;
        }
        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .exchange-input {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e2e8f0;
            border-radius: 14px;
            font-size: 14px;
            font-weight: 700;
            margin-bottom: 15px;
            outline: none;
        }
        .exchange-input:focus { border-color: #fbbf24; }
        .btn-exchange-submit {
            width: 100%;
            padding: 14px;
            background: #fbbf24;
            color: #92400e;
            border: none;
            border-radius: 14px;
            font-size: 14px;
            font-weight: 800;
            cursor: pointer;
        }
        .exchange-rate-info { font-size: 11px; color: var(--text-light); text-align: center; margin-top: 10px; }
    </style>
</head>
<body>
    <header class="header">
        <a href="{{ route('customer.dashboard') }}" class="back-btn">
            <i class="fa-solid fa-chevron-left"></i>
        </a>
        <h2 style="font-size: 18px;">Top-up Wallet</h2>
    </header>

    <main class="container">
        <div class="balance-card">
            <p>Current Balance</p>
            <h2>{{ number_format($customer->wallet_balance) }} Ks</h2>
        </div>

        @if(session('success'))
            <div style="background: #dcfce7; color: #166534; padding: 15px; border-radius: 16px; margin-bottom: 20px; font-size: 13px; font-weight: 600;">
                <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div style="background: #fee2e2; color: #991b1b; padding: 15px; border-radius: 16px; margin-bottom: 20px; font-size: 13px; font-weight: 600;">
                <i class="fa-solid fa-circle-exclamation"></i> {{ session('error') }}
            </div>
        @endif


        <form action="{{ route('customer.wallet.topup.store') }}" method="POST" id="topupForm" enctype="multipart/form-data">
            @csrf
            
            <div class="section-title">Step 1: Select Amount</div>
            <div class="amount-grid">
                <div class="amount-btn" onclick="setAmount(5000)">5,000</div>
                <div class="amount-btn" onclick="setAmount(10000)">10,000</div>
                <div class="amount-btn" onclick="setAmount(50000)">50,000</div>
            </div>

            <div class="input-wrapper">
                <i class="fa-solid fa-wallet"></i>
                <input type="number" name="amount" id="amount_input" placeholder="Enter amount manually" required>
            </div>

            <div class="section-title">Step 2: Payment Method</div>
            <div class="method-grid">
                <div class="method-card" id="card_kpay" onclick="selectMethod('kpay', '09 123 456 789', 'Taxi Admin (KPay)')">
                    <img src="https://image.winudf.com/v2/image1/Y29tLmtiei5ncm91cC5rYnpwYXlfaWNvbl8xNjYxNDEzNjU5XzA4Nw/icon.png?w=170&fakeurl=1" alt="KPay" onerror="this.src='https://img.icons8.com/color/96/wallet.png'">
                    <span>KPay</span>
                </div>
                <div class="method-card" id="card_wave" onclick="selectMethod('wave', '09 987 654 321', 'Taxi Admin (Wave)')">
                    <img src="https://image.winudf.com/v2/image1/Y29tLndhdmUubW9uZXkuY3VzdG9tZXJfaWNvbl8xNTk1NDI3NTU3XzA1OQ/icon.png?w=170&fakeurl=1" alt="Wave" onerror="this.src='https://img.icons8.com/color/96/money-bag.png'">
                    <span>WavePay</span>
                </div>
            </div>

            <div id="adminDetails">
                <p style="font-size: 11px; font-weight: 700; margin-bottom: 15px; color: var(--primary); text-transform: uppercase;">Transfer details</p>
                <div class="detail-row">
                    <span class="detail-label">Name</span>
                    <span class="detail-value" id="adminName">-</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Account Number</span>
                    <span class="detail-value" id="adminPhone">-</span>
                </div>
            </div>

            <div class="section-title">Step 3: Upload Receipt</div>
            <div class="file-upload" onclick="document.getElementById('screenshot').click()">
                <i class="fa-solid fa-image"></i>
                <p id="fileName">Click to upload transfer screenshot</p>
                <input type="file" name="screenshot" id="screenshot" style="display: none;" onchange="updateFileName()">
            </div>

            <button type="submit" class="btn-submit">Submit Top-up Request</button>
        </form>

        <div class="info-banner">
            <i class="fa-solid fa-circle-info"></i>
            <p>Demo Mode: Admin will review your transfer. For this demo, your wallet will be updated automatically upon submission.</p>
        </div>
    </main>

    <script>
        function setAmount(val) {
            document.getElementById('amount_input').value = val;
        }

        function selectMethod(method, phone, name) {
            document.querySelectorAll('.method-card').forEach(c => c.classList.remove('active'));
            document.getElementById('card_' + method).classList.add('active');

            document.getElementById('adminName').innerText = name;
            document.getElementById('adminPhone').innerText = phone;
            document.getElementById('adminDetails').style.display = 'block';
        }

        function updateFileName() {
            const file = document.getElementById('screenshot').files[0];
            if(file) document.getElementById('fileName').innerText = file.name;
        }

    </script>
</body>
</html>

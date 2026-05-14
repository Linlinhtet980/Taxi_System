@extends('layout.customer')

@section('title', 'Top-up Wallet - Taxi')

@push('css')
    <style>
        .balance-card {
            background: linear-gradient(135deg, var(--primary), var(--accent-secondary));
            padding: 30px;
            border-radius: 24px;
            color: black;
            text-align: center;
            margin-bottom: 30px;
            box-shadow: 0 10px 30px var(--primary-light);
            border: 1px solid var(--card-border);
        }
        .balance-card p { font-size: 14px; opacity: 0.8; margin-bottom: 8px; font-weight: 600; }
        .balance-card h2 { font-size: 32px; font-weight: 800; }

        .section-title { font-size: 16px; font-weight: 700; margin-bottom: 15px; color: var(--text-main); }

        .amount-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 12px;
            margin-bottom: 20px;
        }
        .amount-btn {
            background: var(--card-glass);
            border: 1px solid var(--card-border);
            padding: 15px 10px;
            border-radius: 16px;
            font-size: 14px;
            font-weight: 700;
            color: var(--text-main);
            cursor: pointer;
            transition: 0.3s;
            text-align: center;
            backdrop-filter: blur(10px);
        }
        .amount-btn:hover { border-color: var(--primary); background: var(--primary-light); }

        .input-wrapper-topup { position: relative; margin-bottom: 30px; }
        .input-wrapper-topup input {
            width: 100%;
            padding: 16px 20px;
            padding-left: 45px;
            background: var(--input-bg);
            border: 1px solid var(--card-border);
            border-radius: 16px;
            font-size: 18px;
            font-weight: 700;
            color: var(--text-main);
            outline: none;
            transition: 0.3s;
        }
        .input-wrapper-topup input:focus { border-color: var(--primary); background: var(--bg-main); box-shadow: 0 0 0 4px var(--primary-light); }
        .input-wrapper-topup i { position: absolute; left: 20px; top: 50%; transform: translateY(-50%); color: var(--primary); font-size: 18px; }

        .method-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
            margin-bottom: 30px;
        }
        .method-card {
            background: var(--card-glass);
            border: 1px solid var(--card-border);
            padding: 20px;
            border-radius: 20px;
            text-align: center;
            cursor: pointer;
            transition: 0.3s;
            backdrop-filter: blur(10px);
        }
        .method-card.active {
            border-color: var(--primary);
            background: var(--primary-light);
            box-shadow: 0 0 15px var(--primary-light);
        }
        .method-card img {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            margin-bottom: 10px;
        }
        .method-card span { display: block; font-size: 14px; font-weight: 700; color: var(--text-main); }

        #adminDetails {
            display: none;
            background: var(--input-bg);
            padding: 20px;
            border-radius: 20px;
            margin-bottom: 30px;
            border: 1px dashed var(--card-border);
        }
        .detail-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }
        .detail-label { font-size: 12px; color: var(--text-dim); }
        .detail-value { font-size: 14px; font-weight: 700; color: var(--text-main); }

        .file-upload-box {
            border: 2px dashed var(--card-border);
            padding: 30px 20px;
            border-radius: 20px;
            text-align: center;
            cursor: pointer;
            background: var(--card-glass);
            margin-bottom: 30px;
            transition: 0.3s;
        }
        .file-upload-box:hover { border-color: var(--primary); background: var(--primary-light); }
        .file-upload-box i { font-size: 30px; color: var(--text-dim); margin-bottom: 10px; }
        .file-upload-box p { font-size: 13px; color: var(--text-dim); font-weight: 500; }

        .btn-submit-topup {
            width: 100%;
            padding: 18px;
            background: var(--primary);
            color: black;
            border: none;
            border-radius: 20px;
            font-size: 16px;
            font-weight: 700;
            cursor: pointer;
            box-shadow: 0 10px 20px var(--primary-light);
            transition: 0.3s;
        }
        .btn-submit-topup:hover { transform: translateY(-2px); filter: brightness(1.1); }

        .info-banner-topup {
            background: var(--primary-light);
            padding: 15px;
            border-radius: 16px;
            display: flex;
            gap: 12px;
            margin-top: 30px;
            border: 1px solid var(--card-border);
        }
        .info-banner-topup i { color: var(--primary); }
        .info-banner-topup p { font-size: 12px; color: var(--text-main); line-height: 1.5; }
    </style>
@endpush

@section('content')
    <div class="animate-fade">
        <div class="balance-card">
            <p>Current Balance</p>
            <h2>{{ number_format($customer->wallet_balance) }} Ks</h2>
        </div>

        @if(session('success'))
            <div style="background: rgba(16, 185, 129, 0.1); color: #10b981; padding: 15px; border-radius: 16px; margin-bottom: 20px; font-size: 13px; font-weight: 600; border: 1px solid rgba(16, 185, 129, 0.2);">
                <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div style="background: rgba(239, 68, 68, 0.1); color: #ef4444; padding: 15px; border-radius: 16px; margin-bottom: 20px; font-size: 13px; font-weight: 600; border: 1px solid rgba(239, 68, 68, 0.2);">
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

            <div class="input-wrapper-topup">
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
            <div class="file-upload-box" onclick="document.getElementById('screenshot').click()">
                <i class="fa-solid fa-image"></i>
                <p id="fileName">Click to upload transfer screenshot</p>
                <input type="file" name="screenshot" id="screenshot" style="display: none;" onchange="updateFileName()">
            </div>

            <button type="submit" class="btn-submit-topup">Submit Top-up Request</button>
        </form>

        <div class="info-banner-topup">
            <i class="fa-solid fa-circle-info"></i>
            <p>Demo Mode: Admin will review your transfer. For this demo, your wallet will be updated automatically upon submission.</p>
        </div>
    </div>

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
@endsection

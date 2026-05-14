<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Taxi - Driver Registration Step 2</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/auth/driver_register.css') }}">
    <style>
        .file-input-wrapper {
            position: relative;
            overflow: hidden;
            display: inline-block;
            width: 100%;
        }
        .file-input-wrapper input[type=file] {
            font-size: 100px;
            position: absolute;
            left: 0;
            top: 0;
            opacity: 0;
            cursor: pointer;
            height: 100%;
        }
    </style>
</head>
<body>
    <div class="reg-card">
        <div class="header">
            <h1>Vehicle & Documents</h1>
            <p>Upload your driving credentials. (Step 2/2)</p>
        </div>

        @if ($errors->any())
            <div style="color: #ef4444; font-size: 0.85rem; margin-bottom: 15px; padding: 10px; background: rgba(239, 68, 68, 0.1); border-radius: 8px;">
                @foreach ($errors->all() as $error)
                    <p style="margin:0;">• {{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form action="{{ route('driver.onboarding.vehicle.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="input-box">
                <label>NRC Number (မှတ်ပုံတင်နံပါတ်)</label>
                <input type="text" name="identity_card_no" placeholder="၁၂/ဗဟန(နိုင်)၁၂၃၄၅၆" value="{{ old('identity_card_no') }}" required>
            </div>
            
            <div class="input-row">
                <div class="input-box">
                    <label>License Number (လိုင်စင်နံပါတ်)</label>
                    <input type="text" name="license_no" placeholder="B/12345/14" value="{{ old('license_no') }}" required>
                </div>
                <div class="input-box">
                    <label>Vehicle Number (ကားနံပါတ်)</label>
                    <input type="text" name="vehicle_no" placeholder="YGN 1A-1234" value="{{ old('vehicle_no') }}" required>
                </div>
            </div>

            <div class="input-box">
                <label>Car Model / Type (ကား အမျိုးအစား)</label>
                <input type="text" name="vehicle_type" placeholder="e.g. Toyota Crown (Saloon)" value="{{ old('vehicle_type') }}" required>
            </div>

            <div class="upload-grid">
                <div class="upload-btn file-input-wrapper">
                    <i class="fa-solid fa-id-card"></i>
                    <span>NRC Photo<br>(မှတ်ပုံတင် ဓာတ်ပုံ)</span>
                    <input type="file" name="nric_photo" accept="image/*" required>
                </div>
                <div class="upload-btn file-input-wrapper">
                    <i class="fa-solid fa-car"></i>
                    <span>License Photo<br>(လိုင်စင် ဓာတ်ပုံ)</span>
                    <input type="file" name="license_photo" accept="image/*" required>
                </div>
            </div>

            <div class="upload-grid">
                <div class="upload-btn file-input-wrapper">
                    <i class="fa-solid fa-book"></i>
                    <span>Owner Book<br>(ကား Owner Book)</span>
                    <input type="file" name="owner_book_photo" accept="image/*" required>
                </div>
                <div class="upload-btn file-input-wrapper">
                    <i class="fa-solid fa-image"></i>
                    <span>Car Photo<br>(ကားပုံ)</span>
                    <input type="file" name="vehicle_photo" accept="image/*" required>
                </div>
            </div>

            <div class="btn-wrapper" style="margin-top: 25px;">
                <button type="submit" class="btn btn-primary" style="width: 100%;">Complete Registration <i class="fa-solid fa-check"></i></button>
            </div>
        </form>
    </div>
</body>
</html>

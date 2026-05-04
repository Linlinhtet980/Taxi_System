<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Personal Profile - Luxury Driver</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #D4AF37;
            --text-dark: #1a1a1a;
            --white: #ffffff;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Outfit', sans-serif; }

        body {
            background: linear-gradient(135deg, #000000 0%, #1a1a1a 50%, #4a362e 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 20px;
        }

        .auth-card {
            background: rgba(20, 20, 20, 0.7);
            backdrop-filter: blur(25px);
            width: 100%;
            max-width: 650px; /* Wider for more fields */
            padding: 45px 35px;
            border-radius: 40px;
            box-shadow: 0 30px 70px rgba(0, 0, 0, 0.5);
            border: 1px solid rgba(212, 175, 55, 0.3);
            color: white;
        }

        .section-title {
            font-size: 14px;
            font-weight: 800;
            color: var(--primary);
            text-transform: uppercase;
            letter-spacing: 1px;
            margin: 25px 0 15px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .section-title::after { content: ''; flex: 1; height: 1px; background: rgba(212, 175, 55, 0.2); }

        .grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
        @media (max-width: 600px) { .grid-2 { grid-template-columns: 1fr; } }

        .form-group { margin-bottom: 20px; }
        .form-group label {
            display: block;
            font-size: 11px;
            font-weight: 700;
            margin-bottom: 8px;
            color: rgba(255,255,255,0.7);
            text-transform: uppercase;
            padding-left: 5px;
        }

        .input-wrapper { position: relative; display: flex; align-items: center; }
        .input-wrapper i { position: absolute; left: 16px; color: var(--primary); font-size: 16px; }
        .input-wrapper input, .input-wrapper textarea {
            width: 100%;
            padding: 14px 15px 14px 45px;
            border-radius: 15px;
            border: 1px solid rgba(255,255,255,0.1);
            background: rgba(255,255,255,0.05);
            font-size: 14px;
            color: #fff;
            outline: none;
            transition: 0.3s;
        }
        .input-wrapper input:focus, .input-wrapper textarea:focus { border-color: var(--primary); background: rgba(255,255,255,0.1); }

        /* File Upload Styling */
        .file-upload {
            position: relative;
            background: rgba(255,255,255,0.03);
            border: 1px dashed rgba(212, 175, 55, 0.3);
            border-radius: 15px;
            padding: 15px;
            text-align: center;
            transition: 0.3s;
            cursor: pointer;
        }
        .file-upload:hover { background: rgba(212, 175, 55, 0.05); border-color: var(--primary); }
        .file-upload i { font-size: 20px; color: var(--primary); margin-bottom: 5px; display: block; }
        .file-upload span { font-size: 10px; color: rgba(255,255,255,0.5); font-weight: 600; }
        .file-upload input { position: absolute; top: 0; left: 0; width: 100%; height: 100%; opacity: 0; cursor: pointer; }

        .btn-continue {
            width: 100%;
            padding: 18px;
            background: var(--primary);
            color: #000;
            border: none;
            border-radius: 18px;
            font-size: 16px;
            font-weight: 800;
            cursor: pointer;
            margin-top: 30px;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            transition: 0.3s;
        }
        .btn-continue:hover { transform: translateY(-3px); box-shadow: 0 15px 30px rgba(212, 175, 55, 0.3); }

        h1 { font-size: 28px; font-weight: 800; text-align: center; margin-bottom: 5px; }
        p.subtitle { text-align: center; opacity: 0.6; font-size: 14px; margin-bottom: 10px; }

        .progress-dots { display: flex; justify-content: center; gap: 8px; margin-bottom: 30px; }
        .dot { width: 8px; height: 8px; border-radius: 50%; background: rgba(255,255,255,0.2); }
        .dot.active { background: var(--primary); box-shadow: 0 0 10px var(--primary); }
    </style>
</head>
<body>

    <div class="auth-card">
        <div class="progress-dots">
            <div class="dot active"></div>
            <div class="dot"></div>
        </div>
        <h1>Personal Profile</h1>
        <p class="subtitle">Complete your identity for verification</p>

        @if($errors->any())
            <div style="padding: 12px; background: rgba(239, 68, 68, 0.2); border: 1px solid #ef4444; color: #fecaca; border-radius: 15px; margin-bottom: 20px; font-size: 12px;">
                <i class="fa-solid fa-circle-exclamation" style="margin-right: 5px;"></i> {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('driver.onboarding.personal.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <!-- Basic Contact -->
            <div class="section-title"><i class="fa-solid fa-address-book"></i> Contact Details</div>
            <div class="grid-2">
                <div class="form-group">
                    <label>Phone Number</label>
                    <div class="input-wrapper">
                        <i class="fa-solid fa-phone"></i>
                        <input type="tel" name="phone_no" placeholder="09xxxxxxxxx" value="{{ old('phone_no', $driver->phone_no) }}" required>
                    </div>
                </div>
                <div class="form-group">
                    <label>Emergency Contact</label>
                    <div class="input-wrapper">
                        <i class="fa-solid fa-heart-pulse"></i>
                        <input type="tel" name="emergency_contact_no" placeholder="Emergency Contact" required>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label>Current Address</label>
                <div class="input-wrapper">
                    <i class="fa-solid fa-location-dot"></i>
                    <input type="text" name="address" placeholder="Full Address (Township, City)" required>
                </div>
            </div>

            <!-- Identity Docs -->
            <div class="section-title"><i class="fa-solid fa-id-card"></i> Identity Documents</div>
            <div class="grid-2">
                <div class="form-group">
                    <label>License Number</label>
                    <div class="input-wrapper">
                        <i class="fa-solid fa-id-badge"></i>
                        <input type="text" name="license_no" placeholder="B/12345/24" required>
                    </div>
                </div>
                <div class="form-group">
                    <label>NRIC Number</label>
                    <div class="input-wrapper">
                        <i class="fa-solid fa-passport"></i>
                        <input type="text" name="identity_card_no" placeholder="12/MAMANA(N)123456" required>
                    </div>
                </div>
            </div>

            <!-- Photo Uploads Grid -->
            <div class="grid-2" style="margin-top: 10px;">
                <div class="form-group">
                    <label>Profile Picture</label>
                    <div class="file-upload">
                        <i class="fa-solid fa-user-circle"></i>
                        <span>Upload Profile Photo</span>
                        <input type="file" name="profile_picture" accept="image/*" required>
                    </div>
                </div>
                <div class="form-group">
                    <label>License (Front)</label>
                    <div class="file-upload">
                        <i class="fa-solid fa-camera"></i>
                        <span>License Front Side</span>
                        <input type="file" name="license_photo" accept="image/*" required>
                    </div>
                </div>
                <div class="form-group">
                    <label>License (Back)</label>
                    <div class="file-upload">
                        <i class="fa-solid fa-camera"></i>
                        <span>License Back Side</span>
                        <input type="file" name="license_photo_back" accept="image/*" required>
                    </div>
                </div>
                <div class="form-group">
                    <label>NRIC (Front)</label>
                    <div class="file-upload">
                        <i class="fa-solid fa-id-card-clip"></i>
                        <span>NRIC Front Side</span>
                        <input type="file" name="nric_photo" accept="image/*" required>
                    </div>
                </div>
                <div class="form-group">
                    <label>NRIC (Back)</label>
                    <div class="file-upload">
                        <i class="fa-solid fa-id-card-clip"></i>
                        <span>NRIC Back Side</span>
                        <input type="file" name="nric_photo_back" accept="image/*" required>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn-continue">Next: Vehicle Assets <i class="fa-solid fa-arrow-right" style="margin-left: 10px;"></i></button>
        </form>
    </div>

</body>
</html>

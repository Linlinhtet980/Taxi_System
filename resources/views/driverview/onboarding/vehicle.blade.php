<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vehicle Details - Luxury Driver</title>
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
            max-width: 700px;
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

        .grid-3 { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 15px; }
        .grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
        @media (max-width: 600px) { 
            .grid-3, .grid-2 { grid-template-columns: 1fr; }
        }

        .form-group { margin-bottom: 18px; }
        .form-group label {
            display: block;
            font-size: 11px;
            font-weight: 700;
            margin-bottom: 8px;
            color: rgba(255,255,255,0.7);
            text-transform: uppercase;
        }

        .input-wrapper { position: relative; display: flex; align-items: center; }
        .input-wrapper i { position: absolute; left: 16px; color: var(--primary); font-size: 16px; }
        .input-wrapper input, .input-wrapper select {
            width: 100%;
            padding: 14px 15px 14px 45px;
            border-radius: 15px;
            border: 1px solid rgba(255,255,255,0.1);
            background: rgba(255,255,255,0.05);
            font-size: 14px;
            color: #fff;
            outline: none;
            transition: 0.3s;
            appearance: none;
        }
        .input-wrapper input:focus { border-color: var(--primary); background: rgba(255,255,255,0.1); }

        /* File Upload */
        .file-upload {
            position: relative;
            background: rgba(255,255,255,0.03);
            border: 1px dashed rgba(212, 175, 55, 0.3);
            border-radius: 15px;
            padding: 15px;
            text-align: center;
            cursor: pointer;
        }
        .file-upload i { font-size: 18px; color: var(--primary); display: block; margin-bottom: 4px; }
        .file-upload span { font-size: 10px; color: rgba(255,255,255,0.5); font-weight: 600; }
        .file-upload input { position: absolute; top: 0; left: 0; width: 100%; height: 100%; opacity: 0; cursor: pointer; }

        .btn-complete {
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
        .btn-complete:hover { transform: translateY(-3px); box-shadow: 0 15px 30px rgba(212, 175, 55, 0.3); }

        h1 { font-size: 28px; font-weight: 800; text-align: center; margin-bottom: 5px; }
        p.subtitle { text-align: center; opacity: 0.6; font-size: 14px; margin-bottom: 10px; }

        .progress-dots { display: flex; justify-content: center; gap: 8px; margin-bottom: 30px; }
        .dot { width: 8px; height: 8px; border-radius: 50%; background: var(--primary); opacity: 0.3; }
        .dot.active { opacity: 1; box-shadow: 0 0 10px var(--primary); }
    </style>
</head>
<body>

    <div class="auth-card">
        <div class="progress-dots">
            <div class="dot active"></div>
            <div class="dot active"></div>
        </div>
        <h1>Vehicle Information</h1>
        <p class="subtitle">Assign your luxury assets to the fleet</p>

        @if($errors->any())
            <div style="padding: 12px; background: rgba(239, 68, 68, 0.2); border: 1px solid #ef4444; color: #fecaca; border-radius: 15px; margin-bottom: 20px; font-size: 12px;">
                <i class="fa-solid fa-circle-exclamation" style="margin-right: 5px;"></i> {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('driver.onboarding.vehicle.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <!-- Basic Specs -->
            <div class="section-title"><i class="fa-solid fa-car-side"></i> Vehicle Specs</div>
            <div class="grid-3">
                <div class="form-group">
                    <label>Brand</label>
                    <div class="input-wrapper">
                        <i class="fa-solid fa-copyright"></i>
                        <input type="text" name="brand" placeholder="Toyota / Honda" required>
                    </div>
                </div>
                <div class="form-group">
                    <label>Model</label>
                    <div class="input-wrapper">
                        <i class="fa-solid fa-car"></i>
                        <input type="text" name="model" placeholder="Belta / Fit" required>
                    </div>
                </div>
                <div class="form-group">
                    <label>Plate Number</label>
                    <div class="input-wrapper">
                        <i class="fa-solid fa-hashtag"></i>
                        <input type="text" name="license_plate" placeholder="YGN 1A-1234" required>
                    </div>
                </div>
            </div>

            <div class="grid-3">
                <div class="form-group">
                    <label>Type</label>
                    <div class="input-wrapper">
                        <i class="fa-solid fa-layer-group"></i>
                        <select name="vehicle_type" required>
                            <option value="Sedan">Sedan</option>
                            <option value="SUV">SUV</option>
                            <option value="Luxury">Luxury</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label>Color</label>
                    <div class="input-wrapper">
                        <i class="fa-solid fa-palette"></i>
                        <input type="text" name="color" placeholder="White / Black" required>
                    </div>
                </div>
                <div class="form-group">
                    <label>Build Year</label>
                    <div class="input-wrapper">
                        <i class="fa-solid fa-calendar-days"></i>
                        <input type="number" name="year" placeholder="2020" required>
                    </div>
                </div>
            </div>

            <!-- Maintenance -->
            <div class="section-title"><i class="fa-solid fa-wrench"></i> Maintenance & Health</div>
            <div class="grid-3">
                <div class="form-group">
                    <label>Last Maintenance</label>
                    <div class="input-wrapper">
                        <i class="fa-solid fa-calendar-check"></i>
                        <input type="date" name="last_maintenance_at" required>
                    </div>
                </div>
                <div class="form-group">
                    <label>Next Maintenance</label>
                    <div class="input-wrapper">
                        <i class="fa-solid fa-calendar-plus"></i>
                        <input type="date" name="next_maintenance_at" required>
                    </div>
                </div>
                <div class="form-group">
                    <label>Current Mileage (km)</label>
                    <div class="input-wrapper">
                        <i class="fa-solid fa-gauge-high"></i>
                        <input type="number" name="mileage" placeholder="50000" required>
                    </div>
                </div>
            </div>

            <!-- Photos -->
            <div class="section-title"><i class="fa-solid fa-images"></i> Vehicle Gallery</div>
            <div class="grid-3">
                <div class="form-group">
                    <label>Main Overview</label>
                    <div class="file-upload">
                        <i class="fa-solid fa-image"></i>
                        <span>Side View</span>
                        <input type="file" name="vehicle_photo" accept="image/*" required>
                    </div>
                </div>
                <div class="form-group">
                    <label>Front View</label>
                    <div class="file-upload">
                        <i class="fa-solid fa-camera"></i>
                        <span>Front Side</span>
                        <input type="file" name="front_photo" accept="image/*" required>
                    </div>
                </div>
                <div class="form-group">
                    <label>Back View</label>
                    <div class="file-upload">
                        <i class="fa-solid fa-camera"></i>
                        <span>Back Side</span>
                        <input type="file" name="back_photo" accept="image/*" required>
                    </div>
                </div>
                <div class="form-group">
                    <label>Left Side</label>
                    <div class="file-upload">
                        <i class="fa-solid fa-camera-rotate"></i>
                        <span>Left Side</span>
                        <input type="file" name="left_side_photo" accept="image/*" required>
                    </div>
                </div>
                <div class="form-group">
                    <label>Right Side</label>
                    <div class="file-upload">
                        <i class="fa-solid fa-camera-rotate"></i>
                        <span>Right Side</span>
                        <input type="file" name="right_side_photo" accept="image/*" required>
                    </div>
                </div>
                <div class="form-group">
                    <label>Interior</label>
                    <div class="file-upload">
                        <i class="fa-solid fa-couch"></i>
                        <span>Inside Cabin</span>
                        <input type="file" name="interior_photo" accept="image/*" required>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn-complete">Finalize Onboarding <i class="fa-solid fa-circle-check" style="margin-left: 10px;"></i></button>
        </form>
    </div>

</body>
</html>

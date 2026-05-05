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

    <div class="split-container">
        <!-- Left Side: Illustration Area -->
        <div class="illustration-side">
            <div class="brand-badge">
                <i class="fa-solid fa-taxi"></i>
                <span>TAXI SERVICE</span>
            </div>
            <div class="illustration-content">
                <div class="illustration-box">
                    <img src="https://img.freepik.com/free-vector/yellow-taxi-car-cab-with-driver-character_107791-16834.jpg" alt="Luxury Taxi">
                </div>
                <div class="illustration-text">
                    <h2>Vehicle Assets</h2>
                    <p>Register your luxury vehicle to our fleet. Provide clear photos to attract more premium passengers.</p>
                </div>
            </div>
        </div>

        <!-- Right Side: Form Area -->
        <div class="form-side">
            <div class="step-progress-wrapper">
                <div class="auth-card animate-fade">
                    <div class="auth-header" style="text-align: center; margin-bottom: 30px;">
                        <div class="vehicle-icon-header">
                            <i class="fa-solid fa-car-rear"></i>
                        </div>
                        <h1>Vehicle Details</h1>
                        <p>Step 3: Fleet Assets & Gallery</p>
                    </div>

                    @if($errors->any())
                        <div class="error-badge">
                            <i class="fa-solid fa-circle-exclamation"></i> {{ $errors->first() }}
                        </div>
                    @endif

                    <form action="{{ route('driver.onboarding.vehicle.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <!-- Basic Specs -->
                        <div class="section-divider">Vehicle Specifications</div>
                        <div class="grid-2">
                            <div class="form-group">
                                <label>Brand & Model</label>
                                <div class="input-wrapper">
                                    <i class="fa-solid fa-car-side"></i>
                                    <input type="text" name="brand" placeholder="Toyota Camry" required>
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
                                    <input type="text" name="color" placeholder="Black" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Year</label>
                                <div class="input-wrapper">
                                    <input type="number" name="year" placeholder="2022" required>
                                </div>
                            </div>
                        </div>

                        <!-- Maintenance -->
                        <div class="section-divider">Maintenance Status</div>
                        <div class="grid-2">
                            <div class="form-group">
                                <label>Last Maintenance</label>
                                <div class="input-wrapper">
                                    <i class="fa-solid fa-calendar-check"></i>
                                    <input type="date" name="last_maintenance_at" required>
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
                        <div class="section-divider">Vehicle Gallery</div>
                        <div class="grid-3">
                            <div class="form-group">
                                <label>Main View</label>
                                <div class="file-drop-area">
                                    <i class="fa-solid fa-image"></i>
                                    <span>Side</span>
                                    <input type="file" name="vehicle_photo" accept="image/*" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Front View</label>
                                <div class="file-drop-area">
                                    <i class="fa-solid fa-camera"></i>
                                    <span>Front</span>
                                    <input type="file" name="front_photo" accept="image/*" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Interior</label>
                                <div class="file-drop-area">
                                    <i class="fa-solid fa-couch"></i>
                                    <span>Inside</span>
                                    <input type="file" name="interior_photo" accept="image/*" required>
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn-complete">Finalize Application <i class="fa-solid fa-circle-check"></i></button>
                    </form>
                </div>

                <!-- Vertical Progress Indicator (Minimalist on Right) -->
                <div class="vertical-progress">
                    <div class="progress-line">
                        <div class="line-fill" style="height: 100%;"></div>
                        <div class="progress-step active" style="top: 0;">
                            <i class="fa-solid fa-check" style="font-size: 8px; color: #000;"></i>
                        </div>
                        <div class="progress-step active" style="top: 50%;">
                            <i class="fa-solid fa-check" style="font-size: 8px; color: #000;"></i>
                        </div>
                        <div class="progress-step active" style="top: 100%;"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        :root {
            --primary: #D4AF37;
            --primary-light: rgba(212, 175, 55, 0.15);
            --bg-dark: #0a0a0a;
            --card-bg: rgba(20, 20, 20, 0.8);
            --text-dim: #94a3b8;
        }

        body {
            background-color: var(--bg-dark);
            min-height: 100vh;
            overflow-x: hidden;
            color: white;
            margin: 0;
        }

        .split-container {
            display: flex;
            min-height: 100vh;
            width: 100%;
        }

        /* Illustration Side */
        .illustration-side {
            flex: 1;
            background: linear-gradient(135deg, #111 0%, #1a1a1a 100%);
            display: flex;
            flex-direction: column;
            padding: 40px;
            position: sticky;
            top: 0;
            height: 100vh;
            border-right: 1px solid rgba(212, 175, 55, 0.1);
        }

        .brand-badge {
            display: flex;
            align-items: center;
            gap: 12px;
            font-weight: 900;
            letter-spacing: 2px;
            color: var(--primary);
        }

        .illustration-content {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
        }

        .illustration-box {
            width: 80%;
            max-width: 400px;
            margin-bottom: 40px;
            filter: drop-shadow(0 20px 50px rgba(0,0,0,0.5));
        }

        .illustration-box img {
            width: 100%;
            border-radius: 30px;
        }

        .illustration-text h2 {
            font-size: 2rem;
            font-weight: 800;
            margin-bottom: 15px;
            background: linear-gradient(to right, #fff, var(--primary));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .illustration-text p { color: var(--text-dim); max-width: 400px; line-height: 1.6; font-size: 0.9rem; }

        /* Form Side */
        .form-side {
            flex: 1.5;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 60px 40px;
            background: radial-gradient(circle at top right, var(--primary-light), transparent);
        }

        .step-progress-wrapper {
            display: flex;
            align-items: center;
            gap: 40px;
            width: 100%;
            max-width: 800px;
        }

        .auth-card {
            flex: 1;
            background: var(--card-bg);
            backdrop-filter: blur(20px);
            padding: 40px;
            border-radius: 35px;
            border: 1px solid rgba(255,255,255,0.05);
            box-shadow: 0 25px 50px rgba(0,0,0,0.4);
        }

        .vehicle-icon-header {
            width: 60px;
            height: 60px;
            background: var(--primary-light);
            border: 1px solid var(--primary);
            border-radius: 18px;
            margin: 0 auto 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary);
            font-size: 24px;
        }

        .auth-header h1 { font-size: 1.8rem; margin-bottom: 5px; font-weight: 800; }
        .auth-header p { color: var(--primary); font-size: 0.8rem; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; }

        .grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
        .grid-3 { display: grid; grid-template-columns: repeat(3, 1fr); gap: 15px; }
        @media (max-width: 650px) { .grid-3, .grid-2 { grid-template-columns: 1fr; } }

        .section-divider {
            margin: 30px 0 20px;
            font-size: 0.75rem;
            font-weight: 800;
            color: var(--text-dim);
            text-transform: uppercase;
            letter-spacing: 2px;
            display: flex;
            align-items: center;
            gap: 15px;
        }
        .section-divider::after { content: ''; flex: 1; height: 1px; background: rgba(255,255,255,0.05); }

        .form-group { margin-bottom: 18px; text-align: left; }
        .form-group label { display: block; font-size: 0.7rem; font-weight: 700; color: var(--text-dim); margin-bottom: 6px; text-transform: uppercase; }

        .input-wrapper { position: relative; display: flex; align-items: center; }
        .input-wrapper i { position: absolute; left: 15px; color: var(--primary); font-size: 1rem; }
        .input-wrapper input, .input-wrapper select {
            width: 100%;
            background: rgba(255,255,255,0.03);
            border: 1px solid rgba(255,255,255,0.1);
            padding: 12px 15px 12px 42px;
            border-radius: 12px;
            color: white;
            font-size: 0.85rem;
            outline: none;
            transition: 0.3s;
        }
        .input-wrapper input:focus, .input-wrapper select:focus { border-color: var(--primary); background: rgba(255,255,255,0.06); }
        .input-wrapper select { padding-left: 15px; }

        .file-drop-area {
            position: relative;
            background: rgba(255,255,255,0.03);
            border: 1px dashed rgba(212, 175, 55, 0.4);
            border-radius: 12px;
            padding: 12px;
            text-align: center;
            cursor: pointer;
        }
        .file-drop-area i { font-size: 18px; color: var(--primary); display: block; margin-bottom: 4px; }
        .file-drop-area span { font-size: 9px; color: var(--text-dim); font-weight: 700; text-transform: uppercase; }
        .file-drop-area input { position: absolute; top: 0; left: 0; width: 100%; height: 100%; opacity: 0; cursor: pointer; }

        .btn-complete {
            width: 100%;
            background: var(--primary);
            color: #000;
            border: none;
            padding: 18px;
            border-radius: 15px;
            font-weight: 900;
            font-size: 1rem;
            cursor: pointer;
            transition: 0.3s;
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 12px;
            margin-top: 25px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .btn-complete:hover { transform: translateY(-3px); box-shadow: 0 15px 30px rgba(212, 175, 55, 0.3); }

        .error-badge {
            padding: 10px;
            border-radius: 12px;
            background: rgba(239, 68, 68, 0.1);
            border: 1px solid rgba(239, 68, 68, 0.2);
            color: #fca5a5;
            font-size: 0.75rem;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        /* Vertical Progress */
        .vertical-progress { height: 300px; width: 2px; position: relative; }
        .progress-line { height: 100%; width: 100%; background: rgba(255,255,255,0.1); position: relative; border-radius: 2px; }
        .line-fill { position: absolute; top: 0; left: 0; width: 100%; background: var(--primary); box-shadow: 0 0 10px var(--primary); transition: 0.5s; }
        .progress-step { position: absolute; left: 50%; transform: translate(-50%, -50%); width: 16px; height: 16px; background: #222; border: 2px solid rgba(255,255,255,0.2); border-radius: 50%; z-index: 2; display: flex; align-items: center; justify-content: center; }
        .progress-step.active { background: var(--primary); border-color: var(--primary); box-shadow: 0 0 15px var(--primary); }
        .step-label { position: absolute; right: 30px; text-align: right; white-space: nowrap; color: var(--text-dim); font-size: 0.75rem; font-weight: 800; text-transform: uppercase; letter-spacing: 1px; }
        .progress-step.active .step-label { color: white; }

        @media (max-width: 1100px) {
            .illustration-side { display: none; }
            .vertical-progress { display: none; }
            .form-side { background: var(--bg-dark); }
        }

        .animate-fade { animation: fadeIn 0.8s ease-out; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
    </style>
</body>

</body>
</html>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Driver Registration - Taxi Luxury</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #D4AF37;
            --primary-dark: #B8860B;
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
            padding: 20px;
            position: relative;
        }

        .auth-card {
            background: rgba(20, 20, 20, 0.7);
            backdrop-filter: blur(25px);
            width: 100%;
            max-width: 400px;
            padding: 40px 30px;
            border-radius: 40px;
            box-shadow: 0 30px 70px rgba(0, 0, 0, 0.5);
            z-index: 1;
            position: relative;
            border: 1px solid rgba(212, 175, 55, 0.3);
            color: white;
        }

        .logo-area {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            margin: 0 auto 20px;
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 10px 20px rgba(212, 175, 55, 0.3);
        }
        .logo-area i { font-size: 26px; color: white; }

        h1 { font-size: 24px; font-weight: 800; text-align: center; color: #fff; margin-bottom: 5px; }
        p.subtitle { text-align: center; color: #fff; opacity: 0.6; font-size: 13px; margin-bottom: 30px; }

        .form-group { margin-bottom: 18px; }
        .form-group label {
            display: block;
            font-size: 11px;
            font-weight: 800;
            margin-bottom: 6px;
            color: var(--primary);
            text-transform: uppercase;
            padding-left: 5px;
        }

        .input-wrapper { position: relative; display: flex; align-items: center; }
        .input-wrapper i { position: absolute; left: 16px; color: var(--primary); font-size: 17px; }
        .input-wrapper input {
            width: 100%;
            padding: 14px 15px 14px 48px;
            border-radius: 16px;
            border: 1px solid rgba(255,255,255,0.1);
            background: rgba(255,255,255,0.05);
            font-size: 14px;
            font-weight: 600;
            color: #fff;
            outline: none;
            transition: 0.3s;
        }
        .input-wrapper input:focus { border-color: var(--primary); background: rgba(255,255,255,0.1); }

        .btn-register {
            width: 100%;
            padding: 18px;
            background: var(--primary);
            color: #000;
            border: none;
            border-radius: 16px;
            font-size: 15px;
            font-weight: 800;
            cursor: pointer;
            margin-top: 10px;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: 0.3s;
        }
        .btn-register:hover { 
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(212, 175, 55, 0.4);
        }

        .footer-text {
            margin-top: 25px;
            font-size: 13px;
            text-align: center;
            color: #fff;
            opacity: 0.6;
        }
        .footer-text a { color: var(--primary); text-decoration: none; font-weight: 800; }
    </style>
</head>
<body>

    <div class="auth-card">
        <div class="logo-area">
            <i class="fa-solid fa-crown"></i>
        </div>
        <h1>Become a Partner</h1>
        <p class="subtitle">Enter your details to start your journey</p>

        @if($errors->any())
            <div style="padding: 10px; border-radius: 12px; background: #FEE2E2; color: #991B1B; font-size: 12px; margin-bottom: 15px; display: flex; align-items: center; gap: 8px;">
                <i class="fa-solid fa-circle-exclamation"></i> {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('driver.register.initial.submit') }}" method="POST">
            @csrf
            <div class="form-group">
                <label>Full Name</label>
                <div class="input-wrapper">
                    <i class="fa-solid fa-user"></i>
                    <input type="text" name="full_name" placeholder="Driver Name" value="{{ old('full_name') }}" required>
                </div>
            </div>

            <div class="form-group">
                <label>Email Address</label>
                <div class="input-wrapper">
                    <i class="fa-solid fa-envelope"></i>
                    <input type="email" name="email" placeholder="email@luxury.com" value="{{ old('email') }}" required>
                </div>
            </div>

            <div class="form-group">
                <label>Password</label>
                <div class="input-wrapper">
                    <i class="fa-solid fa-lock"></i>
                    <input type="password" name="password" id="password" placeholder="••••••••" required>
                    <i class="fa-solid fa-eye-slash toggle-pass" data-target="password" style="position: absolute; right: 18px; left: auto; cursor: pointer; color: var(--primary); opacity: 0.6;"></i>
                </div>
            </div>

            <div class="form-group">
                <label>Confirm Password</label>
                <div class="input-wrapper">
                    <i class="fa-solid fa-shield-check"></i>
                    <input type="password" name="password_confirmation" id="password_confirmation" placeholder="••••••••" required>
                    <i class="fa-solid fa-eye-slash toggle-pass" data-target="password_confirmation" style="position: absolute; right: 18px; left: auto; cursor: pointer; color: var(--primary); opacity: 0.6;"></i>
                </div>
            </div>

            <button type="submit" class="btn-register">Apply Now</button>
        </form>

        <div class="footer-text">
            Already elite? <a href="{{ route('view.login.driver') }}">Sign In</a>
        </div>
    </div>

    <script>
        document.querySelectorAll('.toggle-pass').forEach(icon => {
            icon.addEventListener('click', function() {
                const targetId = this.getAttribute('data-target');
                const input = document.getElementById(targetId);
                const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
                input.setAttribute('type', type);
                this.classList.toggle('fa-eye');
                this.classList.toggle('fa-eye-slash');
            });
        });
    </script>

</body>
</html>

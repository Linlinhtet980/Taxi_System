<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Driver Login - Taxi Luxury</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #D4AF37; /* Gold */
            --primary-dark: #B8860B;
            --text-dark: #1a1a1a;
            --bg-dark: #0f0f0f;
            --white: #ffffff;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Outfit', sans-serif; }

        body {
            background: linear-gradient(135deg, #000000 0%, #1a1a1a 50%, #4a362e 100%);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            position: relative;
            overflow: hidden;
        }

        /* Luxury Gold Glows */
        body::before {
            content: '';
            position: absolute;
            width: 400px;
            height: 400px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(212, 175, 55, 0.1) 0%, transparent 70%);
            top: -100px;
            right: -100px;
        }

        .auth-card {
            background: rgba(20, 20, 20, 0.7);
            backdrop-filter: blur(25px);
            width: 100%;
            max-width: 380px;
            padding: 45px 30px;
            border-radius: 40px;
            box-shadow: 0 30px 70px rgba(0, 0, 0, 0.5);
            z-index: 1;
            position: relative;
            text-align: center;
            border: 1px solid rgba(212, 175, 55, 0.3);
            color: white;
        }

        .logo-area {
            width: 65px;
            height: 65px;
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            margin: 0 auto 24px;
            border-radius: 22px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 10px 20px rgba(212, 175, 55, 0.3);
        }
        .logo-area i { font-size: 28px; color: white; }

        h1 { font-size: 26px; font-weight: 800; color: #fff; margin-bottom: 5px; }
        p.subtitle { color: #fff; opacity: 0.6; font-size: 14px; margin-bottom: 35px; }

        .form-group { text-align: left; margin-bottom: 22px; }
        .form-group label {
            display: block;
            font-size: 12px;
            font-weight: 800;
            margin-bottom: 8px;
            color: var(--primary);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding-left: 5px;
        }

        .input-wrapper { position: relative; display: flex; align-items: center; }
        .input-wrapper i { position: absolute; left: 18px; color: var(--primary); font-size: 18px; }
        .input-wrapper input {
            width: 100%;
            padding: 16px 20px 16px 52px;
            border-radius: 18px;
            border: 1px solid rgba(255,255,255,0.1);
            background: rgba(255,255,255,0.05);
            font-size: 15px;
            font-weight: 600;
            color: #fff;
            outline: none;
            transition: 0.3s;
        }
        .input-wrapper input:focus {
            background: rgba(255,255,255,0.1);
            border-color: var(--primary);
            box-shadow: 0 0 15px rgba(212, 175, 55, 0.1);
        }

        .btn-signin {
            width: 100%;
            padding: 18px;
            background: var(--primary);
            color: #000;
            border: none;
            border-radius: 18px;
            font-size: 16px;
            font-weight: 800;
            cursor: pointer;
            margin-top: 10px;
            transition: 0.3s;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .btn-signin:hover { 
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(212, 175, 55, 0.4);
        }

        .footer-text {
            margin-top: 30px;
            font-size: 14px;
            color: #fff;
            opacity: 0.6;
        }
        .footer-text a { color: var(--primary); text-decoration: none; font-weight: 800; }
    </style>
</head>
<body>

    <div class="auth-card">
        <div class="logo-area">
            <i class="fa-solid fa-car-side"></i>
        </div>
        <h1>Luxury Driver</h1>
        <p class="subtitle">Enter the gold standard of driving</p>

        <form action="{{ route('driver.login.submit') }}" method="POST">
            @csrf
            <div class="form-group">
                <label>Email Access</label>
                <div class="input-wrapper">
                    <i class="fa-solid fa-envelope"></i>
                    <input type="email" name="email" placeholder="luxury@taxi.com" value="{{ old('email') }}" required>
                </div>
            </div>

            <div class="form-group">
                <label>Secure Password</label>
                <div class="input-wrapper">
                    <i class="fa-solid fa-shield-halved"></i>
                    <input type="password" name="password" id="password" placeholder="••••••••" required>
                    <i class="fa-solid fa-eye-slash" id="togglePassword" style="position: absolute; right: 18px; left: auto; cursor: pointer; color: var(--primary); opacity: 0.6;"></i>
                </div>
            </div>

            <button type="submit" class="btn-signin">Authorize & Start</button>
        </form>

        <div class="footer-text">
            Not a partner? <a href="{{ route('view.register.driver') }}">Join Luxury Fleet</a>
        </div>
    </div>

    <script>
        const togglePassword = document.querySelector('#togglePassword');
        const password = document.querySelector('#password');

        togglePassword.addEventListener('click', function (e) {
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);
            this.classList.toggle('fa-eye');
            this.classList.toggle('fa-eye-slash');
        });
    </script>

</body>
</html>

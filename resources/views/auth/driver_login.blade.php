<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Driver Login - Taxi Luxury</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/root/theme.css') }}">
    <script>
        const savedTheme = localStorage.getItem('taxi_theme') || 'dark';
        document.documentElement.setAttribute('data-theme', savedTheme);
    </script>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Outfit', sans-serif; }

        body {
            background-color: var(--bg-main);
            height: 100vh;
            overflow: hidden;
            color: var(--text-main);
        }

        .split-container {
            display: flex;
            height: 100vh;
            width: 100%;
        }

        /* Illustration Side */
        .illustration-side {
            flex: 1;
            background: var(--bg-gradient-sidebar, linear-gradient(135deg, #111 0%, #1a1a1a 100%));
            display: flex;
            flex-direction: column;
            padding: 40px;
            position: relative;
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
            max-width: 450px;
            margin-bottom: 40px;
            filter: drop-shadow(0 20px 50px rgba(0,0,0,0.5));
            animation: float 6s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-20px); }
        }

        .illustration-box img {
            width: 100%;
            border-radius: 30px;
        }

        .illustration-text h2 {
            font-size: 2.5rem;
            font-weight: 800;
            margin-bottom: 15px;
            background: linear-gradient(to right, #fff, var(--primary));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .illustration-text p {
            color: var(--text-dim);
            max-width: 400px;
            line-height: 1.6;
        }

        /* Form Side */
        .form-side {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px;
            background: radial-gradient(circle at top right, var(--primary-light), transparent);
        }

        .auth-card {
            width: 100%;
            max-width: 420px;
            background: var(--card-bg);
            backdrop-filter: blur(20px);
            padding: 40px;
            border-radius: 30px;
            border: 1px solid var(--card-border);
            box-shadow: var(--card-shadow);
        }

        .auth-tabs {
            display: flex;
            border-bottom: 1px solid var(--card-border);
            margin-bottom: 30px;
        }

        .tab-link {
            flex: 1;
            text-align: center;
            padding: 15px;
            text-decoration: none;
            color: var(--text-dim);
            font-weight: 700;
            font-size: 0.9rem;
            transition: 0.3s;
            border-bottom: 2px solid transparent;
        }

        .tab-link.active {
            color: var(--primary);
            border-bottom-color: var(--primary);
        }

        .auth-header h1 { font-size: 1.8rem; margin-bottom: 8px; font-weight: 800; color: var(--text-main); }
        .auth-header p { color: var(--text-dim); font-size: 0.9rem; margin-bottom: 30px; }

        .form-group { margin-bottom: 20px; text-align: left; }
        .form-group label { display: block; font-size: 0.8rem; font-weight: 700; color: var(--text-dim); margin-bottom: 8px; text-transform: uppercase; }

        .input-wrapper { position: relative; display: flex; align-items: center; }
        .input-wrapper i:not(.toggle-pass) { position: absolute; left: 15px; color: var(--primary); font-size: 1.1rem; }
        .input-wrapper input {
            width: 100%;
            background: var(--input-bg);
            border: 1px solid var(--card-border);
            padding: 14px 15px 14px 45px;
            border-radius: 12px;
            color: var(--text-main);
            font-size: 0.95rem;
            outline: none;
            transition: 0.3s;
        }

        .input-wrapper input:focus { border-color: var(--primary); background: var(--input-focus-bg); }
        .input-wrapper input::placeholder { color: var(--input-placeholder); }

        /* Hide browser default password eye icon */
        input::-ms-reveal,
        input::-ms-clear,
        input::-webkit-contacts-auto-fill-button,
        input::-webkit-credentials-auto-fill-button {
            display: none !important;
        }

        .toggle-pass { position: absolute; right: 15px; cursor: pointer; color: var(--text-dim); font-size: 1rem; z-index: 10; }

        .form-options {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
            font-size: 0.85rem;
        }

        .remember-me { display: flex; align-items: center; gap: 8px; cursor: pointer; color: var(--text-dim); }
        .forgot-link { color: var(--primary); text-decoration: none; font-weight: 700; }

        .btn-signin {
            width: 100%;
            background: var(--primary);
            color: var(--bg-main);
            border: none;
            padding: 16px;
            border-radius: 12px;
            font-weight: 900;
            font-size: 1rem;
            cursor: pointer;
            transition: 0.3s;
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 10px;
        }

        .btn-signin:hover { transform: translateY(-3px); box-shadow: 0 10px 20px rgba(212, 175, 55, 0.3); }

        .social-divider {
            position: relative;
            text-align: center;
            margin: 30px 0;
        }
        .social-divider::before { content: ''; position: absolute; left: 0; top: 50%; width: 100%; height: 1px; background: var(--card-border); }
        .social-divider span { position: relative; background: var(--card-bg); color: var(--text-dim); font-size: 0.8rem; padding: 0 15px; }

        .social-login { display: flex; gap: 15px; }
        .btn-social {
            flex: 1;
            background: rgba(255,255,255,0.03);
            border: 1px solid var(--card-border);
            color: var(--text-main);
            padding: 12px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            cursor: pointer;
            font-weight: 600;
            font-size: 0.9rem;
            transition: 0.3s;
        }
        .btn-social:hover { background: rgba(255,255,255,0.06); }
        .btn-social img { width: 18px; }

        @media (max-width: 992px) {
            .illustration-side { display: none; }
            .form-side { background: var(--bg-dark); }
        }

        .animate-fade { animation: fadeIn 0.8s ease-out; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
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
                    <h2>Drive with Excellence</h2>
                    <p>Join the most premium taxi network in the city and elevate your professional journey.</p>
                </div>
            </div>
        </div>

        <!-- Right Side: Auth Form Area -->
        <div class="form-side">
            <div class="auth-card animate-fade">
                <div class="auth-tabs">
                    <a href="{{ route('view.login.driver') }}" class="tab-link active">Login</a>
                    <a href="{{ route('view.register.driver') }}" class="tab-link">Register</a>
                </div>

                <div class="auth-header">
                    <h1>Welcome Back</h1>
                    <p>Enter your credentials to access your dashboard</p>
                </div>

                <form action="{{ route('driver.login.submit') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label>Email Address</label>
                        <div class="input-wrapper">
                            <i class="fa-solid fa-envelope"></i>
                            <input type="email" name="email" placeholder="driver@luxurytaxi.com" value="{{ old('email') }}" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Password</label>
                        <div class="input-wrapper">
                            <i class="fa-solid fa-lock"></i>
                            <input type="password" name="password" id="password" placeholder="••••••••" required>
                            <i class="fa-solid fa-eye-slash toggle-pass" id="togglePassword"></i>
                        </div>
                    </div>

                    <div class="form-options">
                        <label class="remember-me">
                            <input type="checkbox" name="remember"> <span>Remember me</span>
                        </label>
                        <a href="#" class="forgot-link">Forgot Password?</a>
                    </div>

                    <button type="submit" class="btn-signin">Sign In <i class="fa-solid fa-arrow-right-to-bracket"></i></button>
                </form>

                <div class="social-divider">
                    <span>Or continue with</span>
                </div>

                <div class="social-login">
                    <button class="btn-social">
                        <img src="https://cdn-icons-png.flaticon.com/512/2991/2991148.png" alt="Google">
                        Google
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        const togglePassword = document.querySelector('#togglePassword');
        const password = document.querySelector('#password');

        togglePassword.addEventListener('click', function () {
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);
            this.classList.toggle('fa-eye');
            this.classList.toggle('fa-eye-slash');
        });
    </script>
</body>
</html>
</body>
</html>

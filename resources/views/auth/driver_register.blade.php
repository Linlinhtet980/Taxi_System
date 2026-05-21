<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Driver Registration - Taxi Luxury</title>
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
            font-size: 2rem;
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
            font-size: 0.9rem;
        }

        /* Form Side */
        .form-side {
            flex: 1.2;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px;
            background: radial-gradient(circle at top right, var(--primary-light), transparent);
        }

        .step-progress-wrapper {
            display: flex;
            align-items: center;
            gap: 40px;
            width: 100%;
            max-width: 650px;
        }

        .auth-card {
            flex: 1;
            background: var(--card-bg);
            backdrop-filter: blur(20px);
            padding: 35px;
            border-radius: 30px;
            border: 1px solid var(--card-border);
            box-shadow: var(--card-shadow);
        }

        .auth-tabs {
            display: flex;
            border-bottom: 1px solid var(--card-border);
            margin-bottom: 25px;
        }

        .tab-link {
            flex: 1;
            text-align: center;
            padding: 12px;
            text-decoration: none;
            color: var(--text-dim);
            font-weight: 700;
            font-size: 0.85rem;
            transition: 0.3s;
            border-bottom: 2px solid transparent;
        }

        .tab-link.active {
            color: var(--primary);
            border-bottom-color: var(--primary);
        }

        .auth-header h1 { font-size: 1.6rem; margin-bottom: 5px; font-weight: 800; color: var(--text-main); }
        .auth-header p { color: var(--text-dim); font-size: 0.85rem; margin-bottom: 25px; }

        .error-badge {
            padding: 10px;
            border-radius: 12px;
            background: var(--danger-light);
            border: 1px solid var(--danger);
            color: var(--danger);
            font-size: 0.75rem;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .form-group { margin-bottom: 15px; text-align: left; }
        .form-group label { display: block; font-size: 0.75rem; font-weight: 700; color: var(--text-dim); margin-bottom: 6px; text-transform: uppercase; }

        .input-wrapper { position: relative; display: flex; align-items: center; }
        .input-wrapper i:not(.toggle-pass) { position: absolute; left: 15px; color: var(--primary); font-size: 1rem; }
        .input-wrapper input {
            width: 100%;
            background: var(--input-bg);
            border: 1px solid var(--card-border);
            padding: 12px 15px 12px 42px;
            border-radius: 12px;
            color: var(--text-main);
            font-size: 0.9rem;
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

        .step-label { position: absolute; right: 30px; text-align: right; white-space: nowrap; color: var(--text-dim); font-size: 0.75rem; font-weight: 800; text-transform: uppercase; letter-spacing: 1px; }

        .toggle-pass { position: absolute; right: 15px; cursor: pointer; color: var(--text-dim); font-size: 1rem; z-index: 10; }

        .form-options {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin: 15px 0;
            font-size: 0.85rem;
        }

        .remember-me {
            display: flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
            color: var(--text-dim);
            transition: 0.3s;
        }

        .remember-me:hover {
            color: var(--primary);
        }

        .remember-me input {
            width: auto !important;
            margin: 0 !important;
            cursor: pointer;
            accent-color: var(--primary);
        }

        .btn-register {
            width: 100%;
            background: var(--primary);
            color: var(--bg-main);
            border: none;
            padding: 16px;
            border-radius: 12px;
            font-weight: 900;
            font-size: 0.95rem;
            cursor: pointer;
            transition: 0.3s;
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 10px;
            margin-top: 10px;
        }

        .btn-register:hover { transform: translateY(-3px); box-shadow: 0 10px 20px rgba(212, 175, 55, 0.3); }

        /* Vertical Progress */
        .vertical-progress {
            height: 250px;
            width: 2px;
            position: relative;
        }

        .progress-line {
            height: 100%;
            width: 100%;
            background: var(--card-border);
            position: relative;
            border-radius: 2px;
        }

        .line-fill {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            background: var(--primary);
            box-shadow: 0 0 10px var(--primary);
            transition: 0.5s;
        }

        .progress-step {
            position: absolute;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 12px;
            height: 12px;
            background: var(--bg-main);
            border: 2px solid var(--card-border);
            border-radius: 50%;
            z-index: 2;
        }

        .progress-step.active {
            background: var(--primary);
            border-color: var(--primary);
            box-shadow: 0 0 15px var(--primary);
        }

        .step-label {
            position: absolute;
            right: 25px;
            text-align: right;
            white-space: nowrap;
            color: var(--text-dim);
            font-size: 0.7rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .progress-step.active .step-label {
            color: var(--text-main);
        }

        @media (max-width: 1100px) {
            .illustration-side { display: none; }
            .vertical-progress { display: none; }
            .form-side { background: var(--bg-main); }
            .step-progress-wrapper { max-width: 420px; }
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
                    <h2>Your Premium Journey Starts Here</h2>
                    <p>Register as a professional driver and gain access to the city's most elite passenger network.</p>
                </div>
            </div>
        </div>

        <!-- Right Side: Auth Form Area -->
        <div class="form-side">
            <div class="step-progress-wrapper">
                <div class="auth-card animate-fade">
                    <div class="auth-tabs">
                        <a href="{{ route('view.login.driver') }}" class="tab-link">Login</a>
                        <a href="{{ route('view.register.driver') }}" class="tab-link active">Register</a>
                    </div>

                    <div class="auth-header">
                        <h1>Create Account</h1>
                        <p>Fill in the details to set up your driver profile</p>
                    </div>

                    @if($errors->any())
                        <div class="error-badge">
                            <i class="fa-solid fa-circle-exclamation"></i> {{ $errors->first() }}
                        </div>
                    @endif

                    <form action="{{ route('driver.register.initial.submit') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label>Full Name</label>
                            <div class="input-wrapper">
                                <i class="fa-solid fa-user"></i>
                                <input type="text" name="full_name" id="full_name" placeholder="Enter your full name" value="{{ old('full_name') }}" required pattern="[a-zA-Z\s]+" title="Name should only contain English letters and spaces." oninput="this.value = this.value.replace(/[^a-zA-Z\s]/g, '')">
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Email Address</label>
                            <div class="input-wrapper">
                                <i class="fa-solid fa-envelope"></i>
                                <input type="email" name="email" id="email" placeholder="driver@luxurytaxi.com" value="{{ old('email') }}" required pattern="[a-zA-Z0-9._%+-]+@gmail\.com" title="Please enter a valid @gmail.com address.">
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Password</label>
                            <div class="input-wrapper">
                                <i class="fa-solid fa-lock"></i>
                                <input type="password" name="password" id="password" placeholder="••••••••" required minlength="6">
                                <i class="fa-solid fa-eye-slash toggle-pass" data-target="password"></i>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Confirm Password</label>
                            <div class="input-wrapper">
                                <i class="fa-solid fa-shield-check"></i>
                                <input type="password" name="password_confirmation" id="password_confirmation" placeholder="••••••••" required minlength="6">
                                <i class="fa-solid fa-eye-slash toggle-pass" data-target="password_confirmation"></i>
                            </div>
                        </div>

                        <div class="form-options">
                            <label class="remember-me">
                                <input type="checkbox" name="remember"> <span>Remember my session</span>
                            </label>
                        </div>

                        <button type="submit" class="btn-register">Next: Personal Info <i class="fa-solid fa-arrow-right"></i></button>
                    </form>
                </div>

                <!-- Vertical Progress Indicator (Minimalist on Right) -->
                <div class="vertical-progress">
                    <div class="progress-line">
                        <div class="line-fill" style="height: 33%;"></div>
                        <div class="progress-step active" style="top: 0;"></div>
                        <div class="progress-step" style="top: 50%;"></div>
                        <div class="progress-step" style="top: 100%;"></div>
                    </div>
                </div>
            </div>
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

        // Live validation for Driver Registration
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('form');
            const password = document.getElementById('password');
            const confirmPassword = document.getElementById('password_confirmation');
            const emailInput = document.getElementById('email');

            const passwordError = document.createElement('p');
            passwordError.style.color = '#dc3545'; // Danger color compatible with theme
            passwordError.style.fontSize = '0.8rem';
            passwordError.style.marginTop = '5px';
            passwordError.style.display = 'none';
            passwordError.textContent = 'Passwords do not match.';
            confirmPassword.parentNode.insertAdjacentElement('afterend', passwordError);

            const emailError = document.createElement('p');
            emailError.style.color = '#dc3545';
            emailError.style.fontSize = '0.8rem';
            emailError.style.marginTop = '5px';
            emailError.style.display = 'none';
            emailError.textContent = 'Email must be a valid @gmail.com address.';
            emailInput.parentNode.insertAdjacentElement('afterend', emailError);

            function checkPasswords() {
                if (password.value && confirmPassword.value && password.value !== confirmPassword.value) {
                    passwordError.style.display = 'block';
                    return false;
                } else {
                    passwordError.style.display = 'none';
                    return true;
                }
            }

            function validateEmail() {
                const val = emailInput.value.trim();
                if (val && !val.endsWith('@gmail.com')) {
                    emailError.style.display = 'block';
                    return false;
                } else {
                    emailError.style.display = 'none';
                    return true;
                }
            }

            password.addEventListener('input', checkPasswords);
            confirmPassword.addEventListener('input', checkPasswords);
            emailInput.addEventListener('input', validateEmail);

            form.addEventListener('submit', function(e) {
                const isPassValid = checkPasswords();
                const isEmailValid = validateEmail();
                if (!isPassValid || !isEmailValid) {
                    e.preventDefault();
                    if (!isEmailValid) emailInput.focus();
                    else if (!isPassValid) confirmPassword.focus();
                }
            });
        });
    </script>
</body>
</html>

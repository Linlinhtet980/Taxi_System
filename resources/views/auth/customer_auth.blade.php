<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Taxi - Login & Register</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/auth/customer_auth.css') }}">
</head>
<body>

    <div class="container" id="container">
        <!-- Sign Up (Register) -->
        <div class="form-container sign-up">
            <form action="{{ route('customer.register.submit') }}" method="POST">
                @csrf
                <i class="fa-solid fa-car-side auth-icon"></i>
                <h1>Join Premium Taxi</h1>
                <span>Enter your details to register</span>
                
                @if ($errors->any())
                    <div style="color: red; font-size: 0.8rem; margin-bottom: 10px;">
                        @foreach ($errors->all() as $error)
                            <p>{{ $error }}</p>
                        @endforeach
                    </div>
                @endif

                <input type="text" name="name" placeholder="Full Name" required value="{{ old('name') }}">
                <input type="email" name="email" placeholder="Email Address" required value="{{ old('email') }}">
                <div class="phone-input-group">
                    <input type="text" value="+95" class="country-code" readonly>
                    <input type="tel" name="phone" placeholder="Phone Number (9...)" required value="{{ old('phone') }}">
                </div>
                <div class="password-wrapper">
                    <input type="password" name="password" placeholder="Set Password" required id="reg-password">
                    <i class="fa-solid fa-eye toggle-password" onclick="togglePassword('reg-password', this)"></i>
                </div>
                <div class="password-wrapper">
                    <input type="password" name="password_confirmation" placeholder="Confirm Password" required id="reg-password-confirm">
                    <i class="fa-solid fa-eye toggle-password" onclick="togglePassword('reg-password-confirm', this)"></i>
                </div>
                <button type="submit" style="width: 100%; padding: 14px; border-radius: 12px; font-size: 14px;">Register Now</button>
                <p class="mobile-toggle-link">Already have an account? <a href="javascript:void(0)" onclick="document.getElementById('container').classList.remove('active');">Sign In</a></p>
            </form>
        </div>

        <!-- Sign In (Login) -->
        <div class="form-container sign-in">
            <form action="{{ route('customer.login.submit') }}" method="POST">
                @csrf
                <i class="fa-solid fa-taxi auth-icon"></i>
                <h1>Welcome Back</h1>
                <span>Login to your account</span>
                
                @if (session('error'))
                    <div style="color: red; font-size: 0.8rem; margin-bottom: 10px;">{{ session('error') }}</div>
                @endif
                
                <input type="email" name="email" placeholder="Email Address" required value="{{ old('email') }}">
                <div class="password-wrapper">
                    <input type="password" name="password" placeholder="Password" required id="login-password">
                    <i class="fa-solid fa-eye toggle-password" onclick="togglePassword('login-password', this)"></i>
                </div>
                <a href="#" style="align-self: flex-end;">Forgot password?</a>
                <button type="submit" style="width: 100%; padding: 14px; border-radius: 12px; font-size: 14px;">Sign In</button>
                <p class="mobile-toggle-link">Don't have an account? <a href="javascript:void(0)" onclick="document.getElementById('container').classList.add('active');">Register Now</a></p>
            </form>
        </div>

        <!-- Toggle Overlay -->
        <div class="toggle-container">
            <div class="toggle">
                <div class="toggle-panel toggle-left">
                    <h1>Rider Login</h1>
                    <p>Already have an account? Sign in to book your next premium ride.</p>
                    <button class="ghost" id="login">Back to Sign In</button>
                </div>
                <div class="toggle-panel toggle-right">
                    <h1>New Rider?</h1>
                    <p>Register today to experience the most premium taxi service in the city.</p>
                    <button class="ghost" id="register">Create Account</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        const container = document.getElementById('container');
        const registerBtn = document.getElementById('register');
        const loginBtn = document.getElementById('login');

        // Initial mode handling
        @if(isset($mode) && $mode == 'register')
            container.classList.add("active");
        @endif

        registerBtn.addEventListener('click', () => {
            container.classList.add("active");
        });

        loginBtn.addEventListener('click', () => {
            container.classList.remove("active");
        });

        function togglePassword(inputId, icon) {
            const input = document.getElementById(inputId);
            if (input.type === "password") {
                input.type = "text";
                icon.classList.remove("fa-eye");
                icon.classList.add("fa-eye-slash");
            } else {
                input.type = "password";
                icon.classList.remove("fa-eye-slash");
                icon.classList.add("fa-eye");
            }
        }
    </script>
</body>
</html>

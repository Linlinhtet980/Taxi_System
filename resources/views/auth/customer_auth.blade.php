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

                <input type="text" name="name" placeholder="Full Name" required value="{{ old('name') }}" pattern="[a-zA-Z\s]+" title="Name should only contain English letters and spaces." oninput="this.value = this.value.replace(/[^a-zA-Z\s]/g, '')">
                <input type="email" name="email" id="reg-email" placeholder="Email Address" required value="{{ old('email') }}" pattern="[a-zA-Z0-9._%+-]+@gmail\.com" title="Please enter a valid @gmail.com address.">
                <div class="phone-input-group">
                    <input type="text" value="+95" class="country-code" readonly>
                    <input type="tel" name="phone" placeholder="Phone Number (9...)" required value="{{ old('phone') }}" minlength="7" maxlength="11" pattern="[0-9]+" title="Phone number should contain only digits (7 to 11 digits, excluding +95)." oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                </div>
                <div class="password-wrapper">
                    <input type="password" name="password" placeholder="Set Password (Min 6 characters)" required id="reg-password" minlength="6">
                    <i class="fa-solid fa-eye-slash toggle-password" onclick="togglePassword('reg-password', this)"></i>
                </div>
                <div class="password-wrapper">
                    <input type="password" name="password_confirmation" placeholder="Confirm Password (Min 6 characters)" required id="reg-password-confirm" minlength="6">
                    <i class="fa-solid fa-eye-slash toggle-password" onclick="togglePassword('reg-password-confirm', this)"></i>
                </div>
                <div class="form-options">
                    <label class="remember-me">
                        <input type="checkbox" name="remember"> <span>Remember my device</span>
                    </label>
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
                @if ($errors->any())
                    <div style="color: red; font-size: 0.8rem; margin-bottom: 10px;">
                        @foreach ($errors->all() as $error)
                            <p>{{ $error }}</p>
                        @endforeach
                    </div>
                @endif
                
                <input type="email" name="email" id="login-email" placeholder="Email Address" required value="{{ old('email') }}" pattern="[a-zA-Z0-9._%+-]+@gmail\.com" title="Please enter a valid @gmail.com address.">
                <div class="password-wrapper">
                    <input type="password" name="password" placeholder="Password" required id="login-password" minlength="6">
                    <i class="fa-solid fa-eye-slash toggle-password" onclick="togglePassword('login-password', this)"></i>
                </div>
                <div class="form-options">
                    <label class="remember-me">
                        <input type="checkbox" name="remember"> <span>Remember me</span>
                    </label>
                    <a href="#" class="forgot-link">Forgot password?</a>
                </div>
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
                icon.classList.remove("fa-eye-slash");
                icon.classList.add("fa-eye");
            } else {
                input.type = "password";
                icon.classList.remove("fa-eye");
                icon.classList.add("fa-eye-slash");
            }
        }

        // Live Password Mismatch & Email @gmail.com Validation
        document.addEventListener('DOMContentLoaded', function() {
            // === Register Form ===
            const registerForm = document.querySelector('.sign-up form');
            const regPassword = document.getElementById('reg-password');
            const regConfirmPassword = document.getElementById('reg-password-confirm');
            const regEmail = document.getElementById('reg-email');
            
            const passwordError = document.createElement('p');
            passwordError.style.color = 'red';
            passwordError.style.fontSize = '0.8rem';
            passwordError.style.marginTop = '5px';
            passwordError.style.marginBottom = '10px';
            passwordError.style.display = 'none';
            passwordError.textContent = 'Passwords do not match.';
            regConfirmPassword.parentNode.insertAdjacentElement('afterend', passwordError);

            const regEmailError = document.createElement('p');
            regEmailError.style.color = 'red';
            regEmailError.style.fontSize = '0.8rem';
            regEmailError.style.marginTop = '5px';
            regEmailError.style.marginBottom = '10px';
            regEmailError.style.display = 'none';
            regEmailError.textContent = 'Email must be a valid @gmail.com address.';
            regEmail.insertAdjacentElement('afterend', regEmailError);

            function checkPasswords() {
                if (regPassword.value && regConfirmPassword.value && regPassword.value !== regConfirmPassword.value) {
                    passwordError.style.display = 'block';
                    return false;
                } else {
                    passwordError.style.display = 'none';
                    return true;
                }
            }

            function validateRegEmail() {
                const val = regEmail.value.trim();
                if (val && !val.endsWith('@gmail.com')) {
                    regEmailError.style.display = 'block';
                    return false;
                } else {
                    regEmailError.style.display = 'none';
                    return true;
                }
            }

            regPassword.addEventListener('input', checkPasswords);
            regConfirmPassword.addEventListener('input', checkPasswords);
            regEmail.addEventListener('input', validateRegEmail);

            registerForm.addEventListener('submit', function(e) {
                const isPassValid = checkPasswords();
                const isEmailValid = validateRegEmail();
                if (!isPassValid || !isEmailValid) {
                    e.preventDefault();
                    if (!isEmailValid) regEmail.focus();
                    else if (!isPassValid) regConfirmPassword.focus();
                }
            });

            // === Login Form ===
            const loginForm = document.querySelector('.sign-in form');
            const loginEmail = document.getElementById('login-email');
            
            const loginEmailError = document.createElement('p');
            loginEmailError.style.color = 'red';
            loginEmailError.style.fontSize = '0.8rem';
            loginEmailError.style.marginTop = '5px';
            loginEmailError.style.marginBottom = '10px';
            loginEmailError.style.display = 'none';
            loginEmailError.textContent = 'Email must be a valid @gmail.com address.';
            loginEmail.insertAdjacentElement('afterend', loginEmailError);

            function validateLoginEmail() {
                const val = loginEmail.value.trim();
                if (val && !val.endsWith('@gmail.com')) {
                    loginEmailError.style.display = 'block';
                    return false;
                } else {
                    loginEmailError.style.display = 'none';
                    return true;
                }
            }

            loginEmail.addEventListener('input', validateLoginEmail);

            loginForm.addEventListener('submit', function(e) {
                const isEmailValid = validateLoginEmail();
                if (!isEmailValid) {
                    e.preventDefault();
                    loginEmail.focus();
                }
            });
        });
    </script>
</body>
</html>

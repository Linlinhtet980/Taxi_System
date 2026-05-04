<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Taxi - Customer Login</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/auth/customer_login.css') }}">
</head>
<body>
    <div class="login-container">
        <div class="logo-section">
            <i class="fa-solid fa-taxi logo-icon"></i>
            <h2 class="logo-text">PREMIUM TAXI</h2>
        </div>

        <div class="login-card">
            <h1>Sign In</h1>
            <p>Welcome! Enter your phone number to start your premium ride experience.</p>

            <div class="input-group">
                <span class="input-label">Phone Number</span>
                <div class="input-wrapper">
                    <i class="fa-solid fa-mobile-screen-button"></i>
                    <input type="tel" placeholder="09xxxxxxx">
                </div>
            </div>

            <button class="btn-signin">Continue</button>

            <div class="social-section">
                <div class="social-title">Connect with</div>
                <div class="social-icons">
                    <div class="icon-btn"><i class="fa-brands fa-google"></i></div>
                    <div class="icon-btn"><i class="fa-brands fa-facebook-f"></i></div>
                    <div class="icon-btn"><i class="fa-brands fa-apple"></i></div>
                </div>
            </div>
            <div class="footer-text">
                Don't have an account? <a href="{{ route('view.register.customer') }}">Sign up</a>
            </div>
        </div>
    </div>
</body>
</html>

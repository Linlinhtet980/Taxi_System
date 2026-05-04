<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Taxi - Customer Registration</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/auth/customer_register.css') }}">
</head>
<body>
    <div class="reg-container">
        <div class="logo-section">
            <i class="fa-solid fa-user-plus logo-icon"></i>
            <h2 class="logo-text">PREMIUM TAXI</h2>
        </div>

        <div class="reg-card">
            <h1>Create Account</h1>
            <p>Fill in your details to join our premium community.</p>

            <div class="input-group">
                <span class="input-label">Full Name</span>
                <div class="input-wrapper">
                    <i class="fa-solid fa-user"></i>
                    <input type="text" placeholder="John Doe">
                </div>
            </div>

            <div class="input-group">
                <span class="input-label">Phone Number</span>
                <div class="input-wrapper">
                    <i class="fa-solid fa-mobile-screen-button"></i>
                    <input type="tel" placeholder="09xxxxxxx">
                </div>
            </div>

            <div class="input-group">
                <span class="input-label">Email Address</span>
                <div class="input-wrapper">
                    <i class="fa-solid fa-envelope"></i>
                    <input type="email" placeholder="john@example.com">
                </div>
            </div>

            <div class="input-group">
                <span class="input-label">Password</span>
                <div class="input-wrapper">
                    <i class="fa-solid fa-lock"></i>
                    <input type="password" placeholder="••••••••">
                </div>
            </div>

            <button class="btn-register">Create Account</button>

            <div class="footer-text">
                Already have an account? <a href="{{ route('view.login.customer') }}">Sign in</a>
            </div>
        </div>
    </div>
</body>
</html>

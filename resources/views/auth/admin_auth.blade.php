@extends('layout.authlayout')

@section('title', 'Admin Login - Taxi Premium')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/auth/admin_auth.css') }}">
@endsection

@section('content')
<div class="auth-wrapper">
    <div class="auth-card">
        <div class="brand-header">
            <div class="brand-icon">
                <i class="fa-solid fa-crown"></i>
            </div>
            <h1>Admin Portal</h1>
            <p>Sign in to manage the platform</p>
        </div>

        @if ($errors->any())
            <div class="alert-error">
                <i class="fa-solid fa-circle-exclamation"></i>
                <span>{{ $errors->first() }}</span>
            </div>
        @endif

        <form action="{{ route('admin.login.submit') }}" method="POST" id="adminLoginForm">
            @csrf
            
            <div class="form-group">
                <label>Email Address</label>
                <div class="input-container">
                    <i class="fa-solid fa-envelope icon-left"></i>
                    <input type="email" name="email" id="admin-email" placeholder="admin@gmail.com" value="{{ old('email') }}" required autofocus pattern="[a-zA-Z0-9._%+-]+@gmail\.com" title="Please enter a valid @gmail.com address.">
                </div>
            </div>

            <div class="form-group">
                <label>Password</label>
                <div class="input-container">
                    <i class="fa-solid fa-lock icon-left"></i>
                    <input type="password" name="password" id="password" placeholder="••••••••" required minlength="6">
                    <i class="fa-solid fa-eye-slash toggle-password" id="togglePassword"></i>
                </div>
            </div>

            <div class="form-options">
                <label class="remember-me">
                    <input type="checkbox" name="remember" id="remember">
                    <span>Remember my device</span>
                </label>
                <a href="#" class="forgot-link">Recover Access</a>
            </div>

            <button type="submit" class="btn-login">
                Authenticate Securely
                <i class="fa-solid fa-shield-halved"></i>
            </button>
        </form>

        <div class="secure-badge">
            <i class="fa-solid fa-lock"></i>
            End-to-end encrypted connection
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const togglePassword = document.querySelector('#togglePassword');
        const password = document.querySelector('#password');

        if (togglePassword && password) {
            togglePassword.addEventListener('click', function () {
                const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
                password.setAttribute('type', type);
                
                // Toggle the icon
                this.classList.toggle('fa-eye');
                this.classList.toggle('fa-eye-slash');
            });
        }

        // Live Email Validation for Admin Login
        const form = document.getElementById('adminLoginForm');
        const emailInput = document.getElementById('admin-email');

        if (form && emailInput) {
            const emailError = document.createElement('p');
            emailError.style.color = '#dc3545';
            emailError.style.fontSize = '0.75rem';
            emailError.style.marginTop = '5px';
            emailError.style.display = 'none';
            emailError.textContent = 'Email must be a valid @gmail.com address.';
            emailInput.parentNode.insertAdjacentElement('afterend', emailError);

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

            emailInput.addEventListener('input', validateEmail);

            form.addEventListener('submit', function(e) {
                const isEmailValid = validateEmail();
                if (!isEmailValid) {
                    e.preventDefault();
                    emailInput.focus();
                }
            });
        }
    });
</script>
@endsection

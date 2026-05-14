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

        <form action="{{ route('login') }}" method="POST">
            @csrf
            
            <div class="form-group">
                <label>Email Address</label>
                <div class="input-container">
                    <i class="fa-solid fa-envelope icon-left"></i>
                    <input type="email" name="email" placeholder="admin@taxipremium.com" value="{{ old('email') }}" required autofocus>
                </div>
            </div>

            <div class="form-group">
                <label>Password</label>
                <div class="input-container">
                    <i class="fa-solid fa-lock icon-left"></i>
                    <input type="password" name="password" id="password" placeholder="••••••••" required>
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
    });
</script>
@endsection

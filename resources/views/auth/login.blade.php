@extends('layouts.guest')

@section('title', 'User Login')

@section('styles')
@parent
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Space+Grotesk:wght@500;600;700&display=swap" rel="stylesheet">
<style>
    :root {
        /* Dark mode (default) — black canvas */
        --primary: #2563eb;
        --primary-light: #3b82f6;
        --primary-dark: #1d4ed8;
        --accent: #3b82f6;
        --bg-primary: #000000;
        --bg-secondary: #0b0d14;
        --bg-card: #0e1018;
        --text-primary: #f2f5fb;
        --text-secondary: #a1a9b8;
        --text-muted: #6b7280;
        --border-color: #23262f;
        --success: #22c55e;
        --danger: #ef4444;
    }

    @media (prefers-color-scheme: light) {
        :root {
            /* Light mode — white canvas */
            --bg-primary: #ffffff;
            --bg-secondary: #f8fafc;
            --bg-card: #ffffff;
            --text-primary: #0f172a;
            --text-secondary: #475569;
            --text-muted: #94a3b8;
            --border-color: #e5e9f0;
        }
    }

    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        background: var(--bg-primary);
        color: var(--text-primary);
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow-x: hidden;
    }

    /* Background gradient */
    .bg-gradient-overlay {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background:
            radial-gradient(ellipse 80% 50% at 50% -20%, rgba(37, 99, 235, 0.15), transparent),
            radial-gradient(ellipse 60% 40% at 100% 100%, rgba(59, 130, 246, 0.08), transparent);
        pointer-events: none;
        z-index: 0;
    }

    .login-container {
        position: relative;
        z-index: 1;
        width: 100%;
        max-width: 440px;
        padding: 20px;
    }

    /* Logo */
    .logo {
        text-align: center;
        margin-bottom: 32px;
    }

    .logo a {
        display: inline-flex;
        align-items: center;
        gap: 12px;
        font-family: 'Space Grotesk', sans-serif;
        font-size: 1.6rem;
        font-weight: 700;
        color: var(--text-primary);
        text-decoration: none;
        letter-spacing: -0.5px;
    }

    .logo-badge {
        width: 42px; height: 42px; border-radius: 12px; display: grid; place-items: center;
        color: #fff; font-weight: 700;
        background: linear-gradient(135deg, var(--primary), var(--primary-light));
        box-shadow: 0 10px 24px rgba(37, 99, 235, 0.4);
    }

    .logo a span {
        color: var(--primary);
    }

    /* Auth Card */
    .auth-card {
        background: var(--bg-card);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        border: 1px solid var(--border-color);
        border-radius: 20px;
        padding: 36px;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.4);
    }

    /* Header Icon */
    .card-icon {
        width: 64px;
        height: 64px;
        background: rgba(37, 99, 235, 0.15);
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 20px;
    }

    .card-icon i {
        font-size: 28px;
        color: var(--primary);
    }

    .card-title {
        font-family: 'Space Grotesk', sans-serif;
        letter-spacing: -0.02em;
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--text-primary);
        text-align: center;
        margin-bottom: 8px;
    }

    .card-subtitle {
        font-size: 0.95rem;
        color: var(--text-secondary);
        text-align: center;
        margin-bottom: 28px;
        line-height: 1.5;
    }

    /* Alert Messages */
    .alert {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        padding: 14px 16px;
        border-radius: 12px;
        margin-bottom: 20px;
        font-size: 0.9rem;
    }

    .alert-success {
        background: rgba(34, 197, 94, 0.15);
        border: 1px solid rgba(34, 197, 94, 0.3);
        color: var(--success);
    }

    .alert-danger {
        background: rgba(239, 68, 68, 0.15);
        border: 1px solid rgba(239, 68, 68, 0.3);
        color: var(--danger);
    }

    .alert i {
        font-size: 18px;
        flex-shrink: 0;
        margin-top: 2px;
    }

    .alert-content {
        flex: 1;
    }

    .alert ul {
        margin: 0;
        padding-left: 20px;
    }

    .alert-close {
        background: transparent;
        border: none;
        color: inherit;
        opacity: 0.7;
        cursor: pointer;
        padding: 0;
        font-size: 18px;
        transition: opacity 0.2s ease;
    }

    .alert-close:hover {
        opacity: 1;
    }

    /* Form Styles */
    .form-group {
        margin-bottom: 20px;
    }

    .form-label {
        display: block;
        font-size: 0.875rem;
        font-weight: 500;
        color: var(--text-secondary);
        margin-bottom: 8px;
    }

    .input-wrapper {
        position: relative;
    }

    .input-wrapper i {
        position: absolute;
        left: 16px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--text-muted);
        font-size: 16px;
        transition: color 0.2s ease;
    }

    .form-control {
        width: 100%;
        background: var(--bg-primary);
        border: 1px solid var(--border-color);
        border-radius: 12px;
        padding: 14px 16px 14px 48px;
        font-size: 0.95rem;
        color: var(--text-primary);
        transition: all 0.2s ease;
    }

    .form-control:focus {
        outline: none;
        border-color: var(--primary);
        box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.15);
    }

    .form-control:focus + i,
    .input-wrapper:focus-within i {
        color: var(--primary);
    }

    .form-control::placeholder {
        color: var(--text-muted);
    }

    /* Checkbox */
    .form-check {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .form-check-input {
        width: 18px;
        height: 18px;
        background: var(--bg-primary);
        border: 1px solid var(--border-color);
        border-radius: 4px;
        cursor: pointer;
    }

    .form-check-input:checked {
        background-color: var(--primary);
        border-color: var(--primary);
    }

    .form-check-input:focus {
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15);
    }

    .form-check-label {
        font-size: 0.85rem;
        color: var(--text-secondary);
        cursor: pointer;
    }

    /* Submit Button */
    .btn-submit {
        width: 100%;
        background: var(--primary);
        border: none;
        border-radius: 12px;
        padding: 16px 24px;
        font-size: 1rem;
        font-weight: 600;
        color: white;
        cursor: pointer;
        transition: all 0.2s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        margin-top: 8px;
    }

    .btn-submit:hover {
        background: var(--primary-dark);
        transform: translateY(-2px);
        box-shadow: 0 8px 20px -8px rgba(37, 99, 235, 0.5);
    }

    .btn-submit:active {
        transform: translateY(0);
    }

    /* Links */
    .form-link {
        font-size: 0.85rem;
        font-weight: 500;
        color: var(--primary-light);
        text-decoration: none;
        transition: color 0.2s ease;
    }

    .form-link:hover {
        color: var(--accent);
    }

    /* Divider */
    .divider {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin: 24px 0;
    }

    .divider::before,
    .divider::after {
        content: '';
        flex: 1;
        height: 1px;
        background: var(--border-color);
    }

    .divider span {
        font-size: 0.8rem;
        color: var(--text-secondary);
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    /* Social Login Button */
    .btn-social {
        width: 100%;
        background: var(--bg-primary);
        border: 1px solid var(--border-color);
        border-radius: 12px;
        padding: 14px 24px;
        font-size: 0.95rem;
        font-weight: 500;
        color: var(--text-primary);
        cursor: pointer;
        transition: all 0.2s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        text-decoration: none;
    }

    .btn-social:hover {
        border-color: var(--primary);
        color: var(--text-primary);
        transform: translateY(-1px);
    }

    .btn-social i {
        font-size: 18px;
    }

    .social-buttons {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    /* Sign Up Link */
    .signup-link {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        margin-top: 24px;
        padding-top: 24px;
        border-top: 1px solid var(--border-color);
    }

    .signup-link span {
        font-size: 0.9rem;
        color: var(--text-secondary);
    }

    .signup-link a {
        font-size: 0.9rem;
        font-weight: 600;
        color: var(--primary);
        text-decoration: none;
        transition: color 0.2s ease;
    }

    .signup-link a:hover {
        color: var(--primary-light);
    }

    /* Footer */
    .auth-footer {
        text-align: center;
        margin-top: 24px;
        font-size: 0.8rem;
        color: var(--text-muted);
    }

    /* Responsive */
    @media (max-width: 480px) {
        .auth-card {
            padding: 28px 20px;
        }

        .card-title {
            font-size: 1.25rem;
        }
    }
</style>
@endsection

@section('content')
<div class="bg-gradient-overlay"></div>

<div class="login-container">
    <!-- Logo -->
    <div class="logo">
        <a href="/"><span class="logo-badge">Z</span>{{ $settings->site_name ?? 'Zaraex' }}</a>
    </div>

    <!-- Auth Card -->
    <div class="auth-card">
        <!-- Header Icon -->
        <div class="card-icon">
            <i class="fa fa-user"></i>
        </div>

        <h1 class="card-title">Welcome Back</h1>
        <p class="card-subtitle">Sign in to your account to continue</p>

        <!-- Status Message -->
        @if(Session::has('status'))
            <div class="alert alert-danger">
                <i class="fa fa-exclamation-circle"></i>
                <div class="alert-content">{{ session('status') }}</div>
                <button type="button" class="alert-close" onclick="this.parentElement.style.display='none'">
                    <i class="fa fa-times"></i>
                </button>
            </div>
        @endif

        <!-- Error Messages -->
        @if ($errors->any())
            <div class="alert alert-danger">
                <i class="fa fa-exclamation-circle"></i>
                <div class="alert-content">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                <button type="button" class="alert-close" onclick="this.parentElement.style.display='none'">
                    <i class="fa fa-times"></i>
                </button>
            </div>
        @endif

        <!-- Login Form -->
        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="form-group">
                <label class="form-label">Email Address</label>
                <div class="input-wrapper">
                    <input
                        type="email"
                        class="form-control"
                        name="email"
                        value="{{ old('email') }}"
                        placeholder="Enter your email address"
                        required
                        autofocus
                    >
                    <i class="fa fa-envelope"></i>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Password</label>
                <div class="input-wrapper">
                    <input
                        type="password"
                        class="form-control"
                        name="password"
                        placeholder="Enter your password"
                        required
                    >
                    <i class="fa fa-lock"></i>
                </div>
            </div>

            <div class="d-flex justify-content-between align-items-center mb-4">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="remember" name="remember">
                    <label class="form-check-label" for="remember">Remember me</label>
                </div>
                <a href="{{ route('password.request') }}" class="form-link">Forgot password?</a>
            </div>

            <button type="submit" class="btn-submit">
                <i class="fa fa-sign-in-alt"></i>
                Sign In
            </button>
        </form>

        <!-- Social Login (if enabled) / Zaraex SSO (if configured) -->
        @if($settings->enable_social_login == "yes" || config('services.zarex.authorize_url'))
            <div class="divider">
                <span>Or continue with</span>
            </div>

            <div class="social-buttons">
                @if($settings->enable_social_login == "yes")
                    <a href="{{route('social.redirect', ['social' => 'google'])}}" class="btn-social">
                        <i class="fab fa-google" style="color: #DB4437;"></i>
                        Continue with Google
                    </a>
                @endif

                @if(config('services.zarex.authorize_url'))
                    <a href="{{ config('services.zarex.authorize_url') }}" class="btn-social">
                        <i class="fa fa-exchange-alt" style="color: var(--primary);"></i>
                        Continue with Zaraex
                    </a>
                @endif
            </div>
        @endif

        <!-- Sign Up Link -->
        <div class="signup-link">
            <span>Don't have an account?</span>
            <a href="{{ route('register') }}">Sign Up</a>
        </div>
    </div>

    <!-- Footer -->
    <div class="auth-footer">
        &copy; {{ date('Y') }} {{ $settings->site_name ?? 'Zaraex' }}. All rights reserved.
    </div>
</div>
@endsection

@section('scripts')
@parent
@endsection
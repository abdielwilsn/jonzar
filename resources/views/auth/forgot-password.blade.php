@extends('layouts.guest')

@section('title', 'Forgot Password')

@section('styles')
@parent
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<style>
    :root {
        --primary: #2563eb;
        --primary-light: #3b82f6;
        --primary-dark: #1d4ed8;
        --accent: #22d3ee;
        --bg-primary: #0a0a0f;
        --bg-secondary: #12121a;
        --bg-card: rgba(18, 18, 26, 0.9);
        --text-primary: #f8fafc;
        --text-secondary: #94a3b8;
        --text-muted: #7d8ba3;
        --border-color: rgba(37, 99, 235, 0.15);
        --success: #10b981;
        --danger: #ef4444;
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
            radial-gradient(ellipse 60% 40% at 100% 100%, rgba(34, 211, 238, 0.08), transparent);
        pointer-events: none;
        z-index: 0;
    }

    .forgot-password-container {
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
        font-size: 1.75rem;
        font-weight: 700;
        color: var(--text-primary);
        text-decoration: none;
        letter-spacing: -0.5px;
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
        background: rgba(16, 185, 129, 0.15);
        border: 1px solid rgba(16, 185, 129, 0.3);
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

    /* Error Text */
    .error-text {
        display: flex;
        align-items: center;
        gap: 6px;
        margin-top: 8px;
        font-size: 0.8rem;
        color: var(--danger);
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
    }

    .btn-submit:hover {
        background: var(--primary-dark);
        transform: translateY(-2px);
        box-shadow: 0 8px 20px -8px rgba(37, 99, 235, 0.5);
    }

    .btn-submit:active {
        transform: translateY(0);
    }

    /* Back to Login */
    .back-link {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        margin-top: 24px;
        padding-top: 24px;
        border-top: 1px solid var(--border-color);
    }

    .back-link span {
        font-size: 0.9rem;
        color: var(--text-secondary);
    }

    .back-link a {
        font-size: 0.9rem;
        font-weight: 600;
        color: var(--primary);
        text-decoration: none;
        transition: color 0.2s ease;
    }

    .back-link a:hover {
        color: var(--primary-light);
    }

    /* Footer */
    .auth-footer {
        text-align: center;
        margin-top: 24px;
        font-size: 0.8rem;
        color: var(--text-muted);
    }

    /* Security Note */
    .security-note {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        margin-top: 20px;
        padding: 12px;
        background: rgba(37, 99, 235, 0.08);
        border-radius: 8px;
    }

    .security-note i {
        color: var(--primary);
        font-size: 14px;
    }

    .security-note span {
        font-size: 0.8rem;
        color: var(--text-secondary);
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

<div class="forgot-password-container">
    <!-- Logo -->
    <div class="logo">
        <a href="/">{{ $settings->site_name ?? 'Starbiit' }}</a>
    </div>

    <!-- Auth Card -->
    <div class="auth-card">
        <!-- Header Icon -->
        <div class="card-icon">
            <i class="fa fa-lock"></i>
        </div>

        <h1 class="card-title">Forgot Password?</h1>
        <p class="card-subtitle">No worries! Enter your email address and we'll send you a link to reset your password.</p>

        <!-- Success Message -->
        @if (session('status'))
            <div class="alert alert-success">
                <i class="fa fa-check-circle"></i>
                <div class="alert-content">{{ session('status') }}</div>
                <button type="button" class="alert-close" onclick="this.parentElement.style.display='none'">
                    <i class="fa fa-times"></i>
                </button>
            </div>
        @endif

        <!-- Error Message -->
        @if(Session::has('message'))
            <div class="alert alert-danger">
                <i class="fa fa-exclamation-circle"></i>
                <div class="alert-content">{{ Session::get('message') }}</div>
                <button type="button" class="alert-close" onclick="this.parentElement.style.display='none'">
                    <i class="fa fa-times"></i>
                </button>
            </div>
        @endif

        <!-- Form -->
        <form method="POST" action="{{ route('password.email') }}">
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
                @if ($errors->has('email'))
                    <span class="error-text">
                        <i class="fa fa-exclamation-circle"></i>
                        {{ $errors->first('email') }}
                    </span>
                @endif
            </div>

            <button type="submit" class="btn-submit">
                <i class="fa fa-paper-plane"></i>
                Send Reset Link
            </button>
        </form>

        <!-- Security Note -->
        <div class="security-note">
            <i class="fa fa-shield-alt"></i>
            <span>We'll never share your email with anyone else</span>
        </div>

        <!-- Back to Login -->
        <div class="back-link">
            <i class="fa fa-arrow-left" style="color: var(--text-muted); font-size: 12px;"></i>
            <span>Remember your password?</span>
            <a href="{{ route('home') }}">Back to Login</a>
        </div>
    </div>

    <!-- Footer -->
    <div class="auth-footer">
        &copy; {{ date('Y') }} {{ $settings->site_name ?? 'Starbiit' }}. All rights reserved.
    </div>
</div>
@endsection

@section('scripts')
@parent
@endsection

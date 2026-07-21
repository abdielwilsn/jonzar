@extends('layouts.guest')

@section('title', 'Confirm your password')

@section('styles')
    @parent
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #2563eb;
            --primary-light: #3b82f6;
            --primary-dark: #1d4ed8;
            --bg-dark: #0a0a0f;
            --bg-card: rgba(18, 18, 26, 0.95);
            --text-primary: #f8fafc;
            --text-secondary: #94a3b8;
            --text-muted: #7d8ba3;
            --border-color: rgba(37, 99, 235, 0.15);
            --input-bg: #12121a;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background: var(--bg-dark);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
            padding: 20px;
        }

        /* Background gradient */
        body::before {
            content: '';
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

        .auth-container {
            width: 100%;
            max-width: 440px;
            position: relative;
            z-index: 1;
        }

        /* Logo/Brand */
        .auth-brand {
            text-align: center;
            margin-bottom: 32px;
        }

        .auth-brand a {
            font-size: 1.75rem;
            font-weight: 700;
            color: var(--text-primary);
            text-decoration: none;
            letter-spacing: -0.5px;
        }

        .auth-brand a span {
            color: var(--primary-light);
        }

        /* Card */
        .auth-card {
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: 20px;
            padding: 40px;
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.4);
        }

        /* Header */
        .auth-header {
            text-align: center;
            margin-bottom: 32px;
        }

        .auth-icon {
            width: 64px;
            height: 64px;
            border-radius: 16px;
            background: rgba(37, 99, 235, 0.15);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            color: var(--primary-light);
            font-size: 28px;
        }

        .auth-header h1 {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--text-primary);
            margin: 0 0 10px;
        }

        .auth-header p {
            font-size: 0.9rem;
            color: var(--text-secondary);
            margin: 0;
            line-height: 1.5;
        }

        /* Form */
        .auth-form {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .form-label {
            font-size: 0.875rem;
            font-weight: 500;
            color: var(--text-secondary);
        }

        .form-label .required {
            color: #ef4444;
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
            pointer-events: none;
        }

        .form-input {
            width: 100%;
            background: var(--input-bg);
            border: 1px solid var(--border-color);
            border-radius: 12px;
            padding: 14px 16px 14px 48px;
            font-size: 0.95rem;
            color: var(--text-primary);
            font-family: inherit;
            transition: all 0.2s ease;
        }

        .form-input:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15);
        }

        .form-input::placeholder {
            color: var(--text-muted);
        }

        /* Password toggle */
        .password-toggle {
            position: absolute;
            right: 16px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: var(--text-muted);
            cursor: pointer;
            padding: 4px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: color 0.2s ease;
        }

        .password-toggle:hover {
            color: var(--text-secondary);
        }

        /* Error messages */
        .error-list {
            background: rgba(239, 68, 68, 0.1);
            border: 1px solid rgba(239, 68, 68, 0.3);
            border-radius: 10px;
            padding: 14px 16px;
            margin: 0;
            list-style: none;
        }

        .error-list li {
            display: flex;
            align-items: center;
            gap: 8px;
            color: #ef4444;
            font-size: 0.875rem;
        }

        .error-list li::before {
            content: '';
            width: 6px;
            height: 6px;
            background: #ef4444;
            border-radius: 50%;
            flex-shrink: 0;
        }

        /* Submit button */
        .submit-btn {
            width: 100%;
            padding: 16px 24px;
            background: var(--primary);
            border: none;
            border-radius: 12px;
            color: white;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            margin-top: 8px;
        }

        .submit-btn:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 8px 20px -8px rgba(37, 99, 235, 0.5);
        }

        .submit-btn:active {
            transform: translateY(0);
        }

        /* Back link */
        .back-link {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            margin-top: 24px;
            color: var(--text-muted);
            font-size: 0.875rem;
            text-decoration: none;
            transition: color 0.2s ease;
        }

        .back-link:hover {
            color: var(--primary-light);
        }

        /* Security note */
        .security-note {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            margin-top: 24px;
            padding-top: 24px;
            border-top: 1px solid var(--border-color);
            color: var(--text-muted);
            font-size: 0.8rem;
        }

        .security-note i {
            color: #10b981;
        }

        /* Responsive */
        @media (max-width: 480px) {
            .auth-card {
                padding: 28px 24px;
            }

            .auth-header h1 {
                font-size: 1.35rem;
            }
        }
    </style>
@endsection

@section('content')
    <div class="auth-container">
        <!-- Brand -->
        <div class="auth-brand">
            <a href="/">{{ $settings->site_name ?? 'StarBit' }}</a>
        </div>

        <!-- Card -->
        <div class="auth-card">
            <!-- Header -->
            <div class="auth-header">
                <div class="auth-icon">
                    <i class="fa fa-shield-alt"></i>
                </div>
                <h1>Confirm Password</h1>
                <p>This is a secure area. Please confirm your password before continuing.</p>
            </div>

            <!-- Form -->
            <form method="POST" action="{{ route('password.confirm') }}" class="auth-form">
                @csrf

                <!-- Password -->
                <div class="form-group">
                    <label class="form-label">
                        Password <span class="required">*</span>
                    </label>
                    <div class="input-wrapper">
                        <i class="fa fa-lock"></i>
                        <input
                            type="password"
                            id="password"
                            name="password"
                            class="form-input"
                            placeholder="Enter your password"
                            required
                            autocomplete="current-password"
                            autofocus
                        >
                        <button type="button" class="password-toggle" onclick="togglePassword()">
                            <i class="fa fa-eye" id="toggleIcon"></i>
                        </button>
                    </div>
                </div>

                <!-- Errors -->
                @if ($errors->any())
                    <ul class="error-list">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                @endif

                <!-- Submit -->
                <button type="submit" class="submit-btn">
                    <i class="fa fa-check-circle"></i>
                    Confirm Password
                </button>
            </form>

            <!-- Security Note -->
            <div class="security-note">
                <i class="fa fa-lock"></i>
                <span>Your session is encrypted and secure</span>
            </div>
        </div>

        <!-- Back Link -->
        <a href="{{ url()->previous() }}" class="back-link">
            <i class="fa fa-arrow-left"></i>
            Go back
        </a>
    </div>

    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const toggleIcon = document.getElementById('toggleIcon');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
            }
        }
    </script>
@endsection

@section('scripts')
    @parent
@endsection

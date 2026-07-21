<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{$settings->site_name ?? 'Trhayder'}} - Smart Crypto Trading</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #6f42c1;
            --primary-light: #8187f8;
            --primary-dark: #464ee5;
            --accent: #22d3ee;
            --bg-primary: #0a0a0f;
            --bg-secondary: #12121a;
            --bg-card: rgba(18, 18, 26, 0.8);
            --text-primary: #f8fafc;
            --text-secondary: #94a3b8;
            --border-color: rgba(37, 99, 235, 0.15);
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
            overflow-x: hidden;
        }

        /* Subtle background gradient */
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

        /* Navigation */
        .navbar {
            padding: 1.25rem 0;
            background: transparent;
            position: absolute;
            width: 100%;
            z-index: 100;
        }

        .navbar-brand {
            font-weight: 700;
            font-size: 1.5rem;
            color: var(--text-primary) !important;
            letter-spacing: -0.5px;
        }

        .navbar-brand span {
            color: var(--primary-light);
        }

        .nav-link {
            color: var(--text-secondary) !important;
            font-weight: 500;
            font-size: 0.9rem;
            padding: 0.5rem 1rem !important;
            transition: color 0.2s ease;
        }

        .nav-link:hover {
            color: var(--text-primary) !important;
        }

        /* Hero Section */
        .hero {
            min-height: 100vh;
            display: flex;
            align-items: center;
            padding: 120px 0 80px;
            position: relative;
            z-index: 1;
        }

        .hero-title {
            font-size: clamp(2.5rem, 5vw, 3.75rem);
            font-weight: 700;
            line-height: 1.1;
            letter-spacing: -1.5px;
            margin-bottom: 1.5rem;
        }

        .hero-title .highlight {
            background: linear-gradient(135deg, var(--primary-light), var(--accent));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .hero-subtitle {
            font-size: 1.125rem;
            color: var(--text-secondary);
            line-height: 1.7;
            margin-bottom: 2.5rem;
            max-width: 480px;
        }

        /* Stats Row */
        .stats-row {
            display: flex;
            gap: 3rem;
            margin-bottom: 3rem;
        }

        .stat-item {
            text-align: left;
        }

        .stat-value {
            font-size: 1.75rem;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 0.25rem;
        }

        .stat-label {
            font-size: 0.85rem;
            color: var(--text-secondary);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* Trust Badges */
        .trust-badges {
            display: flex;
            align-items: center;
            gap: 1.5rem;
            padding-top: 2rem;
            border-top: 1px solid var(--border-color);
        }

        .badge-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: var(--text-secondary);
            font-size: 0.85rem;
        }

        .badge-icon {
            width: 20px;
            height: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--accent);
        }

        /* Auth Card */
        .auth-card {
            background: var(--bg-card);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid var(--border-color);
            border-radius: 20px;
            padding: 2.5rem;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.4);
        }

        /* Custom Tabs */
        .auth-tabs {
            display: flex;
            background: var(--bg-primary);
            border-radius: 12px;
            padding: 4px;
            margin-bottom: 2rem;
        }

        .auth-tabs .nav-link {
            flex: 1;
            text-align: center;
            border-radius: 10px;
            padding: 0.75rem 1.5rem !important;
            font-weight: 600;
            font-size: 0.9rem;
            color: var(--text-secondary) !important;
            transition: all 0.2s ease;
            border: none;
            background: transparent;
        }

        .auth-tabs .nav-link.active {
            background: var(--primary);
            color: white !important;
        }

        .auth-tabs .nav-link:hover:not(.active) {
            color: var(--text-primary) !important;
        }

        /* Form Styles */
        .form-label {
            font-size: 0.85rem;
            font-weight: 500;
            color: var(--text-secondary);
            margin-bottom: 0.5rem;
        }

        .form-control {
            background: var(--bg-primary);
            border: 1px solid var(--border-color);
            border-radius: 10px;
            padding: 0.875rem 1rem;
            font-size: 0.95rem;
            color: var(--text-primary);
            transition: all 0.2s ease;
        }

        .form-control:focus {
            background: var(--bg-primary);
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15);
            color: var(--text-primary);
        }

        .form-control::placeholder {
            color: #475569;
        }

        /* Buttons */
        .btn-primary-custom {
            background: var(--primary);
            border: none;
            border-radius: 10px;
            padding: 0.875rem 1.5rem;
            font-weight: 600;
            font-size: 0.95rem;
            color: white;
            transition: all 0.2s ease;
            width: 100%;
        }

        .btn-primary-custom:hover {
            background: var(--primary-dark);
            transform: translateY(-1px);
            box-shadow: 0 10px 20px -10px rgba(37, 99, 235, 0.5);
        }

        .btn-secondary-custom {
            background: transparent;
            border: 1px solid var(--border-color);
            border-radius: 10px;
            padding: 0.875rem 1.5rem;
            font-weight: 600;
            font-size: 0.95rem;
            color: var(--text-primary);
            transition: all 0.2s ease;
        }

        .btn-secondary-custom:hover {
            border-color: var(--primary);
            color: var(--primary-light);
        }

        /* Links */
        .form-link {
            color: var(--primary-light);
            text-decoration: none;
            font-size: 0.85rem;
            font-weight: 500;
            transition: color 0.2s ease;
        }

        .form-link:hover {
            color: var(--accent);
        }

        /* Checkbox */
        .form-check-input {
            background-color: var(--bg-primary);
            border-color: var(--border-color);
            border-radius: 4px;
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
        }

        /* Divider */
        .divider {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin: 1.5rem 0;
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

        /* Social Login */
        .social-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.75rem;
            background: var(--bg-primary);
            border: 1px solid var(--border-color);
            border-radius: 10px;
            padding: 0.75rem;
            color: var(--text-primary);
            font-weight: 500;
            font-size: 0.9rem;
            transition: all 0.2s ease;
            text-decoration: none;
        }

        .social-btn:hover {
            border-color: var(--primary);
            color: var(--text-primary);
        }

        /* Responsive */
        @media (max-width: 991px) {
            .hero {
                padding: 100px 0 60px;
            }

            .stats-row {
                gap: 2rem;
            }

            .auth-card {
                margin-top: 3rem;
            }
        }

        @media (max-width: 576px) {
            .stats-row {
                flex-wrap: wrap;
                gap: 1.5rem;
            }

            .stat-item {
                flex: 1 1 40%;
            }

            .trust-badges {
                flex-wrap: wrap;
                gap: 1rem;
            }
        }
    </style>
</head>
<body>

<div class="bg-gradient-overlay"></div>

<!-- Navigation -->
<nav class="navbar">
    <div class="container">
        <a class="navbar-brand" href="#">Tr<span>hay</span>der</a>
        <div class="d-none d-md-flex align-items-center gap-4">
{{--            <a href="#" class="nav-link">Features</a>--}}
{{--            <a href="#" class="nav-link">Pricing</a>--}}
{{--            <a href="#" class="nav-link">Security</a>--}}
{{--            <a href="#" class="nav-link">Support</a>--}}
        </div>
    </div>
</nav>

<div class="container">
    <div class="row hero align-items-center">
        <!-- Left: Hero Content -->
        <div class="col-lg-6">
            <h1 class="hero-title">
                Trade smarter with <span class="highlight">algorithmic precision</span>
            </h1>
            <p class="hero-subtitle">
                Leverage advanced trading algorithms to execute strategies across multiple exchanges. Built for traders who demand performance, security, and control.
            </p>

            <div class="stats-row">
                <div class="stat-item">
                    <div class="stat-value">$2.4B+</div>
                    <div class="stat-label">Trading Volume</div>
                </div>
                <div class="stat-item">
                    <div class="stat-value">150K+</div>
                    <div class="stat-label">Active Traders</div>
                </div>
                <div class="stat-item">
                    <div class="stat-value">99.9%</div>
                    <div class="stat-label">Uptime</div>
                </div>
            </div>

            <div class="trust-badges">
                <div class="badge-item">
                    <div class="badge-icon">
                        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                        </svg>
                    </div>
                    <span>256-bit encryption</span>
                </div>
                <div class="badge-item">
                    <div class="badge-icon">
                        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    {{-- <span>SOC 2 Compliant</span> --}}
                </div>
                <div class="badge-item">
                    <div class="badge-icon">
                        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <span>24/7 Support</span>
                </div>
            </div>
        </div>

        <!-- Right: Auth Card -->
        <div class="col-lg-5 offset-lg-1">
            <div class="auth-card">
                <ul class="nav auth-tabs" id="authTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="login-tab" data-bs-toggle="pill" data-bs-target="#login" type="button">Sign In</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="register-tab" data-bs-toggle="pill" data-bs-target="#register" type="button">Create Account</button>
                    </li>
                </ul>

                <div class="tab-content">
                    <!-- Login Form -->
                    <div class="tab-pane fade show active" id="login">
                        <form action="{{ route('login') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label">Email Address</label>
                                <input type="email" name="email" class="form-control" placeholder="Enter your email address" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Password</label>
                                <input type="password" name="password" class="form-control" placeholder="Enter your password" required>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="remember" name="remember">
                                    <label class="form-check-label" for="remember">Remember me</label>
                                </div>
                                <a href="{{ route('password.request') }}" class="form-link">Forgot password?</a>
                            </div>
                            <button type="submit" class="btn btn-primary-custom">Sign In</button>
                        </form>
                    </div>

                    <!-- Register Form -->
                    <div class="tab-pane fade" id="register">
                        <form action="{{ route('register') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label">Full Name</label>
                                <input type="text" name="name" class="form-control" placeholder="Enter your full name" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Email Address</label>
                                <input type="email" name="email" class="form-control" placeholder="Enter your email" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Username</label>
                                <input type="text" name="username" class="form-control" placeholder="Choose a username">
                            </div>
                              <div class="mb-3">
                                <label class="form-label">Phone Number</label>
                                <input type="text" name="phone" class="form-control" placeholder="+1 (901) 234 5893">
                            </div>
                            <div class="row">
                                <div class="col-6 mb-3">
                                    <label class="form-label">Password</label>
                                    <input type="password" name="password" class="form-control" placeholder="Create password" required>
                                </div>
                                <div class="col-6 mb-3">
                                    <label class="form-label">Confirm</label>
                                    <input type="password" name="password_confirmation" class="form-control" placeholder="Confirm password" required>
                                </div>
                            </div>
                            <div class="form-check mb-4">
                                <input class="form-check-input" type="checkbox" id="terms" required>
                                <label class="form-check-label" for="terms">
                                    I agree to the <a href="#" class="form-link">Terms of Service</a> and <a href="#" class="form-link">Privacy Policy</a>
                                </label>
                            </div>
                            <button type="submit" class="btn btn-primary-custom">Create Account</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

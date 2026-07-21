@extends('layouts.guest')
@section('title', 'Admin Login')
@section('styles')
@parent
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Space+Grotesk:wght@500;600;700&display=swap" rel="stylesheet">
<style>
    :root {
        /* Dark mode (default) — black canvas */
        --primary: #2563eb;
        --primary-light: #3b82f6;
        --primary-dark: #1d4ed8;
        --bg-primary: #000000;
        --bg-card: #0e1018;
        --text-primary: #f2f5fb;
        --text-secondary: #a1a9b8;
        --text-muted: #6b7280;
        --border-color: #23262f;
        --danger: #ef4444;
    }
    @media (prefers-color-scheme: light) {
        :root {
            /* Light mode — white canvas */
            --bg-primary: #ffffff;
            --bg-card: #ffffff;
            --text-primary: #0f172a;
            --text-secondary: #475569;
            --text-muted: #94a3b8;
            --border-color: #e5e9f0;
        }
    }
    * { box-sizing: border-box; }
    body {
        margin: 0; min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 40px 20px;
        background: var(--bg-primary); color: var(--text-primary);
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
    }
    .admin-wrap { width: 100%; max-width: 400px; }
    .admin-logo { display: flex; align-items: center; justify-content: center; gap: 12px; margin-bottom: 28px;
        font-family: 'Space Grotesk', sans-serif; font-size: 1.5rem; font-weight: 700; letter-spacing: -0.02em; color: var(--text-primary); text-decoration: none; }
    .admin-badge { width: 44px; height: 44px; border-radius: 13px; display: grid; place-items: center; color: #fff; font-weight: 700;
        background: linear-gradient(135deg, var(--primary), var(--primary-light)); box-shadow: 0 10px 24px rgba(37, 99, 235, 0.4); }
    .admin-card { background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 20px; padding: 34px;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.35); }
    .admin-title { font-family: 'Space Grotesk', sans-serif; font-size: 1.4rem; font-weight: 700; letter-spacing: -0.02em; text-align: center; margin: 0 0 6px; }
    .admin-sub { text-align: center; color: var(--text-secondary); font-size: 0.92rem; margin: 0 0 26px; }
    .admin-alert { background: rgba(239, 68, 68, 0.15); border: 1px solid rgba(239, 68, 68, 0.3); color: var(--danger);
        padding: 12px 14px; border-radius: 12px; margin-bottom: 20px; font-size: 0.9rem; }
    .fg { margin-bottom: 18px; }
    .fg label { display: block; font-size: 0.875rem; font-weight: 500; color: var(--text-secondary); margin-bottom: 8px; }
    .fg input { width: 100%; background: var(--bg-primary); border: 1px solid var(--border-color); border-radius: 12px;
        padding: 14px 16px; font-size: 0.95rem; color: var(--text-primary); transition: all 0.2s ease; }
    .fg input:focus { outline: none; border-color: var(--primary); box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.15); }
    .fg input::placeholder { color: var(--text-muted); }
    .admin-btn { width: 100%; background: var(--primary); color: #fff; border: none; border-radius: 12px;
        padding: 15px 24px; font-size: 1rem; font-weight: 600; cursor: pointer; transition: all 0.2s ease; }
    .admin-btn:hover { background: var(--primary-dark); transform: translateY(-1px); }
    .admin-foot { text-align: center; color: var(--text-muted); font-size: 0.8rem; margin-top: 22px; }
    .err { color: var(--danger); font-size: 0.8rem; margin-top: 6px; display: block; }
</style>
@endsection

@section('content')
<div class="admin-wrap">
    <a href="/" class="admin-logo"><span class="admin-badge">Z</span>{{ $settings->site_name ?? 'Zaraex' }}</a>

    <div class="admin-card">
        <h1 class="admin-title">Admin Console</h1>
        <p class="admin-sub">Restricted access — authorized staff only.</p>

        @if(Session::has('message'))
            <div class="admin-alert">{{ Session::get('message') }}</div>
        @endif

        <form method="POST" action="{{ route('adminlogin') }}">
            @csrf
            <div class="fg">
                <label>Email</label>
                <input type="email" name="email" value="{{ old('email') }}" placeholder="admin@example.com" required>
                @if ($errors->has('email'))
                    <span class="err">{{ $errors->first('email') }}</span>
                @endif
            </div>

            <div class="fg">
                <label>Password</label>
                <input type="password" name="password" placeholder="Enter your password" required>
                @if ($errors->has('password'))
                    <span class="err">{{ $errors->first('password') }}</span>
                @endif
            </div>

            <button type="submit" class="admin-btn">Sign in to console</button>
        </form>
    </div>

    <p class="admin-foot">&copy; {{ date('Y') }} {{ $settings->site_name ?? 'Zaraex' }}. Protected area — all activity is logged.</p>
</div>
@endsection

@section('scripts')
@parent
@endsection

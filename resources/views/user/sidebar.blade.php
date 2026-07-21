<!-- Sidebar -->
<div class="sidebar sidebar-style-2 sidebar-redesign" data-background-color="{{ $bg }}">
    <div class="sidebar-wrapper scrollbar scrollbar-inner">
        <div class="sidebar-content">

            <!-- User Profile Section -->
            <div class="sidebar-user">
                <div class="user-avatar">
                    <span class="avatar-initials">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</span>
                </div>
                <div class="user-info">
                    <a data-toggle="collapse" href="#userMenu" aria-expanded="true" class="user-name-link">
                        <span class="user-name">{{ Auth::user()->name }}</span>
                        <i class="fa fa-chevron-down user-caret"></i>
                    </a>
                    <span class="user-role">Trader Account</span>
                </div>
                <div class="collapse" id="userMenu">
                    <a href="{{ route('profile') }}" class="user-menu-item">
                        <i class="fa fa-cog"></i>
                        <span>Account Settings</span>
                    </a>
                </div>
            </div>

            <!-- Navigation -->
            <ul class="nav nav-primary sidebar-nav">

                <!-- Dashboard -->
                <li class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <a href="{{ url('/dashboard') }}" class="nav-link">
                        <div class="nav-icon">
                            <i class="fas fa-th-large"></i>
                        </div>
                        <span class="nav-text">Dashboard</span>
                    </a>
                </li>

                <!-- Fund Account (Mobile) -->
                <li class="nav-item d-md-none {{ request()->routeIs('deposits') || request()->routeIs('payment') ? 'active' : '' }}">
                    <a href="{{ url('dashboard/deposits') }}" class="nav-link">
                        <div class="nav-icon">
                            <i class="fa fa-plus-circle"></i>
                        </div>
                        <span class="nav-text">Fund Account</span>
                    </a>
                </li>

                <!-- Withdraw (Mobile) -->
                <li class="nav-item d-md-none {{ request()->routeIs('withdrawalsdeposits') || request()->routeIs('withdrawfunds') ? 'active' : '' }}">
                    <a href="{{ url('dashboard/withdrawals') }}" class="nav-link">
                        <div class="nav-icon">
                            <i class="fa fa-arrow-up"></i>
                        </div>
                        <span class="nav-text">Withdraw</span>
                    </a>
                </li>

                <!-- Section Label -->
                <li class="nav-section">
                    <span class="nav-section-text">Trading</span>
                </li>

                <!-- Trade Now (Dropdown) -->
                <li class="nav-item {{ request()->routeIs('mplans') || request()->routeIs('myplans') ? 'active' : '' }}">
                    <a data-toggle="collapse" href="#tradeMenu" class="nav-link">
                        <div class="nav-icon">
                            <i class="fas fa-chart-line"></i>
                        </div>
                        <span class="nav-text">Trade Now</span>
                        <i class="fa fa-chevron-down nav-arrow"></i>
                    </a>
                    <div class="collapse" id="tradeMenu">
                        <ul class="nav-submenu">
                            <li>
                                <a href="{{ url('dashboard/trading-pairs') }}">
                                    <span>Join a Trade</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ url('dashboard/recent-trades') }}">
                                    <span>Your Recent Trades</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>

                <!-- Trading Record -->
                <li class="nav-item {{ request()->routeIs('tradinghistory') ? 'active' : '' }}">
                    <a href="{{ url('dashboard/recent-trades') }}" class="nav-link">
                        <div class="nav-icon">
                            <i class="fa fa-signal"></i>
                        </div>
                        <span class="nav-text">Trading Record</span>
                    </a>
                </li>

                <!-- Crypto Exchange -->
                @if ($moresettings->use_crypto_feature == 'true')
                    <li class="nav-item {{ request()->routeIs('assetbalance') ? 'active' : '' }}">
                        <a href="{{ route('assetbalance') }}" class="nav-link">
                            <div class="nav-icon">
                                <i class="fab fa-bitcoin"></i>
                            </div>
                            <span class="nav-text">Crypto Exchange</span>
                        </a>
                    </li>
                @endif

                <!-- Subscription Trade -->
                @if ($settings->subscription_service == 'on')
                    <li class="nav-item {{ request()->routeIs('subtrade') ? 'active' : '' }}">
                        <a href="{{ url('dashboard/subtrade') }}" class="nav-link">
                            <div class="nav-icon">
                                <i class="fa fa-layer-group"></i>
                            </div>
                            <span class="nav-text">Subscription Trade</span>
                        </a>
                    </li>
                @endif

                <!-- Section Label -->
                <li class="nav-section">
                    <span class="nav-section-text">Account</span>
                </li>

                <!-- Transaction History -->
                <li class="nav-item {{ request()->routeIs('accounthistory') ? 'active' : '' }}">
                    <a href="{{ url('dashboard/accounthistory') }}" class="nav-link">
                        <div class="nav-icon">
                            <i class="fa fa-history"></i>
                        </div>
                        <span class="nav-text">Transactions</span>
                    </a>
                </li>

                <!-- Refer Users -->
                <li class="nav-item {{ request()->routeIs('referuser') ? 'active' : '' }}">
                    <a href="{{ url('dashboard/referuser') }}" class="nav-link">
                        <div class="nav-icon">
                            <i class="fa fa-user-plus"></i>
                        </div>
                        <span class="nav-text">Refer & Earn</span>
                    </a>
                </li>

                <!-- Referral Earnings -->
                <li class="nav-item {{ request()->routeIs('referral.earnings') ? 'active' : '' }}">
                    <a href="{{ url('dashboard/referral-earnings') }}" class="nav-link">
                        <div class="nav-icon">
                            <i class="fa fa-coins"></i>
                        </div>
                        <span class="nav-text">Referral Earnings</span>
                    </a>
                </li>

                <!-- Support -->
                <li class="nav-item {{ request()->routeIs('support') ? 'active' : '' }}">
                    <a href="{{ url('dashboard/support') }}" class="nav-link">
                        <div class="nav-icon">
                            <i class="fa fa-headset"></i>
                        </div>
                        <span class="nav-text">Support</span>
                    </a>
                </li>

                <!-- Account (Mobile) -->
                <li class="nav-section d-md-none">
                    <span class="nav-section-text">Account</span>
                </li>

                <!-- Account Settings (Mobile) -->
                <li class="nav-item d-md-none {{ request()->routeIs('profile') ? 'active' : '' }}">
                    <a href="{{ route('profile') }}" class="nav-link">
                        <div class="nav-icon">
                            <i class="fa fa-cog"></i>
                        </div>
                        <span class="nav-text">Account Settings</span>
                    </a>
                </li>

                <!-- Logout (Mobile) -->
                <li class="nav-item d-md-none">
                    <a href="{{ route('logout') }}" class="nav-link sidebar-logout"
                       onclick="event.preventDefault(); document.getElementById('sidebar-logout-form').submit();">
                        <div class="nav-icon">
                            <i class="fa fa-sign-out-alt"></i>
                        </div>
                        <span class="nav-text">Logout</span>
                    </a>
                    <form id="sidebar-logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        {{ csrf_field() }}
                    </form>
                </li>

            </ul>
        </div>
    </div>
</div>

<style>
    .sidebar-redesign {
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
    }

    /* Dark theme variables */
    .sidebar-redesign[data-background-color="dark"] {
        --sidebar-bg: #0a0a0f;
        --sidebar-border: rgba(37, 99, 235, 0.1);
        --text-primary: #f8fafc;
        --text-secondary: #94a3b8;
        --text-muted: #7d8ba3;
        --icon-bg: rgba(37, 99, 235, 0.1);
        --section-color: #64748b;
        --avatar-bg: linear-gradient(135deg, #2563eb, #3b82f6);
    }

    /* Light theme variables */
    .sidebar-redesign[data-background-color="light"] {
        --sidebar-bg: transparent;
        --sidebar-border: #e2e8f0;
        --text-primary: #0f172a;
        --text-secondary: #64748b;
        --text-muted: #94a3b8;
        --icon-bg: rgba(37, 99, 235, 0.08);
        --section-color: #94a3b8;
        --avatar-bg: linear-gradient(135deg, #2563eb, #3b82f6);
    }

    .sidebar-redesign {
        background: var(--sidebar-bg) !important;
        border-right: 1px solid var(--sidebar-border);
    }

    .sidebar-redesign .sidebar-wrapper {
        background: transparent !important;
    }

    .sidebar-redesign .sidebar-content {
        padding: 0;
    }

    /* User Profile Section */
    .sidebar-redesign .sidebar-user {
        padding: 24px 20px;
        border-bottom: 1px solid var(--sidebar-border);
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
    }

    .sidebar-redesign .user-avatar {
        width: 56px;
        height: 56px;
        border-radius: 14px;
        background: var(--avatar-bg);
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 12px;
        box-shadow: 0 4px 12px -2px rgba(37, 99, 235, 0.3);
    }

    .sidebar-redesign .avatar-initials {
        color: white;
        font-size: 20px;
        font-weight: 700;
    }

    .sidebar-redesign .user-info {
        width: 100%;
    }

    .sidebar-redesign .user-name-link {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        text-decoration: none;
        color: var(--text-primary);
        transition: color 0.2s ease;
    }

    .sidebar-redesign .user-name-link:hover {
        color: #2563eb;
    }

    .sidebar-redesign .user-name {
        font-size: 0.95rem;
        font-weight: 600;
    }

    .sidebar-redesign .user-caret {
        font-size: 10px;
        color: var(--text-muted);
        transition: transform 0.2s ease;
    }

    .sidebar-redesign .user-name-link[aria-expanded="true"] .user-caret {
        transform: rotate(180deg);
    }

    .sidebar-redesign .user-role {
        display: block;
        font-size: 0.75rem;
        color: var(--text-muted);
        margin-top: 2px;
    }

    .sidebar-redesign .user-menu-item {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 10px 16px;
        margin-top: 12px;
        background: #2563eb;
        border-radius: 8px;
        color: white;
        font-size: 0.85rem;
        text-decoration: none;
        transition: all 0.2s ease;
    }

    .sidebar-redesign .user-menu-item:hover {
        background: #1d4ed8;
        color: white;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px -4px rgba(37, 99, 235, 0.5);
    }

    .sidebar-redesign .user-menu-item i {
        font-size: 12px;
    }

    /* Navigation */
    .sidebar-redesign .sidebar-nav {
        padding: 16px 12px;
    }

    .sidebar-redesign .nav-item {
        margin-bottom: 4px;
        list-style: none;
    }

    .sidebar-redesign .nav-link {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 11px 14px;
        border-radius: 10px;
        color: var(--text-secondary);
        text-decoration: none;
        transition: all 0.2s ease;
        position: relative;
    }

    /* Hover State - Solid Indigo */
    .sidebar-redesign .nav-link:hover {
        background: #2563eb;
        color: white;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px -4px rgba(37, 99, 235, 0.5);
    }

    /* Logout (Mobile) - Destructive */
    .sidebar-redesign .nav-link.sidebar-logout {
        color: #ef4444;
    }

    .sidebar-redesign .nav-link.sidebar-logout:hover {
        background: #ef4444;
        color: white;
        box-shadow: 0 4px 12px -4px rgba(239, 68, 68, 0.5);
    }

    .sidebar-redesign .nav-link.sidebar-logout:hover .nav-icon {
        /* background: rgba(255, 255, 255, 0.2); */
    }

    .sidebar-redesign .nav-link.sidebar-logout:hover .nav-icon i {
        color: white;
    }

    .sidebar-redesign .nav-link:hover .nav-icon {
        /* background: rgba(255, 255, 255, 0.2); */
    }

    .sidebar-redesign .nav-link:hover .nav-icon i {
        color: white;
    }

    .sidebar-redesign .nav-link:hover .nav-arrow {
        color: rgba(255, 255, 255, 0.7);
    }

    /* Active State - Solid Indigo */
    .sidebar-redesign .nav-item.active > .nav-link {
        color: white;
        box-shadow: 0 4px 12px -4px rgba(37, 99, 235, 0.5);
    }

    .sidebar-redesign .nav-item.active > .nav-link .nav-icon {
        /* background: rgba(255, 255, 255, 0.2); */
    }

    .sidebar-redesign .nav-item.active > .nav-link .nav-icon i {
        color: white;
    }

    .sidebar-redesign .nav-item.active > .nav-link .nav-arrow {
        color: rgba(255, 255, 255, 0.7);
    }

    /* Override legacy Atlantis theme. atlantis.min.css forces the active item to a
       WHITE background (background:#fff !important, specificity 0,6,1), which hid the
       redesign's indigo pill and left white-on-white text. Re-assert the indigo
       active/hover pill with a higher-specificity !important selector (0,7,1). */
    .sidebar-redesign.sidebar .nav.nav-primary > .nav-item.active > a.nav-link,
    .sidebar-redesign.sidebar .nav.nav-primary > .nav-item > a.nav-link:hover {
        background: #2563eb !important;
        box-shadow: 0 4px 12px -4px rgba(37, 99, 235, 0.5) !important;
    }

    .sidebar-redesign.sidebar .nav.nav-primary > .nav-item.active > a.nav-link,
    .sidebar-redesign.sidebar .nav.nav-primary > .nav-item.active > a.nav-link .nav-text,
    .sidebar-redesign.sidebar .nav.nav-primary > .nav-item.active > a.nav-link p,
    .sidebar-redesign.sidebar .nav.nav-primary > .nav-item.active > a.nav-link span,
    .sidebar-redesign.sidebar .nav.nav-primary > .nav-item > a.nav-link:hover,
    .sidebar-redesign.sidebar .nav.nav-primary > .nav-item > a.nav-link:hover .nav-text {
        color: #fff !important;
    }

    .sidebar-redesign.sidebar .nav.nav-primary > .nav-item.active > a.nav-link .nav-icon i,
    .sidebar-redesign.sidebar .nav.nav-primary > .nav-item > a.nav-link:hover .nav-icon i {
        color: #fff !important;
    }

    .sidebar-redesign .nav-icon {
        width: 34px;
        height: 34px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: var(--icon-bg);
        border-radius: 8px;
        flex-shrink: 0;
        transition: all 0.2s ease;
    }

    .sidebar-redesign .nav-icon i {
        font-size: 14px;
        color: var(--text-muted);
        transition: color 0.2s ease;
    }

    .sidebar-redesign .nav-text {
        font-size: 0.875rem;
        font-weight: 500;
        flex: 1;
    }

    .sidebar-redesign .nav-arrow {
        font-size: 10px;
        color: var(--text-muted);
        transition: all 0.2s ease;
    }

    .sidebar-redesign .nav-link[aria-expanded="true"] .nav-arrow {
        transform: rotate(180deg);
    }

    /* Section Labels */
    .sidebar-redesign .nav-section {
        padding: 20px 14px 8px;
        list-style: none;
    }

    .sidebar-redesign .nav-section-text {
        font-size: 0.7rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        color: var(--section-color);
    }

    /* Submenu */
    .sidebar-redesign .nav-submenu {
        padding: 8px 0 8px 46px;
        list-style: none;
        margin: 0;
    }

    .sidebar-redesign .nav-submenu li {
        margin-bottom: 2px;
    }

    .sidebar-redesign .nav-submenu a {
        display: block;
        padding: 8px 14px;
        border-radius: 6px;
        color: var(--text-muted);
        font-size: 0.825rem;
        text-decoration: none;
        transition: all 0.2s ease;
        position: relative;
    }

    .sidebar-redesign .nav-submenu a::before {
        content: '';
        position: absolute;
        left: 0;
        top: 50%;
        transform: translateY(-50%);
        width: 4px;
        height: 4px;
        background: var(--text-muted);
        border-radius: 50%;
        transition: all 0.2s ease;
    }

    /* Submenu Hover - Solid Indigo */
    .sidebar-redesign .nav-submenu a:hover {
        color: white;
        background: #2563eb;
        box-shadow: 0 4px 12px -4px rgba(37, 99, 235, 0.5);
    }

    .sidebar-redesign .nav-submenu a:hover::before {
        background: white;
    }

    /* Hide default styles */
    .sidebar-redesign .user {
        display: none !important;
    }

    .sidebar-redesign .nav-primary > .nav-item > a > i:not(.nav-arrow):not(.user-caret) {
        display: none;
    }

    .sidebar-redesign .nav-primary > .nav-item > a > p {
        display: none;
    }

    .sidebar-redesign .nav-primary > .nav-item > a > .caret {
        display: none;
    }

    .sidebar-redesign .nav-collapse {
        display: none;
    }

    /* Scrollbar */
    .sidebar-redesign .scrollbar-inner > .scroll-element {
        opacity: 0;
        transition: opacity 0.2s ease;
    }

    .sidebar-redesign .scrollbar-inner:hover > .scroll-element {
        opacity: 1;
    }

    .sidebar-redesign .scroll-element .scroll-bar {
        background: rgba(37, 99, 235, 0.3) !important;
        border-radius: 4px;
    }
</style>

<!-- ============================================
     BURGER MENU GLOBAL STYLES - FIXED THREE EQUAL DASHES
     ============================================ -->
<style>
    /* Force all burger/toggle buttons to be visible */
    .navbar-toggler,
    .burger-btn,
    .toggle-sidebar,
    .sidenav-toggler,
    .navbar-toggle,
    [data-toggle="sidebar"],
    .nav-toggle,
    .sidebar-toggle,
    .menu-toggle,
    button.navbar-toggler,
    .topbar-toggler,
    .btn-toggle {
        display: flex !important;
        visibility: visible !important;
        opacity: 1 !important;
        border: none !important;
        background: transparent !important;

        padding: 8px !important;
        cursor: pointer;
    }

    /* Sidenav Toggler Structure */
    .sidenav-toggler {
        cursor: pointer;
        padding: 8px;
    }

    .sidenav-toggler-inner {
        position: relative;
        width: 20px;
        height: 14px;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    .sidenav-toggler-line {
        width: 100% !important; /* Force all lines equal */
        height: 2px;
        border-radius: 1px;
        display: block !important;
        transition: all 0.2s ease;
        background-color: inherit !important;
    }

    /* Remove the shorter middle line */
    .sidenav-toggler-line:nth-child(2) {
        width: 100% !important;
    }

    /* =====================
       LIGHT MODE (Default)
       ===================== */
    .sidenav-toggler-line {
        background-color: #475569 !important;
    }

    .navbar-toggler span:not(.sr-only),
    .burger-btn span,
    .toggle-sidebar span,
    .topbar-toggler span {
        background-color: #475569 !important;
        display: block !important;
        height: 2px !important;
        border-radius: 1px !important;
    }

    /* Fixed three equal dashes for Bootstrap toggler */
    .navbar-toggler-icon {
        width: 20px !important;
        height: 16px !important;
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 22'%3e%3cpath stroke='%23475569' stroke-linecap='round' stroke-width='2' d='M2 4h20M2 10h20M2 16h20'/%3e%3c/svg%3e") !important;
        background-size: contain !important;
        background-repeat: no-repeat !important;
    }

    /* =====================
       DARK MODE
       ===================== */
    body.dark .sidenav-toggler-line,
    .wrapper.dark .sidenav-toggler-line,
    .bg-dark .sidenav-toggler-line,
    .navbar-header[data-background-color="dark"] .sidenav-toggler-line,
    .navbar-header[data-background-color="dark2"] .sidenav-toggler-line,
    .logo-header[data-background-color="dark"] .sidenav-toggler-line,
    .logo-header[data-background-color="dark2"] .sidenav-toggler-line,
    .main-header[data-background-color="dark"] .sidenav-toggler-line,
    .main-header.bg-dark .sidenav-toggler-line,
    .topbar.bg-dark .sidenav-toggler-line {
        background-color: #94a3b8 !important;
    }

    body.dark .navbar-toggler span:not(.sr-only),
    .wrapper.dark .navbar-toggler span:not(.sr-only),
    .bg-dark .navbar-toggler span:not(.sr-only) {
        background-color: #94a3b8 !important;
    }

    body.dark .navbar-toggler-icon,
    .wrapper.dark .navbar-toggler-icon,
    .bg-dark .navbar-toggler-icon,
    .navbar-dark .navbar-toggler-icon {
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 22'%3e%3cpath stroke='%2394a3b8' stroke-linecap='round' stroke-width='2' d='M2 4h20M2 10h20M2 16h20'/%3e%3c/svg%3e") !important;
    }

    /* Hover state - indigo for both modes */
    .sidenav-toggler:hover .sidenav-toggler-line {
        background-color: #2563eb !important;
    }

    .navbar-toggler:hover .navbar-toggler-icon {
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 22'%3e%3cpath stroke='%236366f1' stroke-linecap='round' stroke-width='2' d='M2 4h20M2 10h20M2 16h20'/%3e%3c/svg%3e") !important;
    }

    /* Mobile specific */
    @media (max-width: 991px) {
        .sidenav-toggler,
        .navbar-toggler {
            display: flex !important;
            visibility: visible !important;
        }
    }
</style>

<!-- Tawk.to Live Chat -->
<script type="text/javascript">
    var Tawk_API = Tawk_API || {}, Tawk_LoadStart = new Date();
    (function(){
        var s1 = document.createElement("script"), s0 = document.getElementsByTagName("script")[0];
        s1.async = true;
        s1.src = 'https://embed.tawk.to/6234c2a01ffac05b1d7f482a/1fuf1gh40';
        s1.charset = 'UTF-8';
        s1.setAttribute('crossorigin', '*');
        s0.parentNode.insertBefore(s1, s0);
    })();
</script>

{{-- Mobile-app bottom navigation (replaces the sidebar) --}}
<nav class="zx-bottomnav">
    <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9.5 12 3l9 6.5"/><path d="M5 10v10h14V10"/><path d="M9 20v-6h6v6"/></svg>
        <span>Home</span>
    </a>
    <a href="{{ route('trading.pairs') }}" class="{{ request()->routeIs('trading.pairs') || request()->is('*trading-pairs*') || request()->is('*recent-trades*') ? 'active' : '' }}">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 3v18h18"/><path d="M7 14l3-4 3 3 5-7"/></svg>
        <span>Trade</span>
    </a>
    <a href="{{ route('profile') }}" class="{{ request()->routeIs('profile') || request()->is('*account-settings*') || request()->is('*referuser*') || request()->is('*referral-earnings*') ? 'active' : '' }}">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="8" r="4"/><path d="M4 21v-1a6 6 0 0 1 6-6h4a6 6 0 0 1 6 6v1"/></svg>
        <span>Profile</span>
    </a>
</nav>

@verbatim
<style>
    /* Hide the old sidebar + reclaim the space app-wide */
    .sidebar, .sidebar-redesign { display: none !important; }
    .main-panel { width: 100% !important; float: none !important; margin-left: 0 !important; }
    /* Theme reserved 62px at the top of .content for the old fixed header; the nav now
       floats in-flow, so drop that reserved space to remove the gap under the top bar. */
    .main-panel > .content, .main-panel .content { margin-top: 0 !important; }
    .burger-menu-btn, .sidenav-toggler, .toggle-sidebar, .nav-toggle { display: none !important; }

    /* Floating, centered top bar (mobile-friendly, not full width) */
    .main-header.header-redesign {
        position: sticky !important; top: 12px !important;
        left: auto !important; right: auto !important;
        width: calc(100% - 32px) !important; max-width: 560px !important;
        margin: 12px auto 0 !important;
        display: flex !important; align-items: center !important; gap: 8px;
        background: var(--header-bg) !important;
        border: 1px solid var(--header-border) !important;
        border-radius: 20px !important;
        box-shadow: 0 12px 32px rgba(2, 8, 23, .12) !important;
        padding: 6px 10px 6px 16px !important;
        min-height: 0 !important; height: auto !important; overflow: visible !important;
    }
    .main-header.header-redesign .logo-header {
        flex: none !important; background: transparent !important; border: none !important;
        padding: 0 !important; height: auto !important; width: auto !important;
    }
    .main-header.header-redesign .navbar-header {
        flex: 1 1 auto !important; background: transparent !important; border: none !important;
        padding: 0 !important; min-width: 0 !important; height: auto !important;
    }
    .main-header.header-redesign .navbar-header .container-fluid { padding: 0 !important; }
    .wrapper, .main-panel .content { padding-bottom: 104px !important; }

    /* Floating mobile dock (centered, does not span the screen) */
    .zx-bottomnav {
        position: fixed; left: 50%; transform: translateX(-50%);
        bottom: calc(16px + env(safe-area-inset-bottom));
        width: calc(100% - 32px); max-width: 430px; z-index: 1050;
        display: flex; align-items: stretch; gap: 4px;
        background: var(--bg-card, #fff);
        border: 1px solid var(--border-color, #e2e8f0);
        border-radius: 22px; padding: 7px;
        box-shadow: 0 14px 38px rgba(2, 8, 23, .18);
    }
    .zx-bottomnav a {
        flex: 1; display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 4px;
        padding: 9px 4px; border-radius: 15px; text-decoration: none; font-size: .72rem; font-weight: 600;
        color: var(--text-secondary, #94a3b8); transition: .2s ease;
    }
    .zx-bottomnav a svg { width: 22px; height: 22px; }
    .zx-bottomnav a:hover { color: var(--text-primary, #0f172a); }
    .zx-bottomnav a.active { color: #2563eb; background: rgba(37, 99, 235, .12); }
</style>
@endverbatim

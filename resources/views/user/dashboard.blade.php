
<?php
if (Auth::user()->dashboard_style == "light") {
    $bg = "light";
    $text = "dark";
} else {
    $bg = "dark";
    $text = "light";
}
?>

@extends('layouts.app')

@section('content')
    @include('user.topmenu')
    @include('user.sidebar')

    <div class="main-panel dashboard-page" data-theme="{{ $bg }}">
        <div class="content">
            <div class="zx-app">

                <x-danger-alert/>
                <x-success-alert/>

                @php
                    $firstName = explode(' ', trim(Auth::user()->name))[0];
                    $initial = strtoupper(substr(Auth::user()->name, 0, 1));
                    $depositTotal = $deposited[0]->count ?? 0;
                    $withdrawalTotal = $withdrawals[0]->count ?? 0;
                @endphp

                <!-- Greeting -->
                <div class="zx-greet">
                    <div class="zx-greet-user">
                        <span class="zx-avatar">{{ $initial }}</span>
                        <div class="zx-greet-name-wrap">
                            <span class="zx-greet-label">Good day,</span>
                            <span class="zx-greet-name">{{ Auth::user()->name }}</span>
                        </div>
                    </div>
                    <div class="zx-greet-actions">
                        <a href="{{ route('dashboard') }}" class="zx-icon-btn" title="Refresh">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M23 4v6h-6M1 20v-6h6"/><path d="M3.5 9a9 9 0 0 1 14.9-3.4L23 10M1 14l4.6 4.4A9 9 0 0 0 20.5 15"/></svg>
                        </a>
                        <a href="{{ route('notification') }}" class="zx-icon-btn" title="Notifications">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 8a6 6 0 0 0-12 0c0 7-3 9-3 9h18s-3-2-3-9M13.7 21a2 2 0 0 1-3.4 0"/></svg>
                        </a>
                    </div>
                </div>

                <!-- KYC banner -->
                @if($settings->enable_kyc == "yes" && Auth::user()->account_verify != 'Verified')
                    <a href="{{ route('account.verify') }}" class="zx-kyc">
                        <span class="zx-kyc-ico">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10.29 3.86 1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                        </span>
                        <span class="zx-kyc-txt">
                            <strong>Complete KYC to start trading</strong>
                            <span>Upload your ID and get verified in minutes.</span>
                        </span>
                        <span class="zx-kyc-btn">Submit KYC</span>
                    </a>
                @endif

                <!-- Total balance hero -->
                <div class="zx-balance">
                    <div class="zx-balance-top">
                        <span class="zx-balance-label">Total balance</span>
                        <span class="zx-live"><i></i>Live</span>
                    </div>
                    <span class="zx-balance-sub">Hi, {{ $firstName }} 👋</span>
                    <div class="zx-balance-amt">{{ $settings->currency }}{{ number_format(Auth::user()->account_bal, 2) }}</div>
                    <span class="zx-balance-hint">Total profit &nbsp;<b>{{ $settings->currency }}{{ number_format(Auth::user()->roi, 2) }}</b></span>

                    <div class="zx-balance-actions">
                        <a href="{{ route('deposits') }}" class="zx-bbtn primary">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14M19 12l-7 7-7-7"/></svg>
                            Deposit
                        </a>
                        <a href="{{ route('withdrawalsdeposits') }}" class="zx-bbtn ghost">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 19V5M5 12l7-7 7 7"/></svg>
                            Withdraw
                        </a>
                    </div>
                </div>

                <!-- Quick actions -->
                <div class="zx-quick">
                    <a href="{{ route('trading.pairs') }}">
                        <span class="zx-quick-ico trade"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 3v18h18"/><path d="M7 14l3-4 3 3 5-7"/></svg></span>
                        <span>Trade</span>
                    </a>
                    <a href="{{ url('dashboard/recent-trades') }}">
                        <span class="zx-quick-ico history"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 3v5h5"/><path d="M3.05 13A9 9 0 1 0 6 5.3L3 8"/><path d="M12 7v5l3 2"/></svg></span>
                        <span>History</span>
                    </a>
                    <a href="{{ route('support') }}">
                        <span class="zx-quick-ico support"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18.5 14v-3a6.5 6.5 0 0 0-13 0v3"/><rect x="3" y="14" width="4" height="6" rx="1.5"/><rect x="17" y="14" width="4" height="6" rx="1.5"/><path d="M18.5 20a4 4 0 0 1-4 3H12"/></svg></span>
                        <span>Support</span>
                    </a>
                    <a href="{{ route('profile') }}">
                        <span class="zx-quick-ico settings"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 1 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-2.4.98 1.65 1.65 0 0 1-3.16 0 1.65 1.65 0 0 0-2.4-.98l-.06.06a2 2 0 1 1-2.83-2.83l.06-.06A1.65 1.65 0 0 0 4.6 15a1.65 1.65 0 0 0-1.51-1H3a2 2 0 1 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 1 1 2.83-2.83l.06.06A1.65 1.65 0 0 0 9 4.6a1.65 1.65 0 0 0 1-1.51V3a2 2 0 1 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 1 1 2.83 2.83l-.06.06A1.65 1.65 0 0 0 19.4 9z"/></svg></span>
                        <span>Settings</span>
                    </a>
                </div>

                <!-- Promo -->
                <a href="{{ route('trading.pairs') }}" class="zx-promo">
                    <div class="zx-promo-txt">
                        <h3>Trade smarter <span>on Zaraex</span></h3>
                        <p>Start automated trading and grow your portfolio.</p>
                        <span class="zx-promo-pill">Get started &rarr;</span>
                    </div>
                    <div class="zx-promo-glow"></div>
                </a>

                <!-- Two mini stats -->
                <div class="zx-mini-grid">
                    <div class="zx-mini">
                        <span class="zx-mini-label">Total deposits</span>
                        <span class="zx-mini-val">{{ $settings->currency }}{{ number_format($depositTotal, 2) }}</span>
                    </div>
                    <div class="zx-mini">
                        <span class="zx-mini-label">Total withdrawals</span>
                        <span class="zx-mini-val">{{ $settings->currency }}{{ number_format($withdrawalTotal, 2) }}</span>
                    </div>
                </div>

                <!-- Active trades -->
                @php
                    try {
                        $activeTrades = \App\Models\Investment::with('tradingPair')
                            ->where('user_id', Auth::id())->where('status', 'active')
                            ->orderBy('created_at', 'desc')->take(4)->get();
                    } catch (\Exception $e) { $activeTrades = collect([]); }
                @endphp
                <div class="zx-section">
                    <div class="zx-section-head">
                        <span class="zx-section-label">Active trades</span>
                        <a href="{{ url('dashboard/recent-trades') }}" class="zx-see">See all</a>
                    </div>
                    <div class="zx-list">
                        @forelse($activeTrades as $trade)
                            <div class="zx-row">
                                <img src="{{ $trade->tradingPair->base_icon_url ?? '' }}" alt="" class="zx-row-ico img" onerror="this.style.display='none'">
                                <div class="zx-row-main">
                                    <span class="zx-row-title">{{ $trade->tradingPair->base_symbol ?? 'N/A' }}/{{ $trade->tradingPair->quote_symbol ?? 'USDT' }}</span>
                                    <span class="zx-row-sub">Started {{ $trade->created_at->diffForHumans() }}</span>
                                </div>
                                <div class="zx-row-right">
                                    <span class="zx-row-amt">{{ $settings->currency }}{{ number_format($trade->amount, 2) }}</span>
                                    <span class="zx-pill success">Active</span>
                                </div>
                            </div>
                        @empty
                            <div class="zx-empty">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M3 3v18h18"/><path d="M7 14l3-4 3 3 5-7"/></svg>
                                <p>No active trades yet</p>
                                <a href="{{ route('trading.pairs') }}" class="zx-empty-btn">Start trading</a>
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Recent activity -->
                @php
                    try {
                        $recentTransactions = \App\Models\Deposit::where('user_id', Auth::id())
                            ->orderBy('created_at', 'desc')->take(5)->get();
                    } catch (\Exception $e) {
                        try {
                            $recentTransactions = \App\Models\Deposit::where('user', Auth::id())
                                ->orderBy('created_at', 'desc')->take(5)->get();
                        } catch (\Exception $e) { $recentTransactions = collect([]); }
                    }
                @endphp
                <div class="zx-section">
                    <div class="zx-section-head">
                        <span class="zx-section-label">Recent activity</span>
                        <a href="{{ url('dashboard/accounthistory') }}" class="zx-see">See all</a>
                    </div>
                    <div class="zx-list">
                        @forelse($recentTransactions as $transaction)
                            <div class="zx-row">
                                <span class="zx-row-ico {{ $transaction->status == 'Processed' ? 'success' : ($transaction->status == 'Pending' ? 'pending' : 'failed') }}">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14M19 12l-7 7-7-7"/></svg>
                                </span>
                                <div class="zx-row-main">
                                    <span class="zx-row-title">Deposit</span>
                                    <span class="zx-row-sub">{{ $transaction->created_at->diffForHumans() }}</span>
                                </div>
                                <div class="zx-row-right">
                                    <span class="zx-row-amt pos">+{{ $settings->currency }}{{ number_format($transaction->amount, 2) }}</span>
                                    <span class="zx-row-sub">{{ $transaction->status }}</span>
                                </div>
                            </div>
                        @empty
                            <div class="zx-empty">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="5" width="18" height="14" rx="2"/><path d="M3 10h18"/></svg>
                                <p>No recent activity</p>
                                <a href="{{ route('deposits') }}" class="zx-empty-btn">Make your first deposit</a>
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Account status -->
                <div class="zx-section">
                    <div class="zx-section-head">
                        <span class="zx-section-label">Account status</span>
                    </div>
                    <div class="zx-list">
                        <div class="zx-row status">
                            <span class="zx-row-title">Email verified</span>
                            @if(Auth::user()->email_verified_at)
                                <span class="zx-pill success">Verified</span>
                            @else
                                <span class="zx-pill pending">Pending</span>
                            @endif
                        </div>
                        @if($settings->enable_kyc == "yes")
                            <div class="zx-row status">
                                <span class="zx-row-title">KYC verification</span>
                                @if(Auth::user()->account_verify == 'Verified')
                                    <span class="zx-pill success">Verified</span>
                                @else
                                    <a href="{{ route('account.verify') }}" class="zx-pill action">Verify now &rarr;</a>
                                @endif
                            </div>
                        @endif
                        <div class="zx-row status">
                            <span class="zx-row-title">Member since</span>
                            <span class="zx-pill muted">{{ Auth::user()->created_at->format('M Y') }}</span>
                        </div>
                    </div>
                </div>

                <!-- Announcement -->
                @if ($settings->enable_annoc == "on" && !empty($settings->newupdate))
                    <div class="zx-announce">
                        <span class="zx-announce-ico">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 11l18-5v12L3 14v-3zM11.6 16.8a3 3 0 1 1-5.8-1.6"/></svg>
                        </span>
                        <div class="zx-announce-txt">
                            <span class="zx-announce-label">Announcement</span>
                            <p>{{ $settings->newupdate }}</p>
                        </div>
                        <button class="zx-announce-x" onclick="this.parentElement.style.display='none'">&times;</button>
                    </div>
                @endif

            </div>
        </div>
    </div>

    @if (!empty($settings->telegram_channel))
        <a href="{{ str_starts_with($settings->telegram_channel, '@') ? 'https://t.me/' . ltrim($settings->telegram_channel, '@') : $settings->telegram_channel }}"
           class="zx-tg" target="_blank" title="Join our Telegram">
            <svg viewBox="0 0 24 24" fill="currentColor"><path d="M9.8 15.6 9.6 20c.4 0 .6-.2.8-.4l2-1.9 4.1 3c.8.4 1.3.2 1.5-.7L21 4.9c.3-1.2-.4-1.6-1.2-1.3L3.6 9.9c-1.1.4-1.1 1-.2 1.3l4.2 1.3L17.3 6c.5-.3.9-.1.5.2z"/></svg>
        </a>
    @endif

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Space+Grotesk:wght@500;600;700&display=swap" rel="stylesheet">
    @verbatim
    <style>
    /* ============ Zaraex mobile dashboard ============ */
    .dashboard-page{
        --card:#0e1018; --elev:#16181f; --border:#23262f;
        --text:#f2f5fb; --muted:#a1a9b8; --faint:#6b7280;
        --blue:#2563eb; --blue-soft:#3b82f6; --success:#22c55e; --amber:#f59e0b; --danger:#ef4444;
        --tint:rgba(37,99,235,.14);
        font-family:'Inter',system-ui,-apple-system,sans-serif; color:var(--text);
    }
    body[data-theme="light"] .dashboard-page{
        --card:#ffffff; --elev:#f4f6fa; --border:#e8ecf2;
        --text:#0f172a; --muted:#5b6674; --faint:#98a2b3;
    }
    .dashboard-page .content{padding:0}
    .dashboard-page h1,.dashboard-page h2,.dashboard-page h3{font-family:'Space Grotesk','Inter',sans-serif;letter-spacing:-.02em;margin:0}

    /* Narrow, mobile-app column */
    .zx-app{max-width:520px;margin:0 auto;padding:22px 16px 96px;display:flex;flex-direction:column;gap:18px}

    /* Greeting */
    .zx-greet{display:flex;align-items:center;justify-content:space-between;gap:12px}
    .zx-greet-user{display:flex;align-items:center;gap:12px;min-width:0}
    .zx-avatar{width:46px;height:46px;border-radius:50%;flex:none;display:grid;place-items:center;
        font-weight:700;font-size:1.05rem;color:var(--blue-soft);background:var(--tint)}
    .zx-greet-name-wrap{display:flex;flex-direction:column;min-width:0}
    .zx-greet-label{color:var(--faint);font-size:.82rem}
    .zx-greet-name{font-family:'Space Grotesk',sans-serif;font-weight:700;font-size:1.05rem;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
    .zx-greet-actions{display:flex;gap:8px}
    .zx-icon-btn{width:42px;height:42px;border-radius:50%;display:grid;place-items:center;color:var(--muted);
        background:var(--card);border:1px solid var(--border);transition:.2s}
    .zx-icon-btn:hover{color:var(--text);border-color:rgba(59,130,246,.4)}
    .zx-icon-btn svg{width:18px;height:18px}

    /* KYC banner */
    .zx-kyc{display:flex;align-items:center;gap:14px;padding:15px 16px;border-radius:16px;text-decoration:none;
        background:rgba(245,158,11,.12);border:1px solid rgba(245,158,11,.32)}
    .zx-kyc-ico{width:40px;height:40px;border-radius:11px;flex:none;display:grid;place-items:center;color:var(--amber);background:rgba(245,158,11,.16)}
    .zx-kyc-ico svg{width:20px;height:20px}
    .zx-kyc-txt{display:flex;flex-direction:column;flex:1;min-width:0}
    .zx-kyc-txt strong{color:var(--amber);font-size:.95rem;font-weight:700}
    .zx-kyc-txt span{color:var(--muted);font-size:.82rem;margin-top:2px}
    .zx-kyc-btn{flex:none;background:var(--blue);color:#fff;font-weight:600;font-size:.85rem;padding:9px 15px;border-radius:10px}

    /* Balance hero (dark in both themes) */
    .zx-balance{position:relative;overflow:hidden;border-radius:24px;padding:22px;color:#fff;
        background:linear-gradient(160deg,#13233f 0%,#0b1322 100%);box-shadow:0 24px 50px -18px rgba(8,15,30,.7)}
    .zx-balance-top{display:flex;align-items:center;justify-content:space-between}
    .zx-balance-label{text-transform:uppercase;letter-spacing:.1em;font-size:.72rem;font-weight:600;color:rgba(255,255,255,.5)}
    .zx-live{display:inline-flex;align-items:center;gap:6px;font-size:.72rem;font-weight:600;color:#4ade80}
    .zx-live i{width:7px;height:7px;border-radius:50%;background:#4ade80;box-shadow:0 0 0 3px rgba(74,222,128,.25)}
    .zx-balance-sub{display:block;color:rgba(255,255,255,.72);font-size:.9rem;margin-top:16px}
    .zx-balance-amt{font-family:'Space Grotesk',sans-serif;font-size:2.7rem;font-weight:700;line-height:1.1;margin-top:2px;letter-spacing:-.02em}
    .zx-balance-hint{display:block;color:rgba(255,255,255,.55);font-size:.85rem;margin-top:6px}
    .zx-balance-hint b{color:#4ade80;font-weight:600}
    .zx-balance-actions{display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-top:22px}
    .zx-bbtn{display:flex;align-items:center;justify-content:center;gap:8px;padding:14px;border-radius:13px;font-weight:600;font-size:.95rem;text-decoration:none;transition:.2s}
    .zx-bbtn svg{width:17px;height:17px}
    .zx-bbtn.primary{background:var(--blue);color:#fff}
    .zx-bbtn.primary:hover{background:var(--blue-soft)}
    .zx-bbtn.ghost{background:rgba(255,255,255,.09);color:#fff}
    .zx-bbtn.ghost:hover{background:rgba(255,255,255,.16)}

    /* Quick actions */
    .zx-quick{display:grid;grid-template-columns:repeat(4,1fr);gap:10px}
    .zx-quick a{display:flex;flex-direction:column;align-items:center;gap:8px;padding:14px 6px;border-radius:15px;
        background:var(--card);border:1px solid var(--border);text-decoration:none;transition:.2s}
    .zx-quick a:hover{transform:translateY(-2px);border-color:rgba(59,130,246,.4)}
    .zx-quick-ico{width:42px;height:42px;border-radius:12px;display:grid;place-items:center;background:var(--tint);color:var(--blue-soft)}
    .zx-quick-ico svg{width:19px;height:19px}
    .zx-quick-ico.history{background:rgba(6,182,212,.14);color:#06b6d4}
    .zx-quick-ico.refer{background:rgba(37, 99, 235,.16);color:#60a5fa}
    .zx-quick-ico.support{background:rgba(34,197,94,.14);color:#22c55e}
    .zx-quick-ico.settings{background:rgba(148,163,184,.16);color:#94a3b8}
    .zx-quick span{font-size:.78rem;font-weight:500;color:var(--text)}

    /* Promo */
    .zx-promo{position:relative;overflow:hidden;display:block;border-radius:20px;padding:22px;text-decoration:none;
        background:linear-gradient(135deg,#2563eb 0%,#1d4ed8 100%);color:#fff}
    .zx-promo h3{font-size:1.3rem;font-weight:700}
    .zx-promo h3 span{color:#bfdbfe}
    .zx-promo p{margin:8px 0 0;color:rgba(255,255,255,.82);font-size:.9rem;max-width:80%}
    .zx-promo-pill{display:inline-block;margin-top:14px;background:#fff;color:#2563eb;font-weight:700;font-size:.85rem;padding:9px 16px;border-radius:999px}
    .zx-promo-glow{position:absolute;right:-40px;top:-40px;width:180px;height:180px;border-radius:50%;background:rgba(255,255,255,.18);filter:blur(10px)}

    /* Mini stats */
    .zx-mini-grid{display:grid;grid-template-columns:1fr 1fr;gap:12px}
    .zx-mini{background:var(--card);border:1px solid var(--border);border-radius:16px;padding:16px}
    .zx-mini-label{color:var(--faint);font-size:.8rem}
    .zx-mini-val{display:block;font-family:'Space Grotesk',sans-serif;font-weight:700;font-size:1.25rem;margin-top:6px}

    /* Sections + lists */
    .zx-section{display:flex;flex-direction:column;gap:10px}
    .zx-section-head{display:flex;align-items:center;justify-content:space-between;padding:0 4px}
    .zx-section-label{text-transform:uppercase;letter-spacing:.08em;font-size:.75rem;font-weight:700;color:var(--faint)}
    .zx-see{color:var(--blue-soft);font-size:.82rem;font-weight:600;text-decoration:none}
    .zx-list{background:var(--card);border:1px solid var(--border);border-radius:18px;overflow:hidden}
    .zx-row{display:flex;align-items:center;gap:14px;padding:14px 16px;border-bottom:1px solid var(--border)}
    .zx-row:last-child{border-bottom:0}
    .zx-row.status{justify-content:space-between}
    .zx-row-ico{width:42px;height:42px;border-radius:50%;flex:none;display:grid;place-items:center;background:rgba(34,197,94,.14);color:var(--success)}
    .zx-row-ico svg{width:18px;height:18px}
    .zx-row-ico.img{background:var(--elev);object-fit:cover}
    .zx-row-ico.pending{background:rgba(245,158,11,.14);color:var(--amber)}
    .zx-row-ico.failed{background:rgba(239,68,68,.14);color:var(--danger)}
    .zx-row-main{display:flex;flex-direction:column;flex:1;min-width:0}
    .zx-row-title{font-weight:600;font-size:.94rem}
    .zx-row-sub{color:var(--faint);font-size:.8rem;margin-top:2px}
    .zx-row-right{display:flex;flex-direction:column;align-items:flex-end;gap:3px;text-align:right}
    .zx-row-amt{font-family:'Space Grotesk',sans-serif;font-weight:600;font-size:.95rem}
    .zx-row-amt.pos{color:var(--success)}
    .zx-row-amt.neg{color:var(--danger)}
    .zx-pill{font-size:.72rem;font-weight:600;padding:4px 10px;border-radius:999px;text-decoration:none}
    .zx-pill.success{background:rgba(34,197,94,.14);color:var(--success)}
    .zx-pill.pending{background:rgba(245,158,11,.14);color:var(--amber)}
    .zx-pill.action{background:var(--blue);color:#fff}
    .zx-pill.muted{background:var(--elev);color:var(--muted);border:1px solid var(--border)}

    /* Empty */
    .zx-empty{display:flex;flex-direction:column;align-items:center;text-align:center;gap:8px;padding:32px 16px;color:var(--muted)}
    .zx-empty svg{width:30px;height:30px;color:var(--faint)}
    .zx-empty p{margin:0;font-size:.9rem}
    .zx-empty-btn{color:var(--blue-soft);font-weight:600;font-size:.85rem;text-decoration:none}

    /* Announcement */
    .zx-announce{display:flex;align-items:center;gap:14px;padding:16px;border-radius:16px;
        background:linear-gradient(135deg,rgba(37,99,235,.16),rgba(59,130,246,.05));border:1px solid rgba(59,130,246,.3)}
    .zx-announce-ico{width:40px;height:40px;border-radius:11px;flex:none;display:grid;place-items:center;background:var(--blue);color:#fff}
    .zx-announce-ico svg{width:19px;height:19px}
    .zx-announce-txt{flex:1;min-width:0}
    .zx-announce-label{color:var(--blue-soft);font-size:.74rem;font-weight:700;text-transform:uppercase;letter-spacing:.05em}
    .zx-announce-txt p{margin:3px 0 0;font-size:.9rem;color:var(--text)}
    .zx-announce-x{background:transparent;border:none;color:var(--faint);font-size:22px;line-height:1;cursor:pointer;padding:0 4px}

    /* Telegram FAB */
    .zx-tg{position:fixed;bottom:24px;right:24px;width:54px;height:54px;border-radius:50%;display:grid;place-items:center;
        background:#229ed2;color:#fff;box-shadow:0 12px 30px rgba(34,158,210,.45);z-index:60;transition:.2s}
    .zx-tg:hover{transform:translateY(-3px);color:#fff}
    .zx-tg svg{width:26px;height:26px}

    @media (max-width:400px){
        .zx-balance-amt{font-size:2.3rem}
    }
    </style>
    @endverbatim
@endsection

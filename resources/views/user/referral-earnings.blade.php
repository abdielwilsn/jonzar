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

    <div class="main-panel earnings-page" data-theme="{{ $bg }}">
        <div class="content">
            <div class="zx-app">

                <x-danger-alert/>
                <x-success-alert/>

                <!-- Header -->
                <div class="er-head">
                    <div>
                        <h1>Referral earnings</h1>
                        <p>Every bonus you've earned from your referrals</p>
                    </div>
                </div>

                <!-- Total hero -->
                <div class="er-hero">
                    <span class="er-hero-label">Total earned</span>
                    <div class="er-hero-amt">{{ $settings->currency }}{{ number_format($referralTotal + $tradeTotal, 2) }}</div>
                    <span class="er-hero-sub">All-time referral income</span>
                    <a href="{{ url('dashboard/referuser') }}" class="er-hero-btn">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 11h-6M19 8v6"/></svg>
                        Refer &amp; earn
                    </a>
                </div>

                <!-- Breakdown -->
                <div class="er-mini-grid">
                    <div class="er-mini">
                        <span class="er-mini-ico referral">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                        </span>
                        <span class="er-mini-label">Referral bonuses</span>
                        <span class="er-mini-val">{{ $settings->currency }}{{ number_format($referralTotal, 2) }}</span>
                        <span class="er-mini-meta">{{ $referralBonuses->count() }} {{ Str::plural('bonus', $referralBonuses->count()) }}</span>
                    </div>
                    <div class="er-mini">
                        <span class="er-mini-ico trade">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 3v18h18"/><path d="M7 14l3-4 3 3 5-7"/></svg>
                        </span>
                        <span class="er-mini-label">Trade bonuses</span>
                        <span class="er-mini-val">{{ $settings->currency }}{{ number_format($tradeTotal, 2) }}</span>
                        <span class="er-mini-meta">{{ $tradeBonuses->count() }} {{ Str::plural('bonus', $tradeBonuses->count()) }}</span>
                    </div>
                </div>

                <!-- Referral bonuses list -->
                <div class="zx-section">
                    <div class="zx-section-head">
                        <span class="zx-section-label">Referral bonuses</span>
                        <span class="er-badge">{{ $referralBonuses->count() }}</span>
                    </div>
                    <div class="zx-list">
                        @forelse($referralBonuses as $bonus)
                            <div class="zx-row">
                                <span class="zx-row-ico referral">{{ strtoupper(substr($bonus->source_name ?? '?', 0, 1)) }}</span>
                                <div class="zx-row-main">
                                    <span class="zx-row-title">{{ $bonus->source_name ?? 'Referral' }}</span>
                                    <span class="zx-row-sub">{{ $bonus->created_at->format('M d, Y') }}</span>
                                </div>
                                <span class="zx-row-amt pos">+{{ $settings->currency }}{{ number_format((float) $bonus->amount, 2) }}</span>
                            </div>
                        @empty
                            <div class="zx-empty">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
                                <p>No referral bonuses yet</p>
                                <a href="{{ url('dashboard/referuser') }}" class="zx-empty-btn">Invite friends</a>
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Trade bonuses list -->
                <div class="zx-section">
                    <div class="zx-section-head">
                        <span class="zx-section-label">Trade bonuses</span>
                        <span class="er-badge">{{ $tradeBonuses->count() }}</span>
                    </div>
                    <div class="zx-list">
                        @forelse($tradeBonuses as $bonus)
                            <div class="zx-row">
                                <span class="zx-row-ico trade">{{ strtoupper(substr($bonus->source_name ?? '?', 0, 1)) }}</span>
                                <div class="zx-row-main">
                                    <span class="zx-row-title">{{ $bonus->source_name ?? 'Referral' }}</span>
                                    <span class="zx-row-sub">{{ $bonus->created_at->format('M d, Y') }}</span>
                                </div>
                                <span class="zx-row-amt pos">+{{ $settings->currency }}{{ number_format((float) $bonus->amount, 2) }}</span>
                            </div>
                        @empty
                            <div class="zx-empty">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M3 3v18h18"/><path d="M7 14l3-4 3 3 5-7"/></svg>
                                <p>No trade bonuses yet</p>
                                <a href="{{ route('trading.pairs') }}" class="zx-empty-btn">Explore trading</a>
                            </div>
                        @endforelse
                    </div>
                </div>

            </div>
        </div>
    </div>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Space+Grotesk:wght@500;600;700&display=swap" rel="stylesheet">
    @verbatim
    <style>
    /* ============ Zaraex referral earnings ============ */
    .earnings-page{
        --card:#0e1018; --elev:#16181f; --border:#23262f;
        --text:#f2f5fb; --muted:#a1a9b8; --faint:#6b7280;
        --blue:#2563eb; --blue-soft:#3b82f6; --success:#22c55e; --amber:#f59e0b; --danger:#ef4444;
        --tint:rgba(37,99,235,.14);
        font-family:'Inter',system-ui,-apple-system,sans-serif; color:var(--text);
    }
    body[data-theme="light"] .earnings-page{
        --card:#ffffff; --elev:#f4f6fa; --border:#e8ecf2; --text:#0f172a; --muted:#5b6674; --faint:#98a2b3;
    }
    .earnings-page .content{padding:0}
    .earnings-page .zx-app{max-width:520px;margin:0 auto;padding:22px 16px 96px;display:flex;flex-direction:column;gap:18px}
    .earnings-page h1{font-family:'Space Grotesk','Inter',sans-serif;letter-spacing:-.02em;margin:0}

    /* Header */
    .er-head h1{font-size:1.5rem;font-weight:700}
    .er-head p{margin:5px 0 0;color:var(--muted);font-size:.9rem}

    /* Hero */
    .er-hero{position:relative;border-radius:22px;padding:24px;color:#fff;text-align:center;
        background:linear-gradient(160deg,#13233f 0%,#0b1322 100%);box-shadow:0 24px 50px -18px rgba(8,15,30,.7)}
    .er-hero-label{color:rgba(255,255,255,.55);font-size:.74rem;text-transform:uppercase;letter-spacing:.08em}
    .er-hero-amt{font-family:'Space Grotesk',sans-serif;font-size:2.6rem;font-weight:700;line-height:1.1;margin:6px 0 4px}
    .er-hero-sub{display:block;color:rgba(255,255,255,.55);font-size:.82rem}
    .er-hero-btn{display:inline-flex;align-items:center;gap:8px;margin-top:16px;padding:11px 20px;border-radius:12px;
        background:var(--blue);color:#fff;font-weight:600;font-size:.9rem;text-decoration:none;transition:.2s}
    .er-hero-btn:hover{background:var(--blue-soft);color:#fff}
    .er-hero-btn svg{width:17px;height:17px}

    /* Breakdown mini cards */
    .er-mini-grid{display:grid;grid-template-columns:1fr 1fr;gap:12px}
    .er-mini{background:var(--card);border:1px solid var(--border);border-radius:16px;padding:16px}
    .er-mini-ico{width:38px;height:38px;border-radius:11px;display:grid;place-items:center;margin-bottom:12px;background:var(--tint);color:var(--blue-soft)}
    .er-mini-ico.trade{background:rgba(34,197,94,.14);color:var(--success)}
    .er-mini-ico svg{width:19px;height:19px}
    .er-mini-label{display:block;color:var(--faint);font-size:.78rem}
    .er-mini-val{display:block;font-family:'Space Grotesk',sans-serif;font-weight:700;font-size:1.3rem;margin-top:3px}
    .er-mini-meta{display:block;color:var(--muted);font-size:.76rem;margin-top:2px}

    /* Sections + lists */
    .zx-section{display:flex;flex-direction:column;gap:10px}
    .zx-section-head{display:flex;align-items:center;justify-content:space-between;padding:0 4px}
    .zx-section-label{text-transform:uppercase;letter-spacing:.08em;font-size:.75rem;font-weight:700;color:var(--faint)}
    .er-badge{min-width:22px;height:22px;padding:0 7px;border-radius:999px;display:grid;place-items:center;background:var(--tint);color:var(--blue-soft);font-size:.72rem;font-weight:700}
    .zx-list{background:var(--card);border:1px solid var(--border);border-radius:18px;overflow:hidden}
    .zx-row{display:flex;align-items:center;gap:14px;padding:14px 16px;border-bottom:1px solid var(--border)}
    .zx-row:last-child{border-bottom:0}
    .zx-row-ico{width:42px;height:42px;border-radius:50%;flex:none;display:grid;place-items:center;font-weight:700;font-size:.95rem;
        color:#fff;background:linear-gradient(135deg,var(--blue),var(--blue-soft))}
    .zx-row-ico.trade{background:linear-gradient(135deg,#16a34a,#22c55e)}
    .zx-row-main{display:flex;flex-direction:column;flex:1;min-width:0}
    .zx-row-title{font-weight:600;font-size:.94rem}
    .zx-row-sub{color:var(--faint);font-size:.78rem;margin-top:2px}
    .zx-row-amt{font-family:'Space Grotesk',sans-serif;font-weight:600;font-size:.95rem}
    .zx-row-amt.pos{color:var(--success)}

    /* Empty */
    .zx-empty{display:flex;flex-direction:column;align-items:center;text-align:center;gap:8px;padding:34px 16px;color:var(--muted)}
    .zx-empty svg{width:32px;height:32px;color:var(--faint)}
    .zx-empty p{margin:0;font-size:.9rem}
    .zx-empty-btn{color:var(--blue-soft);font-weight:600;font-size:.85rem;text-decoration:none}
    </style>
    @endverbatim
@endsection

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

    <div class="main-panel referral-page" data-theme="{{ $bg }}">
        <div class="content">
            <div class="zx-app">

                <x-danger-alert/>
                <x-success-alert/>

                <!-- Header -->
                <div class="rf-head">
                    <h1>Refer &amp; earn</h1>
                    <p>Invite friends and earn commissions on their activity</p>
                </div>

                <!-- Referral link hero -->
                <div class="rf-hero">
                    <span class="rf-hero-label">Your referral link</span>
                    <div class="rf-copy">
                        <input type="text" id="referralLink" class="rf-link-input" value="{{ $referralLink }}" readonly>
                        <button class="rf-copy-btn" onclick="copyLink()" id="copyBtn">
                            <i class="fa fa-copy" id="copyIcon"></i>
                            <span id="copyText">Copy</span>
                        </button>
                    </div>
                    <div class="rf-code">
                        <span>Referral code</span>
                        <b>{{ Auth::user()->username }}</b>
                    </div>

                    <div class="rf-share">
                        <a href="https://wa.me/?text=Join%20{{ urlencode($settings->site_name) }}%20and%20start%20trading%20today%21%20Use%20my%20link%3A%20{{ urlencode($referralLink) }}" target="_blank" class="rf-share-btn whatsapp"><i class="fab fa-whatsapp"></i>WhatsApp</a>
                        <a href="https://t.me/share/url?url={{ urlencode($referralLink) }}&text=Join%20{{ urlencode($settings->site_name) }}%20and%20start%20trading%21" target="_blank" class="rf-share-btn telegram"><i class="fab fa-telegram-plane"></i>Telegram</a>
                        <a href="https://twitter.com/intent/tweet?text=Join%20{{ urlencode($settings->site_name) }}%20and%20start%20trading%20crypto%21%20Use%20my%20referral%20link%3A%20{{ urlencode($referralLink) }}" target="_blank" class="rf-share-btn twitter"><i class="fab fa-twitter"></i>Twitter</a>
                        <a href="mailto:?subject=Join%20{{ urlencode($settings->site_name) }}&body=Hey%2C%20I%20think%20you%27d%20love%20this%20trading%20platform.%20Use%20my%20link%20to%20sign%20up%3A%20{{ $referralLink }}" class="rf-share-btn email"><i class="fa fa-envelope"></i>Email</a>
                    </div>
                </div>

                <!-- Stats -->
                <div class="rf-mini-grid">
                    <div class="rf-mini">
                        <span class="rf-mini-ico">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                        </span>
                        <span class="rf-mini-label">Total referrals</span>
                        <span class="rf-mini-val">{{ $refs->count() }}</span>
                    </div>
                    <div class="rf-mini">
                        <span class="rf-mini-ico earn">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><ellipse cx="12" cy="6" rx="8" ry="3"/><path d="M4 6v6c0 1.7 3.6 3 8 3s8-1.3 8-3V6M4 12v6c0 1.7 3.6 3 8 3s8-1.3 8-3v-6"/></svg>
                        </span>
                        <span class="rf-mini-label">Bonus earned</span>
                        <span class="rf-mini-val pos">{{ $settings->currency }}{{ number_format(Auth::user()->ref_bonus ?? 0, 2) }}</span>
                    </div>
                </div>

                {{-- <!-- Commission rates -->
                @php
                    $levels = [
                        ['label' => 'Level 1', 'rate' => $settings->referral_commission1 ?? 0, 'desc' => 'Direct referrals'],
                        ['label' => 'Level 2', 'rate' => $settings->referral_commission2 ?? 0, 'desc' => "Your referrals' referrals"],
                        ['label' => 'Level 3', 'rate' => $settings->referral_commission3 ?? 0, 'desc' => '3rd-tier referrals'],
                        ['label' => 'Level 4', 'rate' => $settings->referral_commission4 ?? 0, 'desc' => '4th-tier referrals'],
                        ['label' => 'Level 5', 'rate' => $settings->referral_commission5 ?? 0, 'desc' => '5th-tier referrals'],
                    ];
                    $hasRates = collect($levels)->where('rate', '>', 0)->isNotEmpty();
                @endphp
                @if($hasRates)
                    <div class="rf-panel">
                        <div class="rf-panel-head"><span class="rf-panel-title">Commission rates</span></div>
                        <div class="rf-levels">
                            @foreach($levels as $level)
                                @if($level['rate'] > 0)
                                    <div class="rf-level">
                                        <div>
                                            <span class="rf-level-label">{{ $level['label'] }}</span>
                                            <span class="rf-level-desc">{{ $level['desc'] }}</span>
                                        </div>
                                        <span class="rf-level-rate">{{ $level['rate'] }}%</span>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                @endif --}}

                <!-- Your referrals -->
                <div class="zx-section">
                    <div class="zx-section-head">
                        <span class="zx-section-label">Your referrals</span>
                        <span class="rf-badge">{{ $refs->count() }}</span>
                    </div>
                    <div class="zx-list">
                        @forelse($refs as $ref)
                            <div class="zx-row">
                                <span class="zx-row-ico">{{ strtoupper(substr($ref->name, 0, 1)) }}</span>
                                <div class="zx-row-main">
                                    <span class="zx-row-title">{{ $ref->name }}</span>
                                    <span class="zx-row-sub">{{ $ref->created_at->format('M d, Y') }}</span>
                                </div>
                                @if($ref->account_bal > 0)
                                    <span class="zx-pill success">Active</span>
                                @else
                                    <span class="zx-pill pending">Pending</span>
                                @endif
                            </div>
                        @empty
                            <div class="zx-empty">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
                                <p>No referrals yet</p>
                                <button class="zx-empty-btn" onclick="copyLink()">Copy referral link</button>
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- How it works -->
                <div class="rf-panel">
                    <div class="rf-panel-head"><span class="rf-panel-title">How it works</span></div>
                    <div class="rf-steps">
                        <div class="rf-step"><span>1</span><div><strong>Share your link</strong><p>Send your unique link to friends or post it on social media.</p></div></div>
                        <div class="rf-step"><span>2</span><div><strong>Friends sign up</strong><p>They register with your link and get linked to your account.</p></div></div>
                        <div class="rf-step"><span>3</span><div><strong>They deposit &amp; trade</strong><p>Your commission is triggered when they fund and trade.</p></div></div>
                        <div class="rf-step"><span>4</span><div><strong>You earn</strong><p>Bonuses are credited to your balance automatically.</p></div></div>
                    </div>
                </div>

                <!-- Tips -->
                <div class="rf-panel rf-tips">
                    <div class="rf-panel-head"><span class="rf-panel-title">Tips to maximise earnings</span></div>
                    <ul class="rf-tips-list">
                        <li>Share across social platforms for wider reach</li>
                        <li>Send your link directly to interested contacts</li>
                        <li>Encourage referrals to deposit sooner to activate commission</li>
                        <li>Multi-level commissions mean deeper networks earn more</li>
                    </ul>
                </div>

            </div>
        </div>
    </div>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Space+Grotesk:wght@500;600;700&display=swap" rel="stylesheet">
    @verbatim
    <style>
    /* ============ Zaraex refer & earn ============ */
    .referral-page{
        --card:#0e1018; --elev:#16181f; --border:#23262f;
        --text:#f2f5fb; --muted:#a1a9b8; --faint:#6b7280;
        --blue:#2563eb; --blue-soft:#3b82f6; --success:#22c55e; --amber:#f59e0b; --danger:#ef4444;
        --tint:rgba(37,99,235,.14);
        font-family:'Inter',system-ui,-apple-system,sans-serif; color:var(--text);
    }
    body[data-theme="light"] .referral-page{
        --card:#ffffff; --elev:#f4f6fa; --border:#e8ecf2; --text:#0f172a; --muted:#5b6674; --faint:#98a2b3;
    }
    .referral-page .content{padding:0}
    .referral-page .zx-app{max-width:520px;margin:0 auto;padding:22px 16px 96px;display:flex;flex-direction:column;gap:18px}
    .referral-page h1{font-family:'Space Grotesk','Inter',sans-serif;letter-spacing:-.02em;margin:0}

    .rf-head h1{font-size:1.5rem;font-weight:700}
    .rf-head p{margin:5px 0 0;color:var(--muted);font-size:.9rem}

    /* Hero */
    .rf-hero{border-radius:22px;padding:22px;color:#fff;background:linear-gradient(160deg,#13233f 0%,#0b1322 100%);box-shadow:0 24px 50px -18px rgba(8,15,30,.7)}
    .rf-hero-label{color:rgba(255,255,255,.6);font-size:.74rem;text-transform:uppercase;letter-spacing:.08em}
    .rf-copy{display:flex;gap:8px;margin-top:12px}
    .referral-page .rf-link-input{flex:1;min-width:0;background:rgba(255,255,255,.08)!important;border:1px solid rgba(255,255,255,.14)!important;
        border-radius:12px!important;color:#fff!important;padding:12px 14px!important;font-size:.85rem!important;box-shadow:none!important}
    .rf-copy-btn{flex:none;display:inline-flex;align-items:center;gap:7px;background:var(--blue);color:#fff;border:none;border-radius:12px;
        padding:12px 16px;font-weight:600;font-size:.88rem;cursor:pointer;transition:.2s}
    .rf-copy-btn:hover{background:var(--blue-soft)}
    .rf-copy-btn.copied{background:var(--success)}
    .rf-code{display:flex;align-items:center;justify-content:space-between;margin-top:14px;padding:11px 14px;border-radius:12px;background:rgba(255,255,255,.06)}
    .rf-code span{color:rgba(255,255,255,.6);font-size:.82rem}
    .rf-code b{font-family:'Space Grotesk',sans-serif;font-weight:700;letter-spacing:.02em}
    .rf-share{display:grid;grid-template-columns:1fr 1fr;gap:8px;margin-top:14px}
    .rf-share-btn{display:flex;align-items:center;justify-content:center;gap:8px;padding:11px;border-radius:11px;font-weight:600;font-size:.85rem;text-decoration:none;color:#fff;transition:.2s}
    .rf-share-btn:hover{opacity:.9;color:#fff}
    .rf-share-btn.whatsapp{background:#25d366}
    .rf-share-btn.telegram{background:#229ed2}
    .rf-share-btn.twitter{background:#1d9bf0}
    .rf-share-btn.email{background:rgba(255,255,255,.14)}

    /* Mini stats */
    .rf-mini-grid{display:grid;grid-template-columns:1fr 1fr;gap:12px}
    .rf-mini{background:var(--card);border:1px solid var(--border);border-radius:16px;padding:16px}
    .rf-mini-ico{width:38px;height:38px;border-radius:11px;display:grid;place-items:center;margin-bottom:12px;background:var(--tint);color:var(--blue-soft)}
    .rf-mini-ico.earn{background:rgba(34,197,94,.14);color:var(--success)}
    .rf-mini-ico svg{width:19px;height:19px}
    .rf-mini-label{display:block;color:var(--faint);font-size:.78rem}
    .rf-mini-val{display:block;font-family:'Space Grotesk',sans-serif;font-weight:700;font-size:1.35rem;margin-top:3px}
    .rf-mini-val.pos{color:var(--success)}

    /* Panels */
    .rf-panel{background:var(--card);border:1px solid var(--border);border-radius:20px;padding:20px}
    .rf-panel-head{margin-bottom:14px}
    .rf-panel-title{font-family:'Space Grotesk','Inter',sans-serif;font-weight:600;font-size:1.05rem}

    .rf-levels{display:flex;flex-direction:column;gap:8px}
    .rf-level{display:flex;align-items:center;justify-content:space-between;gap:12px;padding:12px 14px;border-radius:12px;background:var(--elev);border:1px solid var(--border)}
    .rf-level-label{display:block;font-weight:600;font-size:.9rem}
    .rf-level-desc{display:block;color:var(--faint);font-size:.76rem;margin-top:2px}
    .rf-level-rate{font-family:'Space Grotesk',sans-serif;font-weight:700;font-size:1.1rem;color:var(--blue-soft)}

    .rf-steps{display:flex;flex-direction:column;gap:16px}
    .rf-step{display:flex;gap:12px}
    .rf-step>span{width:28px;height:28px;flex:none;border-radius:9px;display:grid;place-items:center;background:var(--tint);color:var(--blue-soft);font-family:'Space Grotesk',sans-serif;font-weight:700;font-size:.82rem}
    .rf-step strong{font-size:.94rem;font-weight:600}
    .rf-step p{margin:3px 0 0;color:var(--muted);font-size:.85rem;line-height:1.5}

    .rf-tips-list{margin:0;padding:0;list-style:none;display:flex;flex-direction:column;gap:11px}
    .rf-tips-list li{position:relative;padding-left:26px;color:var(--muted);font-size:.88rem;line-height:1.5}
    .rf-tips-list li::before{content:"";position:absolute;left:0;top:3px;width:16px;height:16px;border-radius:50%;
        background:rgba(34,197,94,.16) url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='11' height='11' viewBox='0 0 24 24' fill='none' stroke='%2322c55e' stroke-width='3' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='M5 12l5 5 9-11'/%3E%3C/svg%3E") center/11px no-repeat}

    /* Sections + lists */
    .zx-section{display:flex;flex-direction:column;gap:10px}
    .zx-section-head{display:flex;align-items:center;justify-content:space-between;padding:0 4px}
    .zx-section-label{text-transform:uppercase;letter-spacing:.08em;font-size:.75rem;font-weight:700;color:var(--faint)}
    .rf-badge{min-width:22px;height:22px;padding:0 7px;border-radius:999px;display:grid;place-items:center;background:var(--tint);color:var(--blue-soft);font-size:.72rem;font-weight:700}
    .zx-list{background:var(--card);border:1px solid var(--border);border-radius:18px;overflow:hidden}
    .zx-row{display:flex;align-items:center;gap:14px;padding:14px 16px;border-bottom:1px solid var(--border)}
    .zx-row:last-child{border-bottom:0}
    .zx-row-ico{width:42px;height:42px;border-radius:50%;flex:none;display:grid;place-items:center;font-weight:700;font-size:.95rem;color:#fff;background:linear-gradient(135deg,var(--blue),var(--blue-soft))}
    .zx-row-main{display:flex;flex-direction:column;flex:1;min-width:0}
    .zx-row-title{font-weight:600;font-size:.94rem;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
    .zx-row-sub{color:var(--faint);font-size:.78rem;margin-top:2px}
    .zx-pill{font-size:.72rem;font-weight:600;padding:4px 10px;border-radius:999px}
    .zx-pill.success{background:rgba(34,197,94,.14);color:var(--success)}
    .zx-pill.pending{background:rgba(245,158,11,.14);color:var(--amber)}

    .zx-empty{display:flex;flex-direction:column;align-items:center;text-align:center;gap:8px;padding:34px 16px;color:var(--muted)}
    .zx-empty svg{width:32px;height:32px;color:var(--faint)}
    .zx-empty p{margin:0;font-size:.9rem}
    .zx-empty-btn{background:none;border:none;color:var(--blue-soft);font-weight:600;font-size:.85rem;cursor:pointer}
    </style>
    @endverbatim

    <script>
        function copyLink() {
            var input = document.getElementById('referralLink');
            var btn = document.getElementById('copyBtn');
            var icon = document.getElementById('copyIcon');
            var text = document.getElementById('copyText');

            if (navigator.clipboard) {
                navigator.clipboard.writeText(input.value).then(function() {
                    showCopied(btn, icon, text);
                });
            } else {
                input.select();
                document.execCommand('copy');
                showCopied(btn, icon, text);
            }
        }

        function showCopied(btn, icon, text) {
            btn.classList.add('copied');
            icon.className = 'fa fa-check';
            text.textContent = 'Copied!';
            setTimeout(function() {
                btn.classList.remove('copied');
                icon.className = 'fa fa-copy';
                text.textContent = 'Copy';
            }, 2500);
        }
    </script>

@endsection

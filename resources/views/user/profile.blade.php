<?php
	if (Auth::user()->dashboard_style == "light") {
		$bgmenu="blue";
    $bg="light";
    $text = "dark";
} else {
    $bgmenu="dark";
    $bg="dark";
    $text = "light";

}
?>
@extends('layouts.app')
    @section('content')
        @include('user.topmenu')
        @include('user.sidebar')

        <div class="main-panel profile-page" data-theme="{{ $bg }}">
            <div class="content">
                <div class="zx-app">
                    <x-danger-alert/>
                    <x-success-alert/>
                    <x-error-alert/>

                    @php $initial = strtoupper(substr(Auth::user()->name, 0, 1)); @endphp

                    <!-- Profile header -->
                    <div class="pf-header">
                        <span class="pf-avatar">{{ $initial }}</span>
                        <div class="pf-id">
                            <h1>{{ Auth::user()->name }}</h1>
                            <p>{{ Auth::user()->email }}</p>
                        </div>
                        <form method="POST" action="{{ route('logout') }}" id="pfLogoutForm">@csrf</form>
                        <button type="button" class="pf-logout" onclick="document.getElementById('pfLogoutForm').submit()" title="Log out">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4M16 17l5-5-5-5M21 12H9"/></svg>
                        </button>
                    </div>

                    <!-- Refer & Earn -->
                    <div class="zx-refer">
                        <div class="zx-refer-ico">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 11h-6M19 8v6"/></svg>
                        </div>
                        <div class="zx-refer-txt">
                            <h4>Refer &amp; Earn</h4>
                            <p>Invite friends to Zaraex and earn rewards on their activity.</p>
                        </div>
                        <div class="zx-refer-actions">
                            <a href="{{ url('dashboard/referuser') }}" class="zx-refer-btn primary">Invite friends</a>
                            <a href="{{ url('dashboard/referral-earnings') }}" class="zx-refer-btn ghost">View earnings</a>
                        </div>
                    </div>

                    <!-- Zarex account link -->
                    @if(config('services.zarex.authorize_url'))
                        <div class="pf-card" style="margin-top:0">
                            @if(empty(Auth::user()->zarex_user_id))
                                <h4 style="margin-bottom:8px">Connect your Zarex account</h4>
                                <p style="color:var(--muted);font-size:.9rem;margin-bottom:14px">Link your Zaraex account to deposit and withdraw straight from your Zarex balance.</p>
                                <a href="{{ config('services.zarex.authorize_url') }}" class="btn-primary" style="display:inline-block;text-decoration:none">Connect Zarex account</a>
                            @else
                                <h4 style="margin-bottom:4px">Zarex account connected</h4>
                                <p style="color:var(--muted);font-size:.9rem;margin:0">You can deposit and withdraw using your Zarex balance.</p>
                            @endif
                        </div>
                    @endif

                    <!-- Settings -->
                    <ul class="nav nav-pills pf-tabs" role="tablist">
                        <li class="nav-item">
                            <a href="#per" class="nav-link active" data-toggle="tab">Personal</a>
                        </li>
                        <li class="nav-item">
                            <a href="#pas" class="nav-link" data-toggle="tab">Security</a>
                        </li>
                        <li class="nav-item">
                            <a href="#set" class="nav-link" data-toggle="tab">Withdrawal</a>
                        </li>
                    </ul>

                    <div class="tab-content">
                        <div class="tab-pane fade show active" id="per">
                            <div class="pf-card">
                                @include('profile.update-profile-information-form')
                            </div>
                        </div>
                        <div class="tab-pane fade" id="pas">
                            <div class="pf-card">
                                @include('profile.update-password-form')
                            </div>
                        </div>
                        <div class="tab-pane fade" id="set">
                            <div class="pf-card">
                                @include('profile.update-withdrawal-method')
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Space+Grotesk:wght@500;600;700&display=swap" rel="stylesheet">
    @verbatim
    <style>
    /* ============ Zaraex profile ============ */
    .profile-page{
        --card:#0e1018; --elev:#16181f; --border:#23262f;
        --text:#f2f5fb; --muted:#a1a9b8; --faint:#6b7280;
        --blue:#2563eb; --blue-soft:#3b82f6; --success:#22c55e; --amber:#f59e0b; --danger:#ef4444;
        --tint:rgba(37,99,235,.14);
        font-family:'Inter',system-ui,-apple-system,sans-serif; color:var(--text);
    }
    body[data-theme="light"] .profile-page{
        --card:#ffffff; --elev:#f4f6fa; --border:#e8ecf2;
        --text:#0f172a; --muted:#5b6674; --faint:#98a2b3;
    }
    .profile-page .content{padding:0}
    .profile-page .zx-app{max-width:520px;margin:0 auto;padding:22px 16px 96px;display:flex;flex-direction:column;gap:18px}
    .profile-page h1,.profile-page h4{font-family:'Space Grotesk','Inter',sans-serif;letter-spacing:-.02em;margin:0}

    /* Header */
    .pf-header{display:flex;align-items:center;gap:14px;background:var(--card);border:1px solid var(--border);border-radius:20px;padding:18px}
    .pf-avatar{width:56px;height:56px;border-radius:50%;flex:none;display:grid;place-items:center;font-family:'Space Grotesk',sans-serif;
        font-weight:700;font-size:1.4rem;color:#fff;background:linear-gradient(135deg,var(--blue),var(--blue-soft))}
    .pf-id{flex:1;min-width:0}
    .pf-id h1{font-size:1.2rem;font-weight:700;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
    .pf-id p{margin:3px 0 0;color:var(--muted);font-size:.86rem;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
    .pf-logout{flex:none;width:42px;height:42px;border-radius:12px;display:grid;place-items:center;cursor:pointer;
        background:rgba(239,68,68,.12);border:1px solid rgba(239,68,68,.25);color:var(--danger);transition:.2s}
    .pf-logout:hover{background:rgba(239,68,68,.2)}
    .pf-logout svg{width:19px;height:19px}

    /* Refer card */
    .zx-refer{display:flex;align-items:center;gap:18px;flex-wrap:wrap;padding:22px;border-radius:20px;
        background:linear-gradient(135deg,#2563eb 0%,#1d4ed8 100%);color:#fff;box-shadow:0 18px 40px -16px rgba(37,99,235,.6)}
    .zx-refer-ico{width:52px;height:52px;border-radius:14px;flex:none;display:grid;place-items:center;background:rgba(255,255,255,.16)}
    .zx-refer-ico svg{width:26px;height:26px}
    .zx-refer-txt{flex:1;min-width:180px}
    .zx-refer-txt h4{font-weight:700;font-size:1.25rem}
    .zx-refer-txt p{margin:5px 0 0;color:rgba(255,255,255,.82);font-size:.9rem}
    .zx-refer-actions{display:flex;gap:10px;flex-wrap:wrap}
    .zx-refer-btn{padding:11px 18px;border-radius:11px;font-weight:600;font-size:.9rem;text-decoration:none;transition:.2s}
    .zx-refer-btn.primary{background:#fff;color:#2563eb}
    .zx-refer-btn.primary:hover{background:#eef2ff;color:#2563eb}
    .zx-refer-btn.ghost{background:rgba(255,255,255,.14);color:#fff}
    .zx-refer-btn.ghost:hover{background:rgba(255,255,255,.24);color:#fff}

    /* Tabs */
    .profile-page .pf-tabs{display:flex;gap:6px;background:var(--elev);border:1px solid var(--border);border-radius:14px;padding:5px;margin:0;list-style:none;flex-wrap:nowrap}
    .profile-page .pf-tabs .nav-item{flex:1}
    .profile-page .pf-tabs .nav-link{display:block;text-align:center;padding:10px 8px;border-radius:10px;color:var(--muted);
        font-weight:600;font-size:.86rem;background:transparent;border:none;transition:.2s}
    .profile-page .pf-tabs .nav-link:hover{color:var(--text)}
    .profile-page .pf-tabs .nav-link.active{background:var(--blue);color:#fff}

    /* Card + forms */
    .pf-card{background:var(--card);border:1px solid var(--border);border-radius:20px;padding:22px;margin-top:16px}
    .profile-page .form-row{display:block;margin:0}
    .profile-page .form-group{width:100%!important;max-width:100%!important;flex:0 0 100%!important;padding:0!important;margin-bottom:16px!important}
    .profile-page .form-group h5,.profile-page label{font-family:'Inter',sans-serif!important;font-size:.8rem!important;font-weight:600!important;
        text-transform:uppercase;letter-spacing:.04em;color:var(--faint)!important;margin-bottom:8px!important}
    .profile-page .form-control,.profile-page input[type="text"],.profile-page input[type="email"],
    .profile-page input[type="password"],.profile-page input[type="date"],.profile-page input[type="tel"],
    .profile-page input[type="number"],.profile-page select,.profile-page textarea{
        width:100%!important;background:var(--elev)!important;border:1px solid var(--border)!important;border-radius:12px!important;
        color:var(--text)!important;padding:13px 15px!important;font-size:.95rem!important;box-shadow:none!important;transition:.2s!important}
    .profile-page .form-control:focus,.profile-page input:focus,.profile-page select:focus,.profile-page textarea:focus{
        border-color:var(--blue)!important;box-shadow:0 0 0 3px rgba(37,99,235,.15)!important;outline:none!important}
    .profile-page .form-control[readonly],.profile-page input[readonly]{opacity:.7;cursor:not-allowed}
    .profile-page .form-control::placeholder{color:var(--faint)!important}
    .profile-page textarea{min-height:80px;resize:vertical}
    .profile-page .btn-primary,.profile-page button[type="submit"]{background:var(--blue)!important;border:none!important;border-radius:12px!important;
        padding:13px 24px!important;font-weight:600!important;font-size:.95rem!important;color:#fff!important;box-shadow:none!important;transition:.2s!important}
    .profile-page .btn-primary:hover,.profile-page button[type="submit"]:hover{background:var(--blue-soft)!important;transform:translateY(-1px)}
    .profile-page .btn-danger{background:var(--danger)!important;border:none!important;border-radius:12px!important}
    .profile-page h3,.profile-page h4:not(.zx-refer-txt h4){color:var(--text)}
    .profile-page hr{border-color:var(--border)!important}
    </style>
    @endverbatim
    @endsection

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Zaraex — Trade smarter, on autopilot</title>
  <meta name="description" content="Zaraex is a modern automated trading platform. Deposit crypto, choose a strategy, and let automated execution grow your portfolio." />
  <meta name="theme-color" content="#ffffff" media="(prefers-color-scheme: light)" />
  <meta name="theme-color" content="#000000" media="(prefers-color-scheme: dark)" />
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Space+Grotesk:wght@500;600;700&display=swap" rel="stylesheet">
  <style>
    :root{
      /* Dark mode (default) — black canvas */
      --bg:#000000;
      --surface:#0b0d14;
      --card:#0e1018;
      --elevated:#16181f;
      --border:#23262f;
      --border-soft:#191b22;
      --text:#f2f5fb;
      --muted:#a1a9b8;
      --faint:#6b7280;
      --panel:rgba(255,255,255,.03);
      --glass:rgba(255,255,255,.04);
      --nav-bg:rgba(0,0,0,.72);
      --spark-bg:rgba(255,255,255,.03);
      --card-shadow:0 40px 80px rgba(0,0,0,.5);
      --glow-1:rgba(37,99,235,.18);
      --glow-2:rgba(59,130,246,.10);
      --primary:#2563eb;
      --primary-soft:#3b82f6;
      --accent:#f59e0b;
      --success:#22c55e;
      --danger:#ef4444;
      --max:1180px;
      --radius:18px;
    }
    @media (prefers-color-scheme: light){
      :root{
        /* Light mode — white canvas */
        --bg:#ffffff;
        --surface:#f8fafc;
        --card:#ffffff;
        --elevated:#ffffff;
        --border:#e5e9f0;
        --border-soft:#eef1f6;
        --text:#0f172a;
        --muted:#475569;
        --faint:#94a3b8;
        --panel:#f8fafc;
        --glass:#ffffff;
        --nav-bg:rgba(255,255,255,.82);
        --spark-bg:#f1f5f9;
        --card-shadow:0 30px 60px rgba(15,23,42,.12);
        --glow-1:rgba(37,99,235,.10);
        --glow-2:rgba(59,130,246,.07);
      }
    }
    *{box-sizing:border-box}
    html{scroll-behavior:smooth}
    body{
      margin:0;
      font-family:'Inter',system-ui,-apple-system,sans-serif;
      color:var(--text);
      background:
        radial-gradient(60rem 40rem at 15% -5%, var(--glow-1), transparent 60%),
        radial-gradient(50rem 34rem at 100% 8%, var(--glow-2), transparent 60%),
        var(--bg);
      -webkit-font-smoothing:antialiased;
      overflow-x:hidden;
    }
    a{text-decoration:none;color:inherit}
    img{max-width:100%;display:block}
    h1,h2,h3{font-family:'Space Grotesk','Inter',sans-serif;letter-spacing:-.02em;margin:0}
    .wrap{width:min(calc(100% - 40px),var(--max));margin:0 auto}
    .muted{color:var(--muted)}
    ::selection{background:rgba(37,99,235,.35);color:#fff}

    /* Buttons */
    .btn{display:inline-flex;align-items:center;justify-content:center;gap:8px;font-weight:600;font-size:.95rem;
      padding:12px 20px;border-radius:12px;border:1px solid transparent;cursor:pointer;transition:.2s ease;white-space:nowrap}
    .btn-primary{background:var(--primary);color:#fff;box-shadow:0 10px 30px rgba(37,99,235,.30)}
    .btn-primary:hover{background:var(--primary-soft);transform:translateY(-1px)}
    .btn-ghost{background:var(--panel);border-color:var(--border);color:var(--text)}
    .btn-ghost:hover{border-color:rgba(59,130,246,.5)}
    .btn-lg{padding:15px 26px;font-size:1rem;border-radius:14px}

    /* Nav */
    header.nav{position:sticky;top:0;z-index:50;background:var(--nav-bg);backdrop-filter:blur(16px);
      border-bottom:1px solid var(--border-soft)}
    .nav-inner{display:flex;align-items:center;justify-content:space-between;gap:16px;padding:16px 0}
    .brand{display:flex;align-items:center;gap:10px;font-family:'Space Grotesk',sans-serif;font-weight:700;font-size:1.25rem}
    .badge{width:36px;height:36px;border-radius:11px;display:grid;place-items:center;color:#fff;font-weight:700;
      background:linear-gradient(135deg,var(--primary),var(--primary-soft));box-shadow:0 8px 20px rgba(37,99,235,.35)}
    .nav-links{display:flex;align-items:center;gap:30px}
    .nav-links a{color:var(--muted);font-weight:500;font-size:.95rem;transition:.2s}
    .nav-links a:hover{color:var(--text)}
    .nav-cta{display:flex;align-items:center;gap:10px}
    .nav-login{color:var(--muted);font-weight:600;padding:10px 14px;border-radius:10px}
    .nav-login:hover{color:var(--text)}
    .hamburger{display:none;width:40px;height:40px;border-radius:10px;border:1px solid var(--border);
      background:var(--panel);color:var(--muted);cursor:pointer;place-items:center}
    .mobile-menu{display:none;border-top:1px solid var(--border-soft);padding:12px 0}
    .mobile-menu a{display:block;padding:11px 4px;color:var(--muted);font-weight:500}
    .mobile-menu a:hover{color:var(--text)}

    /* Pills / eyebrow */
    .pill{display:inline-flex;align-items:center;gap:8px;padding:6px 14px;border-radius:999px;
      border:1px solid var(--border);background:var(--panel);font-size:.82rem;color:var(--muted);font-weight:500}
    .dot{width:8px;height:8px;border-radius:50%;background:var(--success);box-shadow:0 0 0 4px rgba(34,197,94,.18)}

    /* Hero */
    .hero{padding:80px 0 64px}
    .hero-grid{display:grid;grid-template-columns:1.05fr .95fr;gap:56px;align-items:center}
    .hero h1{font-size:clamp(2.4rem,5vw,3.7rem);line-height:1.05;font-weight:700;margin:22px 0 0}
    .grad{background:linear-gradient(90deg,var(--primary-soft),#7dd3fc);-webkit-background-clip:text;background-clip:text;color:transparent}
    .hero .lead{font-size:1.12rem;line-height:1.7;color:var(--muted);margin:22px 0 0;max-width:34rem}
    .hero-actions{display:flex;gap:12px;margin-top:32px;flex-wrap:wrap}
    .trust{display:flex;align-items:center;gap:14px;margin-top:32px}
    .avatars{display:flex}
    .avatars span{width:36px;height:36px;border-radius:50%;border:2px solid var(--bg);margin-left:-8px;
      display:grid;place-items:center;font-size:.72rem;font-weight:700;color:#fff;
      background:linear-gradient(135deg,var(--primary),var(--accent))}
    .avatars span:first-child{margin-left:0}
    .trust p{margin:0;color:var(--muted);font-size:.9rem}
    .trust strong{color:var(--text)}

    /* Hero card */
    .glass{background:var(--glass);
      border:1px solid var(--border);border-radius:22px;backdrop-filter:blur(12px)}
    .hero-card{position:relative;padding:22px;box-shadow:var(--card-shadow)}
    .hc-head{display:flex;align-items:center;justify-content:space-between}
    .hc-user{display:flex;align-items:center;gap:12px}
    .hc-ava{width:44px;height:44px;border-radius:50%;display:grid;place-items:center;font-weight:700;color:#fff;
      background:linear-gradient(135deg,var(--primary),var(--accent))}
    .tag-live{font-size:.74rem;font-weight:600;color:var(--success);background:rgba(34,197,94,.14);padding:5px 10px;border-radius:999px}
    .hc-balance{margin-top:22px}
    .hc-balance .lbl{color:var(--faint);font-size:.8rem}
    .hc-balance .val{font-family:'Space Grotesk',sans-serif;font-size:2.1rem;font-weight:700;margin-top:2px}
    .hc-balance .val small{color:var(--success);font-size:1rem;font-weight:600}
    .spark{margin-top:16px;height:96px;width:100%;border-radius:14px;background:var(--spark-bg);padding:10px;border:1px solid var(--border-soft)}
    .chip{position:absolute;background:var(--elevated);border:1px solid var(--border);border-radius:14px;padding:11px 14px;
      box-shadow:0 20px 40px rgba(0,0,0,.4);font-size:.82rem}
    .chip small{color:var(--faint);display:block;margin-bottom:2px}
    .chip b{font-weight:600}
    .chip-1{bottom:-22px;left:-20px}
    .chip-2{top:-16px;right:-14px;color:var(--accent);font-weight:600}
    @keyframes floaty{0%,100%{transform:translateY(0)}50%{transform:translateY(-12px)}}
    .float{animation:floaty 6s ease-in-out infinite}

    /* Ticker */
    .ticker{border-top:1px solid var(--border-soft);border-bottom:1px solid var(--border-soft);
      background:var(--panel);overflow:hidden;padding:12px 0}
    .ticker-track{display:flex;gap:36px;width:max-content;animation:scroll 30s linear infinite;white-space:nowrap}
    @keyframes scroll{from{transform:translateX(0)}to{transform:translateX(-50%)}}
    .ticker-item{display:flex;align-items:center;gap:8px;color:var(--muted);font-size:.9rem;font-weight:500}
    .ticker-item i{width:6px;height:6px;border-radius:50%;background:var(--primary-soft);display:inline-block}
    .up{color:var(--success)}.down{color:var(--danger)}

    /* Section */
    .section{padding:88px 0}
    .sec-head{max-width:42rem;margin:0 auto;text-align:center}
    .kicker{color:var(--primary-soft);font-weight:600;font-size:.82rem;letter-spacing:.12em;text-transform:uppercase}
    .sec-head h2{font-size:clamp(1.9rem,3.4vw,2.6rem);font-weight:700;margin:12px 0 0}
    .sec-head p{color:var(--muted);margin:16px 0 0;line-height:1.7}

    /* Stats */
    .stats{display:grid;grid-template-columns:repeat(4,1fr);border:1px solid var(--border);border-radius:20px;overflow:hidden}
    .stat{padding:30px 22px;text-align:center;border-right:1px solid var(--border);background:var(--panel)}
    .stat:last-child{border-right:0}
    .stat .n{font-family:'Space Grotesk',sans-serif;font-size:2rem;font-weight:700}
    .stat .l{color:var(--muted);font-size:.9rem;margin-top:6px}

    /* Cards grid */
    .grid{display:grid;gap:20px;margin-top:56px}
    .grid-3{grid-template-columns:repeat(3,1fr)}
    .card{padding:26px;border-radius:var(--radius);border:1px solid var(--border);background:var(--card);transition:.25s}
    .card:hover{border-color:rgba(59,130,246,.4);transform:translateY(-3px)}
    .ico{width:46px;height:46px;border-radius:12px;display:grid;place-items:center;color:var(--primary-soft);
      background:rgba(37,99,235,.14);margin-bottom:18px}
    .card h3{font-size:1.18rem;font-weight:600}
    .card p{color:var(--muted);line-height:1.7;margin:10px 0 0;font-size:.96rem}
    .step-n{font-family:'Space Grotesk',sans-serif;font-size:.9rem;font-weight:700;color:var(--primary-soft);
      background:rgba(37,99,235,.14);width:44px;height:44px;border-radius:12px;display:grid;place-items:center;margin-bottom:18px}

    /* CTA */
    .cta{position:relative;overflow:hidden;text-align:center;padding:72px 24px;border-radius:28px;
      border:1px solid var(--border);background:linear-gradient(180deg,var(--surface),var(--card))}
    .cta::before{content:"";position:absolute;top:-40%;left:50%;transform:translateX(-50%);
      width:40rem;height:24rem;background:radial-gradient(circle,rgba(37,99,235,.28),transparent 70%);pointer-events:none}
    .cta h2{position:relative;font-size:clamp(2rem,4vw,3rem);font-weight:700}
    .cta p{position:relative;color:var(--muted);max-width:38rem;margin:18px auto 0;line-height:1.7}
    .cta-actions{position:relative;display:flex;gap:12px;justify-content:center;margin-top:32px;flex-wrap:wrap}
    .cta small{position:relative;display:block;color:var(--faint);font-size:.82rem;margin-top:22px}

    /* Footer */
    footer.ft{border-top:1px solid var(--border-soft);padding:56px 0 30px;margin-top:20px}
    .ft-grid{display:grid;grid-template-columns:1.6fr 1fr 1fr 1fr;gap:40px}
    .ft-brand p{color:var(--muted);line-height:1.7;margin:16px 0 0;max-width:20rem;font-size:.95rem}
    .ft-col h4{font-size:.95rem;font-weight:600;margin:0 0 16px}
    .ft-col a{display:block;color:var(--muted);font-size:.92rem;padding:6px 0;transition:.2s}
    .ft-col a:hover{color:var(--text)}
    .ft-bottom{display:flex;justify-content:space-between;gap:16px;flex-wrap:wrap;
      border-top:1px solid var(--border-soft);margin-top:40px;padding-top:24px;color:var(--faint);font-size:.82rem}

    /* Reveal */
    .reveal{opacity:0;transform:translateY(18px);transition:.6s cubic-bezier(.2,.7,.2,1)}
    .reveal.in{opacity:1;transform:none}

    @media(max-width:900px){
      .hero-grid{grid-template-columns:1fr;gap:44px}
      .hero-visual{order:-1}
      .grid-3{grid-template-columns:1fr}
      .stats{grid-template-columns:repeat(2,1fr)}
      .stat:nth-child(2){border-right:0}
      .stat:nth-child(1),.stat:nth-child(2){border-bottom:1px solid var(--border)}
      .ft-grid{grid-template-columns:1fr 1fr}
      .nav-links{display:none}
      .hamburger{display:grid}
      .nav-login{display:none}
    }
    @media(max-width:560px){
      .stats{grid-template-columns:1fr 1fr}
      .ft-grid{grid-template-columns:1fr}
      .chip-1,.chip-2{display:none}
    }
  </style>
</head>
<body>

  <header class="nav">
    <div class="wrap nav-inner">
      <a href="{{ route('home') }}" class="brand">
        <span class="badge">Z</span> Zaraex
      </a>
      <nav class="nav-links">
        <a href="#features">Platform</a>
        <a href="#how">How it works</a>
        <a href="{{ route('faq') }}">FAQ</a>
        <a href="{{ route('about') }}">About</a>
      </nav>
      <div class="nav-cta">
        <a href="{{ route('login') }}" class="nav-login">Log in</a>
        <a href="{{ route('register') }}" class="btn btn-primary">Get started</a>
        <button class="hamburger" id="hamburger" aria-label="Menu">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M4 7h16M4 12h16M4 17h16"/></svg>
        </button>
      </div>
    </div>
    <div class="wrap mobile-menu" id="mobileMenu">
      <a href="#features">Platform</a>
      <a href="#how">How it works</a>
      <a href="{{ route('faq') }}">FAQ</a>
      <a href="{{ route('about') }}">About</a>
      <a href="{{ route('login') }}">Log in</a>
    </div>
  </header>

  <!-- HERO -->
  <section class="hero">
    <div class="wrap hero-grid">
      <div>
        <span class="pill"><span class="dot"></span> Automated trading, reimagined</span>
        <h1>Trade smarter.<br>Earn better. <span class="grad">On autopilot.</span></h1>
        <p class="lead">
          Deposit crypto, choose a strategy, and let Zaraex analyze the market and execute trades intelligently — around the clock, no experience required.
        </p>
        <div class="hero-actions">
          <a href="{{ route('register') }}" class="btn btn-primary btn-lg">
            Start trading free
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
          </a>
          <a href="#features" class="btn btn-ghost btn-lg">Explore the platform</a>
        </div>
        <div class="trust">
          <div class="avatars">
            <span>A</span><span>L</span><span>S</span><span>K</span>
          </div>
          <p><strong>120,000+</strong> traders growing their portfolios worldwide</p>
        </div>
      </div>

      <div class="hero-visual">
        <div class="glass hero-card float">
          <div class="hc-head">
            <div class="hc-user">
              <span class="hc-ava">ZX</span>
              <div>
                <div style="font-weight:600;font-size:.95rem">My Portfolio</div>
                <div style="color:var(--faint);font-size:.8rem">Balanced strategy</div>
              </div>
            </div>
            <span class="tag-live">● Live</span>
          </div>

          <div class="hc-balance">
            <div class="lbl">Total balance</div>
            <div class="val">$48,213.90 <small>+12.4%</small></div>
          </div>

          <div class="spark">
            <svg viewBox="0 0 100 34" preserveAspectRatio="none" width="100%" height="100%">
              <defs>
                <linearGradient id="g" x1="0" y1="0" x2="0" y2="1">
                  <stop offset="0%" stop-color="#3b82f6" stop-opacity=".35"/>
                  <stop offset="100%" stop-color="#3b82f6" stop-opacity="0"/>
                </linearGradient>
              </defs>
              <polyline points="0,34 2,26 14,28 26,20 38,23 50,14 62,17 74,9 86,12 100,4 100,34" fill="url(#g)"/>
              <polyline points="2,26 14,28 26,20 38,23 50,14 62,17 74,9 86,12 100,4" fill="none" stroke="#3b82f6" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
          </div>

          <div class="chip chip-1 float" style="animation-delay:1s">
            <small>Executed just now</small>
            <b>BTC long · <span class="up">+2.4%</span></b>
          </div>
          <div class="chip chip-2 float" style="animation-delay:2s">◆ Verified strategy</div>
        </div>
      </div>
    </div>
  </section>

  <!-- TICKER -->
  <div class="ticker">
    <div class="ticker-track">
      @php $ticks = ['BTC +4.2%','ETH +2.8%','SOL +9.1%','XAU +0.6%','EUR/USD +0.3%','BNB +3.4%','ADA +5.7%','NASDAQ +0.9%']; @endphp
      @foreach(array_merge($ticks,$ticks) as $t)
        <span class="ticker-item"><i></i>{{ $t }}</span>
      @endforeach
    </div>
  </div>

  <!-- STATS -->
  <section class="section" style="padding-top:64px">
    <div class="wrap">
      <div class="stats reveal">
        <div class="stat"><div class="n">$2.4B</div><div class="l">Traded volume</div></div>
        <div class="stat"><div class="n">120K+</div><div class="l">Active traders</div></div>
        <div class="stat"><div class="n">99.9%</div><div class="l">Platform uptime</div></div>
        <div class="stat"><div class="n">90+</div><div class="l">Markets covered</div></div>
      </div>
    </div>
  </section>

  <!-- FEATURES -->
  <section class="section" id="features" style="padding-top:24px">
    <div class="wrap">
      <div class="sec-head reveal">
        <div class="kicker">The platform</div>
        <h2>Everything you need to trade smarter</h2>
        <p>A complete automated-trading stack built for transparency, speed, and control.</p>
      </div>
      <div class="grid grid-3">
        <div class="card reveal">
          <div class="ico"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M13 2L3 14h7l-1 8 10-12h-7l1-8z"/></svg></div>
          <h3>Automated execution</h3>
          <p>Strategies scan the market and place trades in real time, so you never miss an entry or exit.</p>
        </div>
        <div class="card reveal">
          <div class="ico"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10zM9 12l2 2 4-4"/></svg></div>
          <h3>Verified strategies</h3>
          <p>Every strategy has a transparent, auditable track record. No screenshots, just proof.</p>
        </div>
        <div class="card reveal">
          <div class="ico"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 14a2 2 0 100-4 2 2 0 000 4z"/><path d="M12 12l3-3M4 20a8 8 0 1116 0"/></svg></div>
          <h3>Built-in risk controls</h3>
          <p>Set stop-loss, max drawdown, and per-trade caps. Stay in control of your capital, always.</p>
        </div>
        <div class="card reveal">
          <div class="ico"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><ellipse cx="9" cy="7" rx="6" ry="3"/><path d="M3 7v5c0 1.7 2.7 3 6 3M15 10c3.3 0 6 1.3 6 3s-2.7 3-6 3-6-1.3-6-3"/><path d="M21 13v4c0 1.7-2.7 3-6 3"/></svg></div>
          <h3>Instant crypto rails</h3>
          <p>Deposit and withdraw in USDT, BTC, or your local currency with near-instant settlement.</p>
        </div>
        <div class="card reveal">
          <div class="ico"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 3v18h18"/><path d="M7 14l3-4 3 3 5-7"/></svg></div>
          <h3>Portfolio analytics</h3>
          <p>Live P&amp;L, exposure, and performance attribution across every strategy you run.</p>
        </div>
        <div class="card reveal">
          <div class="ico"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="4" y="11" width="16" height="10" rx="2"/><path d="M8 11V7a4 4 0 118 0v4"/></svg></div>
          <h3>Bank-grade security</h3>
          <p>Cold-storage custody, 2FA, and hardened infrastructure protect your funds around the clock.</p>
        </div>
      </div>
    </div>
  </section>

  <!-- HOW IT WORKS -->
  <section class="section" id="how" style="padding-top:24px">
    <div class="wrap">
      <div class="sec-head reveal">
        <div class="kicker">How it works</div>
        <h2>Start earning in three steps</h2>
        <p>From sign-up to your first automated trade takes less than five minutes.</p>
      </div>
      <div class="grid grid-3">
        <div class="card reveal">
          <div class="step-n">01</div>
          <h3>Create &amp; fund</h3>
          <p>Open a free account and deposit crypto or local currency. Funds land in your wallet instantly.</p>
        </div>
        <div class="card reveal">
          <div class="step-n">02</div>
          <h3>Choose a strategy</h3>
          <p>Browse verified strategies and plans, filter by risk and performance, then activate in one tap.</p>
        </div>
        <div class="card reveal">
          <div class="step-n">03</div>
          <h3>Earn on autopilot</h3>
          <p>Zaraex executes trades for you in real time, sized to your balance and your risk limits.</p>
        </div>
      </div>
    </div>
  </section>

  <!-- CTA -->
  <section class="section" style="padding-top:24px">
    <div class="wrap">
      <div class="cta reveal">
        <h2>Ready to trade smarter?</h2>
        <p>Open a free account, fund it in seconds, and let automated execution work your portfolio — no monthly fees, withdraw anytime.</p>
        <div class="cta-actions">
          <a href="{{ route('register') }}" class="btn btn-primary btn-lg">Create free account</a>
          <a href="{{ route('contact') }}" class="btn btn-ghost btn-lg">Talk to our team</a>
        </div>
        <small>No credit card required · Regulated custody · Cancel anytime</small>
      </div>
    </div>
  </section>

  <!-- FOOTER -->
  <footer class="ft">
    <div class="wrap">
      <div class="ft-grid">
        <div class="ft-brand">
          <a href="{{ route('home') }}" class="brand"><span class="badge">Z</span> Zaraex</a>
          <p>A modern automated trading platform. Deposit, choose a strategy, and let your portfolio trade itself.</p>
        </div>
        <div class="ft-col">
          <h4>Product</h4>
          <a href="#features">Platform</a>
          <a href="#how">How it works</a>
          <a href="{{ route('faq') }}">FAQ</a>
          <a href="{{ route('register') }}">Get started</a>
        </div>
        <div class="ft-col">
          <h4>Company</h4>
          <a href="{{ route('about') }}">About</a>
          <a href="{{ route('contact') }}">Contact</a>
          <a href="{{ route('login') }}">Log in</a>
        </div>
        <div class="ft-col">
          <h4>Legal</h4>
          <a href="{{ route('terms') }}">Terms</a>
          <a href="{{ route('privacy') }}">Privacy</a>
        </div>
      </div>
      <div class="ft-bottom">
        <span>© {{ date('Y') }} Zaraex. All rights reserved.</span>
        <span>Trading involves risk. Past performance is not indicative of future results.</span>
      </div>
    </div>
  </footer>

  <script>
    // Mobile menu
    var hb = document.getElementById('hamburger');
    var mm = document.getElementById('mobileMenu');
    if (hb) hb.addEventListener('click', function(){
      mm.style.display = (mm.style.display === 'block') ? 'none' : 'block';
    });

    // Reveal on scroll
    var io = new IntersectionObserver(function(entries){
      entries.forEach(function(e){ if(e.isIntersecting){ e.target.classList.add('in'); } });
    }, {threshold:.12});
    document.querySelectorAll('.reveal').forEach(function(el){ io.observe(el); });
  </script>
</body>
</html>

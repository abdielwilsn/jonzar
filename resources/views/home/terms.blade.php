<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Terms & Conditions | GAL TRHAYDERS AI</title>
  <meta name="description" content="Terms and Conditions of Use for GAL TRHAYDERS AI trading platform by Great Adetula Limited." />
  <meta name="theme-color" content="#071325" />
  <style>
    :root{
      --bg:#061121;
      --bg2:#091a33;
      --bg3:#0d2243;
      --panel:#0b1730;
      --panel2:#102447;
      --card:rgba(255,255,255,.06);
      --card2:rgba(255,255,255,.04);
      --line:rgba(255,255,255,.10);
      --text:#f4f8ff;
      --muted:#afbddb;
      --blue:#2a86ff;
      --cyan:#27d7ff;
      --gold:#e2bb73;
      --gold2:#ffd997;
      --green:#21d78f;
      --red:#ff6b81;
      --shadow:0 28px 90px rgba(0,0,0,.34);
      --radius:28px;
      --max:860px;
    }
    *{box-sizing:border-box;margin:0;padding:0}
    html{scroll-behavior:smooth}
    body{
      font-family:Inter,Arial,Helvetica,sans-serif;
      color:var(--text);
      background:
        radial-gradient(circle at 10% 8%, rgba(42,134,255,.18), transparent 24%),
        radial-gradient(circle at 88% 14%, rgba(39,215,255,.10), transparent 18%),
        radial-gradient(circle at 85% 84%, rgba(226,187,115,.09), transparent 22%),
        linear-gradient(180deg, #030915 0%, #08152a 42%, #0b1f3d 100%);
      overflow-x:hidden;
      min-height:100vh;
    }
    body::before{
      content:"";position:fixed;inset:0;
      background-image:
        linear-gradient(rgba(255,255,255,.025) 1px, transparent 1px),
        linear-gradient(90deg, rgba(255,255,255,.025) 1px, transparent 1px);
      background-size:40px 40px;
      mask-image:linear-gradient(180deg, transparent, #000 12%, #000 88%, transparent);
      pointer-events:none;opacity:.65;z-index:-4;
    }
    body::after{
      content:"";position:fixed;inset:-10%;
      background:
        radial-gradient(circle at 20% 25%, rgba(42,134,255,.10), transparent 20%),
        radial-gradient(circle at 80% 20%, rgba(39,215,255,.10), transparent 22%),
        radial-gradient(circle at 60% 80%, rgba(226,187,115,.08), transparent 18%);
      filter:blur(32px);
      animation:bgMorph 18s ease-in-out infinite alternate;
      pointer-events:none;z-index:-5;
    }
    @keyframes bgMorph{
      0%{transform:translate3d(0,0,0) scale(1)}
      50%{transform:translate3d(20px,-18px,0) scale(1.04)}
      100%{transform:translate3d(-14px,22px,0) scale(1.08)}
    }
    a{text-decoration:none;color:inherit}
    .fx-layer{position:fixed;inset:0;pointer-events:none;z-index:-2;overflow:hidden}
    .orb{position:absolute;border-radius:50%;filter:blur(28px);opacity:.32;animation:floatOrb 16s ease-in-out infinite}
    .orb.a{width:220px;height:220px;left:-60px;top:120px;background:rgba(42,134,255,.24)}
    .orb.b{width:170px;height:170px;right:8%;top:18%;background:rgba(39,215,255,.16);animation-delay:-4s}
    .orb.c{width:190px;height:190px;left:11%;bottom:10%;background:rgba(226,187,115,.14);animation-delay:-8s}
    @keyframes floatOrb{0%,100%{transform:translateY(0) translateX(0)}50%{transform:translateY(-22px) translateX(12px)}}

    .wrap{width:min(calc(100% - 28px), var(--max));margin:0 auto}

    /* Topbar */
    .topbar{
      position:sticky;top:0;z-index:90;
      background:rgba(4,11,24,.78);
      backdrop-filter:blur(18px);
      border-bottom:1px solid rgba(255,255,255,.08);
    }
    .topbar-inner{
      width:min(calc(100% - 28px), 1240px);margin:0 auto;
      display:flex;align-items:center;justify-content:space-between;gap:16px;
      padding:14px 0;
    }
    .brand{display:flex;align-items:center;gap:12px;font-weight:950;letter-spacing:.01em}
    .brand-logo{
      width:46px;height:46px;border-radius:14px;overflow:hidden;
      border:1px solid rgba(255,255,255,.12);
      box-shadow:var(--shadow);background:rgba(255,255,255,.05);flex:none;
    }
    .brand-logo img{width:100%;height:100%;object-fit:cover;display:block}
    .brand small{display:block;color:var(--muted);font-size:.76rem;font-weight:800;margin-top:2px}
    .topbar-nav{display:flex;align-items:center;gap:14px}
    .topbar-nav a{
      padding:11px 18px;border-radius:14px;font-weight:900;font-size:.9rem;
      transition:.25s ease;
    }
    .btn-ghost{background:rgba(255,255,255,.06);border:1px solid rgba(255,255,255,.10);color:#fff}
    .btn-ghost:hover{background:rgba(255,255,255,.10);transform:translateY(-2px)}
    .btn-secondary{
      color:#fff;
      background:linear-gradient(135deg,var(--blue),var(--cyan));
      border:1px solid transparent;
      box-shadow:0 14px 30px rgba(42,134,255,.18);
    }
    .btn-secondary:hover{transform:translateY(-2px)}

    /* Page layout */
    .terms-page{position:relative;z-index:1;padding:48px 0 80px}

    .back-link{
      display:inline-flex;align-items:center;gap:8px;
      font-size:.9rem;font-weight:800;color:var(--muted);
      margin-bottom:32px;transition:color .2s ease;
    }
    .back-link:hover{color:var(--cyan)}
    .back-link svg{width:16px;height:16px;stroke:currentColor;fill:none;stroke-width:2;stroke-linecap:round;stroke-linejoin:round}

    /* Hero */
    .terms-hero{text-align:center;margin-bottom:48px}
    .terms-hero-icon{
      width:72px;height:72px;border-radius:20px;
      display:grid;place-items:center;margin:0 auto 22px;
      background:linear-gradient(135deg, rgba(39,215,255,.16), rgba(226,187,115,.14));
      border:1px solid rgba(255,255,255,.10);
      box-shadow:var(--shadow);
      font-size:30px;
    }
    .terms-hero h1{
      font-size:clamp(1.8rem,4vw,2.8rem);font-weight:950;
      letter-spacing:-.04em;line-height:1.08;margin-bottom:14px;
    }
    .grad{
      background:linear-gradient(135deg,#ffffff 0%, #9fd8ff 28%, #59d9ff 56%, #ffdc9a 100%);
      background-size:220% 220%;
      -webkit-background-clip:text;background-clip:text;color:transparent;
      animation:textFlow 8s ease infinite;
    }
    @keyframes textFlow{0%{background-position:0% 50%}50%{background-position:100% 50%}100%{background-position:0% 50%}}
    .terms-meta{
      display:inline-flex;align-items:center;gap:10px;
      padding:10px 16px;border-radius:999px;
      background:rgba(255,255,255,.06);
      border:1px solid rgba(255,255,255,.10);
      font-size:.82rem;font-weight:900;color:var(--muted);
    }
    .terms-meta::before{
      content:"";width:8px;height:8px;border-radius:50%;
      background:linear-gradient(135deg,var(--cyan),var(--gold));
      box-shadow:0 0 14px rgba(39,215,255,.35);
    }

    /* Main card */
    .terms-card{
      padding:44px;border-radius:var(--radius);
      background:linear-gradient(180deg, rgba(255,255,255,.07), rgba(255,255,255,.03));
      border:1px solid rgba(255,255,255,.08);
      box-shadow:var(--shadow);
      position:relative;overflow:hidden;
    }
    .terms-card::after{
      content:"";position:absolute;inset:auto -60px -80px auto;
      width:240px;height:240px;border-radius:50%;
      background:radial-gradient(circle, rgba(39,215,255,.08), transparent 70%);
      pointer-events:none;
    }

    /* TOC */
    .toc{
      padding:24px 28px;border-radius:20px;
      background:rgba(42,134,255,.06);
      border:1px solid rgba(255,255,255,.08);
      margin-bottom:40px;
    }
    .toc-label{
      font-size:.72rem;font-weight:900;text-transform:uppercase;
      letter-spacing:1.5px;color:var(--cyan);margin-bottom:16px;
    }
    .toc-grid{
      display:grid;grid-template-columns:1fr 1fr;gap:6px 24px;
      list-style:none;counter-reset:toc;
    }
    .toc-grid li{counter-increment:toc}
    .toc-grid a{
      display:flex;align-items:baseline;gap:8px;
      padding:7px 0;font-size:.86rem;font-weight:800;
      color:var(--muted);transition:color .2s ease;
    }
    .toc-grid a::before{
      content:counter(toc, decimal-leading-zero);
      font-size:.72rem;font-weight:900;color:rgba(255,255,255,.25);
      min-width:22px;
    }
    .toc-grid a:hover{color:var(--gold2)}

    /* Sections */
    .t-section{
      margin-bottom:36px;padding-bottom:36px;
      border-bottom:1px solid rgba(255,255,255,.06);
    }
    .t-section:last-child{margin-bottom:0;padding-bottom:0;border-bottom:none}
    .t-section-num{
      display:inline-block;padding:5px 12px;border-radius:10px;
      font-size:.7rem;font-weight:900;text-transform:uppercase;letter-spacing:1.2px;
      background:linear-gradient(135deg, rgba(39,215,255,.14), rgba(226,187,115,.14));
      border:1px solid rgba(255,255,255,.08);
      color:var(--cyan);margin-bottom:10px;
    }
    .t-section h2{
      font-size:1.22rem;font-weight:900;letter-spacing:-.02em;
      margin-bottom:14px;color:var(--text);
    }
    .t-section p{
      font-size:.93rem;color:var(--muted);line-height:1.85;margin-bottom:12px;
    }
    .t-section p:last-child{margin-bottom:0}
    .t-section ul{list-style:none;margin:12px 0;padding:0}
    .t-section li{
      position:relative;font-size:.93rem;color:var(--muted);
      line-height:1.85;padding-left:22px;margin-bottom:6px;
    }
    .t-section li::before{
      content:"";position:absolute;left:6px;top:12px;
      width:6px;height:6px;border-radius:50%;
      background:linear-gradient(135deg,var(--cyan),var(--gold));
    }

    /* Disclaimer box */
    .t-warn{
      padding:18px 22px;border-radius:18px;margin:16px 0;
      background:rgba(255,107,129,.08);
      border:1px solid rgba(255,107,129,.18);
    }
    .t-warn p{color:#ffb3bf;font-weight:700}
    .t-info{
      background:rgba(42,134,255,.08);
      border-color:rgba(42,134,255,.18);
    }
    .t-info p{color:var(--cyan);font-weight:700}

    /* Contact card */
    .t-contact{
      display:grid;gap:10px;margin-top:16px;
      padding:24px 26px;border-radius:20px;
      background:linear-gradient(135deg, rgba(42,134,255,.10), rgba(39,215,255,.06), rgba(226,187,115,.06));
      border:1px solid rgba(255,255,255,.08);
    }
    .t-contact-row{
      display:flex;align-items:center;gap:12px;
      font-size:.92rem;color:var(--muted);font-weight:800;
    }
    .t-contact-row span.ic{
      width:34px;height:34px;min-width:34px;border-radius:12px;
      display:grid;place-items:center;font-size:16px;
      background:linear-gradient(135deg, rgba(39,215,255,.16), rgba(226,187,115,.16));
      border:1px solid rgba(255,255,255,.10);
    }
    .t-contact-row a{color:var(--cyan);transition:color .2s}
    .t-contact-row a:hover{color:var(--gold2)}

    /* Footer */
    .terms-footer{
      text-align:center;margin-top:44px;
      font-size:.82rem;font-weight:800;color:rgba(255,255,255,.25);
    }

    /* Scroll to top */
    .scroll-top{
      position:fixed;right:22px;bottom:28px;z-index:80;
      width:46px;height:46px;border-radius:16px;border:none;
      background:linear-gradient(135deg,var(--blue),var(--cyan));
      color:#fff;font-size:18px;cursor:pointer;
      box-shadow:0 14px 30px rgba(42,134,255,.22);
      display:none;align-items:center;justify-content:center;
      transition:.25s ease;
    }
    .scroll-top:hover{transform:translateY(-3px)}
    .scroll-top.show{display:grid}

    /* Reveal animation */
    .reveal{opacity:0;transform:translateY(28px);transition:opacity .7s ease, transform .7s cubic-bezier(.2,.7,.2,1)}
    .reveal.in{opacity:1;transform:none}

    @media(max-width:640px){
      .terms-page{padding:28px 0 60px}
      .terms-card{padding:24px 18px}
      .toc-grid{grid-template-columns:1fr}
      .topbar-inner{padding:12px 0}
      .brand-logo{width:38px;height:38px;border-radius:12px}
      .brand{font-size:.92rem}
      .brand small{font-size:.7rem}
    }
  </style>
</head>
<body>
  <div class="fx-layer">
    <span class="orb a"></span>
    <span class="orb b"></span>
    <span class="orb c"></span>
  </div>

  <!-- Topbar -->
  <header class="topbar">
    <div class="topbar-inner">
      <a href="index.html" class="brand">
        <div class="brand-logo">
          <img src="https://res.cloudinary.com/draqpi1df/image/upload/f_auto,q_auto/logo.jpeg_ylmvmx" alt="GAL TRHAYDERS logo">
        </div>
        <div>
          GAL TRHAYDERS AI
          <small>Modern Broker Experience</small>
        </div>
      </a>
      <nav class="topbar-nav">
        <a class="btn-ghost" href="index.html">Home</a>
        <a class="btn-secondary" href="https://trhayders.com/register" target="_blank" rel="noopener">Signup</a>
      </nav>
    </div>
  </header>

  <!-- Page -->
  <div class="terms-page">
    <div class="wrap">

      <a href="index.html" class="back-link reveal">
        <svg viewBox="0 0 24 24"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
        Back to Home
      </a>

      <!-- Hero -->
      <div class="terms-hero reveal">
        <div class="terms-hero-icon">📄</div>
        <h1>Terms &amp; <span class="grad">Conditions</span></h1>
        <div class="terms-meta">Effective Date: 7th April, 2026</div>
      </div>

      <!-- Card -->
      <div class="terms-card">

        <!-- TOC -->
        <div class="toc reveal">
          <div class="toc-label">Table of Contents</div>
          <ol class="toc-grid">
            <li><a href="#s1">Introduction</a></li>
            <li><a href="#s2">Definitions</a></li>
            <li><a href="#s3">Eligibility</a></li>
            <li><a href="#s4">Nature of Services</a></li>
            <li><a href="#s5">Account Registration &amp; Security</a></li>
            <li><a href="#s6">Use of the Platform</a></li>
            <li><a href="#s7">Financial Transactions</a></li>
            <li><a href="#s8">Fees &amp; Charges</a></li>
            <li><a href="#s9">Risk Disclosure</a></li>
            <li><a href="#s10">Intellectual Property</a></li>
            <li><a href="#s11">Privacy &amp; Data Protection</a></li>
            <li><a href="#s12">Service Availability</a></li>
            <li><a href="#s13">Limitation of Liability</a></li>
            <li><a href="#s14">Indemnification</a></li>
            <li><a href="#s15">Termination</a></li>
            <li><a href="#s16">Governing Law</a></li>
            <li><a href="#s17">Amendments</a></li>
            <li><a href="#s18">Third-Party Services</a></li>
            <li><a href="#s19">Force Majeure</a></li>
            <li><a href="#s20">Contact Information</a></li>
          </ol>
        </div>

        <!-- 1 -->
        <div class="t-section reveal" id="s1">
          <span class="t-section-num">Section 01</span>
          <h2>Introduction</h2>
          <p>Welcome to GAL TRHAYDERS, a proprietary trading technology platform owned and operated by Great Adetula Limited (GAL), a company duly registered under the laws of the Federal Republic of Nigeria.</p>
          <p>These Terms and Conditions ("Terms") govern your access to and use of the GAL TRHAYDERS platform, including our website, mobile applications, trading tools, artificial intelligence systems, and related services (collectively referred to as the "Platform").</p>
          <p>By accessing or using this Platform, you agree to be bound by these Terms. If you do not agree, you must not use the Platform.</p>
        </div>

        <!-- 2 -->
        <div class="t-section reveal" id="s2">
          <span class="t-section-num">Section 02</span>
          <h2>Definitions</h2>
          <ul>
            <li><strong>"Company"</strong> refers to Great Adetula Limited (GAL).</li>
            <li><strong>"Platform"</strong> refers to GAL TRHAYDERS AI trading system and all related services.</li>
            <li><strong>"User" / "You"</strong> refers to any individual or entity accessing the Platform.</li>
            <li><strong>"Services"</strong> refers to automated trading tools, AI trading bot, signals, analytics, and related offerings.</li>
            <li><strong>"Account"</strong> refers to your registered profile on the Platform.</li>
          </ul>
        </div>

        <!-- 3 -->
        <div class="t-section reveal" id="s3">
          <span class="t-section-num">Section 03</span>
          <h2>Eligibility</h2>
          <p>To use this Platform, you must:</p>
          <ul>
            <li>Be at least 18 years of age</li>
            <li>Have full legal capacity under International/Nigeria law</li>
            <li>Provide accurate and complete registration information</li>
            <li>Not be restricted under any applicable law or regulation</li>
          </ul>
          <p>The Company reserves the right to refuse service to any individual at its sole discretion.</p>
        </div>

        <!-- 4 -->
        <div class="t-section reveal" id="s4">
          <span class="t-section-num">Section 04</span>
          <h2>Nature of Services (Important Disclaimer)</h2>
          <p>GAL TRHAYDERS provides technology tools for automated trading and does NOT:</p>
          <ul>
            <li>Act as a licensed financial advisor</li>
            <li>Guarantee profits or returns</li>
            <li>Provide investment, legal, or tax advice</li>
          </ul>
          <div class="t-warn">
            <p>All trading activities involve significant financial risk, including the potential loss of capital. You are solely responsible for your financial decisions. Past performance does not guarantee future results. You understand the risks associated with forex, crypto, and financial markets.</p>
          </div>
        </div>

        <!-- 5 -->
        <div class="t-section reveal" id="s5">
          <span class="t-section-num">Section 05</span>
          <h2>Account Registration &amp; Security</h2>
          <p>You agree to:</p>
          <ul>
            <li>Provide accurate and updated personal information</li>
            <li>Maintain confidentiality of your login credentials</li>
            <li>Accept full responsibility for all activities under your account</li>
          </ul>
          <p>The Company shall not be liable for losses resulting from unauthorized access, user negligence, or sharing of login details.</p>
        </div>

        <!-- 6 -->
        <div class="t-section reveal" id="s6">
          <span class="t-section-num">Section 06</span>
          <h2>Use of the Platform</h2>
          <p>You agree NOT to:</p>
          <ul>
            <li>Use the Platform for illegal or fraudulent activities</li>
            <li>Attempt to hack, disrupt, or reverse-engineer the system</li>
            <li>Misuse the AI trading technology</li>
            <li>Upload malicious software or harmful content</li>
          </ul>
          <p>Violation of this section may result in immediate suspension or termination of your account.</p>
        </div>

        <!-- 7 -->
        <div class="t-section reveal" id="s7">
          <span class="t-section-num">Section 07</span>
          <h2>Financial Transactions</h2>
          <p>Users are solely responsible for funding their trading accounts, choosing brokers or exchanges, and managing deposits and withdrawals.</p>
          <p>GAL TRHAYDERS does NOT hold user funds directly (except where explicitly stated), act as a financial custodian, or guarantee liquidity or execution.</p>
          <div class="t-warn t-info">
            <p>All payments made to the Company (if applicable) are subject to non-refundable policies, except otherwise stated.</p>
          </div>
        </div>

        <!-- 8 -->
        <div class="t-section reveal" id="s8">
          <span class="t-section-num">Section 08</span>
          <h2>Fees &amp; Charges</h2>
          <p>The Company may charge subscription fees, performance fees, or service/access fees. All applicable fees will be clearly communicated before payment.</p>
          <p>The Company reserves the right to modify fees at any time with prior notice.</p>
        </div>

        <!-- 9 -->
        <div class="t-section reveal" id="s9">
          <span class="t-section-num">Section 09</span>
          <h2>Risk Disclosure</h2>
          <p>Trading involves market volatility, system risks, liquidity risks, and technological failures.</p>
          <p>You acknowledge that losses may exceed expectations, AI systems are not infallible, and market conditions can change rapidly.</p>
          <div class="t-warn">
            <p>You agree that the Company is NOT liable for financial losses, missed opportunities, or market fluctuations.</p>
          </div>
        </div>

        <!-- 10 -->
        <div class="t-section reveal" id="s10">
          <span class="t-section-num">Section 10</span>
          <h2>Intellectual Property</h2>
          <p>All content — including software, algorithms, branding, logos, and AI systems — remains the exclusive property of Great Adetula Limited (GAL).</p>
          <p>You may NOT copy, reproduce, distribute, reverse-engineer the system, or use the technology for competing purposes.</p>
        </div>

        <!-- 11 -->
        <div class="t-section reveal" id="s11">
          <span class="t-section-num">Section 11</span>
          <h2>Privacy &amp; Data Protection</h2>
          <p>We are committed to protecting your data in accordance with the Nigeria Data Protection Act (NDPA).</p>
          <p>By using the Platform, you consent to the collection of personal data, use of cookies and analytics, and processing of data for service improvement.</p>
        </div>

        <!-- 12 -->
        <div class="t-section reveal" id="s12">
          <span class="t-section-num">Section 12</span>
          <h2>Service Availability</h2>
          <p>The Platform is provided "as is" and "as available." We do not guarantee uninterrupted service, error-free operation, or continuous system availability.</p>
          <p>We may upgrade, modify, or suspend services at any time.</p>
        </div>

        <!-- 13 -->
        <div class="t-section reveal" id="s13">
          <span class="t-section-num">Section 13</span>
          <h2>Limitation of Liability</h2>
          <p>To the fullest extent permitted under Nigerian law, Great Adetula Limited (GAL) shall NOT be liable for direct or indirect financial losses, loss of profits or revenue, data loss or system errors, or third-party service failures.</p>
          <div class="t-warn">
            <p>Your use of the Platform is at your sole risk.</p>
          </div>
        </div>

        <!-- 14 -->
        <div class="t-section reveal" id="s14">
          <span class="t-section-num">Section 14</span>
          <h2>Indemnification</h2>
          <p>You agree to indemnify and hold harmless Great Adetula Limited from claims arising from your use of the Platform, violation of these Terms, or breach of applicable laws.</p>
        </div>

        <!-- 15 -->
        <div class="t-section reveal" id="s15">
          <span class="t-section-num">Section 15</span>
          <h2>Termination</h2>
          <p>We reserve the right to suspend or terminate your account and restrict access without notice. Grounds include violation of Terms, fraudulent activities, or regulatory requirements.</p>
        </div>

        <!-- 16 -->
        <div class="t-section reveal" id="s16">
          <span class="t-section-num">Section 16</span>
          <h2>Governing Law</h2>
          <p>These Terms shall be governed by the laws of the Federal Republic of Nigeria. Any disputes shall be resolved in the courts of competent jurisdiction in Nigeria.</p>
        </div>

        <!-- 17 -->
        <div class="t-section reveal" id="s17">
          <span class="t-section-num">Section 17</span>
          <h2>Amendments</h2>
          <p>We may update these Terms at any time. Continued use of the Platform constitutes acceptance of the updated Terms.</p>
        </div>

        <!-- 18 -->
        <div class="t-section reveal" id="s18">
          <span class="t-section-num">Section 18</span>
          <h2>Third-Party Services</h2>
          <p>The Platform may integrate with brokers, payment providers, and external APIs. We are NOT responsible for third-party failures, external service disruptions, or independent policies of such providers.</p>
        </div>

        <!-- 19 -->
        <div class="t-section reveal" id="s19">
          <span class="t-section-num">Section 19</span>
          <h2>Force Majeure</h2>
          <p>We shall not be liable for failure to perform due to natural disasters, government actions, network failures, or unforeseen circumstances.</p>
        </div>

        <!-- 20 -->
        <div class="t-section reveal" id="s20">
          <span class="t-section-num">Section 20</span>
          <h2>Contact Information</h2>
          <p>For inquiries, support, or complaints, contact Great Adetula Limited (GAL):</p>
          <div class="t-contact">
            <div class="t-contact-row">
              <span class="ic">📞</span>
              <span>07080069456 &nbsp;/&nbsp; +1 559 998-9794</span>
            </div>
            <div class="t-contact-row">
              <span class="ic">📧</span>
              <a href="mailto:trhayders247@gmail.com">trhayders247@gmail.com</a>
            </div>
            <div class="t-contact-row">
              <span class="ic">🌐</span>
              <a href="https://www.trhayders.com" target="_blank" rel="noopener">www.trhayders.com</a>
            </div>
          </div>
        </div>

      </div><!-- /terms-card -->

      <!-- Footer -->
      <div class="terms-footer reveal">
        &copy; 2026 GAL TRHAYDERS AI. A product of Great Adetula Limited. All rights reserved.
      </div>

    </div><!-- /wrap -->
  </div><!-- /terms-page -->

  <!-- Scroll to top -->
  <button class="scroll-top" id="scrollTop" onclick="window.scrollTo({top:0,behavior:'smooth'})">↑</button>

  <script>
    // Reveal on scroll
    const io = new IntersectionObserver((entries) => {
      entries.forEach((entry, i) => {
        if (entry.isIntersecting) {
          entry.target.style.transitionDelay = (i % 6) * 0.04 + 's';
          entry.target.classList.add('in');
        }
      });
    }, { threshold: .1 });
    document.querySelectorAll('.reveal').forEach(el => io.observe(el));

    // Scroll-to-top button
    const stBtn = document.getElementById('scrollTop');
    window.addEventListener('scroll', () => {
      stBtn.classList.toggle('show', window.scrollY > 400);
    }, { passive: true });

    // Parallax orbs
    const orbs = document.querySelectorAll('.orb');
    window.addEventListener('scroll', () => {
      const y = window.scrollY;
      orbs.forEach((orb, i) => {
        orb.style.transform = `translateY(${y * (i + 1) * 0.08}px)`;
      });
    }, { passive: true });
  </script>
</body>
</html>

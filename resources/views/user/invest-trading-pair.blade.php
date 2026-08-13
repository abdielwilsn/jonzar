<?php
if (Auth::user()->dashboard_style == "light") {
    $bgmenu = "light";
    $bg = "light";
    $text = "dark";
} else {
    $bgmenu = "dark";
    $bg = "dark";
    $text = "light";
}

?>

@extends('layouts.app')

@section('content')
    @include('user.topmenu')
    @include('user.sidebar')

    <div class="main-panel invest-page" data-theme="{{ $bg }}">
        <div class="content">
            <div class="page-inner">

                <!-- Back Button -->
                <a href="{{ route('trading.pairs') }}" class="back-link">
                    <i class="fa fa-arrow-left"></i>
                    <span>Back to Trading Pairs</span>
                </a>

                <x-danger-alert/>
                <x-success-alert/>

                <div class="invest-layout">
                    <!-- Main Form Card -->
                    <div class="invest-form-card">
                        <!-- Coin Header -->
                        <div class="coin-header">
                            <div class="coin-info">
                                <div class="coin-icon">
                                    <img src="{{ $tradingPair->base_icon_url ?? 'https://via.placeholder.com/48' }}"
                                         alt="{{ $tradingPair->base_symbol }}"
                                         onerror="this.src='https://via.placeholder.com/48'">
                                </div>
                                <div class="coin-details">
                                    <h1 class="coin-symbol">{{ $tradingPair->base_symbol }}/{{ $tradingPair->quote_symbol }}</h1>
                                    <span class="coin-name">{{ $tradingPair->base_name }}</span>
                                </div>
                            </div>
                            <div class="coin-price">
                                <span class="price-label">Market Direction</span>
                                <span class="price-value" id="market-direction-label">{{ $tradingPair->price_change_24h >= 0 ? 'Uptrend' : 'Downtrend' }}</span>
                                <span id="market-direction-badge" class="price-change {{ $tradingPair->price_change_24h >= 0 ? 'positive' : 'negative' }}">
                                    <i class="fa fa-{{ $tradingPair->price_change_24h >= 0 ? 'arrow-up' : 'arrow-down' }}"></i>
                                    {{ $tradingPair->price_change_24h >= 0 ? 'Momentum rising' : 'Momentum falling' }}
                                </span>
                            </div>
                        </div>

                        <div class="pair-chart-shell">
                            <div class="pair-chart-toolbar">
                                <div class="chart-type-switch">
                                    <button type="button" class="chart-type-btn active" data-chart-mode="line">Line</button>
                                       <button type="button" class="chart-type-btn" data-chart-mode="candles">Candlestick</button>
                                </div>
                                <div class="chart-trend-badge" id="chart-trend-badge">Trend: Up</div>
                            </div>
                            <div class="pair-chart-canvas" id="pair-chart-canvas">
                                <svg id="pair-chart-svg" viewBox="0 0 100 48" preserveAspectRatio="none" aria-label="Pair trend chart"></svg>
                            </div>
                               
                        </div>

                        <!-- Investment Form -->
                        <form action="{{ route('user.trading-pairs.store-investment', $tradingPair->id) }}" method="POST" id="investForm">
                            @csrf

                            <!-- Amount Section -->
                            <div class="form-section">
                                <label class="section-label">
                                    <i class="fa fa-coins"></i>
                                    Trade Amount
                                </label>
                                <div class="amount-input-wrapper">
                                    <span class="currency-symbol ">{{ $settings->currency }}</span>
                                    <input
                                        type="number"
                                        name="amount"
                                        id="amount"
                                        class="amount-input"
                                        min="{{ $tradingPair->min_investment }}"
                                        max="{{ $tradingPair->max_investment }}"
                                        step="0.01"
                                        value="{{ old('amount', $tradingPair->min_investment) }}"
                                        placeholder="0.00"
                                        required
                                    >
                                </div>
                                <div class="amount-range">
                                    <span>Min: {{ $settings->currency }}{{ number_format($tradingPair->min_investment, 2) }}</span>
                                    <span>Max: {{ $settings->currency }}{{ number_format($tradingPair->max_investment, 2) }}</span>
                                </div>
                                <!-- Quick Amount Buttons -->
                                <div class="quick-amounts">
                                    <button type="button" class="quick-amount-btn" data-amount="{{ $tradingPair->min_investment }}">Min</button>
                                    <button type="button" class="quick-amount-btn" data-amount="{{ ($tradingPair->min_investment + $tradingPair->max_investment) / 4 }}">25%</button>
                                    <button type="button" class="quick-amount-btn" data-amount="{{ ($tradingPair->min_investment + $tradingPair->max_investment) / 2 }}">50%</button>
                                    <button type="button" class="quick-amount-btn" data-amount="{{ $tradingPair->max_investment }}">Max</button>
                                </div>
                                @error('amount')
                                <span class="error-text">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Duration Section -->
                            <div class="form-section">
                                <label class="section-label">
                                    <i class="fa fa-clock"></i>
                                    Trade Duration
                                </label>
                                @php
                                    $durMin = (int) $tradingPair->investment_duration;
                                    $durMax = (int) $tradingPair->max_investment_duration;
                                    $durVal = (int) old('duration', $durMin);
                                @endphp
                                @if ($durMax > $durMin)
                                    <div class="amount-input-wrapper">
                                        <input
                                            type="number"
                                            name="duration"
                                            id="durationInput"
                                            class="amount-input"
                                            min="{{ $durMin }}"
                                            max="{{ $durMax }}"
                                            step="1"
                                            value="{{ $durVal }}"
                                            inputmode="numeric"
                                            autocomplete="off"
                                            placeholder="{{ $durMin }}"
                                            required
                                            aria-label="Trade duration in days"
                                        >
                                        <span class="currency-symbol" id="durationLabel">{{ $durVal == 1 ? 'day' : 'days' }}</span>
                                    </div>
                                    <div class="amount-range">
                                        <span>Min: {{ $durMin }} day{{ $durMin > 1 ? 's' : '' }}</span>
                                        <span>Max: {{ $durMax }} days</span>
                                    </div>
                                @else
                                    <div class="amount-input-wrapper">
                                        <input type="number" class="amount-input" value="{{ $durMin }}" disabled aria-label="Fixed trade duration">
                                        <span class="currency-symbol">{{ $durMin == 1 ? 'day' : 'days' }}</span>
                                    </div>
                                    <input type="hidden" name="duration" id="durationInput" value="{{ $durMin }}">
                                    <p class="duration-fixed-note">Fixed duration of {{ $durMin }} day{{ $durMin > 1 ? 's' : '' }}.</p>
                                @endif
                                <p class="helper-text">
                                    <i class="fa fa-info-circle"></i>
                                    Longer durations may qualify for higher returns
                                </p>
                                @error('duration')
                                <span class="error-text">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Estimated Return Preview -->
                            <div class="return-preview">
                                <div class="preview-header">
                                    <i class="fa fa-chart-line"></i>
                                    <span>Estimated Return</span>
                                </div>
                                <div class="preview-content">
                                    <div class="preview-row">
                                        <span class="preview-label">Trade Amount</span>
                                        <span class="preview-value" id="previewAmount">{{ $settings->currency }}{{ number_format($tradingPair->min_investment, 2) }}</span>
                                    </div>
                                    <div class="preview-row">
                                        <span class="preview-label">Duration</span>
                                        <span class="preview-value" id="previewDuration">{{ $tradingPair->investment_duration }} day(s)</span>
                                    </div>
                                    <div class="preview-row">
                                        <span class="preview-label">Return Range</span>
                                        <span class="preview-value return-range">{{ number_format($tradingPair->min_return_percentage, 1) }}% — {{ number_format($tradingPair->max_return_percentage, 1) }}%</span>
                                    </div>
                                    <div class="preview-divider"></div>
                                    <div class="preview-row highlight">
                                        <span class="preview-label">Potential Profit</span>
                                        <span class="preview-value profit" id="previewProfit">
                                            {{ $settings->currency }}{{ number_format($tradingPair->min_investment * $tradingPair->min_return_percentage / 100 * $tradingPair->investment_duration, 2) }} — {{ $settings->currency }}{{ number_format($tradingPair->min_investment * $tradingPair->max_return_percentage / 100 * $tradingPair->investment_duration, 2) }}
                                        </span>
                                    </div>
                                </div>
                                <p class="preview-disclaimer">
                                    * Actual return calculated at maturity based on market conditions
                                </p>
                            </div>

                            <!-- Error Messages -->
                            @if ($errors->any())
                                <div class="error-box">
                                    <i class="fa fa-exclamation-circle"></i>
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <!-- Submit Buttons -->
                            <div class="form-actions">
                                <button type="submit" class="submit-btn">
                                    <i class="fa fa-check-circle"></i>
                                    Confirm Trade
                                </button>
                                <a href="{{ route('trading.pairs') }}" class="cancel-btn">
                                    Cancel
                                </a>
                            </div>
                        </form>
                    </div>

                    <!-- Sidebar Info -->
                    <div class="invest-sidebar">
                        <!-- Investment Parameters -->
                        {{-- <div class="info-card params">
                            <h4>
                                <i class="fa fa-sliders-h"></i>
                                Trade Parameters
                            </h4>
                            <div class="param-list">
                                <div class="param-item">
                                    <span class="param-label">Min Amount</span>
                                    <span class="param-value">{{ $settings->currency }}{{ number_format($tradingPair->min_investment, 2) }}</span>
                                </div>
                                <div class="param-item">
                                    <span class="param-label">Max Amount</span>
                                    <span class="param-value">{{ $settings->currency }}{{ number_format($tradingPair->max_investment, 2) }}</span>
                                </div>
                                <div class="param-item">
                                    <span class="param-label">Duration Range</span>
                                    <span class="param-value">{{ $tradingPair->investment_duration }} — {{ $tradingPair->max_investment_duration }} days</span>
                                </div>
                                <div class="param-item highlight">
                                    <span class="param-label">Expected Return</span>
                                    <span class="param-value positive">{{ number_format($tradingPair->min_return_percentage, 1) }}% — {{ number_format($tradingPair->max_return_percentage, 1) }}%</span>
                                </div>
                            </div>
                        </div> --}}

                        <!-- Your Balance -->
                        <div class="info-card balance">
                            <h4>
                                <i class="fa fa-wallet"></i>
                                Your Balance
                            </h4>
                            <div class="balance-display">
                                <span class="balance-amount">{{ $settings->currency }}{{ number_format(Auth::user()->account_bal, 2) }}</span>
                                <span class="balance-label">Available</span>
                            </div>
                            @if(Auth::user()->account_bal < $tradingPair->min_investment)
                                <div class="balance-warning">
                                    <i class="fa fa-exclamation-triangle"></i>
                                    <span>Insufficient balance for minimum trade</span>
                                </div>
                                <a href="{{ route('deposits') }}" class="deposit-btn">
                                    <i class="fa fa-plus"></i>
                                    Deposit Funds
                                </a>
                            @endif
                        </div>

                        <!-- How It Works -->
                        <div class="info-card">
                            <h4>
                                <i class="fa fa-question-circle"></i>
                                How It Works
                            </h4>
                            <div class="steps-list">
                                <div class="step-item">
                                    <span class="step-number">1</span>
                                    <span class="step-text">Enter your trade amount</span>
                                </div>
                                <div class="step-item">
                                    <span class="step-number">2</span>
                                    <span class="step-text">Select trade duration</span>
                                </div>
                                <div class="step-item">
                                    <span class="step-number">3</span>
                                    <span class="step-text">Confirm and start earning</span>
                                </div>
                                <div class="step-item">
                                    <span class="step-number">4</span>
                                    <span class="step-text">Receive returns at maturity</span>
                                </div>
                            </div>
                        </div>

                        <!-- Risk Notice -->
                        {{-- <div class="info-card risk">
                            <h4>
                                <i class="fa fa-shield-alt"></i>
                                Risk Notice
                            </h4>
                            <p>Cryptocurrency trading involves risk. Past performance does not guarantee future results. Only invest what you can afford to lose.</p>
                        </div> --}}
                    </div>
                </div>

            </div>
        </div>
    </div>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Space+Grotesk:wght@500;600;700&display=swap" rel="stylesheet">
    @verbatim
    <style>
    /* ============ Zaraex invest ============ */
    .invest-page{
        --card:#0e1018; --elev:#16181f; --border:#23262f;
        --text:#f2f5fb; --muted:#a1a9b8; --faint:#6b7280;
        --blue:#2563eb; --blue-soft:#3b82f6; --success:#22c55e; --amber:#f59e0b; --danger:#ef4444;
        --tint:rgba(37,99,235,.14); --dur-track:rgba(255,255,255,.12);
        font-family:'Inter',system-ui,-apple-system,sans-serif; color:var(--text);
    }
    body[data-theme="light"] .invest-page{
        --card:#ffffff; --elev:#f4f6fa; --border:#e8ecf2; --text:#0f172a; --muted:#5b6674; --faint:#98a2b3; --dur-track:#e2e8f0;
    }
    .invest-page .content{padding:0}
    .invest-page .page-inner{max-width:560px;margin:0 auto;padding:22px 16px 96px}
    .invest-page h1,.invest-page h4{font-family:'Space Grotesk','Inter',sans-serif;letter-spacing:-.02em;margin:0}

    .invest-page .back-link{display:inline-flex;align-items:center;gap:8px;color:var(--muted);text-decoration:none;font-weight:600;font-size:.9rem;margin-bottom:18px}
    .invest-page .back-link:hover{color:var(--text)}

    .invest-layout{display:flex;flex-direction:column;gap:16px}
    .invest-form-card{background:var(--card);border:1px solid var(--border);border-radius:20px;padding:20px}
    .invest-sidebar{display:flex;flex-direction:column;gap:16px}

    /* Coin header */
    .coin-header{display:flex;align-items:flex-start;justify-content:space-between;gap:12px;padding-bottom:16px;border-bottom:1px solid var(--border);margin-bottom:16px}
    .coin-info{display:flex;align-items:center;gap:12px;min-width:0}
    .coin-icon{width:46px;height:46px;border-radius:50%;flex:none;overflow:hidden;background:var(--elev)}
    .coin-icon img{width:100%;height:100%;object-fit:cover}
    .coin-symbol{font-size:1.2rem;font-weight:700}
    .coin-name{color:var(--faint);font-size:.82rem;text-transform:capitalize}
    .coin-price{text-align:right;flex:none}
    .coin-price .price-label{display:block;color:var(--faint);font-size:.68rem;text-transform:uppercase;letter-spacing:.05em}
    .coin-price .price-value{font-family:'Space Grotesk',sans-serif;font-weight:700;font-size:.95rem}
    .price-change{display:inline-flex;align-items:center;gap:5px;margin-top:4px;padding:3px 9px;border-radius:999px;font-size:.72rem;font-weight:600}
    .price-change.positive{background:rgba(34,197,94,.14);color:var(--success)}
    .price-change.negative{background:rgba(239,68,68,.14);color:var(--danger)}

    /* Chart */
    .pair-chart-shell{margin-bottom:18px}
    .pair-chart-toolbar{display:flex;align-items:center;justify-content:space-between;margin-bottom:10px}
    .chart-type-switch{display:inline-flex;background:var(--elev);border:1px solid var(--border);border-radius:10px;padding:3px;gap:2px}
    .chart-type-btn{padding:6px 13px;border:none;background:transparent;border-radius:7px;color:var(--muted);font-weight:600;font-size:.8rem;cursor:pointer;transition:.2s}
    .chart-type-btn.active{background:var(--blue);color:#fff}
    .chart-trend-badge{font-size:.74rem;font-weight:600;padding:5px 11px;border-radius:999px;background:var(--tint);color:var(--blue-soft)}
    .pair-chart-canvas{height:120px;width:100%;border-radius:14px;overflow:hidden;background:var(--elev);border:1px solid var(--border);padding:8px}
    .pair-chart-canvas svg{width:100%;height:100%;display:block}

    /* Form sections */
    .form-section{margin-bottom:22px}
    .section-label{display:flex;align-items:center;gap:8px;font-size:.8rem;font-weight:600;text-transform:uppercase;letter-spacing:.05em;color:var(--faint);margin-bottom:10px}
    .section-label i{color:var(--blue-soft)}

    /* Amount */
    .amount-input-wrapper{display:flex;align-items:center;gap:6px;border:1px solid var(--border);border-radius:14px;background:var(--elev);padding:4px 16px;transition:.2s}
    .amount-input-wrapper:focus-within{border-color:var(--blue);box-shadow:0 0 0 3px rgba(37,99,235,.15)}
    .currency-symbol{font-size:1.35rem;font-weight:700;color:var(--muted)}
    .invest-page .amount-input{flex:1!important;min-width:0;border:none!important;background:transparent!important;outline:none;color:var(--text)!important;
        font-family:'Space Grotesk',sans-serif;font-size:1.7rem!important;font-weight:700;padding:12px 4px!important;box-shadow:none!important}
    .amount-input::-webkit-outer-spin-button,.amount-input::-webkit-inner-spin-button{-webkit-appearance:none;margin:0}
    .amount-range{display:flex;justify-content:space-between;margin-top:9px;color:var(--faint);font-size:.78rem}
    .quick-amounts{display:flex;gap:8px;margin-top:12px}
    .quick-amount-btn{flex:1;padding:9px 6px;border-radius:11px;border:1px solid var(--border);background:var(--elev);color:var(--text);font-weight:600;font-size:.82rem;cursor:pointer;transition:.2s}
    .quick-amount-btn:hover{border-color:var(--blue-soft)}
    .quick-amount-btn.active{background:var(--blue);color:#fff;border-color:var(--blue)}
    .error-text{display:block;margin-top:8px;color:var(--danger);font-size:.82rem}

    /* Duration */
    .duration-fixed-note{margin:0;text-align:center;color:var(--muted);font-size:.85rem}
    .helper-text{display:flex;align-items:center;gap:7px;margin:12px 0 0;color:var(--faint);font-size:.82rem}

    /* Return preview */
    .return-preview{background:var(--elev);border:1px solid var(--border);border-radius:16px;padding:18px;margin-bottom:20px}
    .preview-header{display:flex;align-items:center;gap:8px;font-weight:600;color:var(--blue-soft);margin-bottom:12px}
    .preview-row{display:flex;align-items:center;justify-content:space-between;padding:6px 0;font-size:.9rem}
    .preview-label{color:var(--muted)}
    .preview-value{font-family:'Space Grotesk',sans-serif;font-weight:600;text-align:right}
    .preview-divider{height:1px;background:var(--border);margin:8px 0}
    .preview-row.highlight .preview-label{color:var(--text);font-weight:600}
    .preview-value.profit{color:var(--success);font-size:1.02rem}
    .preview-disclaimer{margin:10px 0 0;color:var(--faint);font-size:.76rem}

    /* Error box */
    .error-box{display:flex;gap:10px;background:rgba(239,68,68,.12);border:1px solid rgba(239,68,68,.3);border-radius:14px;padding:14px;margin-bottom:16px;color:var(--danger)}
    .error-box ul{margin:0;padding-left:18px;font-size:.88rem}

    /* Actions */
    .form-actions{display:flex;flex-direction:column;gap:10px;margin-top:20px}
    .submit-btn{display:flex;align-items:center;justify-content:center;gap:8px;padding:15px;border-radius:13px;border:none;cursor:pointer;background:var(--blue);color:#fff;font-weight:600;font-size:1rem;transition:.2s;text-decoration:none}
    .submit-btn:hover{background:var(--blue-soft)}
    .cancel-btn{display:block;text-align:center;padding:13px;border-radius:13px;border:1px solid var(--border);background:transparent;color:var(--muted);font-weight:600;font-size:.95rem;text-decoration:none;transition:.2s}
    .cancel-btn:hover{color:var(--text);border-color:var(--blue-soft)}

    /* Info cards */
    .info-card{background:var(--card);border:1px solid var(--border);border-radius:20px;padding:20px}
    .info-card h4{display:flex;align-items:center;gap:9px;font-size:1rem;font-weight:600;margin-bottom:14px}
    .info-card h4 i{color:var(--blue-soft)}
    .balance-display{display:flex;align-items:baseline;gap:10px}
    .balance-amount{font-family:'Space Grotesk',sans-serif;font-size:1.7rem;font-weight:700}
    .balance-label{color:var(--faint);font-size:.82rem}
    .balance-warning{display:flex;align-items:center;gap:9px;margin-top:14px;padding:11px 13px;border-radius:12px;background:rgba(245,158,11,.12);border:1px solid rgba(245,158,11,.3);color:var(--amber);font-size:.85rem;font-weight:500}
    .deposit-btn{display:flex;align-items:center;justify-content:center;gap:8px;margin-top:12px;padding:13px;border-radius:12px;background:var(--blue);color:#fff;font-weight:600;font-size:.92rem;text-decoration:none;transition:.2s}
    .deposit-btn:hover{background:var(--blue-soft);color:#fff}
    .steps-list{display:flex;flex-direction:column;gap:14px}
    .step-item{display:flex;align-items:center;gap:12px}
    .step-number{width:28px;height:28px;flex:none;border-radius:9px;display:grid;place-items:center;background:var(--tint);color:var(--blue-soft);font-family:'Space Grotesk',sans-serif;font-weight:700;font-size:.82rem}
    .step-text{color:var(--muted);font-size:.9rem}
    </style>
    @endverbatim

    <script>
        const amountInput = document.getElementById('amount');
        const quickAmountBtns = document.querySelectorAll('.quick-amount-btn');
        const previewAmount = document.getElementById('previewAmount');
        const previewDuration = document.getElementById('previewDuration');
        const previewProfit = document.getElementById('previewProfit');

        const chartSvg = document.getElementById('pair-chart-svg');
        const chartModeButtons = document.querySelectorAll('.chart-type-btn');
        const chartTrendBadge = document.getElementById('chart-trend-badge');
        const marketDirectionLabel = document.getElementById('market-direction-label');
        const marketDirectionBadge = document.getElementById('market-direction-badge');

        const currency = '{{ $settings->currency }}';
        const minReturn = {{ $tradingPair->min_return_percentage }};
        const maxReturn = {{ $tradingPair->max_return_percentage }};
        const chartFeedUrl = '{{ route('user.trading-pairs.chart-feed', $tradingPair->id) }}';

        let chartMode = 'line';
        let chartTimer = null;

        function updatePreview() {
            const amount = parseFloat(amountInput.value) || 0;
            const durationEl = document.getElementById('durationInput');
            const duration = durationEl ? (parseInt(durationEl.value, 10) || 0) : 1;

            previewAmount.textContent = `${currency}${amount.toFixed(2)}`;
            previewDuration.textContent = `${duration} day${duration > 1 ? 's' : ''}`;

            const minProfit = (amount * minReturn / 100 * duration).toFixed(2);
            const maxProfit = (amount * maxReturn / 100 * duration).toFixed(2);
            previewProfit.textContent = `${currency}${minProfit} — ${currency}${maxProfit}`;
        }

        function normalizeValues(values) {
            const min = Math.min(...values);
            const max = Math.max(...values);
            const spread = Math.max(max - min, 0.001);
            return values.map(value => ((value - min) / spread));
        }

        function toChartY(normalizedValue) {
            return 4 + (1 - normalizedValue) * 40;
        }

        function renderLineChart(series, trend) {
            if (!series.length) {
                chartSvg.innerHTML = '';
                return;
            }

            const normalized = normalizeValues(series);
            const stepX = series.length > 1 ? 100 / (series.length - 1) : 100;

            let linePath = '';
            normalized.forEach((value, index) => {
                const x = (index * stepX).toFixed(3);
                const y = toChartY(value).toFixed(3);
                linePath += `${index === 0 ? 'M' : 'L'} ${x} ${y} `;
            });

            const color = trend === 'up' ? '#10b981' : '#ef4444';

            chartSvg.innerHTML = `
                <defs>
                    <linearGradient id="lineFill" x1="0" y1="0" x2="0" y2="1">
                        <stop offset="0%" stop-color="${color}" stop-opacity="0.35"></stop>
                        <stop offset="100%" stop-color="${color}" stop-opacity="0"></stop>
                    </linearGradient>
                </defs>
                <path d="${linePath} L 100 46 L 0 46 Z" fill="url(#lineFill)"></path>
                <path d="${linePath}" fill="none" stroke="${color}" stroke-width="1.2" stroke-linecap="round"></path>
            `;
        }

        function renderCandles(candles) {
            if (!candles.length) {
                chartSvg.innerHTML = '';
                return;
            }

            const values = [];
            candles.forEach(candle => {
                values.push(candle.o, candle.h, candle.l, candle.c);
            });

            const min = Math.min(...values);
            const max = Math.max(...values);
            const spread = Math.max(max - min, 0.001);

            const normalize = value => (value - min) / spread;
            const stepX = candles.length > 0 ? 100 / candles.length : 100;
               const bodyWidth = Math.max(stepX * 0.58, 0.8);

            let svg = '';

            candles.forEach((candle, index) => {
                const xCenter = (index * stepX) + (stepX / 2);
                const openY = toChartY(normalize(candle.o));
                const closeY = toChartY(normalize(candle.c));
                const highY = toChartY(normalize(candle.h));
                const lowY = toChartY(normalize(candle.l));
                const bodyTop = Math.min(openY, closeY);
                   const bodyHeight = Math.max(Math.abs(openY - closeY), 0.6);
                   const wickHeight = Math.max(Math.abs(highY - lowY), bodyHeight + 0.6);
                   const bodyWidth = clamp(stepX * (0.24 + ((bodyHeight / 40) * 1.02)), 0.75, stepX * 0.9);
                   const wickWidth = clamp(0.22 + (wickHeight / 44), 0.2, 1.0);
                const rising = candle.c >= candle.o;
                const bodyColor = rising ? '#10b981' : '#ef4444';

                svg += `
                       <line x1="${xCenter.toFixed(3)}" y1="${highY.toFixed(3)}" x2="${xCenter.toFixed(3)}" y2="${lowY.toFixed(3)}" stroke="${bodyColor}" stroke-width="${wickWidth.toFixed(3)}" stroke-linecap="round"></line>
                    <rect x="${(xCenter - (bodyWidth / 2)).toFixed(3)}" y="${bodyTop.toFixed(3)}" width="${bodyWidth.toFixed(3)}" height="${bodyHeight.toFixed(3)}" fill="${bodyColor}" opacity="0.8" rx="0.2"></rect>
                `;
            });

            chartSvg.innerHTML = svg;
        }

        function parseViewBox(svg) {
            const raw = svg.dataset.baseViewBox || svg.getAttribute('viewBox') || '0 0 100 48';
            const parts = raw.split(/\s+/).map(Number);
            return {
                x: parts[0] || 0,
                y: parts[1] || 0,
                width: parts[2] || 100,
                height: parts[3] || 48,
            };
        }

        function clamp(value, min, max) {
            return Math.min(Math.max(value, min), max);
        }

        function ensureChartZoomState(svg) {
            if (!svg.dataset.baseViewBox) {
                svg.dataset.baseViewBox = svg.getAttribute('viewBox') || '0 0 100 48';
            }

            if (!svg._zoomState) {
                const base = parseViewBox(svg);
                svg._zoomState = {
                    base,
                    current: { ...base },
                    dragging: false,
                    startClientX: 0,
                    startClientY: 0,
                    startViewBox: { ...base },
                };
            }

            return svg._zoomState;
        }

        function setChartViewBox(svg, box) {
            svg.setAttribute('viewBox', `${box.x} ${box.y} ${box.width} ${box.height}`);
            if (svg._zoomState) {
                svg._zoomState.current = { ...box };
            }
        }

        function resetChartZoom(svg) {
            const state = ensureChartZoomState(svg);
            state.current = { ...state.base };
            setChartViewBox(svg, state.base);
        }

        function zoomChart(svg, delta, clientX, clientY) {
            const state = ensureChartZoomState(svg);
            const rect = svg.getBoundingClientRect();
            const pointerX = clamp((clientX - rect.left) / rect.width, 0, 1);
            const pointerY = clamp((clientY - rect.top) / rect.height, 0, 1);
            const scale = delta < 0 ? 0.88 : 1.14;

            const base = state.current;
            const nextWidth = clamp(base.width * scale, state.base.width / 8, state.base.width);
            const nextHeight = clamp(base.height * scale, state.base.height / 8, state.base.height);

            const worldX = base.x + (base.width * pointerX);
            const worldY = base.y + (base.height * pointerY);

            const nextX = clamp(worldX - (nextWidth * pointerX), state.base.x, state.base.x + state.base.width - nextWidth);
            const nextY = clamp(worldY - (nextHeight * pointerY), state.base.y, state.base.y + state.base.height - nextHeight);

            setChartViewBox(svg, { x: nextX, y: nextY, width: nextWidth, height: nextHeight });
        }

        function panChart(svg, clientX, clientY) {
            const state = ensureChartZoomState(svg);
            if (!state.dragging) {
                return;
            }

            const rect = svg.getBoundingClientRect();
            const deltaX = (clientX - state.startClientX) / rect.width * state.startViewBox.width;
            const deltaY = (clientY - state.startClientY) / rect.height * state.startViewBox.height;

            const nextX = clamp(state.startViewBox.x - deltaX, state.base.x, state.base.x + state.base.width - state.startViewBox.width);
            const nextY = clamp(state.startViewBox.y - deltaY, state.base.y, state.base.y + state.base.height - state.startViewBox.height);

            setChartViewBox(svg, {
                x: nextX,
                y: nextY,
                width: state.startViewBox.width,
                height: state.startViewBox.height,
            });
        }

        function attachChartInteractions(svg) {
            if (!svg || svg.dataset.zoomBound === '1') {
                return;
            }

            ensureChartZoomState(svg);
            svg.dataset.zoomBound = '1';

            svg.addEventListener('wheel', event => {
                event.preventDefault();
                zoomChart(svg, event.deltaY, event.clientX, event.clientY);
            }, { passive: false });

            svg.addEventListener('pointerdown', event => {
                const state = ensureChartZoomState(svg);
                state.dragging = true;
                state.startClientX = event.clientX;
                state.startClientY = event.clientY;
                state.startViewBox = { ...state.current };
                svg.classList.add('is-dragging');
                svg.setPointerCapture(event.pointerId);
            });

            svg.addEventListener('pointermove', event => {
                panChart(svg, event.clientX, event.clientY);
            });

            const endDrag = event => {
                const state = ensureChartZoomState(svg);
                state.dragging = false;
                svg.classList.remove('is-dragging');
                if (event && svg.hasPointerCapture(event.pointerId)) {
                    svg.releasePointerCapture(event.pointerId);
                }
            };

            svg.addEventListener('pointerup', endDrag);
            svg.addEventListener('pointercancel', endDrag);
            svg.addEventListener('mouseleave', () => {
                const state = ensureChartZoomState(svg);
                state.dragging = false;
                svg.classList.remove('is-dragging');
            });

            svg.addEventListener('dblclick', () => {
                resetChartZoom(svg);
            });
        }

        function applyTrend(trend) {
            const isUp = trend === 'up';

            chartTrendBadge.textContent = `Trend: ${isUp ? 'Up' : 'Down'}`;
            chartTrendBadge.classList.toggle('down', !isUp);

            marketDirectionLabel.textContent = isUp ? 'Uptrend' : 'Downtrend';
            marketDirectionBadge.classList.toggle('positive', isUp);
            marketDirectionBadge.classList.toggle('negative', !isUp);
            marketDirectionBadge.innerHTML = `<i class="fa fa-${isUp ? 'arrow-up' : 'arrow-down'}"></i> ${isUp ? 'Momentum rising' : 'Momentum falling'}`;
        }

        async function refreshPairChart() {
            try {
                const response = await fetch(chartFeedUrl, {
                    headers: {
                        'Accept': 'application/json'
                    }
                });

                if (!response.ok) {
                    throw new Error(`Chart feed failed with status ${response.status}`);
                }

                const payload = await response.json();
                if (!payload.success) {
                    return;
                }

                const lineValues = (payload.line || []).map(point => Number(point.v));
                const candles = payload.candles || [];
                const trend = payload.trend || 'up';

                applyTrend(trend);

                if (chartMode === 'candles') {
                    renderCandles(candles);
                    attachChartInteractions(chartSvg);
                } else {
                    renderLineChart(lineValues, trend);
                    attachChartInteractions(chartSvg);
                }
            } catch (error) {
                console.error('Failed to refresh chart:', error);
            }
        }

        function setupChartModeToggle() {
            chartModeButtons.forEach(button => {
                button.addEventListener('click', () => {
                    chartModeButtons.forEach(item => item.classList.remove('active'));
                    button.classList.add('active');
                    chartMode = button.dataset.chartMode === 'candles' ? 'candles' : 'line';
                    refreshPairChart();
                });
            });
        }

        function startChartAutoRefresh() {
            refreshPairChart();

            if (chartTimer) {
                clearInterval(chartTimer);
            }

            chartTimer = setInterval(refreshPairChart, 400);
        }

        quickAmountBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                amountInput.value = parseFloat(this.dataset.amount).toFixed(2);
                quickAmountBtns.forEach(b => b.classList.remove('active'));
                this.classList.add('active');
                updatePreview();
            });
        });

        amountInput.addEventListener('input', function() {
            quickAmountBtns.forEach(b => b.classList.remove('active'));
            updatePreview();
        });

        const durationInput = document.getElementById('durationInput');
        const durationLabel = document.getElementById('durationLabel');
        if (durationInput && durationInput.type === 'number') {
            const durMin = parseInt(durationInput.min, 10);
            const durMax = parseInt(durationInput.max, 10);
            const syncDuration = () => {
                const v = parseInt(durationInput.value, 10);
                if (durationLabel && !isNaN(v)) { durationLabel.textContent = v === 1 ? 'day' : 'days'; }
                updatePreview();
            };
            // Let the user type freely; only coerce to an integer within bounds
            // when they leave the field so partial input isn't fought while typing.
            durationInput.addEventListener('input', syncDuration);
            durationInput.addEventListener('blur', function() {
                let v = parseInt(durationInput.value, 10);
                if (isNaN(v)) { v = durMin; }
                v = Math.min(durMax, Math.max(durMin, v));
                durationInput.value = v;
                syncDuration();
            });
            syncDuration();
        }

        document.addEventListener('visibilitychange', () => {
            if (document.hidden && chartTimer) {
                clearInterval(chartTimer);
                chartTimer = null;
                return;
            }

            if (!document.hidden) {
                startChartAutoRefresh();
            }
        });

        window.addEventListener('beforeunload', () => {
            if (chartTimer) {
                clearInterval(chartTimer);
                chartTimer = null;
            }
        });

        updatePreview();
        setupChartModeToggle();
        startChartAutoRefresh();
    </script>
@endsection

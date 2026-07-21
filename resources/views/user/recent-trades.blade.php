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

    <div class="main-panel trades-page" data-theme="{{ $bg }}">
        <div class="content">
            <div class="page-inner">

                <!-- Page Header -->
                <div class="page-header">
                    <div class="header-content">
                        <h1 class="page-title">Recent Trades</h1>
                        <p class="page-subtitle">Track your active and completed trades</p>
                    </div>
                    <div class="balance-card">
                        <div class="balance-icon">
                            <i class="fa fa-wallet"></i>
                        </div>
                        <div class="balance-info">
                            <span class="balance-label">Available Balance</span>
                            <span class="balance-value">{{ $settings->currency }}{{ number_format(auth()->user()->account_bal, 2) }}</span>
                        </div>
                    </div>
                </div>

                <x-danger-alert/>
                <x-success-alert/>

                <!-- Stats Overview -->
                @if (!$investments->isEmpty())
                    <div class="stats-grid">
                        <div class="stat-card">
                            <div class="stat-icon active-icon">
                                <i class="fa fa-spinner fa-pulse"></i>
                            </div>
                            <div class="stat-content">
                                <span class="stat-value">{{ $investments->where('status', 'active')->count() }}</span>
                                <span class="stat-label">Active Trades</span>
                            </div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-icon completed-icon">
                                <i class="fa fa-check-circle"></i>
                            </div>
                            <div class="stat-content">
                                <span class="stat-value">{{ $investments->where('status', 'completed')->count() }}</span>
                                <span class="stat-label">Completed</span>
                            </div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-icon total-icon">
                                <i class="fa fa-coins"></i>
                            </div>
                            <div class="stat-content">
                                <span class="stat-value">{{ $settings->currency }}{{ number_format($investments->sum('amount'), 2) }}</span>
                                <span class="stat-label">Total Invested</span>
                            </div>
                        </div>
                    </div>

                    <div class="history-chart-toolbar">
                        <span class="history-chart-note">Pair movement charts are shared per trading pair.</span>
                        <div class="history-chart-mode-switch" id="historyChartModeSwitch">
                            <button type="button" class="history-chart-mode-btn active" data-mode="line">Line</button>
                            <button type="button" class="history-chart-mode-btn" data-mode="candles">Candlestick</button>
                        </div>
                    </div>
                @endif

                <!-- Trades Content -->
                <div class="trades-card">
                    @if ($investments->isEmpty())
                        <div class="empty-state">
                            <div class="empty-icon">
                                <i class="fa fa-chart-line"></i>
                            </div>
                            <h4>No Trades Yet</h4>
                            <p>You haven't made any trades. Start trading to see your history here.</p>
                            <a href="{{ route('trading.pairs') }}" class="empty-action-btn">
                                <i class="fa fa-plus"></i>
                                Start Trading
                            </a>
                        </div>
                    @else
                        <!-- Trades List -->
                        <div class="trades-list">
                            @foreach ($investments as $investment)
                                <div class="trade-item {{ $investment->status === 'active' ? 'active' : '' }}">
                                    <div class="trade-main">
                                        <div class="trade-pair">
                                            <div class="pair-icon">
                                                @if ($investment->tradingPair && $investment->tradingPair->base_icon_url)
                                                    <img src="{{ $investment->tradingPair->base_icon_url ?? asset('images/default-coin.png') }}"
                                                         alt="{{ $investment->tradingPair->base_symbol }}"
                                                         onerror="this.src='https://via.placeholder.com/40'">
                                                @else
                                                    <i class="fa fa-coins"></i>
                                                @endif
                                            </div>
                                            <div class="pair-info">
                                                <span class="pair-symbol">
                                                    {{ $investment->tradingPair ? $investment->tradingPair->base_symbol . '/' . $investment->tradingPair->quote_symbol : 'N/A' }}
                                                </span>
                                                <span class="pair-dates">
                                                    {{ $investment->start_date->format('M d, Y') }}
                                                    <i class="fa fa-arrow-right"></i>
                                                    {{ $investment->end_date ? $investment->end_date->format('M d, Y') : 'Ongoing' }}
                                                </span>
                                            </div>
                                        </div>

                                        <div class="trade-status-wrapper">
                                            @if($investment->status === 'active')
                                                <span class="status-badge active">
                                                    <span class="status-dot"></span>
                                                    Active
                                                </span>
                                            @else
                                                <span class="status-badge completed">
                                                    <i class="fa fa-check"></i>
                                                    Completed
                                                </span>
                                            @endif

                                            @if($investment->tradingPair && $investment->status === 'active')
                                                <span class="status-badge trend" id="trend-badge-{{ $investment->id }}">
                                                    <i class="fa fa-arrow-up"></i>
                                                    Uptrend
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                    @if($investment->tradingPair && $investment->status === 'active')
                                        <div class="trade-chart-wrap">
                                            <svg class="trade-chart-svg"
                                                 id="trade-chart-svg-{{ $investment->id }}"
                                                 data-investment-id="{{ $investment->id }}"
                                                 data-pair-id="{{ $investment->tradingPair->id }}"
                                                 viewBox="0 0 100 36"
                                                 preserveAspectRatio="none"
                                                 aria-label="{{ $investment->tradingPair->base_symbol }} trend chart"></svg>
                                        </div>
                                    @endif

                                    <div class="trade-details">
                                        <div class="detail-item">
                                            <span class="detail-label">Amount</span>
                                            <span class="detail-value">{{ $settings->currency }}{{ number_format($investment->amount, 2) }}</span>
                                        </div>
                                        <div class="detail-item">
                                            <span class="detail-label">Profit</span>
                                            <span class="detail-value profit-display"
                                                  data-investment-id="{{ $investment->id }}"
                                                  data-pair-id="{{ $investment->tradingPair ? $investment->tradingPair->id : '' }}"
                                                  data-amount="{{ $investment->amount }}"
                                                  data-min-return="{{ $investment->tradingPair ? $investment->tradingPair->min_return_percentage : 0 }}"
                                                  data-max-return="{{ $investment->tradingPair ? $investment->tradingPair->max_return_percentage : 0 }}"
                                                  data-status="{{ $investment->status }}"
                                                  data-profit="{{ $investment->profit ?? 0 }}">
                                                {{ $investment->profit !== null ? $settings->currency . number_format($investment->profit, 2) : 'Calculating...' }}
                                            </span>
                                        </div>
                                        <div class="detail-item">
                                            <span class="detail-label">Time Left</span>
                                            <span class="detail-value countdown-timer"
                                                  data-endtime="{{ $investment->end_date ? $investment->end_date->toISOString() : '' }}">
                                                {{ $investment->end_date ? '' : 'N/A' }}
                                            </span>
                                        </div>
                                    </div>

                                    @if($investment->status === 'active')
                                        <div class="trade-progress">
                                            <div class="progress-bar">
                                                <div class="progress-fill"
                                                     data-start="{{ $investment->start_date->timestamp }}"
                                                     data-end="{{ $investment->end_date ? $investment->end_date->timestamp : '' }}">
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>

                        <!-- Pagination -->
                        @if($investments->hasPages())
                            <div class="pagination-wrapper">
                                {{ $investments->links() }}
                            </div>
                        @endif
                    @endif
                </div>

            </div>
        </div>
    </div>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Space+Grotesk:wght@500;600;700&display=swap" rel="stylesheet">
    @verbatim
    <style>
    /* ============ Zaraex recent trades ============ */
    .trades-page{
        --card:#0e1018; --elev:#16181f; --border:#23262f;
        --text:#f2f5fb; --muted:#a1a9b8; --faint:#6b7280;
        --blue:#2563eb; --blue-soft:#3b82f6; --success:#22c55e; --amber:#f59e0b; --cyan:#06b6d4; --danger:#ef4444;
        --tint:rgba(37,99,235,.14);
        font-family:'Inter',system-ui,-apple-system,sans-serif; color:var(--text);
    }
    body[data-theme="light"] .trades-page{
        --card:#ffffff; --elev:#f4f6fa; --border:#e8ecf2; --text:#0f172a; --muted:#5b6674; --faint:#98a2b3;
    }
    .trades-page .content{padding:0}
    .trades-page .page-inner{max-width:560px;margin:0 auto;padding:22px 16px 96px;display:flex;flex-direction:column;gap:16px}
    .trades-page h1,.trades-page h4{font-family:'Space Grotesk','Inter',sans-serif;letter-spacing:-.02em;margin:0}

    /* Header */
    .trades-page .page-header{display:flex;align-items:center;justify-content:space-between;gap:12px;flex-wrap:wrap}
    .trades-page .page-title{font-size:1.5rem;font-weight:700}
    .trades-page .page-subtitle{margin:4px 0 0;color:var(--muted);font-size:.88rem}
    .balance-card{display:flex;align-items:center;gap:10px;background:var(--card);border:1px solid var(--border);border-radius:14px;padding:10px 14px}
    .balance-icon{width:36px;height:36px;border-radius:10px;display:grid;place-items:center;background:var(--tint);color:var(--blue-soft)}
    .balance-label{display:block;color:var(--faint);font-size:.7rem;text-transform:uppercase;letter-spacing:.04em}
    .balance-value{font-family:'Space Grotesk',sans-serif;font-weight:700;font-size:1rem}

    /* Stats */
    .stats-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:12px}
    .stat-card{display:flex;flex-direction:column;gap:10px;background:var(--card);border:1px solid var(--border);border-radius:16px;padding:16px}
    .stat-icon{width:38px;height:38px;border-radius:11px;display:grid;place-items:center;background:var(--tint);color:var(--blue-soft)}
    .stat-icon.completed-icon{background:rgba(34,197,94,.14);color:var(--success)}
    .stat-icon.total-icon{background:rgba(6,182,212,.14);color:var(--cyan)}
    .stat-value{display:block;font-family:'Space Grotesk',sans-serif;font-weight:700;font-size:1.35rem}
    .stat-label{display:block;color:var(--faint);font-size:.76rem;margin-top:2px}

    /* Chart toolbar */
    .history-chart-toolbar{display:flex;align-items:center;justify-content:space-between;gap:10px;flex-wrap:wrap}
    .history-chart-note{color:var(--faint);font-size:.78rem}
    .history-chart-mode-switch{display:inline-flex;background:var(--elev);border:1px solid var(--border);border-radius:10px;padding:3px;gap:2px}
    .history-chart-mode-btn{padding:6px 12px;border:none;background:transparent;border-radius:7px;color:var(--muted);font-weight:600;font-size:.78rem;cursor:pointer;transition:.2s}
    .history-chart-mode-btn.active{background:var(--blue);color:#fff}

    /* Trades card / list */
    .trades-card{display:flex;flex-direction:column;gap:12px}
    .trades-list{display:flex;flex-direction:column;gap:12px}
    .trade-item{background:var(--card);border:1px solid var(--border);border-radius:18px;padding:18px}
    .trade-item.active{border-color:rgba(37,99,235,.4)}
    .trade-main{display:flex;align-items:flex-start;justify-content:space-between;gap:12px}
    .trade-pair{display:flex;align-items:center;gap:12px;min-width:0}
    .pair-icon{width:42px;height:42px;border-radius:50%;flex:none;overflow:hidden;display:grid;place-items:center;background:var(--elev);color:var(--blue-soft)}
    .pair-icon img{width:100%;height:100%;object-fit:cover}
    .pair-symbol{display:block;font-family:'Space Grotesk',sans-serif;font-weight:700;font-size:1rem}
    .pair-dates{display:flex;align-items:center;gap:6px;color:var(--faint);font-size:.76rem;margin-top:2px}
    .trade-status-wrapper{display:flex;flex-direction:column;align-items:flex-end;gap:6px;flex:none}
    .status-badge{display:inline-flex;align-items:center;gap:5px;padding:4px 10px;border-radius:999px;font-size:.72rem;font-weight:600;white-space:nowrap}
    .status-badge.active{background:rgba(37,99,235,.14);color:var(--blue-soft)}
    .status-badge.completed{background:rgba(34,197,94,.14);color:var(--success)}
    .status-badge.trend{background:rgba(34,197,94,.14);color:var(--success)}
    .status-dot{width:7px;height:7px;border-radius:50%;background:var(--blue-soft);box-shadow:0 0 0 3px rgba(37,99,235,.2)}

    /* Chart */
    .trade-chart-wrap{margin-top:14px;height:70px;width:100%;border-radius:12px;overflow:hidden;background:var(--elev);border:1px solid var(--border);padding:6px}
    .trade-chart-svg{width:100%;height:100%;display:block}

    /* Details */
    .trade-details{display:grid;grid-template-columns:repeat(3,1fr);gap:10px;margin-top:14px}
    .detail-item{background:var(--elev);border:1px solid var(--border);border-radius:12px;padding:11px 13px}
    .detail-label{display:block;color:var(--faint);font-size:.72rem}
    .detail-value{display:block;font-family:'Space Grotesk',sans-serif;font-weight:600;font-size:.92rem;margin-top:3px}
    .detail-value.profit-display{color:var(--success)}
    .countdown-timer{color:var(--amber)}

    /* Progress */
    .trade-progress{margin-top:14px}
    .progress-bar{height:7px;border-radius:999px;background:var(--elev);border:1px solid var(--border);overflow:hidden}
    .progress-fill{height:100%;border-radius:999px;background:linear-gradient(90deg,var(--blue),var(--blue-soft));transition:width .4s ease}

    /* Empty */
    .trades-page .empty-state{display:flex;flex-direction:column;align-items:center;text-align:center;gap:8px;padding:44px 20px;background:var(--card);border:1px solid var(--border);border-radius:20px}
    .trades-page .empty-icon{width:60px;height:60px;border-radius:16px;display:grid;place-items:center;margin-bottom:6px;background:var(--tint);color:var(--blue-soft);font-size:26px}
    .trades-page .empty-state h4{font-size:1.15rem}
    .trades-page .empty-state p{color:var(--muted);font-size:.9rem;max-width:340px;margin:0}
    .empty-action-btn{display:inline-flex;align-items:center;gap:8px;margin-top:10px;padding:13px 22px;border-radius:12px;background:var(--blue);color:#fff;font-weight:600;font-size:.92rem;text-decoration:none;transition:.2s}
    .empty-action-btn:hover{background:var(--blue-soft);color:#fff}

    /* Pagination */
    .pagination-wrapper{display:flex;justify-content:center;margin-top:6px}
    .trades-page .pagination{display:flex;gap:6px;list-style:none;padding:0;margin:0;flex-wrap:wrap}
    .trades-page .page-item .page-link,.trades-page .pagination a,.trades-page .pagination span{
        display:grid;place-items:center;min-width:38px;height:38px;padding:0 10px;border-radius:11px!important;
        border:1px solid var(--border)!important;background:var(--card)!important;color:var(--muted)!important;font-weight:600;font-size:.85rem;text-decoration:none}
    .trades-page .page-item.active .page-link,.trades-page .pagination .active span{background:var(--blue)!important;border-color:var(--blue)!important;color:#fff!important}
    .trades-page .page-item.disabled .page-link{opacity:.4}
    </style>
    @endverbatim

    <script>
        const currency = '{{ $settings->currency }}';
        const pairsBaseUrl = '{{ url('/trading-pairs') }}';
        const historyChartModeButtons = document.querySelectorAll('.history-chart-mode-btn');
        const tradeChartNodes = document.querySelectorAll('.trade-chart-svg[data-pair-id]');
        const profitElements = document.querySelectorAll('.profit-display');

        let historyChartMode = 'line';
        let historyChartTimer = null;
        const liveProfitState = new Map();

        function normalizeSeries(values) {
            const min = Math.min(...values);
            const max = Math.max(...values);
            const spread = Math.max(max - min, 0.001);
            return values.map(value => (value - min) / spread);
        }

        function toChartY(value) {
            return 3 + (1 - value) * 30;
        }

        function renderLineChart(svg, series, trend) {
            if (!series.length) {
                svg.innerHTML = '';
                return;
            }

            const normalized = normalizeSeries(series);
            const step = series.length > 1 ? 100 / (series.length - 1) : 100;
            const color = trend === 'up' ? '#10b981' : '#ef4444';

            let path = '';
            normalized.forEach((value, index) => {
                const x = (step * index).toFixed(3);
                const y = toChartY(value).toFixed(3);
                path += `${index === 0 ? 'M' : 'L'} ${x} ${y} `;
            });

            svg.innerHTML = `
                <defs>
                    <linearGradient id="historyLineFill-${svg.id}" x1="0" y1="0" x2="0" y2="1">
                        <stop offset="0%" stop-color="${color}" stop-opacity="0.35"></stop>
                        <stop offset="100%" stop-color="${color}" stop-opacity="0"></stop>
                    </linearGradient>
                </defs>
                <path d="${path} L 100 33 L 0 33 Z" fill="url(#historyLineFill-${svg.id})"></path>
                <path d="${path}" fill="none" stroke="${color}" stroke-width="1.1" stroke-linecap="round"></path>
            `;
        }

        function renderCandleChart(svg, candles) {
            if (!candles.length) {
                svg.innerHTML = '';
                return;
            }

            const values = [];
            candles.forEach(item => values.push(item.o, item.h, item.l, item.c));
            const min = Math.min(...values);
            const max = Math.max(...values);
            const spread = Math.max(max - min, 0.001);
            const scale = value => (value - min) / spread;

            const step = 100 / candles.length;
            let html = '';

            candles.forEach((candle, index) => {
                const x = index * step + (step / 2);
                const openY = toChartY(scale(candle.o));
                const closeY = toChartY(scale(candle.c));
                const highY = toChartY(scale(candle.h));
                const lowY = toChartY(scale(candle.l));
                const top = Math.min(openY, closeY);
                const bodyHeight = Math.max(Math.abs(openY - closeY), 0.6);
                const wickHeight = Math.max(Math.abs(highY - lowY), bodyHeight + 0.6);
                const bodyWidth = clamp(step * (0.24 + ((bodyHeight / 34) * 0.96)), 0.75, step * 0.9);
                const wickWidth = clamp(0.22 + (wickHeight / 42), 0.2, 1.0);
                const rising = candle.c >= candle.o;
                const color = rising ? '#10b981' : '#ef4444';

                html += `
                    <line x1="${x.toFixed(3)}" y1="${highY.toFixed(3)}" x2="${x.toFixed(3)}" y2="${lowY.toFixed(3)}" stroke="${color}" stroke-width="${wickWidth.toFixed(3)}" stroke-linecap="round"></line>
                    <rect x="${(x - bodyWidth / 2).toFixed(3)}" y="${top.toFixed(3)}" width="${bodyWidth.toFixed(3)}" height="${bodyHeight.toFixed(3)}" fill="${color}" opacity="0.88" rx="0.22"></rect>
                `;
            });

            svg.innerHTML = html;
        }

        function applyTrendBadge(investmentId, trend) {
            const badge = document.getElementById(`trend-badge-${investmentId}`);
            if (!badge) {
                return;
            }

            const up = trend === 'up';
            badge.classList.toggle('down', !up);
            badge.innerHTML = `<i class="fa fa-${up ? 'arrow-up' : 'arrow-down'}"></i> ${up ? 'Uptrend' : 'Downtrend'}`;
        }

        function clamp(value, min, max) {
            return Math.min(Math.max(value, min), max);
        }

        function renderCompletedProfits() {
            profitElements.forEach(element => {
                const status = element.dataset.status;
                if (status === 'active') {
                    return;
                }

                const profit = parseFloat(element.dataset.profit || '0');
                element.textContent = `${currency}${profit.toFixed(2)}`;
                element.classList.remove('profit-positive', 'profit-negative');
                element.classList.add(profit >= 0 ? 'profit-positive' : 'profit-negative');
            });
        }

        function parseViewBox(svg) {
            const raw = svg.dataset.baseViewBox || svg.getAttribute('viewBox') || '0 0 100 36';
            const parts = raw.split(/\s+/).map(Number);
            return {
                x: parts[0] || 0,
                y: parts[1] || 0,
                width: parts[2] || 100,
                height: parts[3] || 36,
            };
        }

        function clamp(value, min, max) {
            return Math.min(Math.max(value, min), max);
        }

        function ensureChartZoomState(svg) {
            if (!svg.dataset.baseViewBox) {
                svg.dataset.baseViewBox = svg.getAttribute('viewBox') || '0 0 100 36';
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

            setChartViewBox(svg, {
                x: nextX,
                y: nextY,
                width: nextWidth,
                height: nextHeight,
            });
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

        function updateActiveProfitFromFeed(element, payload) {
            const investmentId = element.dataset.investmentId;
            const amount = parseFloat(element.dataset.amount || '0');
            const minReturn = parseFloat(element.dataset.minReturn || '0') / 100;
            const maxReturn = parseFloat(element.dataset.maxReturn || '0') / 100;

            const lineSeries = (payload.line || []).map(point => Number(point.v));
            if (lineSeries.length < 2 || amount <= 0) {
                return;
            }

            const last = lineSeries[lineSeries.length - 1];
            const prev = lineSeries[lineSeries.length - 2];
            const movingUp = last >= prev;

            const minProfit = -minReturn * amount;
            const maxProfit = maxReturn * amount;

            const defaultProfit = parseFloat(element.dataset.profit || '0');
            const currentProfit = liveProfitState.has(investmentId)
                ? liveProfitState.get(investmentId)
                : defaultProfit;

            const movementRatio = Math.abs(last - prev) / Math.max(Math.abs(prev), 1);
            const averageRange = (minReturn + maxReturn) / 2;
            const baseStep = amount * averageRange * 0.08;
            const movementStep = amount * movementRatio * 6;
            const step = Math.max(baseStep + movementStep, amount * 0.001);

            const nextProfit = clamp(
                currentProfit + (movingUp ? step : -step),
                minProfit,
                maxProfit
            );

            liveProfitState.set(investmentId, nextProfit);

            element.textContent = `${currency}${nextProfit.toFixed(2)}`;
            element.classList.remove('profit-positive', 'profit-negative');
            element.classList.add(nextProfit >= 0 ? 'profit-positive' : 'profit-negative');
        }

        async function fetchPairFeeds(pairIds) {
            const uniquePairIds = Array.from(new Set(pairIds.filter(Boolean)));

            const payloads = await Promise.all(uniquePairIds.map(async pairId => {
                try {
                    const response = await fetch(`${pairsBaseUrl}/${pairId}/chart-feed`, {
                        headers: {
                            'Accept': 'application/json'
                        }
                    });

                    if (!response.ok) {
                        return [pairId, null];
                    }

                    const payload = await response.json();
                    if (!payload.success) {
                        return [pairId, null];
                    }

                    return [pairId, payload];
                } catch (error) {
                    console.error('Failed to refresh recent trade chart feed:', error);
                    return [pairId, null];
                }
            }));

            return new Map(payloads);
        }

        async function refreshAllTradeCharts() {
            const pairIds = [
                ...Array.from(tradeChartNodes).map(svg => svg.dataset.pairId),
                ...Array.from(profitElements).map(node => node.dataset.pairId)
            ];

            const feedByPair = await fetchPairFeeds(pairIds);

            tradeChartNodes.forEach(svg => {
                const pairId = svg.dataset.pairId;
                const investmentId = svg.dataset.investmentId;
                const payload = feedByPair.get(pairId);

                if (!payload) {
                    return;
                }

                const trend = payload.trend || 'up';
                const lineSeries = (payload.line || []).map(point => Number(point.v));
                const candles = payload.candles || [];

                applyTrendBadge(investmentId, trend);

                if (historyChartMode === 'candles') {
                    renderCandleChart(svg, candles);
                    attachChartInteractions(svg);
                    return;
                }

                renderLineChart(svg, lineSeries, trend);
                attachChartInteractions(svg);
            });

            profitElements.forEach(element => {
                if (element.dataset.status !== 'active') {
                    return;
                }

                const pairId = element.dataset.pairId;
                const payload = feedByPair.get(pairId);
                if (!payload) {
                    return;
                }

                updateActiveProfitFromFeed(element, payload);
            });
        }

        historyChartModeButtons.forEach(button => {
            button.addEventListener('click', () => {
                historyChartModeButtons.forEach(item => item.classList.remove('active'));
                button.classList.add('active');
                historyChartMode = button.dataset.mode === 'candles' ? 'candles' : 'line';
                refreshAllTradeCharts();
            });
        });

        function updateCountdowns() {
            const countdownElements = document.querySelectorAll('.countdown-timer');

            countdownElements.forEach(el => {
                const endTimeStr = el.dataset.endtime;
                if (!endTimeStr) {
                    el.textContent = 'N/A';
                    return;
                }

                const endTime = new Date(endTimeStr);
                const now = new Date();
                const diff = endTime - now;

                if (diff <= 0) {
                    el.textContent = '0.0.0';
                    el.classList.add('countdown-expired');
                    el.classList.remove('countdown-active');
                    return;
                }

                const days = Math.floor(diff / (1000 * 60 * 60 * 24));
                const hours = Math.floor((diff / (1000 * 60 * 60)) % 24);
                const minutes = Math.floor((diff / (1000 * 60)) % 60);
                const seconds = Math.floor((diff / 1000) % 60);

                let timeString = '';
                if (days > 0) {
                    timeString = `${days}d ${String(hours).padStart(2, '0')}h ${String(minutes).padStart(2, '0')}m`;
                } else if (hours > 0) {
                    timeString = `${String(hours).padStart(2, '0')}h ${String(minutes).padStart(2, '0')}m ${String(seconds).padStart(2, '0')}s`;
                } else {
                    timeString = `${String(minutes).padStart(2, '0')}m ${String(seconds).padStart(2, '0')}s`;
                }

                el.textContent = timeString;
                el.classList.add('countdown-active');
                el.classList.remove('countdown-expired');
            });
        }

        function updateProgressBars() {
            const progressBars = document.querySelectorAll('.progress-fill');

            progressBars.forEach(bar => {
                const startTime = parseInt(bar.dataset.start) * 1000;
                const endTime = parseInt(bar.dataset.end) * 1000;

                if (!endTime) return;

                const now = Date.now();
                const total = endTime - startTime;
                const elapsed = now - startTime;
                const progress = Math.min(Math.max((elapsed / total) * 100, 0), 100);

                bar.style.width = `${progress}%`;
            });
        }

        // Initialize
        renderCompletedProfits();
        updateCountdowns();
        updateProgressBars();
        refreshAllTradeCharts();

        // Update intervals
        setInterval(updateCountdowns, 1000);
        setInterval(updateProgressBars, 1000);

        historyChartTimer = setInterval(refreshAllTradeCharts, 400);

        document.addEventListener('visibilitychange', () => {
            if (document.hidden && historyChartTimer) {
                clearInterval(historyChartTimer);
                historyChartTimer = null;
                return;
            }

            if (!document.hidden) {
                refreshAllTradeCharts();
                historyChartTimer = setInterval(refreshAllTradeCharts, 400);
            }
        });

        window.addEventListener('beforeunload', () => {
            if (historyChartTimer) {
                clearInterval(historyChartTimer);
            }
        });
    </script>
@endsection

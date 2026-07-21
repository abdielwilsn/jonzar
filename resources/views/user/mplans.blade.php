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

    <div class="main-panel trading-pairs-page" data-theme="{{ $bg }}">
        <div class="content">
            <div class="page-inner">

                <!-- Page Header -->
                <div class="page-header">
                    <div class="header-content">
                        <h1 class="page-title">Trading Pairs</h1>
                        <p class="page-subtitle">
                            <span class="live-indicator">
                                <span class="live-dot"></span>
                              Pair movement
                            </span>
                            synchronized according to our trades.
                        </p>
                    </div>
                    <div class="header-stats">
                        <div class="market-stat">
                            <span class="stat-label">Available Pairs</span>
                            <span class="stat-value">{{ $tradingPairs->count() }}</span>
                        </div>
                    </div>
                </div>

                <x-danger-alert/>
                <x-success-alert/>

                @if ($tradingPairs->isEmpty())
                    <div class="empty-state-card">
                        <div class="empty-icon">
                            <i class="fa fa-chart-line"></i>
                        </div>
                        <h4>No Trading Pairs Available</h4>
                        <p>Trading pairs are currently being updated. Please check back later.</p>
                    </div>
                @else
                    <!-- Search & Filter Bar -->
                    <div class="filter-bar">
                        <div class="search-box">
                            <i class="fa fa-search"></i>
                            <input type="text" id="searchInput" placeholder="Search coins..." autocomplete="off">
                        </div>
                        <div class="filter-tabs">
                            <button class="filter-tab active" data-filter="all">All</button>
                            <button class="filter-tab" data-filter="gainers">Gainers</button>
                            <button class="filter-tab" data-filter="losers">Losers</button>
                        </div>
                        <div class="chart-mode-switch" id="chartModeSwitch">
                            <button type="button" class="chart-mode-btn active" data-mode="line">Line</button>
                            <button type="button" class="chart-mode-btn" data-mode="candles">Candlestick</button>
                        </div>
                    </div>

                    <!-- Trading Pairs Grid -->
                    <div class="pairs-grid" id="pairsGrid">
                        @foreach ($tradingPairs as $pair)
                            <div class="pair-card"
                                 data-name="{{ strtolower($pair->base_name) }}"
                                 data-symbol="{{ strtolower($pair->base_symbol) }}"
                                   data-pair-id="{{ $pair->id }}"
                                 data-change="{{ $pair->price_change_24h }}">

                                <div class="pair-header">
                                    <div class="pair-info">
                                        <div class="coin-icon">
                                            <img src="{{ $pair->base_icon_url ?? asset('images/default-coin.png') }}"
                                                 alt="{{ $pair->base_symbol }}"
                                                 onerror="this.src='https://via.placeholder.com/40'">
                                        </div>
                                        <div class="coin-details">
                                            <span class="coin-symbol">{{ $pair->base_symbol }}/{{ $pair->quote_symbol }}</span>
                                            <span class="coin-name">{{ $pair->base_name }}</span>
                                        </div>
                                    </div>
                                    <div class="pair-change" id="change-wrapper-{{ $pair->id }}">
                                        <span class="change-badge {{ $pair->price_change_24h >= 0 ? 'positive' : 'negative' }}"
                                              id="change-{{ $pair->id }}">
                                            <i class="fa fa-{{ $pair->price_change_24h >= 0 ? 'arrow-up' : 'arrow-down' }}"></i>
                                            {{ $pair->price_change_24h >= 0 ? 'Rising' : 'Falling' }}
                                        </span>
                                    </div>
                                </div>

                                <div class="pair-price">
                                    <span class="price-label">Market Direction</span>
                                    <span class="price-value" id="direction-{{ $pair->id }}">
                                        {{ $pair->price_change_24h >= 0 ? 'Uptrend' : 'Downtrend' }}
                                    </span>
                                </div>

                                <div class="card-chart-switch" data-pair-switch>
                                    <button type="button" class="card-chart-btn active" data-card-mode="line">Line</button>
                                    <button type="button" class="card-chart-btn" data-card-mode="candles">Candle</button>
                                </div>

                                <div class="pair-chart" id="pair-chart-{{ $pair->id }}">
                                    <svg id="pair-chart-svg-{{ $pair->id }}" viewBox="0 0 100 38" preserveAspectRatio="none" aria-label="{{ $pair->base_symbol }} chart"></svg>
                                </div>

                                <div class="pair-stats">
                                    <div class="stat-item">
                                        <span class="stat-label">Market Cap</span>
                                        <span class="stat-value">{{ $settings->currency }}{{ formatNumber($pair->market_cap) }}</span>
                                    </div>
                                    <div class="stat-item">
                                        <span class="stat-label">24h Volume</span>
                                        <span class="stat-value">{{ formatNumber($pair->volume_24h) }}</span>
                                    </div>
                                </div>

                                <a href="{{ route('user.trading-pairs.invest', $pair->id) }}" class="trade-btn">
                                    <i class="fa fa-chart-line"></i>
                                    Trade Now
                                </a>
                            </div>
                        @endforeach
                    </div>

                    <!-- Desktop Table View (Alternative) -->
                    <div class="table-card d-none">
                        <div class="table-wrapper">
                            <table class="pairs-table">
                                <thead>
                                <tr>
                                    <th>Coin</th>
                                    <th class="text-end">Price</th>
                                    <th class="text-end">24h Change</th>
                                    <th class="text-end">Market Cap</th>
                                    <th class="text-end">24h Volume</th>
                                    <th class="text-end">Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($tradingPairs as $pair)
                                    <tr>
                                        <td>
                                            <div class="coin-cell">
                                                <img src="{{ $pair->base_icon_url ?? asset('images/default-coin.png') }}"
                                                     alt="{{ $pair->base_symbol }}">
                                                <div class="coin-info">
                                                    <span class="symbol">{{ $pair->base_symbol }}/{{ $pair->quote_symbol }}</span>
                                                    <span class="name">{{ $pair->base_name }}</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-end">
                                            <span class="table-price">{{ $settings->currency }}{{ number_format($pair->current_price, 2) }}</span>
                                        </td>
                                        <td class="text-end">
                                                <span class="table-change {{ $pair->price_change_24h >= 0 ? 'positive' : 'negative' }}">
                                                    {{ number_format($pair->price_change_24h, 2) }}%
                                                </span>
                                        </td>
                                        <td class="text-end">{{ $settings->currency }}{{ formatNumber($pair->market_cap) }}</td>
                                        <td class="text-end">{{ formatNumber($pair->volume_24h) }}</td>
                                        <td class="text-end">
                                            <a href="{{ route('user.trading-pairs.invest', $pair->id) }}" class="table-trade-btn">
                                                Trade
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif

            </div>
        </div>
    </div>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Space+Grotesk:wght@500;600;700&display=swap" rel="stylesheet">
    @verbatim
    <style>
    /* ============ Zaraex trading pairs ============ */
    .trading-pairs-page{
        --card:#0e1018; --elev:#16181f; --border:#23262f;
        --text:#f2f5fb; --muted:#a1a9b8; --faint:#6b7280;
        --blue:#2563eb; --blue-soft:#3b82f6; --success:#22c55e; --amber:#f59e0b; --danger:#ef4444;
        --tint:rgba(37,99,235,.14);
        font-family:'Inter',system-ui,-apple-system,sans-serif; color:var(--text);
    }
    body[data-theme="light"] .trading-pairs-page{
        --card:#ffffff; --elev:#f4f6fa; --border:#e8ecf2;
        --text:#0f172a; --muted:#5b6674; --faint:#98a2b3;
    }
    .trading-pairs-page .content{padding:0}
    .trading-pairs-page h1,.trading-pairs-page h4{font-family:'Space Grotesk','Inter',sans-serif;letter-spacing:-.02em;margin:0}
    .trading-pairs-page .page-inner{max-width:560px;margin:0 auto;padding:22px 16px 96px}

    /* Header */
    .trading-pairs-page .page-header{display:flex;align-items:flex-start;justify-content:space-between;gap:14px;margin-bottom:18px}
    .trading-pairs-page .page-title{font-size:1.5rem;font-weight:700}
    .trading-pairs-page .page-subtitle{margin:5px 0 0;color:var(--muted);font-size:.86rem;line-height:1.4}
    .live-indicator{display:inline-flex;align-items:center;gap:6px;color:var(--success);font-weight:600}
    .live-dot{width:7px;height:7px;border-radius:50%;background:var(--success);box-shadow:0 0 0 3px rgba(34,197,94,.22);animation:zxpulse 1.8s ease-in-out infinite}
    @keyframes zxpulse{0%,100%{opacity:1}50%{opacity:.4}}
    .header-stats{flex:none}
    .market-stat{background:var(--card);border:1px solid var(--border);border-radius:14px;padding:10px 16px;text-align:center}
    .market-stat .stat-label{display:block;color:var(--faint);font-size:.7rem;text-transform:uppercase;letter-spacing:.05em}
    .market-stat .stat-value{font-family:'Space Grotesk',sans-serif;font-weight:700;font-size:1.15rem}

    /* Empty */
    .empty-state-card{text-align:center;padding:40px 20px;background:var(--card);border:1px solid var(--border);border-radius:20px}
    .empty-icon{width:56px;height:56px;border-radius:16px;display:inline-grid;place-items:center;margin-bottom:14px;background:var(--tint);color:var(--blue-soft);font-size:24px}
    .empty-state-card h4{font-size:1.1rem;margin-bottom:6px}
    .empty-state-card p{color:var(--muted);font-size:.9rem;margin:0}

    /* Filter bar */
    .filter-bar{display:flex;flex-direction:column;gap:12px;margin-bottom:18px}
    .search-box{position:relative;display:flex;align-items:center}
    .search-box i{position:absolute;left:15px;z-index:2;color:var(--faint);font-size:14px;pointer-events:none}
    .trading-pairs-page .search-box input{width:100%;background:var(--card)!important;border:1px solid var(--border)!important;border-radius:13px!important;
        padding:13px 16px 13px 42px!important;color:var(--text)!important;font-size:.95rem;outline:none;transition:.2s}
    .trading-pairs-page .search-box input:focus{border-color:var(--blue)!important;box-shadow:0 0 0 3px rgba(37,99,235,.15)!important}
    .trading-pairs-page .search-box input::placeholder{color:var(--faint)!important}
    .filter-tabs{display:flex;gap:8px}
    .filter-tab{flex:1;padding:9px 8px;border-radius:11px;border:1px solid var(--border);background:var(--card);
        color:var(--muted);font-weight:600;font-size:.85rem;cursor:pointer;transition:.2s}
    .filter-tab:hover{border-color:var(--blue-soft)}
    .filter-tab.active{background:var(--blue);color:#fff;border-color:var(--blue)}
    .chart-mode-switch{display:inline-flex;align-self:flex-start;background:var(--elev);border:1px solid var(--border);border-radius:11px;padding:3px;gap:2px}
    .chart-mode-btn{padding:7px 14px;border:none;background:transparent;border-radius:8px;color:var(--muted);font-weight:600;font-size:.82rem;cursor:pointer;transition:.2s}
    .chart-mode-btn.active{background:var(--blue);color:#fff}

    /* Pairs (single-column mobile list) */
    .pairs-grid{display:grid;grid-template-columns:1fr;gap:14px}
    .pair-card{background:var(--card);border:1px solid var(--border);border-radius:18px;padding:18px;transition:.2s}
    .pair-card:hover{border-color:rgba(59,130,246,.4)}
    .pair-card.hidden{display:none}
    .pair-header{display:flex;align-items:center;justify-content:space-between;gap:10px}
    .pair-info{display:flex;align-items:center;gap:12px;min-width:0}
    .coin-icon{width:42px;height:42px;border-radius:50%;flex:none;overflow:hidden;background:var(--elev);display:grid;place-items:center}
    .coin-icon img{width:100%;height:100%;object-fit:cover}
    .coin-details{display:flex;flex-direction:column;min-width:0}
    .coin-symbol{font-family:'Space Grotesk',sans-serif;font-weight:700;font-size:1rem}
    .coin-name{color:var(--faint);font-size:.8rem;margin-top:1px;text-transform:capitalize}
    .change-badge{display:inline-flex;align-items:center;gap:5px;padding:5px 11px;border-radius:999px;font-size:.76rem;font-weight:600;white-space:nowrap}
    .change-badge.positive{background:rgba(34,197,94,.14);color:var(--success)}
    .change-badge.negative{background:rgba(239,68,68,.14);color:var(--danger)}

    .pair-price{display:flex;align-items:center;justify-content:space-between;margin-top:16px}
    .pair-price .price-label{color:var(--faint);font-size:.8rem}
    .pair-price .price-value{font-family:'Space Grotesk',sans-serif;font-weight:700;font-size:.95rem}

    .card-chart-switch{display:inline-flex;margin-top:14px;background:var(--elev);border:1px solid var(--border);border-radius:9px;padding:2px;gap:2px}
    .card-chart-btn{padding:5px 12px;border:none;background:transparent;border-radius:7px;color:var(--muted);font-weight:600;font-size:.76rem;cursor:pointer;transition:.2s}
    .card-chart-btn.active{background:var(--blue);color:#fff}

    .pair-chart{margin-top:12px;height:92px;width:100%;border-radius:12px;overflow:hidden;background:var(--elev);border:1px solid var(--border);padding:6px}
    .pair-chart svg{width:100%;height:100%;display:block}

    .pair-stats{display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-top:14px}
    .pair-stats .stat-item{background:var(--elev);border:1px solid var(--border);border-radius:12px;padding:11px 13px}
    .pair-stats .stat-label{display:block;color:var(--faint);font-size:.72rem}
    .pair-stats .stat-value{font-family:'Space Grotesk',sans-serif;font-weight:600;font-size:.92rem;margin-top:3px}

    .trade-btn{display:flex;align-items:center;justify-content:center;gap:8px;margin-top:16px;width:100%;
        padding:13px;border-radius:12px;background:var(--blue);color:#fff;font-weight:600;font-size:.95rem;text-decoration:none;transition:.2s}
    .trade-btn:hover{background:var(--blue-soft);color:#fff}
    .trade-btn i{font-size:14px}

    /* Table (kept hidden by .d-none, minimal styling for safety) */
    .table-card{background:var(--card);border:1px solid var(--border);border-radius:18px;overflow:hidden}
    .table-wrapper{overflow-x:auto}
    .pairs-table{width:100%;border-collapse:collapse;color:var(--text)}
    .pairs-table th{background:var(--elev);color:var(--faint);font-size:.78rem;text-align:left;padding:12px 14px}
    .pairs-table td{border-top:1px solid var(--border);padding:12px 14px;font-size:.9rem}
    .coin-cell{display:flex;align-items:center;gap:10px}
    .coin-cell img{width:30px;height:30px;border-radius:50%}
    .table-change.positive{color:var(--success)}
    .table-change.negative{color:var(--danger)}
    .table-trade-btn{background:var(--blue);color:#fff;padding:7px 14px;border-radius:9px;font-weight:600;font-size:.8rem;text-decoration:none}
    </style>
    @endverbatim

    <script>
        // Search functionality
        document.getElementById('searchInput').addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            const cards = document.querySelectorAll('.pair-card');

            cards.forEach(card => {
                const name = card.dataset.name;
                const symbol = card.dataset.symbol;

                if (name.includes(searchTerm) || symbol.includes(searchTerm)) {
                    card.classList.remove('hidden');
                } else {
                    card.classList.add('hidden');
                }
            });
        });

        // Filter tabs
        document.querySelectorAll('.filter-tab').forEach(tab => {
            tab.addEventListener('click', function() {
                // Update active tab
                document.querySelectorAll('.filter-tab').forEach(t => t.classList.remove('active'));
                this.classList.add('active');

                const filter = this.dataset.filter;
                const cards = document.querySelectorAll('.pair-card');

                cards.forEach(card => {
                    const change = parseFloat(card.dataset.change);

                    if (filter === 'all') {
                        card.classList.remove('hidden');
                    } else if (filter === 'gainers' && change >= 0) {
                        card.classList.remove('hidden');
                    } else if (filter === 'losers' && change < 0) {
                        card.classList.remove('hidden');
                    } else {
                        card.classList.add('hidden');
                    }
                });
            });
        });

        const pairsBaseUrl = '{{ url('/trading-pairs') }}';
        const pairCards = document.querySelectorAll('.pair-card[data-pair-id]');
        const chartModeButtons = document.querySelectorAll('.chart-mode-btn');

        let chartMode = 'line';
        let chartRefreshTimer = null;

        function normalizeSeries(values) {
            const min = Math.min(...values);
            const max = Math.max(...values);
            const spread = Math.max(max - min, 0.001);
            return values.map(value => (value - min) / spread);
        }

        function toY(value) {
            return 3 + (1 - value) * 32;
        }

        function renderLine(svg, series, trend) {
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
                const y = toY(value).toFixed(3);
                path += `${index === 0 ? 'M' : 'L'} ${x} ${y} `;
            });

            svg.innerHTML = `
                <defs>
                    <linearGradient id="pairLineFill-${svg.id}" x1="0" y1="0" x2="0" y2="1">
                        <stop offset="0%" stop-color="${color}" stop-opacity="0.35"></stop>
                        <stop offset="100%" stop-color="${color}" stop-opacity="0"></stop>
                    </linearGradient>
                </defs>
                <path d="${path} L 100 36 L 0 36 Z" fill="url(#pairLineFill-${svg.id})"></path>
                <path d="${path}" fill="none" stroke="${color}" stroke-width="1.1" stroke-linecap="round"></path>
            `;
        }

        function renderCandles(svg, candles) {
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
                const x = index * step + step / 2;
                const openY = toY(scale(candle.o));
                const closeY = toY(scale(candle.c));
                const highY = toY(scale(candle.h));
                const lowY = toY(scale(candle.l));
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

        function parseViewBox(svg) {
            const raw = svg.dataset.baseViewBox || svg.getAttribute('viewBox') || '0 0 100 38';
            const parts = raw.split(/\s+/).map(Number);
            return {
                x: parts[0] || 0,
                y: parts[1] || 0,
                width: parts[2] || 100,
                height: parts[3] || 38,
            };
        }

        function clamp(value, min, max) {
            return Math.min(Math.max(value, min), max);
        }

        function ensureChartZoomState(svg) {
            if (!svg.dataset.baseViewBox) {
                svg.dataset.baseViewBox = svg.getAttribute('viewBox') || '0 0 100 38';
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

        function applyDirection(card, trend) {
            const pairId = card.dataset.pairId;
            const directionLabel = document.getElementById(`direction-${pairId}`);
            const changeBadge = document.getElementById(`change-${pairId}`);
            const isUp = trend === 'up';

            if (directionLabel) {
                directionLabel.textContent = isUp ? 'Uptrend' : 'Downtrend';
            }

            if (changeBadge) {
                changeBadge.className = `change-badge ${isUp ? 'positive' : 'negative'}`;
                changeBadge.innerHTML = `<i class="fa fa-${isUp ? 'arrow-up' : 'arrow-down'}"></i> ${isUp ? 'Rising' : 'Falling'}`;
            }
        }

        async function refreshCardChart(card) {
            const pairId = card.dataset.pairId;
            const svg = document.getElementById(`pair-chart-svg-${pairId}`);

            if (!pairId || !svg) {
                return;
            }

            try {
                const response = await fetch(`${pairsBaseUrl}/${pairId}/chart-feed`, {
                    headers: {
                        'Accept': 'application/json'
                    }
                });

                if (!response.ok) {
                    return;
                }

                const payload = await response.json();
                if (!payload.success) {
                    return;
                }

                const trend = payload.trend || 'up';
                const line = (payload.line || []).map(point => Number(point.v));
                const candles = payload.candles || [];

                applyDirection(card, trend);

                if (getCardMode(card) === 'candles') {
                    renderCandles(svg, candles);
                    attachChartInteractions(svg);
                    return;
                }

                renderLine(svg, line, trend);
                attachChartInteractions(svg);
            } catch (error) {
                console.error('Error refreshing pair chart:', error);
            }
        }

        function getCardMode(card) {
            return card.dataset.chartMode === 'candles' ? 'candles' : 'line';
        }

        function syncCardSwitch(card, mode) {
            const switchEl = card.querySelector('[data-pair-switch]');
            if (!switchEl) {
                return;
            }
            switchEl.querySelectorAll('.card-chart-btn').forEach(btn => {
                btn.classList.toggle('active', btn.dataset.cardMode === mode);
            });
        }

        pairCards.forEach(card => {
            card.dataset.chartMode = chartMode;
            const switchEl = card.querySelector('[data-pair-switch]');
            if (!switchEl) {
                return;
            }
            switchEl.querySelectorAll('.card-chart-btn').forEach(btn => {
                btn.addEventListener('click', () => {
                    const mode = btn.dataset.cardMode === 'candles' ? 'candles' : 'line';
                    card.dataset.chartMode = mode;
                    syncCardSwitch(card, mode);
                    refreshCardChart(card);
                });
            });
        });

        function refreshAllCharts() {
            pairCards.forEach(card => {
                refreshCardChart(card);
            });
        }

        chartModeButtons.forEach(button => {
            button.addEventListener('click', () => {
                chartModeButtons.forEach(item => item.classList.remove('active'));
                button.classList.add('active');
                chartMode = button.dataset.mode === 'candles' ? 'candles' : 'line';
                pairCards.forEach(card => {
                    card.dataset.chartMode = chartMode;
                    syncCardSwitch(card, chartMode);
                });
                refreshAllCharts();
            });
        });

        function startChartRefresh() {
            refreshAllCharts();

            if (chartRefreshTimer) {
                clearInterval(chartRefreshTimer);
            }

            chartRefreshTimer = setInterval(refreshAllCharts, 400);
        }

        document.addEventListener('visibilitychange', () => {
            if (document.hidden && chartRefreshTimer) {
                clearInterval(chartRefreshTimer);
                chartRefreshTimer = null;
                return;
            }

            if (!document.hidden) {
                startChartRefresh();
            }
        });

        window.addEventListener('beforeunload', () => {
            if (chartRefreshTimer) {
                clearInterval(chartRefreshTimer);
            }
        });

        startChartRefresh();
    </script>

@endsection

@php
    function formatNumber($num) {
        if ($num >= 1000000000) {
            return number_format($num / 1000000000, 2) . 'B';
        } elseif ($num >= 1000000) {
            return number_format($num / 1000000, 2) . 'M';
        } elseif ($num >= 1000) {
            return number_format($num / 1000, 2) . 'K';
        }
        return number_format($num, 0);
    }
@endphp

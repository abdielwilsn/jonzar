@extends('layouts.app')

<link rel="stylesheet" href="{{ asset('css/trade.css') }}">

@section('content')
    @include('user.topmenu')
    @include('user.sidebar')

    @php
        $bg = Auth::user()->dashboard_style == 'light' ? 'light' : 'dark';
        $text = $bg === 'light' ? 'dark' : 'light';
        $pairMap = [];
        foreach ($currencies as $pair) {
            $pairMap[strtolower($pair->base_currency)] = $pair->coingecko_id;
        }
    @endphp




    <div class="trading-container">
        <!-- Chart Section -->
        <div class="chart-section">
            <div class="pair-selector">
                <div class="current-pair">
                    <div class="pair-icon" id="pair-icon">{{ strtoupper($currencies[0]->base_currency[0] ?? 'B') }}</div>
                    <div class="pair-info">
                        <select name="currency" id="pair-select" class="form-input">
                            @if($currencies->isEmpty())
                                <option value="">No currency pairs available</option>
                            @else
                                @foreach($currencies as $pair)
                                    <option value="{{ strtolower($pair->base_currency) }}"
                                        data-quote="{{ strtolower($pair->quote_currency) }}"
                                        data-coingecko-id="{{ $pair->coingecko_id }}">
                                        {{ strtoupper($pair->base_currency) }}/{{ strtoupper($pair->quote_currency) }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                        <div class="pair-price" id="live-price">$0.00</div>
                        <div class="pair-change" id="pair-change">0.00% ($0.00)</div>
                    </div>
                </div>
            </div>

            <!-- Open Trades -->
            <div class="open-trades">
                <h4 class="text-{{ $text }} mb-4">Open Trades</h4>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Type</th>
                            <th>Currency Pair</th>
                            <th>Entry Price</th>
                            <th>Current Price</th>
                            <th>Volume</th>
                            <th>Amount (USD)</th>
                            <th>P/L (USD)</th>
                            @if(Auth::user()->is_admin)
                                <th>User</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($openTrades as $trade)
                            <tr>
                                <td>{{ ucfirst($trade->type) }}</td>
                                <td>{{ strtoupper($trade->pair_base) }}/{{ strtoupper($trade->quote_currency) }}</td>
                                <td>${{ number_format($trade->entry_price, 2) }}</td>
                                <td>${{ number_format($trade->current_price, 2) }}</td>
                                <td>{{ number_format($trade->volume, 6) }}</td>
                                <td>${{ number_format($trade->amount, 2) }}</td>
                                <td class="{{ $trade->unrealized_pl >= 0 ? 'text-success' : 'text-danger' }}">
                                    ${{ number_format($trade->unrealized_pl, 2) }}
                                </td>
                                @if(Auth::user()->is_admin)
                                    <td>{{ $trade->user->name }}</td>
                                @endif
                                <td>
                                    <form action="{{ route('trade.close', $trade->id) }}" method="POST" class="close-trade-form">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to close this trade?')">
                                            Close Trade
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ Auth::user()->is_admin ? 8 : 7 }}">No open trades</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Trading Panel -->
        <div class="trading-panel">
            <!-- Balance Card -->
            <div class="balance-card">
                <div class="balance-title">Available Balance</div>
                <div class="balance-amount" id="balance-amount">${{ number_format(Auth::user()->balance, 2) }}</div>
                <div class="balance-usd">≈ {{ number_format(Auth::user()->balance, 2) }} USDT</div>
            </div>

            <!-- Order Form -->
            <div class="order-form">
                @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif
                @if (session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @endif

                <div class="order-tabs">
                    <button class="tab-button buy active" data-tab="buy">Buy</button>
                    <button class="tab-button sell" data-tab="sell">Sell</button>
                </div>

                <form id="trading-form" action="{{ route('trade.execute') }}" method="POST">
                    @csrf
                    <input type="hidden" name="type" id="order-type" value="buy">
                    <input type="hidden" name="order_type" id="order-type-value" value="market">
                    <input type="hidden" name="pair" id="pair-input" value="{{ strtolower($currencies[0]->base_currency ?? '') }}">

                    <div class="order-type-selector">
                        <button type="button" class="order-type-btn active" data-order-type="market">Market</button>
                        <button type="button" class="order-type-btn" data-order-type="limit">Limit</button>
                        <button type="button" class="order-type-btn" data-order-type="stop">Stop</button>
                    </div>

                    <div class="form-group" id="price-group" style="display: none;">
                        <label class="form-label">Price</label>
                        <div class="price-input-group">
                            <input type="number" name="price" class="form-input" id="price-input" step="0.01" placeholder="0.00">
                            <span class="currency-suffix">USDT</span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Amount</label>
                        <div class="price-input-group">
                            <input type="number" name="amount" class="form-input" id="amount-input" step="0.00000001" placeholder="0.00000000" oninput="calculateTotal()">
                            <span class="currency-suffix" id="amount-suffix">{{ strtoupper($currencies[0]->base_currency ?? 'BTC') }}</span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Total</label>
                        <div class="price-input-group">
                            <input type="number" class="form-input" id="total-input" step="0.01" placeholder="0.00" oninput="calculateAmount()">
                            <span class="currency-suffix">USDT</span>
                        </div>
                    </div>

                    <div class="advanced-options">
                        <div class="checkbox-group">
                            <input type="checkbox" class="checkbox" id="stop-loss" onchange="toggleAdvancedOption('stop-loss')">
                            <label class="checkbox-label" for="stop-loss">Stop Loss</label>
                        </div>

                        <div class="form-group" id="stop-loss-group" style="display: none;">
                            <label class="form-label">Stop Loss Price</label>
                            <div class="price-input-group">
                                <input type="number" name="stop_loss" class="form-input" id="stop-loss-input" step="0.01" placeholder="0.00">
                                <span class="currency-suffix">USDT</span>
                            </div>
                        </div>

                        <div class="checkbox-group">
                            <input type="checkbox" class="checkbox" id="take-profit" onchange="toggleAdvancedOption('take-profit')">
                            <label class="checkbox-label" for="take-profit">Take Profit</label>
                        </div>

                        <div class="form-group" id="take-profit-group" style="display: none;">
                            <label class="form-label">Take Profit Price</label>
                            <div class="price-input-group">
                                <input type="number" name="take_profit" class="form-input" id="take-profit-input" step="0.01" placeholder="0.00">
                                <span class="currency-suffix">USDT</span>
                            </div>
                        </div>
                    </div>

                    <div class="order-summary">
                        <div class="summary-row">
                            <span class="summary-label">Order Type:</span>
                            <span class="summary-value" id="summary-type">Market Buy</span>
                        </div>
                        <div class="summary-row">
                            <span class="summary-label">Fee (0.1%):</span>
                            <span class="summary-value" id="summary-fee">$0.00</span>
                        </div>
                        <div class="summary-row">
                            <span class="summary-label">Total Cost:</span>
                            <span class="summary-value" id="summary-total">$0.00</span>
                        </div>
                    </div>

                    <button type="submit" class="submit-button buy-button" id="submit-btn" disabled>
                        Buy <span id="submit-currency">{{ strtoupper($currencies[0]->base_currency ?? 'BTC') }}</span>
                    </button>

                    <div class="insufficient-balance" id="insufficient-balance" style="display: none;">
                        Insufficient balance. You need $0.00 more to complete this order.
                    </div>
                </form>
            </div>
        </div>
    </div>
<script>
    // Enhanced JavaScript code for live trading prices using CoinGecko API

let currentTab = 'buy';
let currentOrderType = 'market';
let currentPrice = 0;
let userBalance = {{ Auth::user()->account_bal ?? 0 }};
let priceUpdateInterval = null;
let currentCoingeckoId = null;
const pairMap = @json($pairMap);

// CoinGecko API configuration
const COINGECKO_API_BASE = 'https://api.coingecko.com/api/v3';
const PRICE_UPDATE_INTERVAL = 60000; // 300ms as requested

async function fetchCoinGeckoPrice(coingeckoId) {
    if (!coingeckoId) {
        console.error('No CoinGecko ID provided');
        return null;
    }

    try {
        const response = await fetch(`${COINGECKO_API_BASE}/simple/price?ids=${coingeckoId}&vs_currencies=usd&include_24hr_change=true`, {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
            }
        });

        if (!response.ok) {
            throw new Error(`CoinGecko API error: ${response.status}`);
        }

        const data = await response.json();
        
        if (data[coingeckoId]) {
            return {
                price: data[coingeckoId].usd,
                change24h: data[coingeckoId].usd_24h_change || 0
            };
        } else {
            throw new Error('Price data not found for this coin');
        }
    } catch (error) {
        console.error('Error fetching CoinGecko price:', error);
        return null;
    }
}

async function updateLivePrice() {
    if (!currentCoingeckoId) {
        console.log('No CoinGecko ID set for price updates');
        return;
    }

    const priceField = document.getElementById('live-price');
    const pairChange = document.getElementById('pair-change');
    
    if (!priceField || !pairChange) {
        console.error('Price display elements not found');
        return;
    }

    try {
        const priceData = await fetchCoinGeckoPrice(currentCoingeckoId);
        
        if (priceData && priceData.price) {
            const newPrice = parseFloat(priceData.price);
            const change24h = parseFloat(priceData.change24h);
            
            // Update current price
            currentPrice = newPrice;
            
            // Update price display with animation
            const oldPrice = parseFloat(priceField.textContent.replace('$', '').replace(',', '')) || 0;
            priceField.textContent = `$${newPrice.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 6 })}`;
            
            // Add price change animation
            if (oldPrice > 0) {
                if (newPrice > oldPrice) {
                    priceField.classList.add('price-up');
                    setTimeout(() => priceField.classList.remove('price-up'), 1000);
                } else if (newPrice < oldPrice) {
                    priceField.classList.add('price-down');
                    setTimeout(() => priceField.classList.remove('price-down'), 1000);
                }
            }
            
            // Update 24h change
            const changePercent = change24h.toFixed(2);
            const changeValue = (newPrice * change24h / 100).toFixed(2);
            const changeText = `${changePercent >= 0 ? '+' : ''}${changePercent}% ($${changeValue >= 0 ? '+' : ''}${changeValue})`;
            
            pairChange.textContent = changeText;
            pairChange.className = change24h >= 0 ? 'pair-change positive' : 'pair-change negative';
            
            // Enable submit button if it was disabled due to price issues
            const submitBtn = document.getElementById('submit-btn');
            if (submitBtn && submitBtn.disabled && currentPrice > 0) {
                updateSummary(); // This will re-enable button if other conditions are met
            }
            
            // Update summary calculations
            updateSummary();
            
        } else {
            throw new Error('Invalid price data received');
        }
    } catch (error) {
        console.error('Error updating live price:', error);
        
        // Show error state but don't disable trading completely
        const priceField = document.getElementById('live-price');
        const pairChange = document.getElementById('pair-change');
        
        if (priceField && !priceField.textContent.includes('Error')) {
            priceField.textContent = 'Price Error';
        }
        if (pairChange && !pairChange.textContent.includes('Error')) {
            pairChange.textContent = 'Error loading change';
            pairChange.className = 'pair-change';
        }
    }
}

function startLivePriceUpdates(coingeckoId) {
    // Clear existing interval
    if (priceUpdateInterval) {
        clearInterval(priceUpdateInterval);
        priceUpdateInterval = null;
    }
    
    if (!coingeckoId) {
        console.error('Cannot start price updates: No CoinGecko ID provided');
        return;
    }
    
    currentCoingeckoId = coingeckoId;
    console.log(`Starting live price updates for: ${coingeckoId}`);
    
    // Initial price fetch
    updateLivePrice();
    
    // Set up interval for live updates every 300ms
    priceUpdateInterval = setInterval(updateLivePrice, PRICE_UPDATE_INTERVAL);
}

function stopLivePriceUpdates() {
    if (priceUpdateInterval) {
        clearInterval(priceUpdateInterval);
        priceUpdateInterval = null;
        currentCoingeckoId = null;
        console.log('Stopped live price updates');
    }
}


document.querySelectorAll('.close-trade-form').forEach(form => {
    form.addEventListener('submit', async function(e) {
        e.preventDefault();
        if (!confirm('Are you sure you want to close this trade?')) return;

        const form = this;
        const url = form.action;
        const method = form.querySelector('input[name="_method"]').value || form.method;
        const token = form.querySelector('input[name="_token"]').value;

        try {
            const response = await fetch(url, {
                method: method,
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': token
                }
            });

            const data = await response.json();
            if (response.ok) {
                alert(data.message);
                window.location.reload(); // Refresh to update open trades
            } else {
                alert(data.error || 'Failed to close trade');
            }
        } catch (error) {
            console.error('Error closing trade:', error);
            alert('An error occurred while closing the trade');
        }
    });
});

async function fetchPairData(symbol) {
    console.log('Setting up pair data for symbol:', symbol);
    
    const priceField = document.getElementById('live-price');
    const pairChange = document.getElementById('pair-change');
    const submitBtn = document.getElementById('submit-btn');
    
    if (!priceField || !pairChange || !submitBtn) {
        console.error('Required DOM elements not found');
        return;
    }
    
    // Stop previous price updates
    stopLivePriceUpdates();
    
    // Reset display
    priceField.textContent = 'Loading...';
    pairChange.textContent = 'Loading...';
    currentPrice = 0;

    if (!symbol || !pairMap[symbol]) {
        console.error('Invalid symbol or not found in pairMap:', symbol, pairMap);
        priceField.textContent = 'Symbol not found';
        pairChange.textContent = '0.00% ($0.00)';
        submitBtn.disabled = true;
        updateSummary();
        return;
    }

    // Get CoinGecko ID from pair map
    const coingeckoId = pairMap[symbol];
    console.log(`Found CoinGecko ID for ${symbol}:`, coingeckoId);
    
    // Start live price updates with CoinGecko
    startLivePriceUpdates(coingeckoId);
}

function switchTab(tab) {
    if (tab !== 'buy' && tab !== 'sell') {
        console.error('Invalid tab:', tab);
        return;
    }

    currentTab = tab;
    document.querySelectorAll('.tab-button').forEach(btn => btn.classList.remove('active'));
    const activeButton = document.querySelector(`.tab-button[data-tab="${tab}"]`);
    if (activeButton) {
        activeButton.classList.add('active');
    }
    
    const submitBtn = document.getElementById('submit-btn');
    const orderTypeInput = document.getElementById('order-type');
    const pairSelect = document.getElementById('pair-select');
    const currentCurrency = pairSelect && pairSelect.value ? pairSelect.value.toUpperCase() : 'BTC';
    
    if (orderTypeInput) orderTypeInput.value = tab;
    if (submitBtn) {
        submitBtn.className = `submit-button ${tab === 'buy' ? 'buy-button' : 'sell-button'}`;
        submitBtn.innerHTML = `${tab.charAt(0).toUpperCase() + tab.slice(1)} <span id="submit-currency">${currentCurrency}</span>`;
    }
    updateSummary();
}

function selectOrderType(type) {
    if (!['market', 'limit', 'stop'].includes(type)) {
        console.error('Invalid order type:', type);
        return;
    }

    currentOrderType = type;
    document.querySelectorAll('.order-type-btn').forEach(btn => btn.classList.remove('active'));
    const activeButton = document.querySelector(`.order-type-btn[data-order-type="${type}"]`);
    if (activeButton) {
        activeButton.classList.add('active');
    }
    
    const priceGroup = document.getElementById('price-group');
    const orderTypeInput = document.getElementById('order-type-value');
    const priceInput = document.getElementById('price-input');
    
    if (orderTypeInput) orderTypeInput.value = type;
    
    if (priceGroup) {
        if (type === 'limit' || type === 'stop') {
            priceGroup.style.display = 'block';
            if (priceInput && currentPrice) {
                priceInput.value = currentPrice.toFixed(2);
            }
        } else {
            priceGroup.style.display = 'none';
        }
    }
    updateSummary();
}

function toggleAdvancedOption(option) {
    const checkbox = document.getElementById(option);
    const group = document.getElementById(`${option}-group`);
    if (checkbox && group) {
        group.style.display = checkbox.checked ? 'block' : 'none';
    }
}

function calculateTotal() {
    const amountInput = document.getElementById('amount-input');
    const totalInput = document.getElementById('total-input');
    const priceInput = document.getElementById('price-input');
    
    if (!amountInput || !totalInput) return;
    
    const amount = parseFloat(amountInput.value) || 0;
    const price = currentOrderType === 'market' 
        ? currentPrice 
        : (priceInput ? parseFloat(priceInput.value) : currentPrice) || currentPrice;
    const total = amount * price;
    
    totalInput.value = total ? total.toFixed(2) : '';
    updateSummary();
}

function calculateAmount() {
    const amountInput = document.getElementById('amount-input');
    const totalInput = document.getElementById('total-input');
    const priceInput = document.getElementById('price-input');
    
    if (!amountInput || !totalInput) return;
    
    const total = parseFloat(totalInput.value) || 0;
    const price = currentOrderType === 'market' 
        ? currentPrice 
        : (priceInput ? parseFloat(priceInput.value) : currentPrice) || currentPrice;
    const amount = price ? total / price : 0;
    
    amountInput.value = amount ? amount.toFixed(8) : '';
    updateSummary();
}

function updateSummary() {
    const amountInput = document.getElementById('amount-input');
    const totalInput = document.getElementById('total-input');
    
    if (!amountInput || !totalInput) return;
    
    const amount = parseFloat(amountInput.value) || 0;
    const total = parseFloat(totalInput.value) || 0;
    const fee = total * 0.001;
    const totalCost = total + fee;
    
    const summaryType = document.getElementById('summary-type');
    const summaryFee = document.getElementById('summary-fee');
    const summaryTotal = document.getElementById('summary-total');
    
    if (summaryType) summaryType.textContent = `${currentOrderType.charAt(0).toUpperCase() + currentOrderType.slice(1)} ${currentTab.charAt(0).toUpperCase() + currentTab.slice(1)}`;
    if (summaryFee) summaryFee.textContent = `$${fee.toFixed(2)}`;
    if (summaryTotal) summaryTotal.textContent = `$${totalCost.toFixed(2)}`;
    
    const insufficientDiv = document.getElementById('insufficient-balance');
    const submitBtn = document.getElementById('submit-btn');
    
    if (!insufficientDiv || !submitBtn) return;
    
    if (currentTab === 'buy' && totalCost > userBalance) {
        const needed = totalCost - userBalance;
        insufficientDiv.textContent = `Insufficient balance. You need $${needed.toFixed(2)} more to complete this order.`;
        insufficientDiv.style.display = 'block';
        submitBtn.disabled = true;
        submitBtn.style.opacity = '0.5';
    } else if (!currentPrice || currentPrice <= 0) {
        insufficientDiv.textContent = 'Price data unavailable. Please try again later.';
        insufficientDiv.style.display = 'block';
        submitBtn.disabled = true;
        submitBtn.style.opacity = '0.5';
    } else {
        insufficientDiv.style.display = 'none';
        submitBtn.disabled = false;
        submitBtn.style.opacity = '1';
    }
}

function setupPairSelectListener() {
    const pairSelect = document.getElementById('pair-select');
    if (!pairSelect) {
        console.error('pair-select element not found');
        return;
    }
    
    pairSelect.addEventListener('change', function() {
        console.log('Pair selection changed to:', this.value);
        
        const symbol = this.value;
        const selectedOption = this.options[this.selectedIndex];
        const quoteCurrency = selectedOption?.dataset?.quote?.toUpperCase() || 'USDT';
        
        // Update UI elements
        const pairIcon = document.getElementById('pair-icon');
        const amountSuffix = document.getElementById('amount-suffix');
        const submitCurrency = document.getElementById('submit-currency');
        const pairInput = document.getElementById('pair-input');
        
        if (pairIcon) pairIcon.textContent = symbol ? symbol.charAt(0).toUpperCase() : 'B';
        if (amountSuffix) amountSuffix.textContent = symbol ? symbol.toUpperCase() : 'BTC';
        if (submitCurrency) submitCurrency.textContent = symbol ? symbol.toUpperCase() : 'BTC';
        if (pairInput) pairInput.value = symbol || '';
        
        // Update all currency suffixes except amount suffix
        document.querySelectorAll('.currency-suffix').forEach(suffix => {
            if (suffix.id !== 'amount-suffix') {
                suffix.textContent = quoteCurrency;
            }
        });
        
        // Clear previous data and start new price fetching
        currentPrice = 0;
        const priceField = document.getElementById('live-price');
        const pairChange = document.getElementById('pair-change');
        if (priceField) priceField.textContent = '$0.00';
        if (pairChange) pairChange.textContent = '0.00% ($0.00)';
        
        // Fetch new data with live updates
        if (symbol && symbol.trim() !== '') {
            fetchPairData(symbol);
        } else {
            stopLivePriceUpdates();
            if (priceField) priceField.textContent = 'No pair selected';
            const submitBtn = document.getElementById('submit-btn');
            if (submitBtn) submitBtn.disabled = true;
            updateSummary();
        }
    });
}

function setupFormSubmission() {
    const tradingForm = document.getElementById('trading-form');
    if (!tradingForm) return;
    
    tradingForm.addEventListener('submit', function(e) {
        if (!currentPrice || currentPrice <= 0) {
            e.preventDefault();
            const insufficientDiv = document.getElementById('insufficient-balance');
            if (insufficientDiv) {
                insufficientDiv.textContent = 'Cannot submit order: Price data unavailable.';
                insufficientDiv.style.display = 'block';
            }
            return false;
        }
        
        const amountInput = document.getElementById('amount-input');
        const amount = amountInput ? parseFloat(amountInput.value) : 0;
        
        if (!amount || amount <= 0) {
            e.preventDefault();
            alert('Please enter a valid amount');
            return false;
        }
    });
}

function setupEventListeners() {
    // Tab buttons
    document.querySelectorAll('.tab-button').forEach(button => {
        button.addEventListener('click', () => {
            const tab = button.dataset.tab;
            switchTab(tab);
        });
    });

    // Order type buttons
    document.querySelectorAll('.order-type-btn').forEach(button => {
        button.addEventListener('click', () => {
            const type = button.dataset.orderType;
            selectOrderType(type);
        });
    });
    
    // Input field listeners
    const amountInput = document.getElementById('amount-input');
    const totalInput = document.getElementById('total-input');
    const priceInput = document.getElementById('price-input');
    
    if (amountInput) {
        amountInput.addEventListener('input', calculateTotal);
    }
    
    if (totalInput) {
        totalInput.addEventListener('input', calculateAmount);
    }
    
    if (priceInput) {
        priceInput.addEventListener('input', updateSummary);
    }
}

// Cleanup function for when page is unloaded
window.addEventListener('beforeunload', function() {
    stopLivePriceUpdates();
});

// Handle visibility change to pause/resume updates when tab is not active
document.addEventListener('visibilitychange', function() {
    if (document.hidden) {
        // Tab is not visible, you might want to reduce update frequency
        if (priceUpdateInterval) {
            clearInterval(priceUpdateInterval);
            // Restart with longer interval when tab is hidden
            priceUpdateInterval = setInterval(updateLivePrice, 5000); // 5 seconds when hidden
        }
    } else {
        // Tab is visible again, resume normal frequency
        if (currentCoingeckoId && priceUpdateInterval) {
            clearInterval(priceUpdateInterval);
            priceUpdateInterval = setInterval(updateLivePrice, PRICE_UPDATE_INTERVAL);
        }
    }
});

// Main initialization function
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM loaded, initializing live trading interface');
    console.log('Initial pairMap:', pairMap);
    
    setupEventListeners();
    setupPairSelectListener();
    setupFormSubmission();
    
    const pairSelect = document.getElementById('pair-select');
    if (!pairSelect) {
        console.error('pair-select element not found during initialization');
        return;
    }
    
    const symbol = pairSelect.value;
    const selectedOption = pairSelect.options[pairSelect.selectedIndex];
    const quoteCurrency = selectedOption?.dataset?.quote?.toUpperCase() || 'USDT';
    
    console.log('Initial symbol:', symbol, 'Quote currency:', quoteCurrency);
    
    if (symbol && symbol.trim() !== '') {
        // Update UI elements
        const pairIcon = document.getElementById('pair-icon');
        const amountSuffix = document.getElementById('amount-suffix');
        const submitCurrency = document.getElementById('submit-currency');
        const pairInput = document.getElementById('pair-input');
        
        if (pairIcon) pairIcon.textContent = symbol.charAt(0).toUpperCase();
        if (amountSuffix) amountSuffix.textContent = symbol.toUpperCase();
        if (submitCurrency) submitCurrency.textContent = symbol.toUpperCase();
        if (pairInput) pairInput.value = symbol;
        
        // Update currency suffixes
        document.querySelectorAll('.currency-suffix').forEach(suffix => {
            if (suffix.id !== 'amount-suffix') {
                suffix.textContent = quoteCurrency;
            }
        });
        
        // Start live price updates

        fetchPairData(symbol);
    } else {
        const priceField = document.getElementById('live-price');
        const submitBtn = document.getElementById('submit-btn');
        if (priceField) priceField.textContent = 'No pair selected';
        if (submitBtn) submitBtn.disabled = true;
    }
    
    updateSummary();
});
    </script>
@endsection
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

    <div class="main-panel withdrawal-page" data-theme="{{ $bg }}">
        <div class="content">
            <div class="zx-app">

                <x-danger-alert/>
                <x-success-alert/>

                <!-- Header -->
                <div class="wd-head">
                    <div>
                        <h1>Withdraw funds</h1>
                        <p>Request a withdrawal to your wallet</p>
                    </div>
                    <div class="wd-bal">
                        <span>Available</span>
                        <b>{{ $settings->currency }}{{ number_format(Auth::user()->account_bal, 2) }}</b>
                    </div>
                </div>

                @if ($settings->enable_with == "false")
                    <div class="wd-disabled">
                        <span class="wd-disabled-ico">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M10 9v6M14 9v6"/></svg>
                        </span>
                        <h3>Withdrawals temporarily disabled</h3>
                        <p>Withdrawal services are currently unavailable. Please check back later or contact support.</p>
                        <a href="{{ route('support') }}" class="wd-help-btn">Contact support</a>
                    </div>
                @else
                    <form action="{{ route('withdrawamount') }}" method="POST" id="withdrawalForm">
                        @csrf
                        <input type="hidden" name="amount" id="netAmount" value="0">
                        <input type="hidden" name="gross_amount" id="grossAmountSubmitted" value="0">
                        <input type="hidden" name="coin" id="zarexCoinInput" value="USDT">

                        <!-- Amount -->
                        <div class="wd-card">
                            <label class="wd-label">Withdrawal amount</label>
                            <div class="wd-amount">
                                <span class="wd-cur">{{ $settings->currency }}</span>
                                <input type="number" id="grossAmount" placeholder="0.00" min="1" step="any" required>
                            </div>
                            <div class="wd-quick">
                                <button type="button" class="quick-amount-btn" data-amount="50">{{ $settings->currency }}50</button>
                                <button type="button" class="quick-amount-btn" data-amount="100">{{ $settings->currency }}100</button>
                                <button type="button" class="quick-amount-btn" data-amount="250">{{ $settings->currency }}250</button>
                                <button type="button" class="quick-amount-btn" data-amount="500">{{ $settings->currency }}500</button>
                                <button type="button" class="quick-amount-btn" data-amount="all">All</button>
                            </div>

                            <div class="wd-fees">
                                <div class="fee-row">
                                    <span class="fee-label">Requested amount</span>
                                    <span class="fee-value" id="requestedAmount">{{ $settings->currency }}0.00</span>
                                </div>
                                <div class="fee-row">
                                    <span class="fee-label">Service fee ({{ $settings->withdrawal_percentage }}%)</span>
                                    <span class="fee-value negative" id="feeAmount">-{{ $settings->currency }}0.00</span>
                                </div>
                                <div class="fee-row total">
                                    <span class="fee-label">You'll receive</span>
                                    <span class="fee-value" id="receiveAmount">{{ $settings->currency }}0.00</span>
                                </div>
                            </div>
                        </div>

                        <!-- Destination -->
                        <div class="wd-card">
                            <label class="wd-label">Withdraw to</label>
                            <div class="dest-options">
                                <button type="button" class="dest-option-btn selected" data-dest="external">External wallet</button>
                                <button type="button" class="dest-option-btn" data-dest="zarex">Zarex balance</button>
                            </div>

                            <div id="externalFields" style="margin-top:20px">
                                <label class="wd-label">Wallet address</label>
                                <input type="text" name="wallet_address" id="walletAddressInput" class="wd-input" placeholder="Enter your wallet address (e.g. 0x...)" required>

                                <label class="wd-label" style="margin-top:20px">Network</label>
                                <div class="network-options">
                                    <label class="network-option selected">
                                        <input type="radio" name="network" value="BSC" checked>
                                        <div class="network-content">
                                            <span class="network-name">BNB Smart Chain</span>
                                            <span class="network-tag">BEP20</span>
                                        </div>
                                        <span class="network-check"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12l5 5 9-11"/></svg></span>
                                    </label>
                                    <label class="network-option">
                                        <input type="radio" name="network" value="ERC20">
                                        <div class="network-content">
                                            <span class="network-name">Ethereum</span>
                                            <span class="network-tag">ERC20</span>
                                        </div>
                                        <span class="network-check"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12l5 5 9-11"/></svg></span>
                                    </label>
                                </div>
                            </div>

                            <div id="zarexFields" style="display:none;margin-top:20px">
                                <label class="wd-label">Coin</label>
                                <div class="dest-options">
                                    <button type="button" class="coin-option-btn selected" data-coin="USDT">USDT</button>
                                    <button type="button" class="coin-option-btn" data-coin="USDC">USDC</button>
                                </div>

                                <label class="wd-label" style="margin-top:20px">Network</label>
                                <div class="network-options">
                                    <label class="network-option selected" data-coin="USDT">
                                        <input type="radio" name="network" value="erc20" checked>
                                        <div class="network-content"><span class="network-name">Ethereum</span><span class="network-tag">ERC20</span></div>
                                        <span class="network-check"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12l5 5 9-11"/></svg></span>
                                    </label>
                                    <label class="network-option" data-coin="USDT">
                                        <input type="radio" name="network" value="trc20">
                                        <div class="network-content"><span class="network-name">Tron</span><span class="network-tag">TRC20</span></div>
                                        <span class="network-check"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12l5 5 9-11"/></svg></span>
                                    </label>
                                    <label class="network-option" data-coin="USDT">
                                        <input type="radio" name="network" value="bep20">
                                        <div class="network-content"><span class="network-name">BNB Smart Chain</span><span class="network-tag">BEP20</span></div>
                                        <span class="network-check"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12l5 5 9-11"/></svg></span>
                                    </label>
                                    <label class="network-option" data-coin="USDC" style="display:none">
                                        <input type="radio" name="network" value="erc20">
                                        <div class="network-content"><span class="network-name">Ethereum</span><span class="network-tag">ERC20</span></div>
                                        <span class="network-check"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12l5 5 9-11"/></svg></span>
                                    </label>
                                </div>
                                <p class="wd-opt" style="margin-top:10px">Sent as a real on-chain transfer straight to your Zarex deposit address.</p>
                            </div>

                            <label class="wd-label" style="margin-top:20px">Notes <span class="wd-opt">(optional)</span></label>
                            <textarea name="notes" class="wd-textarea" rows="3" placeholder="Add any notes for this withdrawal request..."></textarea>

                            <button type="submit" class="wd-submit">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 2 11 13M22 2l-7 20-4-9-9-4 20-7z"/></svg>
                                <span>Submit withdrawal request</span>
                            </button>
                        </div>
                    </form>

                    <!-- Notice -->
                    <div class="wd-card wd-notice">
                        <h4>
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10.29 3.86 1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><path d="M12 9v4M12 17h.01"/></svg>
                            Important
                        </h4>
                        <ul class="wd-note-list">
                            <li>{{ $settings->network_note }}</li>
                            <li>Processing time: <strong>10&ndash;15 minutes</strong></li>
                            <li>Service charge: <strong>{{ $settings->withdrawal_percentage }}%</strong></li>
                            <li>You receive <strong>{{ 100 - $settings->withdrawal_percentage }}%</strong> of the requested amount</li>
                        </ul>
                    </div>

                    @if (!empty($settings->telegram_channel))
                        <a href="{{ str_starts_with($settings->telegram_channel, '@') ? 'https://t.me/' . ltrim($settings->telegram_channel, '@') : $settings->telegram_channel }}" target="_blank" class="wd-tg">
                            <svg viewBox="0 0 24 24" fill="currentColor"><path d="M9.8 15.6 9.6 20c.4 0 .6-.2.8-.4l2-1.9 4.1 3c.8.4 1.3.2 1.5-.7L21 4.9c.3-1.2-.4-1.6-1.2-1.3L3.6 9.9c-1.1.4-1.1 1-.2 1.3l4.2 1.3L17.3 6c.5-.3.9-.1.5.2z"/></svg>
                            Join our Telegram for real-time support
                        </a>
                    @endif
                @endif

            </div>
        </div>
    </div>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Space+Grotesk:wght@500;600;700&display=swap" rel="stylesheet">
    @verbatim
    <style>
    /* ============ Zaraex withdrawals ============ */
    .withdrawal-page{
        --card:#0e1018; --elev:#16181f; --border:#23262f;
        --text:#f2f5fb; --muted:#a1a9b8; --faint:#6b7280;
        --blue:#2563eb; --blue-soft:#3b82f6; --success:#22c55e; --amber:#f59e0b; --danger:#ef4444;
        --tint:rgba(37,99,235,.14);
        font-family:'Inter',system-ui,-apple-system,sans-serif; color:var(--text);
    }
    body[data-theme="light"] .withdrawal-page{
        --card:#ffffff; --elev:#f4f6fa; --border:#e8ecf2;
        --text:#0f172a; --muted:#5b6674; --faint:#98a2b3;
    }
    .withdrawal-page .content{padding:0}
    .withdrawal-page h1,.withdrawal-page h3,.withdrawal-page h4{font-family:'Space Grotesk','Inter',sans-serif;letter-spacing:-.02em;margin:0}
    .withdrawal-page .zx-app{max-width:520px;margin:0 auto;padding:22px 16px 96px;display:flex;flex-direction:column;gap:18px}

    /* Header */
    .wd-head{display:flex;align-items:flex-start;justify-content:space-between;gap:12px}
    .wd-head h1{font-size:1.5rem;font-weight:700}
    .wd-head p{margin:4px 0 0;color:var(--muted);font-size:.9rem}
    .wd-bal{text-align:right;flex:none}
    .wd-bal span{display:block;color:var(--faint);font-size:.75rem}
    .wd-bal b{font-family:'Space Grotesk',sans-serif;font-size:1.05rem}

    /* Card + labels */
    .wd-card{background:var(--card);border:1px solid var(--border);border-radius:20px;padding:22px;margin-bottom:0}
    #withdrawalForm .wd-card{margin-bottom:16px}
    .wd-label{display:block;font-size:.8rem;font-weight:600;text-transform:uppercase;letter-spacing:.05em;color:var(--faint);margin-bottom:10px}
    .wd-opt{text-transform:none;letter-spacing:0;color:var(--faint);font-weight:500}

    /* Amount */
    .wd-amount{display:flex;align-items:center;gap:6px;border:1px solid var(--border);border-radius:14px;background:var(--elev);padding:4px 16px;transition:.2s}
    .wd-amount:focus-within{border-color:var(--blue);box-shadow:0 0 0 3px rgba(37,99,235,.15)}
    .wd-cur{font-size:1.4rem;font-weight:700;color:var(--muted)}
    .wd-amount input{flex:1;min-width:0;border:none;background:transparent;outline:none;color:var(--text);
        font-family:'Space Grotesk',sans-serif;font-size:1.9rem;font-weight:700;padding:12px 4px}
    .wd-amount input::-webkit-outer-spin-button,.wd-amount input::-webkit-inner-spin-button{-webkit-appearance:none;margin:0}
    .wd-quick{display:flex;gap:8px;flex-wrap:wrap;margin-top:12px}
    .quick-amount-btn{flex:1;min-width:52px;padding:9px 6px;border-radius:11px;border:1px solid var(--border);
        background:var(--elev);color:var(--text);font-weight:600;font-size:.85rem;cursor:pointer;transition:.2s}
    .quick-amount-btn:hover{border-color:var(--blue-soft)}
    .quick-amount-btn.active{background:var(--blue);color:#fff;border-color:var(--blue)}

    /* Fees */
    .wd-fees{margin-top:18px;padding:16px;border-radius:14px;background:var(--elev);border:1px solid var(--border)}
    .fee-row{display:flex;align-items:center;justify-content:space-between;padding:6px 0;font-size:.9rem}
    .fee-label{color:var(--muted)}
    .fee-value{font-family:'Space Grotesk',sans-serif;font-weight:600}
    .fee-value.negative{color:var(--danger)}
    .fee-row.total{margin-top:6px;padding-top:12px;border-top:1px solid var(--border)}
    .fee-row.total .fee-label{color:var(--text);font-weight:600}
    .fee-row.total .fee-value{color:var(--success);font-size:1.1rem}

    /* Inputs */
    .wd-input,.wd-textarea{width:100%;border:1px solid var(--border);border-radius:14px;background:var(--elev);
        color:var(--text);font-size:.95rem;padding:14px 16px;outline:none;transition:.2s;font-family:'Inter',sans-serif}
    .wd-input:focus,.wd-textarea:focus{border-color:var(--blue);box-shadow:0 0 0 3px rgba(37,99,235,.15)}
    .wd-input::placeholder,.wd-textarea::placeholder{color:var(--faint)}
    .wd-textarea{resize:vertical;min-height:80px}

    /* Destination / coin toggle */
    .dest-options{display:flex;gap:10px}
    .dest-option-btn,.coin-option-btn{flex:1;padding:12px;border-radius:12px;border:1px solid var(--border);background:var(--elev);
        color:var(--text);font-weight:600;font-size:.9rem;cursor:pointer;transition:.2s}
    .dest-option-btn:hover,.coin-option-btn:hover{border-color:var(--blue-soft)}
    .dest-option-btn.selected,.coin-option-btn.selected{border-color:var(--blue);background:rgba(37,99,235,.08)}

    /* Network */
    .network-options{display:flex;flex-direction:column;gap:10px}
    .network-option{position:relative;display:flex;align-items:center;gap:12px;padding:14px 16px;border-radius:14px;cursor:pointer;
        border:1px solid var(--border);background:var(--elev);transition:.2s}
    .network-option:hover{border-color:var(--blue-soft)}
    .network-option.selected{border-color:var(--blue);background:rgba(37,99,235,.08)}
    .network-option input{position:absolute;opacity:0;pointer-events:none}
    .network-content{display:flex;flex-direction:column;flex:1;min-width:0}
    .network-name{font-weight:600;font-size:.92rem}
    .network-tag{color:var(--faint);font-size:.76rem;margin-top:2px}
    .network-check{width:22px;height:22px;border-radius:50%;flex:none;display:grid;place-items:center;
        border:1.5px solid var(--border);color:#fff;transition:.2s}
    .network-check svg{width:12px;height:12px;opacity:0}
    .network-option.selected .network-check{background:var(--blue);border-color:var(--blue)}
    .network-option.selected .network-check svg{opacity:1}

    /* Submit */
    .wd-submit{width:100%;margin-top:20px;display:flex;align-items:center;justify-content:center;gap:8px;
        padding:15px;border-radius:13px;border:none;cursor:pointer;background:var(--blue);color:#fff;font-weight:600;font-size:1rem;transition:.2s}
    .wd-submit:hover{background:var(--blue-soft)}
    .wd-submit svg{width:17px;height:17px}

    /* Disabled */
    .wd-disabled{text-align:center;padding:36px 22px;background:var(--card);border:1px solid var(--border);border-radius:20px}
    .wd-disabled-ico{width:60px;height:60px;border-radius:16px;display:inline-grid;place-items:center;margin-bottom:14px;
        background:rgba(245,158,11,.14);color:var(--amber)}
    .wd-disabled-ico svg{width:30px;height:30px}
    .wd-disabled h3{font-size:1.2rem;margin-bottom:8px}
    .wd-disabled p{color:var(--muted);font-size:.92rem;max-width:360px;margin:0 auto 18px}
    .wd-help-btn{display:inline-block;padding:12px 22px;border-radius:12px;background:var(--blue);color:#fff;font-weight:600;font-size:.9rem;text-decoration:none}

    /* Notice */
    .wd-notice h4{display:flex;align-items:center;gap:9px;font-size:1.02rem;margin-bottom:14px}
    .wd-notice h4 svg{width:19px;height:19px;color:var(--amber)}
    .wd-note-list{margin:0;padding-left:18px;display:flex;flex-direction:column;gap:9px}
    .wd-note-list li{color:var(--muted);font-size:.88rem;line-height:1.5}
    .wd-note-list strong{color:var(--text)}

    /* Telegram */
    .wd-tg{display:flex;align-items:center;gap:12px;padding:15px 18px;border-radius:16px;text-decoration:none;font-weight:600;font-size:.9rem;color:#fff;
        background:linear-gradient(135deg,#229ed2,#1c8bc0)}
    .wd-tg svg{width:22px;height:22px;flex:none}
    </style>
    @endverbatim

    <script>
        const grossAmountInput = document.getElementById('grossAmount');
        const netAmountInput = document.getElementById('netAmount');
        const grossAmountSubmittedInput = document.getElementById('grossAmountSubmitted');
        const quickAmountBtns = document.querySelectorAll('.quick-amount-btn');
        const requestedAmount = document.getElementById('requestedAmount');
        const feeAmount = document.getElementById('feeAmount');
        const receiveAmount = document.getElementById('receiveAmount');
        const networkOptions = document.querySelectorAll('.network-option');
        const feePercentage = {{ $settings->withdrawal_percentage }};
        const userBalance = {{ Auth::user()->account_bal }};
        const currency = '{{ $settings->currency }}';

        const withdrawalForm = document.getElementById('withdrawalForm');
        const destOptionBtns = document.querySelectorAll('.dest-option-btn');
        const externalFields = document.getElementById('externalFields');
        const zarexFields = document.getElementById('zarexFields');
        const walletAddressInput = document.getElementById('walletAddressInput');
        const coinOptionBtns = document.querySelectorAll('.coin-option-btn');
        const zarexCoinInput = document.getElementById('zarexCoinInput');
        let destination = 'external';

        function selectFirstVisibleNetwork(container) {
            const visible = Array.from(container.querySelectorAll('.network-option')).filter(o => o.style.display !== 'none');
            networkOptions.forEach(o => o.classList.remove('selected'));
            if (visible.length) {
                visible[0].classList.add('selected');
                visible[0].querySelector('input').checked = true;
            }
        }

        destOptionBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                destOptionBtns.forEach(b => b.classList.remove('selected'));
                this.classList.add('selected');
                destination = this.dataset.dest;

                if (destination === 'zarex') {
                    externalFields.style.display = 'none';
                    zarexFields.style.display = '';
                    walletAddressInput.required = false;
                    selectFirstVisibleNetwork(zarexFields);
                } else {
                    zarexFields.style.display = 'none';
                    externalFields.style.display = '';
                    walletAddressInput.required = true;
                    selectFirstVisibleNetwork(externalFields);
                }
            });
        });

        coinOptionBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                coinOptionBtns.forEach(b => b.classList.remove('selected'));
                this.classList.add('selected');
                const coin = this.dataset.coin;
                zarexCoinInput.value = coin;

                zarexFields.querySelectorAll('.network-option').forEach(option => {
                    option.style.display = option.dataset.coin === coin ? '' : 'none';
                });
                selectFirstVisibleNetwork(zarexFields);
            });
        });

        // Calculate fees and update hidden inputs with payout and total debit amounts.
        function calculateFees() {
            const gross = parseFloat(grossAmountInput.value) || 0;
            const fee = (gross * feePercentage) / 100;
            const net = gross - fee;

            // Update display
            requestedAmount.textContent = `${currency}${gross.toFixed(2)}`;
            feeAmount.textContent = `-${currency}${fee.toFixed(2)}`;
            receiveAmount.textContent = `${currency}${net.toFixed(2)}`;

            // Keep the submitted values aligned with the UI.
            netAmountInput.value = net.toFixed(2);
            grossAmountSubmittedInput.value = gross.toFixed(2);
        }

        // Quick amount buttons
        quickAmountBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                const amount = this.dataset.amount;

                if (amount === 'all') {
                    grossAmountInput.value = userBalance;
                } else {
                    grossAmountInput.value = amount;
                }

                quickAmountBtns.forEach(b => b.classList.remove('active'));
                this.classList.add('active');
                calculateFees();
            });
        });

        // Amount input change
        grossAmountInput.addEventListener('input', function() {
            quickAmountBtns.forEach(b => b.classList.remove('active'));
            calculateFees();
        });

        // Network selection
        networkOptions.forEach(option => {
            option.addEventListener('click', function() {
                networkOptions.forEach(o => o.classList.remove('selected'));
                this.classList.add('selected');
                this.querySelector('input').checked = true;
            });
        });

        // Form validation before submit
        withdrawalForm.addEventListener('submit', function(e) {
            const gross = parseFloat(grossAmountInput.value) || 0;
            const net = parseFloat(netAmountInput.value) || 0;

            if (gross <= 0) {
                e.preventDefault();
                alert('Please enter a valid withdrawal amount.');
                return;
            }

            if (gross > userBalance) {
                e.preventDefault();
                alert('Insufficient balance. Your available balance is ' + currency + userBalance.toFixed(2));
                return;
            }

            if (net <= 0) {
                e.preventDefault();
                alert('The amount after fees must be greater than zero.');
                return;
            }

            withdrawalForm.action = destination === 'zarex'
                ? "{{ route('withdrawtozarex') }}"
                : "{{ route('withdrawamount') }}";
        });

        // Initial calculation
        calculateFees();
    </script>
@endsection

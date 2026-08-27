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

    <div class="main-panel deposit-page" data-theme="{{ $bg }}">
        <div class="content">
            <div class="zx-app">

                <x-danger-alert/>
                <x-success-alert/>

                <!-- Header -->
                <div class="dep-head">
                    <div>
                        <h1>Fund your account</h1>
                        <p>Add funds to start trading</p>
                    </div>
                    <div class="dep-bal">
                        <span>Balance</span>
                        <b>{{ $settings->currency }}{{ number_format(Auth::user()->account_bal, 2) }}</b>
                    </div>
                </div>

                <!-- Minimum notice -->
                <div class="dep-notice">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4M12 8h.01"/></svg>
                    <span>Minimum deposit is <strong>{{ $settings->currency }}50</strong>. Smaller amounts won't be processed.</span>
                </div>

                <!-- Deposit form -->
                <div class="dep-card">
                    <form action="javascript:;" method="post" id="submitpaymentform">
                        @csrf
                        <input type="hidden" name="payment_method" id="paymethod">

                        <label class="dep-label">Enter amount</label>
                        <div class="dep-amount">
                            <span class="dep-cur">{{ $settings->currency }}</span>
                            <input type="number" name="amount" id="amount" placeholder="0.00" min="50" required>
                        </div>
                        <div class="dep-quick">
                            <button type="button" class="quick-amount-btn" data-amount="50">{{ $settings->currency }}50</button>
                            <button type="button" class="quick-amount-btn" data-amount="100">{{ $settings->currency }}100</button>
                            <button type="button" class="quick-amount-btn" data-amount="250">{{ $settings->currency }}250</button>
                            <button type="button" class="quick-amount-btn" data-amount="500">{{ $settings->currency }}500</button>
                            <button type="button" class="quick-amount-btn" data-amount="1000">{{ $settings->currency }}1000</button>
                        </div>

                        <label class="dep-label" style="margin-top:22px">Payment method</label>
                        <div class="dep-methods">
                            @foreach ($dmethods as $method)
                                <div class="payment-method-card"
                                     data-method="{{ $method->name }}"
                                     data-id="{{ $method->id }}"
                                     onclick="selectPaymentMethod(this)">
                                    <span class="pm-icon">
                                        @if (!empty($method->img_url))
                                            <img src="{{ $method->img_url }}" alt="{{ $method->name }}">
                                        @else
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="6" width="18" height="13" rx="2.5"/><path d="M16 12h2M3 10h18"/></svg>
                                        @endif
                                    </span>
                                    <span class="pm-name">{{ $method->name }}</span>
                                    <span class="pm-check">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12l5 5 9-11"/></svg>
                                    </span>
                                </div>
                            @endforeach

                            {{-- Always available — not admin-configured, doesn't depend on a Wdmethod row. --}}
                            <div class="payment-method-card"
                                 data-method="Zarex"
                                 data-id="zarex"
                                 onclick="selectPaymentMethod(this)">
                                <span class="pm-icon">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="6" width="18" height="13" rx="2.5"/><path d="M16 12h2M3 10h18"/></svg>
                                </span>
                                <span class="pm-name">Zarex</span>
                                <span class="pm-check">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12l5 5 9-11"/></svg>
                                </span>
                            </div>
                        </div>

                        <div id="zarexCoinPicker" class="dep-zarex-coin" style="display:none">
                            <label class="dep-label">Pay from Zarex balance</label>
                            <input type="hidden" name="coin" id="zarexCoin">
                            <div class="dep-coin-options">
                                <button type="button" class="coin-option-btn" data-coin="USDT">USDT</button>
                                <button type="button" class="coin-option-btn" data-coin="USDC">USDC</button>
                            </div>
                        </div>

                        <button type="submit" class="dep-submit" id="submitBtn">
                            <span>Proceed to payment</span>
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
                        </button>
                    </form>
                </div>

                <!-- Recent deposits -->
                @if(count($deposits) > 0)
                    <div class="zx-section">
                        <div class="zx-section-head">
                            <span class="zx-section-label">Recent deposits</span>
                            <a href="{{ url('dashboard/accounthistory') }}" class="zx-see">See all</a>
                        </div>
                        <div class="zx-list">
                            @foreach($deposits->take(5) as $d)
                                @php $st = strtolower($d->status); @endphp
                                <div class="zx-row">
                                    <span class="zx-row-ico {{ $st == 'processed' ? 'success' : ($st == 'pending' ? 'pending' : 'failed') }}">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14M19 12l-7 7-7-7"/></svg>
                                    </span>
                                    <div class="zx-row-main">
                                        <span class="zx-row-title">{{ $d->payment_mode ?? 'Deposit' }}</span>
                                        <span class="zx-row-sub">{{ $d->created_at ? $d->created_at->diffForHumans() : '' }}</span>
                                    </div>
                                    <div class="zx-row-right">
                                        <span class="zx-row-amt">{{ $settings->currency }}{{ number_format($d->amount, 2) }}</span>
                                        <span class="zx-pill {{ $st == 'processed' ? 'success' : ($st == 'pending' ? 'pending' : 'failed') }}">{{ ucfirst($d->status) }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- How it works -->
                <div class="dep-card dep-how">
                    <h4>How it works</h4>
                    <div class="dep-steps">
                        <div class="dep-step"><span>1</span> Enter the amount you want to deposit</div>
                        <div class="dep-step"><span>2</span> Choose your preferred payment method</div>
                        <div class="dep-step"><span>3</span> Complete payment on the next page</div>
                        <div class="dep-step"><span>4</span> Funds are credited once confirmed</div>
                    </div>
                    <a href="{{ route('support') }}" class="dep-help">Need help? Contact support &rarr;</a>
                </div>

            </div>
        </div>
    </div>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Space+Grotesk:wght@500;600;700&display=swap" rel="stylesheet">
    @verbatim
    <style>
    /* ============ Zaraex deposits ============ */
    .deposit-page{
        --card:#0e1018; --elev:#16181f; --border:#23262f;
        --text:#f2f5fb; --muted:#a1a9b8; --faint:#6b7280;
        --blue:#2563eb; --blue-soft:#3b82f6; --success:#22c55e; --amber:#f59e0b; --danger:#ef4444;
        --tint:rgba(37,99,235,.14);
        font-family:'Inter',system-ui,-apple-system,sans-serif; color:var(--text);
    }
    body[data-theme="light"] .deposit-page{
        --card:#ffffff; --elev:#f4f6fa; --border:#e8ecf2;
        --text:#0f172a; --muted:#5b6674; --faint:#98a2b3;
    }
    .deposit-page .content{padding:0}
    .deposit-page h1,.deposit-page h4{font-family:'Space Grotesk','Inter',sans-serif;letter-spacing:-.02em;margin:0}
    .deposit-page .zx-app{max-width:520px;margin:0 auto;padding:22px 16px 96px;display:flex;flex-direction:column;gap:18px}

    /* Header */
    .dep-head{display:flex;align-items:flex-start;justify-content:space-between;gap:12px}
    .dep-head h1{font-size:1.5rem;font-weight:700}
    .dep-head p{margin:4px 0 0;color:var(--muted);font-size:.9rem}
    .dep-bal{text-align:right;flex:none}
    .dep-bal span{display:block;color:var(--faint);font-size:.75rem}
    .dep-bal b{font-family:'Space Grotesk',sans-serif;font-size:1.05rem}

    /* Notice */
    .dep-notice{display:flex;align-items:center;gap:12px;padding:13px 15px;border-radius:14px;font-size:.85rem;color:var(--muted);
        background:rgba(37,99,235,.08);border:1px solid rgba(37,99,235,.25)}
    .dep-notice svg{width:20px;height:20px;flex:none;color:var(--blue-soft)}
    .dep-notice strong{color:var(--text)}

    /* Card */
    .dep-card{background:var(--card);border:1px solid var(--border);border-radius:20px;padding:22px}
    .dep-label{display:block;font-size:.8rem;font-weight:600;text-transform:uppercase;letter-spacing:.05em;color:var(--faint);margin-bottom:10px}

    /* Amount */
    .dep-amount{display:flex;align-items:center;gap:6px;border:1px solid var(--border);border-radius:14px;background:var(--elev);padding:4px 16px;transition:.2s}
    .dep-amount:focus-within{border-color:var(--blue);box-shadow:0 0 0 3px rgba(37,99,235,.15)}
    .dep-cur{font-size:1.4rem;font-weight:700;color:var(--muted)}
    .dep-amount input{flex:1;min-width:0;border:none;background:transparent;outline:none;color:var(--text);
        font-family:'Space Grotesk',sans-serif;font-size:1.9rem;font-weight:700;padding:12px 4px}
    .dep-amount input::-webkit-outer-spin-button,.dep-amount input::-webkit-inner-spin-button{-webkit-appearance:none;margin:0}
    .dep-quick{display:flex;gap:8px;flex-wrap:wrap;margin-top:12px}
    .quick-amount-btn{flex:1;min-width:56px;padding:9px 6px;border-radius:11px;border:1px solid var(--border);
        background:var(--elev);color:var(--text);font-weight:600;font-size:.85rem;cursor:pointer;transition:.2s}
    .quick-amount-btn:hover{border-color:var(--blue-soft)}
    .quick-amount-btn.active{background:var(--blue);color:#fff;border-color:var(--blue)}

    /* Methods */
    .dep-methods{display:grid;grid-template-columns:repeat(2,1fr);gap:10px}
    .payment-method-card{position:relative;display:flex;align-items:center;gap:11px;padding:14px;border-radius:14px;cursor:pointer;
        border:1px solid var(--border);background:var(--elev);transition:.2s}
    .payment-method-card:hover{border-color:var(--blue-soft)}
    .payment-method-card.selected{border-color:var(--blue);background:rgba(37,99,235,.08)}
    .pm-icon{width:38px;height:38px;border-radius:11px;flex:none;display:grid;place-items:center;overflow:hidden;
        background:var(--tint);color:var(--blue-soft)}
    .pm-icon img{width:100%;height:100%;object-fit:cover}
    .pm-icon svg{width:19px;height:19px}
    .pm-name{font-weight:600;font-size:.9rem;overflow:hidden;text-overflow:ellipsis;white-space:nowrap}
    .pm-check{position:absolute;top:12px;right:12px;width:18px;height:18px;border-radius:50%;display:grid;place-items:center;
        background:var(--blue);color:#fff;opacity:0;transform:scale(.6);transition:.2s}
    .pm-check svg{width:11px;height:11px}
    .payment-method-card.selected .pm-check{opacity:1;transform:scale(1)}

    /* Zarex coin picker */
    .dep-zarex-coin{margin-top:16px}
    .dep-coin-options{display:flex;gap:10px}
    .coin-option-btn{flex:1;padding:12px;border-radius:12px;border:1px solid var(--border);background:var(--elev);
        color:var(--text);font-weight:600;font-size:.9rem;cursor:pointer;transition:.2s}
    .coin-option-btn:hover{border-color:var(--blue-soft)}
    .coin-option-btn.selected{border-color:var(--blue);background:rgba(37,99,235,.08)}

    /* Submit */
    .dep-submit{width:100%;margin-top:18px;display:flex;align-items:center;justify-content:center;gap:8px;
        padding:15px;border-radius:13px;border:none;cursor:pointer;background:var(--blue);color:#fff;font-weight:600;font-size:1rem;transition:.2s}
    .dep-submit:hover{background:var(--blue-soft)}
    .dep-submit svg{width:17px;height:17px}
    .dep-empty{text-align:center;padding:26px 10px;color:var(--muted)}
    .dep-empty svg{width:32px;height:32px;color:var(--faint);margin-bottom:8px}
    .dep-empty h4{font-size:1rem;margin-bottom:6px}
    .dep-empty p{margin:0;font-size:.88rem}

    /* Recent list (shared) */
    .zx-section{display:flex;flex-direction:column;gap:10px}
    .zx-section-head{display:flex;align-items:center;justify-content:space-between;padding:0 4px}
    .zx-section-label{text-transform:uppercase;letter-spacing:.08em;font-size:.75rem;font-weight:700;color:var(--faint)}
    .zx-see{color:var(--blue-soft);font-size:.82rem;font-weight:600;text-decoration:none}
    .zx-list{background:var(--card);border:1px solid var(--border);border-radius:18px;overflow:hidden}
    .zx-row{display:flex;align-items:center;gap:14px;padding:14px 16px;border-bottom:1px solid var(--border)}
    .zx-row:last-child{border-bottom:0}
    .zx-row-ico{width:40px;height:40px;border-radius:50%;flex:none;display:grid;place-items:center;background:rgba(34,197,94,.14);color:var(--success)}
    .zx-row-ico svg{width:17px;height:17px}
    .zx-row-ico.pending{background:rgba(245,158,11,.14);color:var(--amber)}
    .zx-row-ico.failed{background:rgba(239,68,68,.14);color:var(--danger)}
    .zx-row-main{display:flex;flex-direction:column;flex:1;min-width:0}
    .zx-row-title{font-weight:600;font-size:.92rem;text-transform:capitalize}
    .zx-row-sub{color:var(--faint);font-size:.78rem;margin-top:2px}
    .zx-row-right{display:flex;flex-direction:column;align-items:flex-end;gap:3px}
    .zx-row-amt{font-family:'Space Grotesk',sans-serif;font-weight:600;font-size:.92rem}
    .zx-pill{font-size:.7rem;font-weight:600;padding:3px 9px;border-radius:999px}
    .zx-pill.success{background:rgba(34,197,94,.14);color:var(--success)}
    .zx-pill.pending{background:rgba(245,158,11,.14);color:var(--amber)}
    .zx-pill.failed{background:rgba(239,68,68,.14);color:var(--danger)}

    /* How it works */
    .dep-how h4{font-size:1.05rem;margin-bottom:14px}
    .dep-steps{display:flex;flex-direction:column;gap:12px}
    .dep-step{display:flex;align-items:center;gap:12px;font-size:.9rem;color:var(--muted)}
    .dep-step span{width:26px;height:26px;flex:none;border-radius:8px;display:grid;place-items:center;
        background:var(--tint);color:var(--blue-soft);font-weight:700;font-size:.8rem}
    .dep-help{display:inline-block;margin-top:16px;color:var(--blue-soft);font-weight:600;font-size:.88rem;text-decoration:none}
    </style>
    @endverbatim

    <script>
        let paymethod = document.querySelector('#paymethod');
        const amountInput = document.getElementById('amount');
        const quickAmountBtns = document.querySelectorAll('.quick-amount-btn');
        const zarexCoinPicker = document.getElementById('zarexCoinPicker');
        const zarexCoin = document.getElementById('zarexCoin');
        const coinOptionBtns = document.querySelectorAll('.coin-option-btn');

        coinOptionBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                coinOptionBtns.forEach(b => b.classList.remove('selected'));
                this.classList.add('selected');
                zarexCoin.value = this.dataset.coin;
            });
        });

        // Quick amount buttons
        quickAmountBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                const amount = this.dataset.amount;
                amountInput.value = amount;

                // Update active state
                quickAmountBtns.forEach(b => b.classList.remove('active'));
                this.classList.add('active');
            });
        });

        // Remove active class when typing custom amount
        amountInput.addEventListener('input', function() {
            quickAmountBtns.forEach(b => b.classList.remove('active'));
        });

        // Select payment method
        function selectPaymentMethod(element) {
            const methodCards = document.querySelectorAll('.payment-method-card');
            methodCards.forEach(card => card.classList.remove('selected'));
            element.classList.add('selected');

            const methodId = element.dataset.id;
            const methodName = element.dataset.method;

            if (methodName === 'Zarex') {
                zarexCoinPicker.style.display = '';
                // Not a real Wdmethod row — nothing to look up server-side.
                paymethod.value = 'Zarex';
                return;
            }

            zarexCoinPicker.style.display = 'none';
            zarexCoin.value = '';
            coinOptionBtns.forEach(b => b.classList.remove('selected'));

            let url = "{{ url('/dashboard/get-method/') }}" + '/' + methodId;
            fetch(url)
                .then(res => res.json())
                .then(response => {
                    paymethod.value = response;
                    $.notify({
                        icon: 'fa fa-check-circle',
                        title: 'Payment Method Selected',
                        message: 'You have selected ' + response,
                    }, {
                        type: 'success',
                        placement: { from: "top", align: "right" },
                        delay: 3000,
                        animate: {
                            enter: 'animated fadeInDown',
                            exit: 'animated fadeOutUp'
                        },
                    });
                })
                .catch(err => console.log(err));
        }

        // Auto-select first method on load
        document.addEventListener('DOMContentLoaded', function() {
            const firstMethod = document.querySelector('.payment-method-card');
            if (firstMethod) {
                selectPaymentMethod(firstMethod);
            }
        });

        // Form submission
        $('#submitpaymentform').on('submit', function(e) {
            const amount = parseFloat(amountInput.value);

            if (paymethod.value === "") {
                e.preventDefault();
                $.notify({
                    icon: 'fa fa-exclamation-circle',
                    title: 'Select Payment Method',
                    message: 'Please select a payment method to continue',
                }, {
                    type: 'danger',
                    placement: { from: "top", align: "right" },
                    delay: 4000,
                    animate: {
                        enter: 'animated fadeInDown',
                        exit: 'animated fadeOutUp'
                    },
                });
            } else if (paymethod.value === 'Zarex' && !zarexCoin.value) {
                e.preventDefault();
                $.notify({
                    icon: 'fa fa-exclamation-circle',
                    title: 'Select a Coin',
                    message: 'Please choose USDT or USDC to deposit via Zarex',
                }, {
                    type: 'danger',
                    placement: { from: "top", align: "right" },
                    delay: 4000,
                    animate: {
                        enter: 'animated fadeInDown',
                        exit: 'animated fadeOutUp'
                    },
                });
            } else if (!amount || amount < 50) {
                e.preventDefault();
                $.notify({
                    icon: 'fa fa-exclamation-circle',
                    title: 'Invalid Amount',
                    message: 'Minimum deposit amount is $50',
                }, {
                    type: 'danger',
                    placement: { from: "top", align: "right" },
                    delay: 4000,
                    animate: {
                        enter: 'animated fadeInDown',
                        exit: 'animated fadeOutUp'
                    },
                });
            } else {
                document.getElementById("submitpaymentform").action = "{{ url('/dashboard/newdeposit') }}";
            }
        });
    </script>
@endsection

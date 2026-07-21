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

    <div class="main-panel transactions-page" data-theme="{{ $bg }}">
        <div class="content">
            <div class="page-inner">

                <!-- Page Header -->
                <div class="page-header">
                    <div class="header-content">
                        <h1 class="page-title">Transaction History</h1>
                        <p class="page-subtitle">View all your account transactions</p>
                    </div>
                    <div class="header-actions">
                        <a href="{{ route('deposits') }}" class="action-btn primary">
                            <i class="fa fa-plus"></i>
                            <span>Deposit</span>
                        </a>
                        <a href="{{ route('withdrawalsdeposits') }}" class="action-btn secondary">
                            <i class="fa fa-arrow-up"></i>
                            <span>Withdraw</span>
                        </a>
                    </div>
                </div>

                <x-danger-alert/>
                <x-success-alert/>

                <!-- Summary Cards -->
                <div class="summary-cards">
                    <div class="summary-card">
                        <div class="summary-icon deposits">
                            <i class="fa fa-arrow-down"></i>
                        </div>
                        <div class="summary-content">
                            <span class="summary-value">{{ $deposits->count() }}</span>
                            <span class="summary-label">Total Deposits</span>
                        </div>
                    </div>
                    <div class="summary-card">
                        <div class="summary-icon withdrawals">
                            <i class="fa fa-arrow-up"></i>
                        </div>
                        <div class="summary-content">
                            <span class="summary-value">{{ $withdrawals->count() }}</span>
                            <span class="summary-label">Total Withdrawals</span>
                        </div>
                    </div>
                    <div class="summary-card">
                        <div class="summary-icon others">
                            <i class="fa fa-exchange-alt"></i>
                        </div>
                        <div class="summary-content">
                            <span class="summary-value">{{ $t_history->count() }}</span>
                            <span class="summary-label">Other Transactions</span>
                        </div>
                    </div>
                </div>

                <!-- Transactions Card -->
                <div class="transactions-card">
                    <!-- Tabs -->
                    <div class="tabs-wrapper">
                        <nav class="tabs-nav">
                            <button class="tab-btn active" data-tab="deposits">
                                <i class="fa fa-arrow-down"></i>
                                <span>Deposits</span>
                                <span class="tab-count">{{ $deposits->count() }}</span>
                            </button>
                            <button class="tab-btn" data-tab="withdrawals">
                                <i class="fa fa-arrow-up"></i>
                                <span>Withdrawals</span>
                                <span class="tab-count">{{ $withdrawals->count() }}</span>
                            </button>
                            <button class="tab-btn" data-tab="others">
                                <i class="fa fa-receipt"></i>
                                <span>Others</span>
                                <span class="tab-count">{{ $t_history->count() }}</span>
                            </button>
                        </nav>
                    </div>

                    <!-- Tab Content -->
                    <div class="tabs-content">

                        <!-- Deposits Tab -->
                        <div class="tab-panel active" id="deposits">
                            @if($deposits->count() > 0)
                                <div class="table-wrapper">
                                    <table class="transactions-table">
                                        <thead>
                                        <tr>
                                            <th>Amount</th>
                                            <th>Payment Method</th>
                                            <th>Status</th>
                                            <th>Date</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($deposits as $deposit)
                                            <tr>
                                                <td>
                                                        <span class="amount positive">
                                                            +{{ $settings->currency }}{{ number_format($deposit->amount, 2) }}
                                                        </span>
                                                </td>
                                                <td>
                                                    <div class="payment-method">
                                                        <div class="method-icon">
                                                            @if(strtolower($deposit->payment_mode) == 'bitcoin' || strtolower($deposit->payment_mode) == 'btc')
                                                                <i class="fab fa-bitcoin"></i>
                                                            @elseif(strtolower($deposit->payment_mode) == 'ethereum' || strtolower($deposit->payment_mode) == 'eth')
                                                                <i class="fab fa-ethereum"></i>
                                                            @elseif(strtolower($deposit->payment_mode) == 'bank' || str_contains(strtolower($deposit->payment_mode), 'bank'))
                                                                <i class="fa fa-university"></i>
                                                            @else
                                                                <i class="fa fa-wallet"></i>
                                                            @endif
                                                        </div>
                                                        <span>{{ $deposit->payment_mode }}</span>
                                                    </div>
                                                </td>
                                                <td>
                                                    @if($deposit->status == "Processed")
                                                        <span class="status-badge success">
                                                                <i class="fa fa-check-circle"></i>
                                                                Completed
                                                            </span>
                                                    @else
                                                        <span class="status-badge pending">
                                                                <i class="fa fa-clock"></i>
                                                                {{ $deposit->status }}
                                                            </span>
                                                    @endif
                                                </td>
                                                <td>
                                                        <span class="date-text">
                                                            {{ \Carbon\Carbon::parse($deposit->created_at)->format('M d, Y') }}
                                                        </span>
                                                    <span class="time-text">
                                                            {{ \Carbon\Carbon::parse($deposit->created_at)->format('h:i A') }}
                                                        </span>
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="empty-state">
                                    <div class="empty-icon">
                                        <i class="fa fa-arrow-down"></i>
                                    </div>
                                    <h4>No Deposits Yet</h4>
                                    <p>You haven't made any deposits. Start by funding your account.</p>
                                    <a href="{{ route('deposits') }}" class="empty-action-btn">
                                        <i class="fa fa-plus"></i>
                                        Make a Deposit
                                    </a>
                                </div>
                            @endif
                        </div>

                        <!-- Withdrawals Tab -->
                        <div class="tab-panel" id="withdrawals">
                            @if($withdrawals->count() > 0)
                                <div class="table-wrapper">
                                    <table class="transactions-table">
                                        <thead>
                                        <tr>
                                            <th>Amount</th>
                                            <th>Total Deducted</th>
                                            <th>Method</th>
                                            <th>Status</th>
                                            <th>Date</th>
                                            <th>Action</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($withdrawals as $withdrawal)
                                            <tr>
                                                <td>
                                                        <span class="amount negative">
                                                            -{{ $settings->currency }}{{ number_format($withdrawal->amount, 2) }}
                                                        </span>
                                                </td>
                                                <td>
                                                        <span class="amount-secondary">
                                                            {{ $settings->currency }}{{ number_format($withdrawal->to_deduct, 2) }}
                                                        </span>
                                                </td>
                                                <td>
                                                    <div class="payment-method">
                                                        <div class="method-icon">
                                                            @if(strtolower($withdrawal->payment_mode) == 'bitcoin' || strtolower($withdrawal->payment_mode) == 'btc')
                                                                <i class="fab fa-bitcoin"></i>
                                                            @elseif(strtolower($withdrawal->payment_mode) == 'ethereum' || strtolower($withdrawal->payment_mode) == 'eth')
                                                                <i class="fab fa-ethereum"></i>
                                                            @elseif(strtolower($withdrawal->payment_mode) == 'bank' || str_contains(strtolower($withdrawal->payment_mode), 'bank'))
                                                                <i class="fa fa-university"></i>
                                                            @else
                                                                <i class="fa fa-wallet"></i>
                                                            @endif
                                                        </div>
                                                        <span>{{ $withdrawal->payment_mode }}</span>
                                                    </div>
                                                </td>
                                                <td>
                                                    @if($withdrawal->status == "Processed")
                                                        <span class="status-badge success">
                                                                <i class="fa fa-check-circle"></i>
                                                                Completed
                                                            </span>
                                                    @else
                                                        <span class="status-badge pending">
                                                                <i class="fa fa-clock"></i>
                                                                {{ $withdrawal->status }}
                                                            </span>
                                                    @endif
                                                </td>
                                                <td>
                                                        <span class="date-text">
                                                            {{ \Carbon\Carbon::parse($withdrawal->created_at)->format('M d, Y') }}
                                                        </span>
                                                    <span class="time-text">
                                                            {{ \Carbon\Carbon::parse($withdrawal->created_at)->format('h:i A') }}
                                                        </span>
                                                </td>
                                                <td>
                                                    @if(strtolower((string) $withdrawal->status) === 'pending')
                                                        <form action="{{ route('cancelwithdrawal', $withdrawal->id) }}" method="POST" onsubmit="return confirm('Cancel this withdrawal request?');">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="status-badge failed" style="border: 0; cursor: pointer;">
                                                                <i class="fa fa-times"></i>
                                                                Cancel
                                                            </button>
                                                        </form>
                                                    @else
                                                        <span class="status-badge success">N/A</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="empty-state">
                                    <div class="empty-icon">
                                        <i class="fa fa-arrow-up"></i>
                                    </div>
                                    <h4>No Withdrawals Yet</h4>
                                    <p>You haven't made any withdrawals from your account.</p>
                                </div>
                            @endif
                        </div>

                        <!-- Others Tab -->
                        <div class="tab-panel" id="others">
                            @if($t_history->count() > 0)
                                <div class="table-wrapper">
                                    <table class="transactions-table">
                                        <thead>
                                        <tr>
                                            <th>Amount</th>
                                            <th>Type</th>
                                            <th>Description</th>
                                            <th>Date</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($t_history as $history)
                                            <tr>
                                                <td>
                                                        <span class="amount">
                                                            {{ $settings->currency }}{{ number_format($history->amount, 2) }}
                                                        </span>
                                                </td>
                                                <td>
                                                        <span class="type-badge">
                                                            {{ $history->type }}
                                                        </span>
                                                </td>
                                                <td>
                                                        <span class="description-text">
                                                            {{ $history->plan }}
                                                        </span>
                                                </td>
                                                <td>
                                                        <span class="date-text">
                                                            {{ \Carbon\Carbon::parse($history->created_at)->format('M d, Y') }}
                                                        </span>
                                                    <span class="time-text">
                                                            {{ \Carbon\Carbon::parse($history->created_at)->format('h:i A') }}
                                                        </span>
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="empty-state">
                                    <div class="empty-icon">
                                        <i class="fa fa-receipt"></i>
                                    </div>
                                    <h4>No Other Transactions</h4>
                                    <p>No additional transactions to display.</p>
                                </div>
                            @endif
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>

    @include('user.modals')

    <style>
        .transactions-page {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
        }

        /* Theme Variables */
        .transactions-page[data-theme="dark"] {
            --bg-primary: #0a0a0f;
            --bg-card: rgba(18, 18, 26, 0.9);
            --text-primary: #f8fafc;
            --text-secondary: #94a3b8;
            --text-muted: #7d8ba3;
            --border-color: rgba(37, 99, 235, 0.15);
            --hover-bg: rgba(37, 99, 235, 0.08);
        }

        .transactions-page[data-theme="light"] {
            --bg-primary: #f8fafc;
            --bg-card: #ffffff;
            --text-primary: #0f172a;
            --text-secondary: #64748b;
            --text-muted: #94a3b8;
            --border-color: #e2e8f0;
            --hover-bg: rgba(37, 99, 235, 0.05);
        }

        .transactions-page .content {
            background: var(--bg-primary) !important;
        }

        /* Page Header */
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 28px;
            flex-wrap: wrap;
            gap: 16px;
        }

        .page-title {
            font-size: 1.75rem;
            font-weight: 700;
            color: var(--text-primary);
            margin: 0 0 6px;
        }

        .page-subtitle {
            font-size: 0.95rem;
            color: var(--text-secondary);
            margin: 0;
        }

        .header-actions {
            display: flex;
            gap: 10px;
        }

        .action-btn {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 10px 18px;
            border-radius: 10px;
            font-size: 0.875rem;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.2s ease;
        }

        .action-btn.primary {
            background: #2563eb;
            color: white;
        }

        .action-btn.primary:hover {
            background: #1d4ed8;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px -4px rgba(37, 99, 235, 0.5);
        }

        .action-btn.secondary {
            background: transparent;
            border: 1px solid var(--border-color);
            color: var(--text-primary);
        }

        .action-btn.secondary:hover {
            border-color: #2563eb;
            color: #2563eb;
        }

        /* Summary Cards */
        .summary-cards {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 16px;
            margin-bottom: 24px;
        }

        @media (max-width: 768px) {
            .summary-cards {
                grid-template-columns: 1fr;
            }

            .header-actions {
                width: 100%;
            }

            .action-btn {
                flex: 1;
                justify-content: center;
            }
        }

        .summary-card {
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: 14px;
            padding: 18px 20px;
            display: flex;
            align-items: center;
            gap: 14px;
            transition: all 0.2s ease;
        }

        .summary-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px -10px rgba(0, 0, 0, 0.2);
        }

        .summary-icon {
            width: 46px;
            height: 46px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
        }

        .summary-icon.deposits {
            background: rgba(16, 185, 129, 0.15);
            color: #10b981;
        }

        .summary-icon.withdrawals {
            background: rgba(239, 68, 68, 0.15);
            color: #ef4444;
        }

        .summary-icon.others {
            background: rgba(37, 99, 235, 0.15);
            color: #2563eb;
        }

        .summary-content {
            display: flex;
            flex-direction: column;
        }

        .summary-value {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--text-primary);
        }

        .summary-label {
            font-size: 0.8rem;
            color: var(--text-secondary);
        }

        /* Transactions Card */
        .transactions-card {
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: 16px;
            overflow: hidden;
        }

        /* Tabs */
        .tabs-wrapper {
            border-bottom: 1px solid var(--border-color);
        }

        .tabs-nav {
            display: flex;
            padding: 0 8px;
            overflow-x: auto;
        }

        .tab-btn {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 16px 20px;
            background: transparent;
            border: none;
            color: var(--text-secondary);
            font-size: 0.9rem;
            font-weight: 500;
            cursor: pointer;
            position: relative;
            transition: all 0.2s ease;
            white-space: nowrap;
        }

        .tab-btn:hover {
            color: var(--text-primary);
        }

        .tab-btn.active {
            color: #2563eb;
        }

        .tab-btn.active::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 20px;
            right: 20px;
            height: 2px;
            background: #2563eb;
            border-radius: 2px 2px 0 0;
        }

        .tab-btn i {
            font-size: 14px;
        }

        .tab-count {
            background: var(--hover-bg);
            padding: 2px 8px;
            border-radius: 10px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .tab-btn.active .tab-count {
            background: rgba(37, 99, 235, 0.15);
            color: #2563eb;
        }

        /* Tab Content */
        .tabs-content {
            padding: 0;
        }

        .tab-panel {
            display: none;
        }

        .tab-panel.active {
            display: block;
        }

        /* Table */
        .table-wrapper {
            overflow-x: auto;
        }

        .transactions-table {
            width: 100%;
            border-collapse: collapse;
        }

        .transactions-table th {
            text-align: left;
            padding: 14px 20px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: var(--text-muted);
            background: var(--hover-bg);
        }

        .transactions-table td {
            padding: 16px 20px;
            font-size: 0.9rem;
            color: var(--text-primary);
            border-bottom: 1px solid var(--border-color);
            vertical-align: middle;
        }

        .transactions-table tbody tr {
            transition: background 0.2s ease;
        }

        .transactions-table tbody tr:hover {
            background: var(--hover-bg);
        }

        .transactions-table tbody tr:last-child td {
            border-bottom: none;
        }

        /* Amount Styling */
        .amount {
            font-weight: 600;
            font-size: 0.95rem;
        }

        .amount.positive {
            color: #10b981;
        }

        .amount.negative {
            color: #ef4444;
        }

        .amount-secondary {
            color: var(--text-secondary);
            font-size: 0.875rem;
        }

        /* Payment Method */
        .payment-method {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .method-icon {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            background: var(--hover-bg);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--text-secondary);
            font-size: 14px;
        }

        /* Status Badge */
        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
        }

        .status-badge.success {
            background: rgba(16, 185, 129, 0.15);
            color: #10b981;
        }

        .status-badge.pending {
            background: rgba(245, 158, 11, 0.15);
            color: #f59e0b;
        }

        .status-badge i {
            font-size: 10px;
        }

        /* Type Badge */
        .type-badge {
            display: inline-block;
            padding: 6px 12px;
            background: rgba(37, 99, 235, 0.15);
            color: #2563eb;
            border-radius: 6px;
            font-size: 0.8rem;
            font-weight: 600;
        }

        /* Date */
        .date-text {
            display: block;
            color: var(--text-primary);
            font-size: 0.875rem;
        }

        .time-text {
            display: block;
            color: var(--text-muted);
            font-size: 0.75rem;
            margin-top: 2px;
        }

        .description-text {
            color: var(--text-secondary);
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 60px 20px;
        }

        .empty-icon {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            background: rgba(37, 99, 235, 0.1);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            color: #2563eb;
            font-size: 28px;
        }

        .empty-state h4 {
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--text-primary);
            margin: 0 0 8px;
        }

        .empty-state p {
            font-size: 0.9rem;
            color: var(--text-secondary);
            margin: 0 0 20px;
        }

        .empty-action-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 12px 24px;
            background: #2563eb;
            color: white;
            border-radius: 10px;
            font-size: 0.9rem;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.2s ease;
        }

        .empty-action-btn:hover {
            background: #1d4ed8;
            color: white;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px -4px rgba(37, 99, 235, 0.5);
        }

        /* Mobile Responsive */
        @media (max-width: 576px) {
            .tab-btn span:not(.tab-count) {
                display: none;
            }

            .tab-btn {
                padding: 16px 14px;
            }

            .transactions-table th,
            .transactions-table td {
                padding: 12px 14px;
            }

            .payment-method span {
                display: none;
            }
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const tabBtns = document.querySelectorAll('.tab-btn');
            const tabPanels = document.querySelectorAll('.tab-panel');

            tabBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    const tabId = this.getAttribute('data-tab');

                    // Remove active from all
                    tabBtns.forEach(b => b.classList.remove('active'));
                    tabPanels.forEach(p => p.classList.remove('active'));

                    // Add active to clicked
                    this.classList.add('active');
                    document.getElementById(tabId).classList.add('active');
                });
            });
        });
    </script>
@endsection

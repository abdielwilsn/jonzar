<?php
if (Auth('admin')->User()->dashboard_style == "light") {
    $text = "dark";
} else {
    $text = "light";
}
?>
@extends('layouts.app')

@section('content')
    @include('admin.topmenu')
    @include('admin.sidebar')

    <div class="main-panel bg-{{ Auth('admin')->User()->dashboard_style }}">
        <div class="content bg-{{ Auth('admin')->User()->dashboard_style }}">
            <div class="page-inner">
                <h2 class="text-{{ $text }}">Manage Trading Pairs</h2>

                <x-success-alert />

                <!-- Form to add new trading pair -->
                <div class="card shadow p-4 bg-{{ Auth('admin')->User()->dashboard_style }}">
                    <form action="{{ route('admin.store-trading-pairs') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label class="text-{{ $text }}">Base Currency</label>
                            <input type="text" name="base_currency" class="form-control bg-{{ Auth('admin')->User()->dashboard_style }} text-{{ $text }}" required>
                        </div>
                        <div class="form-group">
    <label class="text-{{ $text }}">CoinGecko ID</label>
    <input type="text" name="coingecko_id" class="form-control" placeholder="e.g., bitcoin, ethereum" required>
</div>

                        <div class="form-group">
                            <label class="text-{{ $text }}">Quote Currency</label>
                            <input type="text" name="quote_currency" class="form-control bg-{{ Auth('admin')->User()->dashboard_style }} text-{{ $text }}" value="USDT" required>
                        </div>
                        <div class="form-group">
                            <label class="text-{{ $text }}">Active</label><br>
                            <input type="checkbox" name="is_active" value="1" checked>
                        </div>
                        <button type="submit" class="btn btn-primary">Add Pair</button>
                    </form>
                </div>

                <!-- List of trading pairs -->
                <h4 class="text-{{ $text }} mt-4">Trading Pairs</h4>
                {{-- <table class="table table-bordered table-{{ Auth('admin')->User()->dashboard_style }} text-{{ $text }}">
                    <thead>
                        <tr>
                            <th>Base Currency</th>
                            <th>Quote Currency</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pairs as $pair)
                            <tr>
                                <td>{{ $pair->base_currency }}</td>
                                <td>{{ $pair->quote_currency }}</td>
                                <td>{{ $pair->is_active ? 'Active' : 'Inactive' }}</td>
                                <td>
                                    <form action="{{ route('admin.trading-pairs.toggle', $pair->id) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-sm {{ $pair->is_active ? 'btn-warning' : 'btn-success' }}">
                                            {{ $pair->is_active ? 'Deactivate' : 'Activate' }}
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table> --}}


                <!-- Open Trades Table - Updated for new schema -->
<div class="open-trades">
    <h4 class="text-{{ $text }} mb-4">Open Positions</h4>
    <table class="table">
        <thead>
            <tr>
                <th>Type</th>
                <th>Currency Pair</th>
                <th>Order Type</th>
                <th>Entry Price</th>
                <th>Current Price</th>
                <th>Volume</th>
                <th>Amount (USD)</th>
                <th>Fee</th>
                <th>P/L (USD)</th>
                @if(Auth::user()->is_admin)
                    <th>User</th>
                @endif
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($openTrades as $trade)
                <tr>
                    <td>
                        <span class="badge badge-{{ $trade->type === 'buy' ? 'success' : 'danger' }}">
                            {{ ucfirst($trade->type) }}
                        </span>
                    </td>
                    <td>{{ $trade->full_pair }}</td>
                    <td>
                        <span class="badge badge-secondary">
                            {{ ucfirst($trade->order_type) }}
                        </span>
                    </td>
                    <td>${{ number_format($trade->entry_price, 4) }}</td>
                    <td>${{ number_format($trade->current_price, 4) }}</td>
                    <td>{{ number_format($trade->volume, 8) }}</td>
                    <td>${{ number_format($trade->amount, 2) }}</td>
                    <td>${{ number_format($trade->fee, 2) }}</td>
                    <td class="{{ $trade->unrealized_pl >= 0 ? 'text-success' : 'text-danger' }}">
                        ${{ number_format($trade->unrealized_pl, 2) }}
                        @if($trade->unrealized_pl != 0)
                            <small class="d-block">
                                ({{ number_format(($trade->unrealized_pl / $trade->amount) * 100, 2) }}%)
                            </small>
                        @endif
                    </td>
                    @if(Auth::user()->is_admin)
                        <td>{{ $trade->user->name }}</td>
                    @endif
                    <td>
                        <div class="btn-group btn-group-sm">
                            @if($trade->stop_loss)
                                <small class="text-muted d-block">SL: ${{ number_format($trade->stop_loss, 2) }}</small>
                            @endif
                            @if($trade->take_profit)
                                <small class="text-muted d-block">TP: ${{ number_format($trade->take_profit, 2) }}</small>
                            @endif
                            <!-- Add close position button if needed -->
                            <button class="btn btn-sm btn-outline-danger" 
                                    onclick="closePosition({{ $trade->id }})">
                                Close
                            </button>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="{{ Auth::user()->is_admin ? 11 : 10 }}">No open positions</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Optional: Recent Trades History -->
@if(isset($recentTrades) && $recentTrades->count() > 0)
<div class="recent-trades mt-4">
    <h4 class="text-{{ $text }} mb-4">Recent Trades</h4>
    <table class="table table-sm">
        <thead>
            <tr>
                <th>Date</th>
                <th>Type</th>
                <th>Pair</th>
                <th>Price</th>
                <th>Volume</th>
                <th>P/L</th>
            </tr>
        </thead>
        <tbody>
            @foreach($recentTrades as $trade)
                <tr>
                    <td>{{ $trade->closed_at ? $trade->closed_at->format('M d, H:i') : 'N/A' }}</td>
                    <td>
                        <span class="badge badge-{{ $trade->type === 'buy' ? 'success' : 'danger' }} badge-sm">
                            {{ ucfirst($trade->type) }}
                        </span>
                    </td>
                    <td>{{ $trade->full_pair }}</td>
                    <td>${{ number_format($trade->entry_price, 4) }}</td>
                    <td>{{ number_format($trade->volume, 6) }}</td>
                    <td class="{{ $trade->realized_pl >= 0 ? 'text-success' : 'text-danger' }}">
                        ${{ number_format($trade->realized_pl, 2) }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endif

<!-- Optional: Pending Orders -->
@if(isset($pendingOrders) && $pendingOrders->count() > 0)
<div class="pending-orders mt-4">
    <h4 class="text-{{ $text }} mb-4">Pending Orders</h4>
    <table class="table table-sm">
        <thead>
            <tr>
                <th>Type</th>
                <th>Pair</th>
                <th>Order Type</th>
                <th>Target Price</th>
                <th>Volume</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($pendingOrders as $order)
                <tr>
                    <td>
                        <span class="badge badge-{{ $order->type === 'buy' ? 'success' : 'danger' }} badge-sm">
                            {{ ucfirst($order->type) }}
                        </span>
                    </td>
                    <td>{{ $order->full_pair }}</td>
                    <td>{{ ucfirst($order->order_type) }}</td>
                    <td>${{ number_format($order->entry_price, 4) }}</td>
                    <td>{{ number_format($order->volume, 6) }}</td>
                    <td>
                        <button class="btn btn-sm btn-outline-danger" 
                                onclick="cancelOrder({{ $order->id }})">
                            Cancel
                        </button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endif

            </div>
        </div>
    </div>
@endsection

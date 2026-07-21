<?php
if (Auth('admin')->User()->dashboard_style == "light") {
    $text = "dark";
    $bg = 'light';
} else {
    $text = "light";
    $bg = 'dark';
}
?>

@extends('layouts.app')

@section('content')
    @include('admin.topmenu')
    @include('admin.sidebar')

    <div class="main-panel">
        <div class="content bg-{{ $bg }}">
            <div class="page-inner">
                <div class="mt-2 mb-4">
                    <h1 class="title1 text-{{ $text }}">Add New Trading Pair</h1>
                    <p class="text-{{ $text }} opacity-75">Create a new cryptocurrency trading pair for investment</p>
                </div>

                <x-danger-alert/>
                <x-success-alert/>

                <div class="card bg-{{ $bg }} shadow border-0">
                    <div class="card-body p-4">
                        <form action="{{ route('admin.trading-pairs.store') }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="coingecko_id" class="form-label text-{{ $text }}">CoinGecko ID *</label>
                                        <input type="text" class="form-control" id="coingecko_id" name="coingecko_id" required placeholder="e.g., bitcoin, ethereum, cardano">
                                        <small class="text-{{ $text }} opacity-75">Find the exact ID from CoinGecko website</small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="quote_symbol" class="form-label text-{{ $text }}">Quote Currency *</label>
                                        <select class="form-control" id="quote_symbol" name="quote_symbol" required>
                                            <option value="USDT">USDT</option>
                                            <option value="USD">USD</option>
                                            <option value="BTC">BTC</option>
                                            <option value="ETH">ETH</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="min_investment" class="form-label text-{{ $text }}">Minimum Investment *</label>
                                        <input type="number" class="form-control" id="min_investment" name="min_investment" step="0.01" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="max_investment" class="form-label text-{{ $text }}">Maximum Investment *</label>
                                        <input type="number" class="form-control" id="max_investment" name="max_investment" step="0.01" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="min_return_percentage" class="form-label text-{{ $text }}">Minimum Return (%) *</label>
                                        <input type="number" class="form-control" id="min_return_percentage" name="min_return_percentage" step="0.1" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="max_return_percentage" class="form-label text-{{ $text }}">Maximum Return (%) *</label>
                                        <input type="number" class="form-control" id="max_return_percentage" name="max_return_percentage" step="0.1" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="investment_duration" class="form-label text-{{ $text }}"> Min Trade Duration (Days) *</label>
                                        <input type="number" class="form-control" id="investment_duration" name="investment_duration" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="max_investment_duration" class="form-label text-{{ $text }}">Max Trade Duration (Days) *</label>
                                        <input type="number" class="form-control" id="max_investment_duration" name="max_investment_duration" required>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="is_active" class="form-label text-{{ $text }}">Status</label>
                                        <select class="form-control" id="is_active" name="is_active">
                                            <option value="1">Active</option>
                                            <option value="0">Inactive</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-4">
                                <a href="{{ route('admin.trading-pairs.index') }}" class="btn btn-secondary">Cancel</a>
                                <button type="submit" class="btn btn-primary">Add Trading Pair</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

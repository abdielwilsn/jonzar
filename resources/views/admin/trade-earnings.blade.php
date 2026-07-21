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
                    <h1 class="title1 text-{{ $text }}">Trade Earnings</h1>
                    <small class="text-{{ $text }}">Net profit users have made from their trades, ranked by top earners.</small>
                </div>
                <x-danger-alert />
                <x-success-alert />

                <!-- Summary cards -->
                <div class="mb-4 row">
                    <div class="col-md-6">
                        <div class="card shadow bg-{{ Auth('admin')->User()->dashboard_style }}">
                            <div class="card-body">
                                <h5 class="text-{{ $text }}">Total Platform Earnings</h5>
                                <h2 class="text-primary mb-0">{{ $settings->currency }}{{ number_format($platformTotal, 2) }}</h2>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card shadow bg-{{ Auth('admin')->User()->dashboard_style }}">
                            <div class="card-body">
                                <h5 class="text-{{ $text }}">Earning Users</h5>
                                <h2 class="text-primary mb-0">{{ number_format($earnersCount) }}</h2>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12 card shadow p-4 bg-{{ Auth('admin')->User()->dashboard_style }}">
                        @if ($earners->isEmpty())
                            <div class="alert alert-info text-{{ $text }} mb-0">
                                No trade earnings recorded yet.
                            </div>
                        @else
                            <div class="table-responsive" data-example-id="hoverable-table">
                                <table id="ShipTable" class="table table-hover text-{{ $text }}">
                                    <thead>
                                        <tr>
                                            <th>Rank</th>
                                            <th>Client name</th>
                                            <th>Client email</th>
                                            <th>Closed trades</th>
                                            <th>Total earnings</th>
                                            <th>Option</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($earners as $earner)
                                            <tr>
                                                <th scope="row">{{ $loop->iteration }}</th>
                                                <td>{{ optional($earner->user)->name ?? 'Deleted user' }}</td>
                                                <td>{{ optional($earner->user)->email ?? '—' }}</td>
                                                <td>{{ number_format($earner->trades_count) }}</td>
                                                <td>{{ $settings->currency }}{{ number_format($earner->total_profit, 2) }}</td>
                                                <td>
                                                    @if ($earner->user)
                                                        <a href="{{ route('admin.user-trades', $earner->user->id) }}" class="btn btn-primary btn-sm">
                                                            View trades
                                                        </a>
                                                    @endif
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
        </div>
    </div>
@endsection

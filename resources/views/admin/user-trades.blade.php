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
                <x-danger-alert />
                <x-success-alert />
                <!-- Beginning of User Trades -->
                <div class="row">
                    <div class="col-md-12">
                        <div class="p-3 card bg-{{ $bg }}">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <h1 class="d-inline text-primary">{{ $user->name }}'s Trades</h1>
                                        <div class="d-inline">
                                            <div class="float-right btn-group">
                                                <a class="btn btn-primary btn-sm" href="{{ route('manageusers') }}"> <i class="fa fa-arrow-left"></i> Back to Users</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-4">
                                    @if ($investments->isEmpty())
                                        <div class="alert alert-info text-{{ $text }}">
                                            No trades found for this user.
                                        </div>
                                    @else
                                        <div class="table-responsive">
                                            <table class="table text-{{ $text }}">
                                                <thead>
                                                    <tr>
                                                        <th>Trading Pair</th>
                                                        <th>Amount</th>
                                                        <th>Status</th>
                                                        <th>Start Date</th>
                                                        <th>End Date</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($investments as $investment)
                                                        <tr>
                                                            <td>{{ $investment->tradingPair->pair_name }}</td>
                                                            <td>{{ $settings->currency }}{{ number_format($investment->amount, 2) }}</td>
                                                            <td>
                                                                @if ($investment->status == 'active')
                                                                    <span class="badge badge-success">Active</span>
                                                                @else
                                                                    <span class="badge badge-secondary">{{ ucfirst($investment->status) }}</span>
                                                                @endif
                                                            </td>
                                                            <td>{{ \Carbon\Carbon::parse($investment->start_date)->toDayDateTimeString() }}</td>
                                                            <td>{{ \Carbon\Carbon::parse($investment->end_date)->toDayDateTimeString() }}</td>
                                                            <td>
                                                                <form action="{{ route('admin.user-trades.delete', $investment->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this trade? This will refund {{ $settings->currency }}{{ number_format($investment->amount, 2) }} to the user\'s balance.');">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit" class="btn btn-danger btn-sm">
                                                                        <i class="fa fa-trash"></i> Delete
                                                                    </button>
                                                                </form>
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
            </div>
        </div>
    @include('admin.Users.users_actions')
@endsection
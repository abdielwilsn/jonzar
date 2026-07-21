<?php
if (Auth('admin')->User()->dashboard_style == "light") {
    $bg = "light";
    $text = "dark";
    $gradient = "primary";
} else {
    $bg = "dark";
    $text = "light";
    $gradient = "dark";
}
?>

@extends('layouts.app')
@section('content')
    @include('admin.topmenu')
    @include('admin.sidebar')
    <style>
        .admin-dashboard-stats {
            margin-left: -10px;
            margin-right: -10px;
            padding: 10px 6px 0 !important;
            border-radius: 14px;
        }

        .admin-dashboard-stats [class*="col-"] {
            padding-left: 10px;
            padding-right: 10px;
            margin-bottom: 20px;
        }

        .admin-dashboard-stats [class*="col"] .card:before {
            display: none !important;
        }

        .admin-dashboard-stats .card {
            margin-bottom: 0 !important;
            border-width: 1px !important;
            box-shadow: var(--shadow) !important;
        }

        .admin-dashboard-stats .card-stats .card-body {
            padding: 18px !important;
        }

        .admin-dashboard-stats .card-stats .col-stats {
            padding-left: 10px;
        }

        .admin-dashboard-stats .card-stats .card-category {
            margin-bottom: 6px;
            line-height: 1.25;
        }

        .admin-dashboard-stats .numbers {
            line-height: 1.35;
        }

        @media (max-width: 767.98px) {
            .admin-dashboard-stats {
                margin-left: -6px;
                margin-right: -6px;
                padding: 8px 4px 0 !important;
            }

            .admin-dashboard-stats [class*="col-"] {
                padding-left: 6px;
                padding-right: 6px;
                margin-bottom: 12px;
            }

            .admin-dashboard-stats .card-stats .card-body {
                padding: 14px !important;
            }
        }
    </style>
    <div class="main-panel">
        <div class="content bg-{{$bg}}">
            <div class="panel-header bg-{{$gradient}}-gradient">
                <div class="py-5 page-inner">
                    <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row">
                        <div>
                            <h2 class="pb-2 text-white fw-bold">Dashboard</h2>
                            <h5 class="mb-2 text-white op-7">Welcome, {{ Auth('admin')->User()->firstName }} {{ Auth('admin')->User()->lastName }}!</h5>
                        </div>
                        <div class="py-2 ml-md-auto py-md-0">
                            <a href="{{route('mdeposits')}}" class="mr-2 btn btn-success btn-border">Deposits</a>
                            @if (Auth('admin')->User()->type == "Super Admin" || Auth('admin')->User()->type == "Admin")

                            <a href="{{route('mwithdrawals')}}" class="mr-2 btn btn-danger btn-border">Withdrawals</a>

                            @endif
                            <a href="{{route('manageusers')}}" class="btn btn-secondary">Users</a>
                        </div>
                    </div>
                </div>
            </div>
            <x-danger-alert/>
            <x-success-alert/>
            <div class="page-inner mt--5">
                <!-- Beginning of Dashboard Stats -->
{{--                @if (Auth('admin')->User()->type == "Super Admin" || Auth('admin')->User()->type == "Admin")--}}
                    <div class="row row-card-no-pd admin-dashboard-stats bg-{{$bg}} shadow-lg mt--2">


                        @if (Auth('admin')->User()->type == "Super Admin" || Auth('admin')->User()->type == "Admin")

                        <div class="col-sm-6 col-md-3">
                            <div class="card card-stats card-round bg-{{$bg}} full-height">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-4">
                                            <div class="text-center icon-big">
                                                <i class="fa fa-download text-warning"></i>
                                            </div>
                                        </div>
                                        <div class="col-8 col-stats">
                                            <div class="numbers">
                                                <p class="card-category">Total Deposit</p>
                                                @foreach($total_deposited as $deposited)
                                                    @if(!empty($deposited->count))
                                                        {{$settings->currency}}{{number_format($deposited->count)}}
                                                    @else
                                                        {{$settings->currency}}0.00
                                                    @endif
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @endif
                        <div class="col-sm-6 col-md-3">
                            <div class="card card-stats card-round bg-{{$bg}} full-height">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-4">
                                            <div class="text-center icon-big">
                                                <i class="flaticon-download text-info"></i>
                                            </div>
                                        </div>
                                        <div class="col-8 col-stats">
                                            <div class="numbers">
                                                <p class="card-category">Pending Deposit(s)</p>
                                                @foreach($pending_deposited as $deposited)
                                                    @if(!empty($deposited->count))
                                                        {{$settings->currency}}{{number_format($deposited->count)}}
                                                    @else
                                                        {{$settings->currency}}0.00
                                                    @endif
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                            @if (Auth('admin')->User()->type == "Super Admin" || Auth('admin')->User()->type == "Admin")

                            <div class="col-sm-6 col-md-3">
                            <div class="card card-stats card-round bg-{{$bg}} full-height">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-4">
                                            <div class="text-center icon-big">
                                                <i class="flaticon-arrows-1 text-danger"></i>
                                            </div>
                                        </div>
                                        <div class="col-8 col-stats">
                                            <div class="numbers">
                                                <p class="card-category">Total Withdrawal</p>
                                                @foreach($total_withdrawn as $deposited)
                                                    @if(!empty($deposited->count))
                                                        {{$settings->currency}}{{number_format($deposited->count)}}
                                                    @else
                                                        {{$settings->currency}}0.00
                                                    @endif
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="col-sm-6 col-md-3">
                            <div class="card card-stats card-round bg-{{$bg}} full-height">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-4">
                                            <div class="text-center icon-big">
                                                <i class="flaticon-arrow text-secondary"></i>
                                            </div>
                                        </div>
                                        <div class="col-8 col-stats">
                                            <div class="numbers">
                                                <p class="card-category">Pending Withdrawal</p>
                                                @foreach($pending_withdrawn as $deposited)
                                                    @if(!empty($deposited->count))
                                                        {{$settings->currency}}{{number_format($deposited->count)}}
                                                    @else
                                                        {{$settings->currency}}0.00
                                                    @endif
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-3">
                            <div class="card card-stats card-round bg-{{$bg}}">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-4">
                                            <div class="text-center icon-big">
                                                <i class="flaticon-users text-success"></i>
                                            </div>
                                        </div>
                                        <div class="col-8 col-stats">
                                            <div class="numbers">
                                                <p class="card-category">Total Users</p>
                                                <h4 class="card-title text-{{$text}}">{{number_format($user_count)}}</h4>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                            @endif
                        <div class="col-sm-6 col-md-3">
                            <div class="card card-stats card-round bg-{{$bg}}">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-4">
                                            <div class="text-center icon-big">
                                                <i class="flaticon-remove-user text-danger"></i>
                                            </div>
                                        </div>


                                        <div class="col-8 col-stats">
                                            <div class="numbers">
                                                <p class="card-category">Block Users</p>
                                                <h4 class="card-title text-{{$text}}">{{$blockeusers}}</h4>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                            @if (Auth('admin')->User()->type == "Super Admin" || Auth('admin')->User()->type == "Admin")

                                <div class="col-sm-6 col-md-3">
                            <div class="card card-stats card-round bg-{{$bg}}">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-4">
                                            <div class="text-center icon-big">
                                                <i class="flaticon-user-2 text-success"></i>
                                            </div>
                                        </div>
                                        <div class="col-8 col-stats">
                                            <div class="numbers">
                                                <p class="card-category">Active Users</p>
                                                <h4 class="card-title text-{{$text}}">{{$activeusers}}</h4>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                            @endif
                        <div class="col-sm-6 col-md-3">
                            <div class="card card-stats card-round bg-{{Auth('admin')->User()->dashboard_style}}">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-4">
                                            <div class="text-center icon-big">
                                                <i class="flaticon-diagram text-warning"></i>
                                            </div>
                                        </div>
                                        <div class="col-8 col-stats">
                                            <div class="numbers">
                                                <p class="card-category">Trading Plans</p>
                                                <h4 class="card-title text-{{$text}}">{{$plans}}</h4>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                @if (Auth('admin')->User()->type == "Super Admin" || Auth('admin')->User()->type == "Admin")

                <div class="row">
                        <div class="col-md-12">
                            <div class="overflow-auto">
                                <canvas id="myChart" height="100" class="text-{{$text}}"></canvas>
                            </div>
                            <script>
                                var ctx = document.getElementById('myChart').getContext('2d');
                                var myChart = new Chart(ctx, {
                                    type: 'bar',
                                    data: {
                                        labels: ['Deposit', 'Pending Deposit', 'Withdrawal', 'Pending Withdrawal', 'Total Transactions'],
                                        datasets: [{
                                            label: "# System Statistics in {{$settings->currency}}",
                                            data: [
                                                "{{$chart_pdepsoit}}",
                                                "{{$chart_pendepsoit}}",
                                                "{{$chart_pwithdraw}}",
                                                "{{$chart_pendwithdraw}}",
                                                "{{$chart_trans}}"
                                            ],
                                            backgroundColor: [
                                                'rgba(255, 99, 132, 0.2)',
                                                'rgba(54, 162, 235, 0.2)',
                                                'rgba(255, 206, 86, 0.2)',
                                                'rgba(75, 192, 192, 0.2)',
                                                'rgba(153, 102, 255, 0.2)'
                                            ],
                                            borderColor: [
                                                'rgba(255, 99, 132, 1)',
                                                'rgba(54, 162, 235, 1)',
                                                'rgba(255, 206, 86, 1)',
                                                'rgba(75, 192, 192, 1)',
                                                'rgba(153, 102, 255, 1)'
                                            ],
                                            borderWidth: 1
                                        }]
                                    },
                                    options: {
                                        scales: {
                                            y: {
                                                beginAtZero: true
                                            }
                                        }
                                    }
                                });
                            </script>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

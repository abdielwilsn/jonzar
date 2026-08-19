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
    <div class="main-panel">
        <div class="content bg-{{Auth('admin')->User()->dashboard_style}} ">
            <div class="page-inner">
                <div class="mt-2 mb-4">
                    <h1 class="title1 text-{{$text}}">Manage clients deposits</h1>
                </div>
                <x-danger-alert/>
                <x-success-alert/>
                <div class="mb-5 row">
                    <div class="col-12">
                        <small class="text-{{$text}}">if you can't see the image, try switching your uploaded location to another option from your admin settings page.</small>
                    </div>
                    <div class="col-12 card shadow p-4 bg-{{Auth('admin')->User()->dashboard_style}}">
                        <div class="table-responsive" data-example-id="hoverable-table">
                            <table id="ShipTable" class="table table-hover text-{{$text}}">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Client name</th>
                                    <th>Client email</th>
                                    <th>Amount</th>
                                    <th>Payment Method</th>
                                    <th>Status</th>
                                    <th>Date created</th>
                                    <th>Option</th>
                                </tr>
                                </thead>
                                <tbody>
                                @if (Auth('admin')->User()->type == "Super Admin" || Auth('admin')->User()->type == "Admin")
                                    @foreach($deposits as $deposit)
                                        <tr>
                                            <th scope="row">{{$deposit->id}}</th>
                                            <td>{{$deposit->duser->name}}</td>
                                            <td>{{$deposit->duser->email}}</td>
                                            <td>{{$settings->currency}}{{number_format($deposit->amount)}}</td>
                                            <td>{{$deposit->payment_mode}}</td>
                                            <td>
                                                @if ($deposit->status == "Processed")
                                                    <span class="badge badge-success">{{$deposit->status}}</span>
                                                @else
                                                    <span class="badge badge-danger">{{$deposit->status}}</span>
                                                @endif
                                            </td>
                                            <td>{{\Carbon\Carbon::parse($deposit->created_at)->toDayDateTimeString()}}</td>
                                            <td>
                                                <a href="{{route('viewdepositimage', $deposit->id)}}" class="btn btn-{{$text}} btn-sm m-1" title="View payment screenshot">
                                                    <i class="fa fa-eye"></i>
                                                </a>
                                                <form action="{{route('deldeposit', $deposit->id)}}" method="POST" class="d-inline"
                                                      data-confirm="This will permanently delete deposit #{{$deposit->id}} ({{$settings->currency}}{{number_format($deposit->amount)}}) for {{$deposit->duser->name}}. This cannot be undone."
                                                      data-confirm-title="Delete this deposit?" data-confirm-button="Yes, delete it">
                                                    @csrf
                                                    <button type="submit" class="m-1 btn btn-danger btn-sm">Delete</button>
                                                </form>
                                                @if($deposit->status != "Processed")
                                                    <form action="{{route('pdeposit', $deposit->id)}}" method="POST" class="d-inline"
                                                          data-confirm="This will approve deposit #{{$deposit->id}} of {{$settings->currency}}{{number_format($deposit->amount)}} for {{$deposit->duser->name}} and credit their balance."
                                                          data-confirm-title="Approve this deposit?" data-confirm-button="Yes, approve it" data-confirm-danger="false">
                                                        @csrf
                                                        <button type="submit" class="btn btn-primary btn-sm">Process</button>
                                                    </form>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    @foreach($deposits->where('status', '!=', 'Processed') as $deposit)
                                        <tr>
                                            <th scope="row">{{$deposit->id}}</th>
                                            <td>{{$deposit->duser->name}}</td>
                                            <td>{{$deposit->duser->email}}</td>
                                            <td>{{$settings->currency}}{{number_format($deposit->amount)}}</td>
                                            <td>{{$deposit->payment_mode}}</td>
                                            <td>
                                                <span class="badge badge-danger">{{$deposit->status}}</span>
                                            </td>
                                            <td>{{\Carbon\Carbon::parse($deposit->created_at)->toDayDateTimeString()}}</td>
                                            <td>
                                                <a href="{{route('viewdepositimage', $deposit->id)}}" class="btn btn-{{$text}} btn-sm m-1" title="View payment screenshot">
                                                    <i class="fa fa-eye"></i>
                                                </a>
                                                <form action="{{route('deldeposit', $deposit->id)}}" method="POST" class="d-inline"
                                                      data-confirm="This will permanently delete deposit #{{$deposit->id}} ({{$settings->currency}}{{number_format($deposit->amount)}}) for {{$deposit->duser->name}}. This cannot be undone."
                                                      data-confirm-title="Delete this deposit?" data-confirm-button="Yes, delete it">
                                                    @csrf
                                                    <button type="submit" class="m-1 btn btn-danger btn-sm">Delete</button>
                                                </form>
                                                <form action="{{route('pdeposit', $deposit->id)}}" method="POST" class="d-inline"
                                                      data-confirm="This will approve deposit #{{$deposit->id}} of {{$settings->currency}}{{number_format($deposit->amount)}} for {{$deposit->duser->name}} and credit their balance."
                                                      data-confirm-title="Approve this deposit?" data-confirm-button="Yes, approve it" data-confirm-danger="false">
                                                    @csrf
                                                    <button type="submit" class="btn btn-primary btn-sm">Process</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

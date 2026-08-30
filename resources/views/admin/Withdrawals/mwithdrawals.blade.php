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
        <div class="content bg-{{Auth('admin')->User()->dashboard_style}}">
            <div class="page-inner">
                <div class="mt-2 mb-4">
                    <h1 class="title1 text-{{$text}}">Manage Clients Withdrawals</h1>
                </div>
                <x-danger-alert/>
                <x-success-alert/>

                <div class="mb-5 row">
                    <div class="col card p-3 shadow bg-{{Auth('admin')->User()->dashboard_style}}">
                        <div class="bs-example widget-shadow table-responsive" data-example-id="hoverable-table">
                            <table id="ShipTable" class="table table-hover text-{{$text}}">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Client Name</th>
                                        <th>Amount</th>
                                        <th>Payment Mode</th>
                                        <th>Wallet Address</th>
                                        <th>Network</th>
                                        <th>Notes</th>
                                        <th>Status</th>
                                        <th>Date Created</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($withdrawals as $withdrawal)
                                    <tr>
                                        <th scope="row">{{$withdrawal->id}}</th>
{{--                                        /admin/dashboard/user-details/58--}}
{{--                                        <a href=""></a>--}}

                                        <td>
                                            <a href="{{ route('viewuser', $withdrawal->user->id) }}" class="text-{{$text}}">
                                                {{ $withdrawal->user->name }}
                                            </a>
                                        </td>

{{--                                        <td>{{$withdrawal->user->name}}</td>--}}
                                        <td>{{$settings->currency}}{{number_format($withdrawal->amount, 2)}}</td>
                                        <td>{{$withdrawal->payment_mode ?? 'N/A'}}</td>
                                        <td>{{$withdrawal->wallet_address ?? 'N/A'}}</td>
                                        <td>{{$withdrawal->network ?? 'N/A'}}</td>
                                        <td>{{$withdrawal->notes ?? '—'}}</td>
                                        <td>
                                            @if ($withdrawal->status == "Processed")
                                                <span class="badge badge-success">{{$withdrawal->status}}</span>
                                            @else
                                                <span class="badge badge-danger">{{$withdrawal->status}}</span>
                                            @endif
                                        </td>
                                        <td>{{\Carbon\Carbon::parse($withdrawal->created_at)->toDayDateTimeString()}}</td>
                                        <td>
                                            @if (Auth('admin')->User()->type === 'Super Admin')
                                                <a href="{{route('processwithdraw',$withdrawal->id)}}" class="m-1 btn btn-info btn-sm">
                                                    <i class="fa fa-eye"></i> Process
                                                </a>
                                            @endif
                                            <form action="{{ route('deletewithdrawal', $withdrawal->id) }}" method="POST" class="d-inline"
                                                  data-confirm="This will permanently delete withdrawal #{{$withdrawal->id}} ({{$settings->currency}}{{number_format($withdrawal->amount, 2)}}) for {{$withdrawal->user->name}}."
                                                  data-confirm-title="Delete this withdrawal?" data-confirm-button="Yes, delete it">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="m-1 btn btn-danger btn-sm">
                                                    <i class="fa fa-trash"></i> Delete
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
@endsection

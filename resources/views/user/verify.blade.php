<?php
	if (Auth::user()->dashboard_style == "light") {
		$bg="light";
		$text = "dark";
	} else {
		$bg="dark";
		$text = "light";
	}
?>
@extends('layouts.app')
    @section('content')
        @include('user.topmenu')
        @include('user.sidebar')
        <div class="main-panel bg-{{$bg}}">
            <div class="content bg-{{$bg}}">
                <div class="page-inner">
                    <div class="mt-2 mb-5">
                        <h1 class="title1 text-{{$text}}">Account Verification</h1> <br> <br>
                    </div>
                    <x-danger-alert/>
					<x-success-alert/>
                    <div class="mb-5 row">
                        <div class="col-lg-8 offset-lg-2 card p-4 shadow-lg bg-{{$bg}}">
                            <div class="py-3">
                                <h5 class=" text-{{$text}}">KYC verification - Upload documents below to get verified.</h5>
                            </div>
                           <form role="form" method="post" action="{{route('kycsubmit')}}" enctype="multipart/form-data">
    <h5 class="text-{{$text}}">Enter your ID Number <small>(NIN, Voter’s Card, Passport No, etc.)</small></h5>
    <input type="text" class="form-control bg-{{$bg}} text-{{$text}}" name="id_number" placeholder="e.g. 1234 5678 9012" required>

@error('id_number')
    <span class="text-danger">{{ $message }}</span>
@enderror
<br>

    <h5 class="text-{{$text}}">Valid identity card <small>(Driver’s Licence, International Passport, or any government-approved document)</small></h5>
    <input type="file" class="form-control bg-{{$bg}} text-{{$text}}" name="idcard" required><br>

    <h5 class="text-{{$text}}">Passport Photograph</h5>
    <input type="file" class="form-control bg-{{$bg}} text-{{$text}}" name="passport" required><br>

    <input type="hidden" name="_token" value="{{ csrf_token() }}">
    <input type="submit" class="btn btn-{{$text}}" value="Submit documents">
</form>

                        </div>
                    </div>
                </div>
            </div>
    @endsection

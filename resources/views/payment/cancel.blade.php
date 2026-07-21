{{-- resources/views/payment/cancel.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header text-warning">{{ __('Payment Cancelled') }}</div>

                <div class="card-body text-center">
                    <div class="alert alert-warning">
                        <h4>{{ __('Payment Cancelled') }}</h4>
                        <p>{{ $message }}</p>
                    </div>

                    <div class="mt-4">
                        <a href="{{ route('payment.form') }}" class="btn btn-primary">{{ __('Try Again') }}</a>
                        <a href="{{ url('/') }}" class="btn btn-secondary">{{ __('Back to Home') }}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
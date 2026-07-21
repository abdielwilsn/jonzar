{{-- resources/views/payment/partial.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header text-info">{{ __('Partial Payment') }}</div>

                <div class="card-body text-center">
                    <div class="alert alert-info">
                        <h4>{{ __('Partially Paid') }}</h4>
                        <p>{{ $message }}</p>
                        <p>{{ __('Order ID:') }} {{ $order_id }}</p>
                    </div>

                    <div class="mt-4">
                        <a href="{{ route('payment.form') }}" class="btn btn-primary">{{ __('Complete Payment') }}</a>
                        <a href="{{ url('/') }}" class="btn btn-secondary">{{ __('Back to Home') }}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
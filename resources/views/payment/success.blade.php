{{-- resources/views/payment/success.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header text-success">{{ __('Payment Successful') }}</div>

                <div class="card-body text-center">
                    <div class="alert alert-success">
                        <h4>{{ __('Thank you!') }}</h4>
                        <p>{{ $message }}</p>
                    </div>

                    @if(isset($details) && !empty($details))
                        <div class="mt-4">
                            <h5>{{ __('Payment Details') }}</h5>
                            <div class="text-left">
                                @foreach($details as $key => $value)
                                    <p><strong>{{ ucfirst(str_replace('_', ' ', $key)) }}:</strong> {{ $value }}</p>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <div class="mt-4">
                        <a href="{{ route('payment.form') }}" class="btn btn-primary">{{ __('Make Another Payment') }}</a>
                        <a href="{{ url('/') }}" class="btn btn-secondary">{{ __('Back to Home') }}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
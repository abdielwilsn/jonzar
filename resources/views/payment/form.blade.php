@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">

            @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <div class="card shadow rounded">
                <div class="card-header text-center">
                    <h4>Pay with Crypto</h4>
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route('crypto.create') }}">
                        @csrf

                        <div class="form-group mb-3">
                            <label for="amount">Amount (USD):</label>
                            <input type="number" step="0.01" min="1" name="amount" id="amount" class="form-control" required>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">Pay Now</button>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection

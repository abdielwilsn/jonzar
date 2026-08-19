{{--  --}}
@extends('layouts.app')
@section('content')
    <style>
        /* Make the text inside the form boxes black on a white field, overriding
           the global dark-theme .form-control rules (which use !important). */
        .pwithdrawal-page .form-control,
        .pwithdrawal-page input.form-control,
        .pwithdrawal-page select.form-control,
        .pwithdrawal-page textarea.form-control {
            color: #000 !important;
            background: #fff !important;
            border: 1px solid #ced4da !important;
        }
        .pwithdrawal-page .form-control:focus {
            color: #000 !important;
            background: #fff !important;
            border-color: #2563eb !important;
        }
        .pwithdrawal-page .form-control::placeholder {
            color: #6c757d !important;
        }

        /* Make the details card a light panel with dark, readable headings/labels,
           so the wallet/network/amount section is clearly visible in dark mode. */
        .pwithdrawal-page .card {
            background: #ffffff !important;
        }
        .pwithdrawal-page .card h4,
        .pwithdrawal-page .card h5,
        .pwithdrawal-page .card h6,
        .pwithdrawal-page .card label {
            color: #212529 !important;
        }
        .pwithdrawal-page .card .text-success {
            color: #198754 !important;
        }

        .textba {
            color: black !important;
        }
    </style>
    @include('admin.topmenu')
    @include('admin.sidebar')
    <div class="main-panel bg-{{$bg}} pwithdrawal-page">
        <div class="content bg-{{$bg}}">
            <div class="page-inner">
                <div class="mt-2 mb-5">
                    <h1 class="title1 d-inline text-{{$text}}">Process Withdrawal Request</h1>
                    <div class="d-inline">
                        <div class="float-right btn-group">
                            <a class="btn btn-primary btn-sm" href="{{route('mwithdrawals')}}">
                                <i class="fa fa-arrow-left"></i> Back
                            </a>
                            @if (Auth('admin')->User()->type === 'Super Admin')
                                <form action="{{ route('deletewithdrawal', $withdrawal->id) }}" method="POST" class="d-inline ml-2"
                                      data-confirm="This will permanently delete withdrawal #{{$withdrawal->id}} ({{$settings->currency}}{{number_format($withdrawal->amount, 2)}}) for {{$withdrawal->user->name}}."
                                      data-confirm-title="Delete this withdrawal?" data-confirm-button="Yes, delete it">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">
                                        <i class="fa fa-trash"></i> Delete
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
                <x-danger-alert/>
                <x-success-alert/>

                <div class="mb-5 row">
                    <div class="col-lg-8 offset-lg-2 card p-md-4 p-2 bg-{{$bg}} shadow">
                        <div class="mb-3">
                            @if ($withdrawal->status != "Processed")
                                <h4 class="text-{{$text}}">Send funds to {{$withdrawal->user->name}} using the wallet details below:</h4>
                            @else
                                <h4 class="text-success">Payment Completed</h4>
                            @endif
                        </div>

                        {{-- Wallet Details --}}
                        <div class="mb-3 form-group">
                            <h5 class="text-{{$text}} textba">Wallet Address</h5>
                            <input type="text" class="form-control text-{{$text}} bg-{{$bg}}" value="{{$withdrawal->wallet_address}}" readonly>
                        </div>

                        <div class="mb-3 form-group">
                            <h5 class="text-{{$text}} textba">Network</h5>
                            <input type="text" class="form-control text-{{$text}} bg-{{$bg}}" value="{{$withdrawal->network}}" readonly>
                        </div>

                        @if(!empty($withdrawal->notes))
                        <div class="mb-3 form-group">
                            <h5 class="text-{{$text}} textba">User Notes</h5>
                            <textarea class="form-control text-{{$text}} bg-{{$bg}}" rows="3" readonly>{{$withdrawal->notes}}</textarea>
                        </div>
                        @endif

                        <div class="mb-3 form-group">
                            <h5 class="text-{{$text}} textba">Amount</h5>
                            <input type="text" class="form-control text-{{$text}} bg-{{$bg}}" value="{{$settings->currency}}{{number_format($withdrawal->amount, 2)}}" readonly>
                        </div>

                        {{-- Action Form --}}
                        @if ($withdrawal->status != "Processed")
                            <div class="mt-3">
                                <form action="{{route('pwithdrawal')}}" method="POST" id="processWithdrawalForm" data-submitted="false">
                                    @csrf
                                    <div class="form-group">
                                        <h6 class="text-{{$text}}">Action</h6>
                                        <select name="action" id="action" class="bg-{{$bg}} text-{{$text}} mb-2 form-control">
                                            <option value="Paid">Mark as Paid</option>
                                            <option value="Reject">Reject</option>
                                        </select>
                                    </div>

                                    <div class="form-row d-none" id="emailcheck">
                                        <div class="col-md-12 form-group">
                                            <div class="selectgroup">
                                                <label class="selectgroup-item">
                                                    <input type="radio" name="emailsend" id="dontsend" value="false" class="selectgroup-input" checked>
                                                    <span class="selectgroup-button">Don't Send Email</span>
                                                </label>
                                                <label class="selectgroup-item">
                                                    <input type="radio" name="emailsend" id="sendemail" value="true" class="selectgroup-input">
                                                    <span class="selectgroup-button">Send Email</span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-row d-none" id="emailtext">
                                        <div class="form-group col-md-12">
                                            <h6 class="text-{{$text}}">Subject</h6>
                                            <input type="text" name="subject" id="subject" class="bg-{{$bg}} text-{{$text}} form-control">
                                        </div>
                                        <div class="form-group col-md-12">
                                            <h6 class="text-{{$text}}">Reason for Rejection</h6>
                                            <textarea class="bg-{{$bg}} text-{{$text}} form-control" rows="3" name="reason" id="message"></textarea>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <input type="hidden" name="id" value="{{$withdrawal->id}}">
                                        <input type="submit" class="px-3 btn btn-primary" value="Process">
                                    </div>
                                </form>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Script to show/hide rejection email fields --}}
        <script>
            const action = document.getElementById('action');

            $('#action').change(function(){
                if (action.value === "Reject") {
                    $('#emailcheck').removeClass('d-none');
                } else {
                    $('#emailcheck').addClass('d-none');
                    $('#emailtext').addClass('d-none');
                    $('#dontsend').prop('checked', true);
                    $('#subject, #message').removeAttr('required');
                }
            });

            $('#sendemail').click(function(){
                $('#emailtext').removeClass('d-none');
                $('#subject, #message').attr('required', '');
            });

            $('#dontsend').click(function(){
                $('#emailtext').addClass('d-none');
                $('#subject, #message').removeAttr('required');
            });

            // Confirm before submitting the Paid/Reject action, since either
            // choice has an irreversible side effect (payout marked sent, or
            // the withdrawal is refunded and deleted).
            const processForm = document.getElementById('processWithdrawalForm');
            if (processForm) {
                processForm.addEventListener('submit', function (e) {
                    if (processForm.dataset.submitted === 'true') {
                        return true;
                    }
                    e.preventDefault();

                    const isReject = action.value === 'Reject';
                    swal({
                        title: isReject ? 'Reject this withdrawal?' : 'Mark this withdrawal as paid?',
                        text: isReject
                            ? 'This will reject the withdrawal, refund {{ $settings->currency }}{{ number_format($withdrawal->amount, 2) }} to {{ $withdrawal->user->name }}\'s balance, and delete this request.'
                            : 'Only confirm this once you have actually sent {{ $settings->currency }}{{ number_format($withdrawal->amount, 2) }} to {{ $withdrawal->user->name }}. This cannot be undone.',
                        icon: 'warning',
                        dangerMode: true,
                        buttons: ['Cancel', isReject ? 'Yes, reject it' : 'Yes, mark as paid'],
                    }).then(function (confirmed) {
                        if (confirmed) {
                            processForm.dataset.submitted = 'true';
                            processForm.submit();
                        }
                    });
                });
            }
        </script>
@endsection

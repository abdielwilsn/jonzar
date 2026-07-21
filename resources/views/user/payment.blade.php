<?php
	if (Auth::user()->dashboard_style == "light") {
		$bg="light";
		$text = "dark";
	} else {
		$bg="dark";
		$text = "white";
	}
?>
@extends('layouts.app')
{{-- @section('styles')
    @parent
	<link rel="stylesheet" href="{{asset('dash/css/stripeglobal.css')}}">
	<link rel="stylesheet" href="{{asset('dash/css/stripenormalize.css')}}">
@endsection --}}

    @section('content')
        @include('user.topmenu')
        @include('user.sidebar')
        <div class="main-panel payment-page" data-theme="{{ $bg }}">
			<div class="content">
				<div class="page-inner">
					<div class="pay-topbar">
						<a href="{{ route('deposits') }}" class="pay-back" title="Back to deposit"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 12H5M12 19l-7-7 7-7"/></svg></a><h1 class="title1">Complete payment</h1>

					</div>
					<x-danger-alert/>
					<x-success-alert/>
					<x-error-alert/>
					@if($title != "Complete Payment")
						<div class="pay-hero">
							<span class="pay-hero-label">Amount to pay</span>
							<div class="pay-hero-amt">{{$settings->currency}}{{number_format($amount)}}</div>
							<div class="pay-hero-method">
								@if(!empty($payment_mode->img_url))<img src="{{$payment_mode->img_url}}" alt="">@endif
								{{ $payment_mode->name }}
							</div>
						</div>
					@endif
					<div class="row">

						<div class="col-md-8 offset-md-2">


							<div class="card bg-{{$bg}} shadow-lg p-2 p-md-4">
							    {{-- <div><button class="px-5 btn btn-primary btn-lg"  onclick="window.location.href='https://metafxcrypto.com/howtobuy';">How To Buy Crypto</button></div> --}}

															    {{-- <div><button class="px-5 btn btn-primary btn-lg"> Buy Crypto</button></div> --}}

								<div class="card-body">
									@if($title !="Complete Payment")
										@php
											if($payment_mode->name == "Bitcoin"){
												$coin = 'BTC';
											}elseif ($payment_mode->name == "Litecoin") {
												$coin = 'LTC';
											}else {
												$coin = 'ETH';
											}
										@endphp
										<div>
											<h4 class="text-{{$text}}">You are to make payment of <strong>{{$settings->currency}}{{number_format($amount)}}</strong> using your selected payment method. Screenshot and upload the proof of payment</h4>
											<h4>
												@if (!empty($payment_mode->img_url))
												<img src="{{$payment_mode->img_url}}" alt="" class="w-25" >
												@endif
												<strong class="text-{{$text}}">{{$payment_mode->name}}</strong>
											</h4>

											<p style="margin:auto; width:50%;padding:10px;">
											   <p class="text-{{$text}}">



</p>
										</div>

										<div class="mt-5">
											@if($settings->deposit_option != "manual")
												@if ($payment_mode->name == "Bitcoin" or $payment_mode->name == "Litecoin" or $payment_mode->name == "Ethereum")
												<a href="{{ url('dashboard/cpay') }}/{{$amount}}/{{$coin}}/{{ Auth::user()->id }}/new" class="btn btn-{{$text}}">Pay Via Coinpayment</a>
												@else
													@if ((!empty($payment_mode->barcode) or $payment_mode->barcode != NULL) and $payment_mode->methodtype != 'currency')
													<div class="text-center">
														<img src="{{ asset('storage/app/public/photos/'.$payment_mode->barcode)}}" alt="" class="w-50">


													</div>
													@endif
												@endif
											@endif
											@if ($payment_mode->methodtype != 'currency')
												<h3 class="text-{{$text}}">
													<strong>Copy the {{$payment_mode->name}} Address below and proceed to make payment:</strong>
												</h3>
												<div class="form-group">
    												<div class="mb-3 input-group">
    													<input type="text" class="form-control myInput readonly text-black bg-white" value="{{$payment_mode->wallet_address}}" id="myInput" readonly>
    													<div class="input-group-append">
    														<button class="btn btn-outline-secondary" onclick="myFunction()" type="button" id="button-addon2"><i class="fas fa-copy"></i></button>
    													</div>

    												</div>
    													<small class="text-{{$text}}"><strong>Network Type:</strong> {{$payment_mode->network}}</small>
												</div>

												@else
												<h3 class="text-{{$text}}">
													<strong>{{$payment_mode->name}}:</strong>
												</h3>
													@if ($payment_mode->defaultpay == 'yes')
														@if ($payment_mode->name == "Paystack")
															<?php $payamount = $amount * 100  ?>
															<div id="paystack">
																<form method="POST" action="{{ route('pay.paystack') }}" accept-charset="UTF-8" class="form-horizontal" role="form">
																	<input type="hidden" name="email" value="{{auth::user()->email}}">
																	<input type="hidden" name="amount" value="{{$payamount}}">
																	<input type="hidden" name="currency" value="{{$settings->s_currency}}">
																	<input type="hidden" name="metadata" value="{{ json_encode($array = ['key_name' => 'value',]) }}" >
																	<input type="hidden" name="reference" value="{{ Paystack::genTranxRef() }}">
																	<input type="hidden" name="_token" value="{{ csrf_token() }}">
																	<p>
																	<button class="py-2 btn btn-primary" type="submit" value="Pay Now!">
																	<i class="fa fa-credit-card fa-lg"></i> Pay with Paystack
																	</button>
																	</p>
																</form>
															</div>
														@endif
														@if ($payment_mode->name == "Stripe")
															<form id="payment-form" class="sr-payment-form">
																@csrf
																<div class="sr-combo-inputs-row">
																	<div class="sr-input sr-card-element" id="card-element"></div>
																</div>
																{{-- <div class="sr-field-error" id="card-errors" role="alert"></div> --}}

																<button id="stripesubmit">
																	<div class="spinner d-none" id="spinner"></div>
																	<span id="buttontext" class="">Pay</span>
																</button>
															</form>

															<div class="hidden row" id="stripesuccess">
																<div class="col-lg-12">
																	<span>Payment Completed, redirecting.....</span>
																</div>
															</div>

															<form id="selectform" method="POST" action="javascript:void(0)">
																@csrf
																<input type="hidden" name="amount" value="{{$amount}}">
															</form>
														@endif
														@if ($payment_mode->name == "Paypal")
															<div>
																@include('includes.paypal')
															</div>
														@endif
														@if ($payment_mode->name == "Bank Transfer")
														@if (!empty($payment_mode->bankname))
														<div class="d-block">
															<h5 class="text-{{$text}}">Bank Name</h5>
														</div>
														<div class="mb-3 input-group">
															<input type="text" class="form-control myInput readonly text-{{$text}} bg-{{$bg}}" value="{{$payment_mode->bankname}}" readonly>
															<div class="input-group-append">
																<button class="btn btn-outline-secondary" onclick="myFunction()" type="button" id="button-addon2" disabled><i class="fas fa-copy"></i></button>
															</div>
														</div>
														@endif
														@if (!empty($payment_mode->account_name))
														<div class="d-block">
															<h5 class="text-{{$text}}">Account Name</h5>
														</div>
														<div class="mb-3 input-group">
															<input type="text" class="form-control myInput readonly text-{{$text}} bg-{{$bg}}" value="{{$payment_mode->account_name}}" readonly>
															<div class="input-group-append">
																<button class="btn btn-outline-secondary" onclick="myFunction()" type="button" id="button-addon2" disabled><i class="fas fa-copy"></i></button>
															</div>
														</div>
														@endif
														@if (!empty($payment_mode->account_number))
														<div class="d-block">
															<h5 class="text-{{$text}}">Account Number</h5>
														</div>
														<div class="mb-3 input-group">
															<input type="text" class="form-control myInput readonly text-{{$text}} bg-{{$bg}}" value="{{$payment_mode->account_number}}" readonly>
															<div class="input-group-append">
																<button class="btn btn-outline-secondary" onclick="myFunction()" type="button" id="button-addon2" disabled><i class="fas fa-copy"></i></button>
															</div>
														</div>
														@endif
														@if (!empty($payment_mode->swift_code))
														<div class="d-block">
															<h5 class="text-{{$text}}">Swift Code</h5>
														</div>
														<div class="mb-3 input-group">
															<input type="text" class="form-control myInput readonly text-{{$text}} bg-{{$bg}}" value="{{$payment_mode->swift_code}}" readonly>
															<div class="input-group-append">
																<button class="btn btn-outline-secondary" onclick="myFunction()" type="button" id="button-addon2" disabled><i class="fas fa-copy"></i></button>
															</div>
														</div>
														@endif
														@endif
													@else
														@if (!empty($payment_mode->bankname))
														<div class="d-block">
															<h5 class="text-{{$text}}">Bank Name</h5>
														</div>
														<div class="mb-3 input-group">
															<input type="text" class="form-control myInput readonly text-{{$text}} bg-{{$bg}}" value="{{$payment_mode->bankname}}" readonly>
															<div class="input-group-append">
																<button class="btn btn-outline-secondary" onclick="myFunction()" type="button" id="button-addon2" disabled><i class="fas fa-copy"></i></button>
															</div>
														</div>
														@endif
														@if (!empty($payment_mode->account_name))
														<div class="d-block">
															<h5 class="text-{{$text}}">Account Name</h5>
														</div>
														<div class="mb-3 input-group">
															<input type="text" class="form-control myInput readonly text-{{$text}} bg-{{$bg}}" value="{{$payment_mode->account_name}}" readonly>
															<div class="input-group-append">
																<button class="btn btn-outline-secondary" onclick="myFunction()" type="button" id="button-addon2"disabled><i class="fas fa-copy"></i></button>
															</div>
														</div>
														@endif
														@if (!empty($payment_mode->account_number))
														<div class="d-block">
															<h5 class="text-{{$text}}">Account Number</h5>
														</div>
														<div class="mb-3 input-group">
															<input type="text" class="form-control myInput readonly text-{{$text}} bg-{{$bg}}" value="{{$payment_mode->account_number}}" readonly>
															<div class="input-group-append">
																<button class="btn btn-outline-secondary" onclick="myFunction()" type="button" id="button-addon2"disabled><i class="fas fa-copy"></i></button>
															</div>
														</div>
														@endif
														@if (!empty($payment_mode->swift_code))
														<div class="d-block">
															<h5 class="text-{{$text}}">Swift Code</h5>
														</div>
														<div class="mb-3 input-group">
															<input type="text" class="form-control myInput readonly text-{{$text}} bg-{{$bg}}" value="{{$payment_mode->swift_code}}" readonly>
															<div class="input-group-append">
																<button class="btn btn-outline-secondary" onclick="myFunction()" type="button" id="button-addon2"disabled><i class="fas fa-copy"></i></button>
															</div>
														</div>
														@endif
													@endif
											@endif
										</div>
										@if ($settings->deposit_option == "auto" and $payment_mode->defaultpay != 'yes')
											<div>
												<form method="post" action="{{route('savedeposit')}}" enctype="multipart/form-data">
													@csrf
													<div class="form-group">
														<h5 class="text-{{$text}}">Upload Payment proof after payment.</h5>
														<input type="file" name="proof" class="form-control col-lg-4 bg-{{$bg}} text-{{$text}}" required>
													</div>
													<input type="hidden" name="amount" value="{{$amount}}">
													<input type="hidden" name="paymethd_method" value="{{$payment_mode->name}}">

													<div class="form-group">
														<input type="submit" class="btn btn-{{$text}}" value="Submit Payment">
													</div>
												</form>
											</div>
										@endif
										@if($settings->deposit_option == "manual" and $payment_mode->name != "Paystack" and $payment_mode->name != "Stripe" and $payment_mode->name != "Paypal" and $title !="Complete Payment")
											<div>
												<form method="post" action="{{route('savedeposit')}}" enctype="multipart/form-data">
													@csrf
{{--													<div class="form-group">--}}
{{--														<h5 class="text-{{$text}}">Upload Payment proof after payment.</h5>--}}
{{--														<input type="file" name="proof" class="form-control col-lg-4 bg-{{$bg}} text-{{$text}}" required>--}}
{{--													</div>--}}
                                                    <div class="form-group mt-3">
                                                        <h5 class="text-{{$text}}">Upload Payment proof after payment.</h5>
                                                        <input type="file" name="proof" class="form-control col-lg-4 bg-{{$bg}} text-{{$text}}" required>
                                                        <label class="text-{{$text}}">Paste transaction ID or reference (if available)</label>
                                                        <input type="text" name="reference_text" class="form-control bg-{{$bg}} text-{{$text}}" placeholder="Paste TXID or reference text">
                                                    </div>

                                                    <input type="hidden" name="amount" value="{{$amount}}">
													<input type="hidden" name="paymethd_method" value="{{$payment_mode->name}}">

													<div class="form-group">
														<input type="submit" class="btn btn-{{$text}}" value="Submit Payment">
													</div>
												</form>
											</div>
										@endif
									@endif
									{{-- Automatic Cryptopayment qrcode --}}
									@if($title=="Complete Payment")
										<div class="p-2 text-center p-md-5">
											<h4 class="text-{{$text}}">Send {{$amount}} to the below address or scan the {{$coin}} QR code to complete payment.</h4>
											<h4 class="text-{{$text}}"><strong>{{$p_address}}</strong></h4>
											<div>
												<img width="220" height="220" alt="Payment QR code" src="{{$p_qrcode}}">
											</div>
											<div class="mt-3">
												<small>you can exit this page after scanning and completed payment, the system will keep track of your payment and update your account accordingly </small>
											</div>
										</div>
									@endif

								</div>
							</div>
						</div>
					</div>

				</div>
			</div>
			<script>
				function myFunction() {
					/* Get the text field */
					var copyText = document.getElementById("myInput");
					/* Select the text field */
					copyText.select();
					copyText.setSelectionRange(0, 99999); /* For mobile devices */
					/* Copy the text inside the text field */
					document.execCommand("copy");
					/* Alert the copied text */
					//alert("Copied the text: " + copyText.value);
					swal("Copied", copyText.value, "success");
					}
			</script>
			<script type="text/javascript">

				var stripe = Stripe("{{$settings->s_p_k}}");
				var elements = stripe.elements();
				var style = {
					base: {
						color: "#32325d",
					}
				};
				const paybtn = document.querySelector('#stripesubmit');
				console.log(paybtn);
				paybtn.disabled = true;

				var card = elements.create("card", { style: style });
				card.mount("#card-element");

				function checkcardforerrors() {
						card.on('change', function(event) {
						if (event.error) {
							swal("Error", event.error.message, "error");
							paybtn.disabled = true;
						} else {
							paybtn.disabled = false;
						}
					});
				}
				checkcardforerrors();

				var form = document.getElementById('payment-form');

				form.addEventListener('submit', function(ev) {
					paybtn.disabled = true;
					ev.preventDefault();
					checkcardforerrors();
					document.getElementById('spinner').classList.remove('d-none');
					document.getElementById('buttontext').classList.add('d-none');

					// If the client secret was rendered server-side as a data-secret attribute
					// on the <form> element, you can retrieve it here by calling `form.dataset.secret`
					var clientSecret = "{{$intent}}";
					stripe.confirmCardPayment(clientSecret, {
						payment_method: {
							card: card,
							billing_details: {
								name: "{{Auth::user()->name}}"
							}
						}
					}).then(function(result) {
						if (result.error) {
							swal("Error", 'There was an error processing your payment, Please try deposit again from deposit page', "error");
							console.log(result.error.message);
						} else {
							// The payment has been processed!
							if (result.paymentIntent.status === 'succeeded') {
								$.ajax({
									url: "{{url('/dashboard/submit-stripe-payment')}}",
									type: 'POST',
									data:$('#selectform').serialize(),
									success: function (data) {
										swal("Success", data.success, "success");
										setTimeout(function(){window.location.replace("{{route('accounthistory')}}"); }, 3000);
									},
									error: function (error) {
										alert('Error Submiting Payment Data');
										console.log(error);
									},
								});
							}
						}
					});

				});
			</script>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Space+Grotesk:wght@500;600;700&display=swap" rel="stylesheet">
    @verbatim
    <style>
    /* ============ Zaraex payment ============ */
    .payment-page{
        --card:#0e1018; --elev:#16181f; --border:#23262f;
        --text:#f2f5fb; --muted:#a1a9b8; --faint:#6b7280;
        --blue:#2563eb; --blue-soft:#3b82f6; --success:#22c55e; --danger:#ef4444; --tint:rgba(37,99,235,.14);
        font-family:'Inter',system-ui,-apple-system,sans-serif; color:var(--text);
    }
    body[data-theme="light"] .payment-page{
        --card:#ffffff; --elev:#f4f6fa; --border:#e8ecf2; --text:#0f172a; --muted:#5b6674; --faint:#98a2b3;
    }
    .payment-page .content{padding:0}
    .payment-page .page-inner{max-width:540px;margin:0 auto;padding:22px 16px 96px}
    .payment-page .row{margin:0}
    .payment-page .col-md-8{flex:0 0 100%!important;max-width:100%!important;padding:0!important}
    .payment-page .offset-md-2{margin-left:0!important}
    .payment-page h1,.payment-page h3,.payment-page h4,.payment-page h5{font-family:'Space Grotesk','Inter',sans-serif;color:var(--text)}

    /* Topbar */
    .pay-topbar{display:flex;align-items:center;gap:12px;margin-bottom:18px}
    .pay-back{width:40px;height:40px;flex:none;border-radius:12px;display:grid;place-items:center;background:var(--card);border:1px solid var(--border);color:var(--text)}
    .pay-back svg{width:18px;height:18px}
    .payment-page .title1{font-size:1.4rem;font-weight:700;letter-spacing:-.02em;margin:0}

    /* Amount hero (dark in both themes) */
    .pay-hero{border-radius:22px;padding:24px;color:#fff;margin-bottom:18px;text-align:center;
        background:linear-gradient(160deg,#13233f 0%,#0b1322 100%);box-shadow:0 24px 50px -18px rgba(8,15,30,.7)}
    .pay-hero-label{color:rgba(255,255,255,.55);font-size:.76rem;text-transform:uppercase;letter-spacing:.08em}
    .pay-hero-amt{font-family:'Space Grotesk',sans-serif;font-size:2.4rem;font-weight:700;line-height:1.1;margin:6px 0 10px}
    .pay-hero-method{display:inline-flex;align-items:center;gap:8px;color:rgba(255,255,255,.88);font-weight:600;font-size:.88rem;background:rgba(255,255,255,.1);padding:6px 14px;border-radius:999px}
    .pay-hero-method img{width:20px;height:20px;border-radius:50%;object-fit:cover}

    /* Card */
    .payment-page .card{background:var(--card)!important;border:1px solid var(--border)!important;border-radius:20px!important;box-shadow:none!important;padding:0!important;margin:0!important}
    .payment-page .card-body{padding:22px!important}
    .payment-page h4{font-size:.95rem;font-weight:500;line-height:1.55;color:var(--muted)!important;margin-bottom:14px}
    .payment-page h3{font-size:1rem;font-weight:600;margin:20px 0 12px}
    .payment-page h5{font-size:.78rem;font-weight:600!important;text-transform:uppercase;letter-spacing:.04em;color:var(--faint)!important;margin-bottom:8px}
    .payment-page small{color:var(--muted)}
    .payment-page strong{color:var(--text)}

    /* Inputs / copy rows */
    .payment-page .form-group{margin-bottom:16px}
    .payment-page .input-group{margin-bottom:16px!important;flex-wrap:nowrap}
    .payment-page .form-control,.payment-page .myInput,.payment-page input[type="text"]{
        background:var(--elev)!important;border:1px solid var(--border)!important;color:var(--text)!important;
        border-radius:12px!important;padding:13px 15px!important;font-size:.9rem!important;box-shadow:none!important;height:auto!important}
    .payment-page .input-group .form-control,.payment-page .input-group .myInput{border-radius:12px 0 0 12px!important;border-right:none!important}
    .payment-page .input-group-append .btn{border:1px solid var(--border)!important;border-left:none!important;border-radius:0 12px 12px 0!important;
        background:var(--elev)!important;color:var(--blue-soft)!important;padding:0 16px!important}
    .payment-page input[type="file"]{background:var(--elev)!important;border:1px solid var(--border)!important;color:var(--text)!important;
        border-radius:12px!important;padding:11px 13px!important;width:100%!important;max-width:100%!important;flex:0 0 100%!important}
    .payment-page .form-control::placeholder{color:var(--faint)!important}

    /* Buttons */
    .payment-page .btn,.payment-page input[type="submit"],.payment-page #stripesubmit{
        border-radius:12px!important;font-weight:600!important;padding:13px 22px!important;border:none!important;font-size:.95rem!important;transition:.2s!important}
    .payment-page .btn-primary,.payment-page .btn-white,.payment-page .btn-dark,.payment-page .btn-light,
    .payment-page input[type="submit"],.payment-page #stripesubmit{background:var(--blue)!important;color:#fff!important;width:auto}
    .payment-page .btn-primary:hover,.payment-page input[type="submit"]:hover,.payment-page #stripesubmit:hover{background:var(--blue-soft)!important}
    .payment-page .btn-outline-secondary{color:var(--blue-soft)!important}

    /* Stripe */
    .payment-page #card-element,.payment-page .sr-input{background:var(--elev)!important;border:1px solid var(--border)!important;border-radius:12px!important;padding:14px!important;margin-bottom:12px}

    /* Images / QR */
    .payment-page .w-25{width:auto!important;max-height:44px}
    .payment-page .card-body img{border-radius:12px;max-width:100%;height:auto}
    </style>
    @endverbatim
    @endsection

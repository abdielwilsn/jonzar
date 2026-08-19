<?php
// Resolve the active account from whichever guard is logged in. Admins use the
// 'admin' guard and end users the default web guard; keying the theme off only
// the web guard meant admin pages never picked up the admin's saved preference,
// so light and dark looked almost identical (only the sidebar tint changed).
$themeUser = Auth::guard('admin')->check()
	? Auth::guard('admin')->user()
	: (Auth::check() ? Auth::user() : null);

$dashboardStyle = $themeUser->dashboard_style ?? null;

if ($dashboardStyle === "light") {
	$text = "dark";
	$bg = "light";
	$theme = "light";
} else {
	$text = "light";
	$bg = "dark";
	$theme = "dark";
}
// Only follow the OS light/dark setting when the account has no explicit choice.
$autoTheme = ! ($themeUser && in_array($dashboardStyle, ['light', 'dark'], true));
?>

<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
	<script type="text/javascript" src="https://js.stripe.com/v3/"></script>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{$settings->site_name}} | {{$title}}</title>
    <link rel="icon" href="{{ asset('storage/app/public/photos/'.$settings->favicon)}}" type="image/png"/>

	<!-- Google Fonts - Inter -->
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

	<!-- ============================================
	     GLOBAL THEME STYLES
	     ============================================ -->
	<style>
		/* CSS Variables for theming */
		:root {
			/* Light theme (default) */
			--bg-primary: #f8fafc;
			--bg-secondary: #f1f5f9;
			--bg-card: #ffffff;
			--text-primary: #0f172a;
			--text-secondary: #64748b;
			--text-muted: #94a3b8;
			--border-color: #e2e8f0;
			--input-bg: #f1f5f9;
			--hover-bg: rgba(37, 99, 235, 0.05);
			--divider: #e2e8f0;
			--shadow: 0 4px 20px -5px rgba(0, 0, 0, 0.1);

			/* Brand colors */
			--primary: #2563eb;
			--primary-light: #3b82f6;
			--primary-dark: #1d4ed8;
			--success: #10b981;
			--warning: #f59e0b;
			--danger: #ef4444;
			--info: #0ea5e9;
		}

		/* Dark theme */
		[data-theme="dark"] {
			--bg-primary: #0a0a0f;
			--bg-secondary: #12121a;
			--bg-card: rgba(18, 18, 26, 0.95);
			--text-primary: #f8fafc;
			--text-secondary: #94a3b8;
			--text-muted: #7d8ba3;
			--border-color: rgba(37, 99, 235, 0.15);
			--input-bg: #12121a;
			--hover-bg: rgba(37, 99, 235, 0.08);
			--divider: rgba(255, 255, 255, 0.08);
			--shadow: 0 4px 20px -5px rgba(0, 0, 0, 0.4);
		}

		/* Base styles */
		* {
			box-sizing: border-box;
		}

		body {
			font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif !important;
			background: var(--bg-primary) !important;
			color: var(--text-primary) !important;
			transition: background-color 0.3s ease, color 0.3s ease;
		}

		/* Remove default background image */
		body[data-theme="dark"],
		body[data-theme="light"] {
			background-image: none !important;
		}

		/* Main content areas */
		.main-panel,
		.main-panel .content,
		.wrapper {
			background: var(--bg-primary) !important;
		}

		.page-inner {
			background: transparent !important;
		}

		/* Cards */
		.card {
			background: var(--bg-card) !important;
			border: 1px solid var(--border-color) !important;
			border-radius: 14px !important;
			box-shadow: var(--shadow) !important;
		}

		.card-header {
			background: transparent !important;
			border-bottom: 1px solid var(--border-color) !important;
			color: var(--text-primary) !important;
		}

		.card-title {
			color: var(--text-primary) !important;
		}

		.card-body {
			color: var(--text-primary) !important;
		}

		.card-footer {
			background: transparent !important;
			border-top: 1px solid var(--border-color) !important;
		}

		/* Forms */
		.form-control,
		input[type="text"],
		input[type="email"],
		input[type="password"],
		input[type="number"],
		input[type="tel"],
		input[type="url"],
		input[type="search"],
		select,
		textarea {
			background: var(--input-bg) !important;
			border: 1px solid var(--border-color) !important;
			color: var(--text-primary) !important;
			border-radius: 10px !important;
			padding: 12px 16px !important;
			transition: all 0.2s ease !important;
		}

		.form-control:focus,
		input:focus,
		select:focus,
		textarea:focus {
			background: var(--input-bg) !important;
			border-color: var(--primary) !important;
			box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15) !important;
			color: var(--text-primary) !important;
			outline: none !important;
		}

		.form-control::placeholder,
		input::placeholder,
		textarea::placeholder {
			color: var(--text-muted) !important;
		}

		.form-group label,
		label {
			color: var(--text-secondary) !important;
			font-weight: 500 !important;
		}

		/* Tables */
		.table {
			color: var(--text-primary) !important;
		}

		.table thead th {
			background: var(--bg-secondary) !important;
			border-color: var(--border-color) !important;
			color: var(--text-secondary) !important;
			font-weight: 600 !important;
			padding: 14px 16px !important;
		}

		.table tbody td {
			border-color: var(--border-color) !important;
			color: var(--text-primary) !important;
			padding: 14px 16px !important;
		}

		.table tbody tr:hover {
			background: var(--hover-bg) !important;
		}

		.table-striped tbody tr:nth-of-type(odd) {
			background: var(--bg-secondary) !important;
		}

		/* DataTables */
		.dataTables_wrapper .dataTables_length select,
		.dataTables_wrapper .dataTables_filter input {
			background: var(--input-bg) !important;
			border: 1px solid var(--border-color) !important;
			color: var(--text-primary) !important;
			border-radius: 8px !important;
		}

		.dataTables_wrapper .dataTables_info,
		.dataTables_wrapper .dataTables_length label,
		.dataTables_wrapper .dataTables_filter label {
			color: var(--text-secondary) !important;
		}

		.dataTables_wrapper .dataTables_paginate .paginate_button {
			background: var(--bg-card) !important;
			border: 1px solid var(--border-color) !important;
			color: var(--text-secondary) !important;
			border-radius: 8px !important;
		}

		.dataTables_wrapper .dataTables_paginate .paginate_button:hover {
			background: var(--hover-bg) !important;
			border-color: var(--primary) !important;
			color: var(--primary) !important;
		}

		.dataTables_wrapper .dataTables_paginate .paginate_button.current {
			background: var(--primary) !important;
			border-color: var(--primary) !important;
			color: white !important;
		}

		/* Buttons */
		.btn {
			border-radius: 10px !important;
			font-weight: 600 !important;
			padding: 10px 20px !important;
			transition: all 0.2s ease !important;
		}

		.btn-primary {
			background: var(--primary) !important;
			border-color: var(--primary) !important;
			color: white !important;
		}

		.btn-primary:hover {
			background: var(--primary-dark) !important;
			border-color: var(--primary-dark) !important;
			transform: translateY(-1px) !important;
			box-shadow: 0 4px 12px -4px rgba(37, 99, 235, 0.5) !important;
		}

		.btn-secondary {
			background: var(--bg-secondary) !important;
			border-color: var(--border-color) !important;
			color: var(--text-primary) !important;
		}

		.btn-secondary:hover {
			background: var(--hover-bg) !important;
			border-color: var(--primary) !important;
			color: var(--primary) !important;
		}

		.btn-success {
			background: var(--success) !important;
			border-color: var(--success) !important;
			color: white !important;
		}

		.btn-success:hover {
			background: #0d9668 !important;
			border-color: #0d9668 !important;
		}

		.btn-danger {
			background: var(--danger) !important;
			border-color: var(--danger) !important;
			color: white !important;
		}

		.btn-danger:hover {
			background: #dc2626 !important;
			border-color: #dc2626 !important;
		}

		.btn-warning {
			background: var(--warning) !important;
			border-color: var(--warning) !important;
			color: #000 !important;
		}

		.btn-info {
			background: var(--info) !important;
			border-color: var(--info) !important;
			color: white !important;
		}

		/* Dropdowns */
		.dropdown-menu {
			background: var(--bg-card) !important;
			border: 1px solid var(--border-color) !important;
			border-radius: 12px !important;
			box-shadow: 0 10px 40px -10px rgba(0, 0, 0, 0.3) !important;
			padding: 8px !important;
		}

		.dropdown-item {
			color: var(--text-secondary) !important;
			border-radius: 8px !important;
			padding: 10px 16px !important;
			transition: all 0.2s ease !important;
		}

		.dropdown-item:hover,
		.dropdown-item:focus {
			background: var(--hover-bg) !important;
			color: var(--primary) !important;
		}

		.dropdown-divider {
			border-color: var(--divider) !important;
		}

		/* Modals */
		.modal-content {
			background: var(--bg-card) !important;
			border: 1px solid var(--border-color) !important;
			border-radius: 16px !important;
		}

		.modal-header {
			border-bottom: 1px solid var(--border-color) !important;
		}

		.modal-title {
			color: var(--text-primary) !important;
		}

		.modal-body {
			color: var(--text-primary) !important;
		}

		.modal-footer {
			border-top: 1px solid var(--border-color) !important;
		}

		.close {
			color: var(--text-muted) !important;
		}

		/* Alerts */
		.alert {
			border-radius: 12px !important;
			border: none !important;
			padding: 16px 20px !important;
		}

		.alert-success {
			background: rgba(16, 185, 129, 0.15) !important;
			color: var(--success) !important;
			border: 1px solid rgba(16, 185, 129, 0.3) !important;
		}

		.alert-danger {
			background: rgba(239, 68, 68, 0.15) !important;
			color: var(--danger) !important;
			border: 1px solid rgba(239, 68, 68, 0.3) !important;
		}

		.alert-warning {
			background: rgba(245, 158, 11, 0.15) !important;
			color: var(--warning) !important;
			border: 1px solid rgba(245, 158, 11, 0.3) !important;
		}

		.alert-info {
			background: rgba(14, 165, 233, 0.15) !important;
			color: var(--info) !important;
			border: 1px solid rgba(14, 165, 233, 0.3) !important;
		}

		/* Badges */
		.badge {
			padding: 5px 10px !important;
			border-radius: 6px !important;
			font-weight: 600 !important;
		}

		.badge-primary, .badge.bg-primary {
			background: var(--primary) !important;
		}

		.badge-success, .badge.bg-success {
			background: var(--success) !important;
		}

		.badge-danger, .badge.bg-danger {
			background: var(--danger) !important;
		}

		.badge-warning, .badge.bg-warning {
			background: var(--warning) !important;
			color: #000 !important;
		}

		/* Pagination */
		.pagination .page-link {
			background: var(--bg-card) !important;
			border-color: var(--border-color) !important;
			color: var(--text-secondary) !important;
			border-radius: 8px !important;
			margin: 0 2px !important;
		}

		.pagination .page-link:hover {
			background: var(--hover-bg) !important;
			color: var(--primary) !important;
			border-color: var(--primary) !important;
		}

		.pagination .page-item.active .page-link {
			background: var(--primary) !important;
			border-color: var(--primary) !important;
			color: white !important;
		}

		/* Text utilities override */
		[data-theme="dark"] .text-dark {
			color: var(--text-primary) !important;
		}

		[data-theme="dark"] .text-muted {
			color: var(--text-muted) !important;
		}

		[data-theme="dark"] .bg-light {
			background: var(--bg-secondary) !important;
		}

		[data-theme="dark"] .bg-white {
			background: var(--bg-card) !important;
		}

		/* Sidebar override (legacy sidebar only; the redesigned sidebar styles itself) */
		.sidebar:not(.sidebar-redesign) {
			background: var(--bg-secondary) !important;
			border-right: 1px solid var(--border-color) !important;
		}

		.sidebar:not(.sidebar-redesign) .nav > .nav-item a {
			color: var(--text-secondary) !important;
		}

		.sidebar:not(.sidebar-redesign) .nav > .nav-item a:hover,
		.sidebar:not(.sidebar-redesign) .nav > .nav-item a:focus,
		.sidebar:not(.sidebar-redesign) .nav > .nav-item.active > a {
			color: var(--primary) !important;
			background: var(--hover-bg) !important;
		}

		/* Footer */
		.footer {
			background: var(--bg-card) !important;
			border-top: 1px solid var(--border-color) !important;
			color: var(--text-secondary) !important;
		}

		.footer p, .footer a {
			color: var(--text-secondary) !important;
		}

		/* Scrollbar for dark theme */
		[data-theme="dark"] ::-webkit-scrollbar {
			width: 8px;
			height: 8px;
		}

		[data-theme="dark"] ::-webkit-scrollbar-track {
			background: var(--bg-secondary);
		}

		[data-theme="dark"] ::-webkit-scrollbar-thumb {
			background: var(--border-color);
			border-radius: 4px;
		}

		[data-theme="dark"] ::-webkit-scrollbar-thumb:hover {
			background: var(--primary);
		}

		/* Stamp icons (for stat cards) */
		.stamp {
			border-radius: 12px !important;
		}

		/* Progress bars */
		.progress {
			background: var(--bg-secondary) !important;
			border-radius: 10px !important;
		}

		.progress-bar {
			background: var(--primary) !important;
		}

		/* Nav tabs */
		.nav-tabs {
			border-color: var(--border-color) !important;
		}

		.nav-tabs .nav-link {
			color: var(--text-secondary) !important;
			border-radius: 10px 10px 0 0 !important;
		}

		.nav-tabs .nav-link:hover {
			border-color: var(--border-color) !important;
			color: var(--primary) !important;
		}

		.nav-tabs .nav-link.active {
			background: var(--bg-card) !important;
			border-color: var(--border-color) var(--border-color) var(--bg-card) !important;
			color: var(--primary) !important;
		}

		/* List groups */
		.list-group-item {
			background: var(--bg-card) !important;
			border-color: var(--border-color) !important;
			color: var(--text-primary) !important;
		}

		.list-group-item:hover {
			background: var(--hover-bg) !important;
		}
	</style>

	@section('styles')
		<!-- Fonts and icons -->
		<script src="{{asset('dash/js/plugin/webfont/webfont.min.js')}}"></script>
		<!-- Sweet Alert -->
		<script src="{{ asset('dash/js/plugin/sweetalert/sweetalert.min.js')}} "></script>
		<!-- CSS Files -->
		<link rel="stylesheet" href="{{asset('dash/css/bootstrap.min.css')}}">
		<link rel="stylesheet" href="{{asset('dash/css/fonts.min.css')}}">
		<link rel="stylesheet" href="{{asset('dash/css/atlantis.min.css')}}">
		<link rel="stylesheet" href="{{asset('dash/css/customs.css')}}">
		<link rel="stylesheet" href="{{asset('dash/css/style.css')}}">
		<link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.6.2/css/buttons.dataTables.min.css">
		<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4/dt-1.10.21/af-2.3.5/b-1.6.3/b-flash-1.6.3/b-html5-1.6.3/b-print-1.6.3/r-2.2.5/datatables.min.css"/>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
		<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
		<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>

		<!-- Bootstrap Notify -->
		<script src="{{ asset('dash/js/plugin/bootstrap-notify/bootstrap-notify.min.js')}} "></script>
		<script src="{{ asset('dash/js/plugin/sweetalert/sweetalert.min.js')}} "></script>
		<script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.8.1/dist/alpine.min.js" defer></script>
		<script src="https://cdn.jsdelivr.net/npm/chart.js@3.2.1/dist/chart.min.js"></script>
		@livewireStyles
	@show

	{{-- Fix bootstrap-notify toast: keep the icon from overlapping the title/message --}}
	<style>
		[data-notify="container"]{
			padding:15px 44px 15px 56px !important;
			border-radius:14px !important;
			box-shadow:0 16px 40px rgba(2,8,23,.28) !important;
			border:none !important;
			max-width:360px;
		}
		[data-notify="icon"]{
			position:absolute !important;
			left:16px !important;
			top:50% !important;
			transform:translateY(-50%) !important;
			width:26px !important;
			height:26px !important;
			line-height:26px !important;
			text-align:center;
			font-size:20px !important;
			background:transparent !important;
		}
		[data-notify="icon"]::before{
			position:static !important;
			left:auto !important;
			top:auto !important;
			transform:none !important;
			background:transparent !important;
			line-height:26px !important;
			font-size:20px !important;
			width:auto !important;
			height:auto !important;
		}
		[data-notify="title"]{display:block;margin-bottom:2px;line-height:1.3}
		[data-notify="message"]{display:block;line-height:1.4}
		[data-notify="dismiss"]{top:8px !important;right:12px !important;}
	</style>

</head>
<body data-theme="{{ $theme }}">
@if($autoTheme)
<script>
    // No explicit user preference: follow the OS light/dark setting and keep the whole
    // layout (body, header, sidebar) on the same dark/white theme so nothing mismatches.
    (function () {
        try {
            var mq = window.matchMedia('(prefers-color-scheme: dark)');
            function theme() { return mq.matches ? 'dark' : 'light'; }
            function syncChrome() {
                var t = theme();
                var header = document.querySelector('.main-header');
                if (header) { header.setAttribute('data-theme', t); }
                document.querySelectorAll('.logo-header, .navbar-header, .sidebar').forEach(function (el) {
                    el.setAttribute('data-background-color', t);
                });
            }
            function applyAll() { document.body.setAttribute('data-theme', theme()); syncChrome(); }
            // Set body immediately (avoids a flash); sync header/sidebar once they exist in the DOM.
            document.body.setAttribute('data-theme', theme());
            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', syncChrome);
            } else {
                syncChrome();
            }
            if (mq.addEventListener) { mq.addEventListener('change', applyAll); }
            else if (mq.addListener) { mq.addListener(applyAll); }
        } catch (e) {}
    })();
</script>
@endif
    <div id="app">

        <!--Start of Tawk.to Script-->
        {{-- <script type="text/javascript">
        {{!! $settings->tawk_to !!}}
        </script> --}}
        <!--End of Tawk.to Script-->

		<div class="wrapper">
			@yield('content')
		</div>
	</div>

	{{-- Mobile bottom nav (+ sidebar hiding) applies to the user end only; admin keeps its sidebar --}}
	@unless(request()->is('admin') || request()->is('admin/*'))
		@include('user.bottomnav')
	@endunless

	@section('scripts')
		<!--   Core JS Files   -->
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
		<script src="{{ asset('dash/js/core/popper.min.js')}}"></script>
		<script src="{{ asset('dash/js/core/bootstrap.min.js')}} "></script>
		<script src="{{ asset('dash/js/customs.js')}}"></script>

		<!-- jQuery UI -->
		<script src="{{ asset('dash/js/plugin/jquery-ui-1.12.1.custom/jquery-ui.min.js')}}"></script>
		<script src="{{ asset('dash/js/plugin/jquery-ui-touch-punch/jquery.ui.touch-punch.min.js')}}"></script>

		<!-- jQuery Scrollbar -->
		<script src="{{ asset('dash/js/plugin/jquery-scrollbar/jquery.scrollbar.min.js')}} "></script>

		<!-- jQuery Sparkline -->
		<script src="{{ asset('dash/js/plugin/jquery.sparkline/jquery.sparkline.min.js')}} "></script>

		<!-- Sweet Alert -->
		<script src="{{ asset('dash/js/plugin/sweetalert/sweetalert.min.js')}} "></script>
		<!-- Bootstrap Notify -->
		<script src="{{ asset('dash/js/plugin/bootstrap-notify/bootstrap-notify.min.js')}} "></script>

		<script type="text/javascript" src="https://cdn.datatables.net/v/bs4/dt-1.10.21/af-2.3.5/b-1.6.3/b-flash-1.6.3/b-html5-1.6.3/b-print-1.6.3/r-2.2.5/datatables.min.js"></script>

		<script src="{{asset('dash/js/atlantis.min.js')}}"></script>
		<script src="{{asset('dash/js/atlantis.js')}}"></script>

		<script type="text/javascript">
			var badWords = [
				'<!--Start of Tawk.to Script-->',
				'<script type="text/javascript">',
				'<!--End of Tawk.to Script-->'
			];
			$(':input').on('blur', function(){
				var value = $(this).val();
				$.each(badWords, function(idx, word){
					value = value.replace(word, '');
				});
				$(this).val(value);
			});
		</script>

		<script>
			$(document).ready(function() {
				$('#ShipTable').DataTable({
					order: [[0, 'desc']],
					dom: 'Bfrtip',
					buttons: ['copy', 'csv', 'print', 'excel', 'pdf']
				});
			});
		</script>

		<script>
			$(document).ready(function() {
				$('.UserTable').DataTable({
					order: [[0, 'desc']]
				});
			});
		</script>

		<style>
			.dataTables_length select {
				padding-left: 8px !important;
				padding-right: 24px !important;
			}
		</style>

		{{-- Global confirmation modal for admin CRUD actions (deposits/withdrawals accept, reject, delete, etc.).
		     Add data-confirm="..." (and optionally data-confirm-title / data-confirm-button / data-confirm-danger="false")
		     to any <form> to require an "are you sure?" modal before it submits. --}}
		<script>
			$(document).on('submit', 'form[data-confirm]', function (e) {
				var form = this;
				if (form.dataset.confirmed === 'true') {
					return true;
				}
				e.preventDefault();

				swal({
					title: form.dataset.confirmTitle || 'Are you sure?',
					text: form.dataset.confirm,
					icon: 'warning',
					dangerMode: form.dataset.confirmDanger !== 'false',
					buttons: ['Cancel', form.dataset.confirmButton || 'Yes, continue'],
				}).then(function (confirmed) {
					if (confirmed) {
						form.dataset.confirmed = 'true';
						form.submit();
					}
				});

				return false;
			});
		</script>

		@stack('modals')
		@stack('scripts')
		@livewireScripts
	@show

</body>
</html>

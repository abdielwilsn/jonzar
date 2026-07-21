@component('mail::layout')
{{-- Header --}}
@slot('header')
@component('mail::header', ['url' => config('app.url')])
<img src="{{ asset('storage/app/public/photos/'.$settings->logo)}}" alt="{{ config('app.name') }}" style="width:60%; max-height:15%;">
@endcomponent
@endslot

{{-- Body --}}
{{ $slot }}

{{-- Subcopy --}}
@isset($subcopy)
@slot('subcopy')
@component('mail::subcopy')
{{ $subcopy }}
@endcomponent
@endslot
@endisset

{{-- Footer --}}
@slot('footer')
@component('mail::footer')
<hr/>
<div style="color:white; font-size:0.6rem; text-align:justify;">You received this auto-generated email because you have an account at {{ config('app.name') }}.com website. 
This is a transactional email and you cannot unsubscribe from it. {{ config('app.name') }} Investment has an AML policy. We comply with 
Office of Foreign Assets Control (“OFAC”) sanctions, and we’re a regulated financial entity, trusted by over 3 million users. 
See what our users have to say about us through our reviews and testimonies. HELP: Live Chat |
Phone: +353 (89) 978-6066 | WhatsApp: +353 (89) 978-6066 | Telegram: +353 (89) 978-6066. {{ config('app.name') }} Inc. 
<span style="font-weight:bold; text-transform: uppercase;">119 RURAL ROAD 3009 freeland avenue Ireland </span></div>
<hr/>
<div style="color:lightgray;"> © {{ date('Y') }} {{ config('app.name') }}. @lang('All rights reserved.') </div>

@endcomponent
@endslot
@endcomponent

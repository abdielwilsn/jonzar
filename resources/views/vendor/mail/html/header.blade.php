<tr>
<td class="header">
<a href="{{ $url }}" style="">
@if (trim($slot) === 'Starbiit')
{{-- <img src="https://laravel.com/img/notification-logo.png" class="logo" alt="Starbiit Logo"> --}}
@else
{{ $slot }}
@endif
</a>
</td>
</tr>

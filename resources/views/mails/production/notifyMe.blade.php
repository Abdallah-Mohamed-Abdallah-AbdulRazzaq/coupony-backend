@component('mail::message')
# {{ $notification['title'] }}

{{ $notification['message'] }}

{{-- @if($actionUrl)
@component('mail::button', ['url' => $actionUrl])
{{ $actionText }}
@endcomponent
@endif --}}

Thanks,<br>
{{ config('app.name') }}
@endcomponent
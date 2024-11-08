@component('mail::message')
# Halo, {{ $user->name }}

{{ $subject }}

@component('mail::button', ['url' => $url])
Kunjungi Website
@endcomponent

{{ $message }},<br>
{{ config('app.name') }}
@endcomponent

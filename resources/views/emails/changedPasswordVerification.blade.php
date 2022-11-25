@php
    $domain = Request::root() == 'http://ads-api.loc'? 'http://localhost:8080/' : 'http://google-vue.dev-basket.com/';
@endphp
@component('mail::message')
    Hello {{$user['first_name']}}

    {{$user['first_name']}}! Are you change your password? Please verify it.

    @component('mail::button', ['url' => $domain.'changed-password/'.$user['token']])
        Verify email change
    @endcomponent

    Thanks,<br>
    {{ config('app.name') }}
@endcomponent

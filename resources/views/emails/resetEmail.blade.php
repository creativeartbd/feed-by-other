@php
    $domain = Request::root() == 'http://ads-api.loc'? 'http://localhost:8080/' : 'http://google-vue.dev-basket.com/';
@endphp
@component('mail::message')
    Hello {{$user['firstName']}}

    {{$user['firstName']}}! Have you forgotten your password?. Click on reset button

  @component('mail::button', ['url' => $domain.'reset-password/'.$user['token']])
    Reset
  @endcomponent

  Thanks,<br>
  {{ config('app.name') }}
@endcomponent

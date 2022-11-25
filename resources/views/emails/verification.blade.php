@php
    $domain = Request::root() == 'http://ads-api.loc'? 'http://localhost:8080/' : 'http://google-vue.dev-basket.com/';
@endphp

@component('mail::message')
# Introduction

For verification your account click on button.

@component('mail::button', ['url' =>$domain.'verify/'.$user->email_verification_token])
Verification Account
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent

@component('mail::message')
# Hello {{$user->name}}

Please verify your email by clicking this button below
@component('mail::button', ['url' => route('verify',$user->verification_token)])
Verify Account
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent

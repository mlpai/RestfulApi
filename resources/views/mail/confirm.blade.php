@component('mail::message')
# Hello {{$user->name}}

you have changed your email so please verify again by clikcing the button below
@component('mail::button', ['url' => route('verify',$user->verification_token)])
Verify Account
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent

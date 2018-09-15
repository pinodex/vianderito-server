@component('mail::message')
# Hi {{ $name }},

You recently requested to reset your password for your Vianderito account. Use the button below to reset it. **This password reset is only valid for the next 30 minutes.**

Copy the code below to Vianderito app to authorize password reset:

## {{ $token }}

Your username is **{{ $username }}**.

For security, this request was received from a IP address {{ $ip_address }}. If you did not request a password reset, please ignore this email.

Thanks!
@endcomponent

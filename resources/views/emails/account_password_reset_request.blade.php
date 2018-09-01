@component('mail::message')
# Hi {{ $name }},

You recently requested to reset your password for your Vianderito employee account. Use the button below to reset it. **This password reset is only valid for the next 30 minutes.**

@component('mail::button', ['url' => $reset_link])
Reset Password
@endcomponent

*If you're having trouble with the button above, copy and paste the URL below into your web browser:*

{{ $reset_link }}

Your username is **{{ $username }}**.

For security, this request was received from a {{ $device_name }} with IP address {{ $ip_address }}. If you did not request a password reset, please ignore this email.

Thanks!
@endcomponent

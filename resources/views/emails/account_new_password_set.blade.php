@component('mail::message')
# Hi {{ $name }},

This is to notify you that your Vianderito employee account password was changed.

For security, this action was received from a {{ $device_name }} with IP address {{ $ip_address }} on {{ $timestamp }}.

If you didn't made a password change, we recommend you immediately review your account security, including changing your password. If you determine that this activity is malicious please contact your administrator.

@endcomponent

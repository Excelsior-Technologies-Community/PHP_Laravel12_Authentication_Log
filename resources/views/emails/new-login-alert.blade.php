@component('mail::message')
# New Login Detected

Hello {{ $loginLog->authenticatable->name }},

We noticed a new login to your account from a different device/location.

**Login Details:**
- **IP Address:** {{ $loginLog->ip_address }}
- **Location:** {{ $loginLog->location ?? 'Unknown' }}
- **Browser/Device:** {{ $loginLog->user_agent }}
- **Time:** {{ $loginLog->login_at->format('Y-m-d H:i:s') }}

@component('mail::button', ['url' => route('auth-log')])
View Login History
@endcomponent

If this wasn't you, please change your password immediately.

Thanks,<br>
{{ config('app.name') }}
@endcomponent
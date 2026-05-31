<p>Hello {{ $user->first_name }},</p>

<p>Your Campus Connect account has been suspended temporarily.</p>

@if($user->suspended_until)
    <p>Suspension ends: {{ $user->suspended_until->format('F j, Y g:i A') }}</p>
@endif

<p>Please contact admin if you believe this is a mistake.</p>
<p>Hello {{ $user->first_name }},</p>

<p>Your Campus Connect account has been banned.</p>

@if($user->ban_reason)
    <p><strong>Reason:</strong> {{ $user->ban_reason }}</p>
@endif

<p>Please contact admin if you believe this is a mistake.</p>
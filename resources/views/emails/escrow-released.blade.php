<h2>Escrow Payment Released</h2>

<p>Hello {{ $booking->provider->first_name }},</p>

<p>
The escrow payment for
<strong>{{ $booking->skill->title }}</strong>
has been released.
</p>

<hr>

<p>
Total Payment:
<strong>₦{{ number_format($booking->escrow_amount, 2) }}</strong>
</p>

<p>
Campus Connect Fee ({{ $booking->platform_fee_percent }}%):
<strong>₦{{ number_format($booking->platform_fee, 2) }}</strong>
</p>

<p>
Provider Payout:
<strong>₦{{ number_format($booking->provider_payout, 2) }}</strong>
</p>

<hr>

<p>
This is a prototype notification.
No real bank transfer was made.
</p>
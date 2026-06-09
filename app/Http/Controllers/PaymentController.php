<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
    public function payEscrow($id)
    {
        $booking = Booking::with(['skill', 'client', 'provider'])->findOrFail($id);

        if ($booking->client_id !== auth()->id()) {
            abort(403);
        }

        if ($booking->status !== 'confirmed') {
            return back()->with('error', 'Provider must accept the booking before escrow payment.');
        }

        if ($booking->escrow_status === 'funded') {
            return back()->with('error', 'Escrow has already been funded for this booking.');
        }

        $reference = 'ESCROW-' . strtoupper(Str::random(12));

        $response = Http::withToken(config('paystack.secret_key'))
            ->post(config('paystack.payment_url') . '/transaction/initialize', [
                'email' => auth()->user()->email,
                'amount' => (int) round($booking->skill->price * 100),
                'reference' => $reference,
                'callback_url' => route('bookings.escrow.callback'),
                'metadata' => [
                    'booking_id' => $booking->id,
                    'client_id' => $booking->client_id,
                    'provider_id' => $booking->provider_id,
                    'skill_id' => $booking->skill_id,
                ],
            ]);

       if (!$response->successful()) {
    \Log::error('Paystack escrow initialize failed', [
        'status' => $response->status(),
        'body' => $response->json(),
    ]);

    return back()->with('error', $response->json('message') ?? 'Unable to start escrow payment.');
}

        $data = $response->json();

        $booking->update([
            'paystack_reference' => $reference,
        ]);

        return redirect($data['data']['authorization_url']);
    }

    public function escrowCallback(Request $request)
    {
        $reference = $request->query('reference');

        if (!$reference) {
            return redirect()->route('bookings.requests')
                ->with('error', 'Payment reference missing.');
        }

     try {
    $response = Http::withToken(config('paystack.secret_key'))
        ->timeout(30)
        ->retry(3, 1000)
        ->get(config('paystack.payment_url') . '/transaction/verify/' . $reference);
} catch (\Exception $e) {
    \Log::error('Paystack escrow verification timeout', [
        'reference' => $reference,
        'error' => $e->getMessage(),
    ]);

    return redirect()->route('bookings.requests')
        ->with('error', 'Payment verification timed out. Please refresh your bookings page in a moment.');
}

        if (!$response->successful()) {
            return redirect()->route('bookings.requests')
                ->with('error', 'Escrow payment verification failed.');
        }

        $data = $response->json();

        if (($data['data']['status'] ?? null) !== 'success') {
            return redirect()->route('bookings.requests')
                ->with('error', 'Payment was not successful.');
        }

        $booking = Booking::with(['skill', 'client', 'provider'])
            ->where('paystack_reference', $reference)
            ->firstOrFail();

       $amount = $booking->skill->price;

$feePercent = 5;

$platformFee = round(
    ($amount * $feePercent) / 100,
    2
);

$providerPayout = round(
    $amount - $platformFee,
    2
);

$booking->update([
    'escrow_status' => 'funded',
    'escrow_paid_at' => now(),

    'escrow_amount' => $amount,
    'platform_fee_percent' => $feePercent,
    'platform_fee' => $platformFee,
    'provider_payout' => $providerPayout,
]);

        Notification::createNotification(
            $booking->provider_id,
            'escrow_funded',
            'Escrow Payment Secured',
            'Payment has been secured for "' . ($booking->skill->title ?? 'your service') . '". You may begin work.',
            route('bookings.skills')
        );

        Notification::createNotification(
            $booking->client_id,
            'escrow_funded',
            'Escrow Payment Successful',
            'Your payment has been secured in escrow. The provider can now begin work.',
            route('bookings.requests')
        );

        return redirect()->route('bookings.requests')
            ->with('success', 'Payment successful. Funds are now secured in escrow.');
    }
}
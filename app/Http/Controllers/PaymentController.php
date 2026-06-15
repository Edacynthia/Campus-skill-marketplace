<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use App\Models\JobApplication;


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

    public function payJobEscrow($id)
    {
        $application = JobApplication::with(['job', 'applicant'])->findOrFail($id);

        if ($application->job->employer_id !== auth()->id()) {
            abort(403);
        }

        if ($application->status !== 'accepted') {
            return back()->with('error', 'You can only fund escrow after accepting an application.');
        }

        if ($application->escrow_status === 'funded') {
            return back()->with('error', 'Escrow has already been funded for this job.');
        }

        $amount = (float) $application->job->salary;

        if ($amount < 100) {
            return back()->with('error', 'Job amount is too low for Paystack escrow. Please use at least ₦100 for testing.');
        }

        $amountInKobo = (int) round($amount * 100);

        $reference = 'JOBESCROW-' . strtoupper(Str::random(12));

        try {
            $response = Http::withToken(config('paystack.secret_key'))
                ->connectTimeout(10)
                ->timeout(20)
                ->post(config('paystack.payment_url') . '/transaction/initialize', [
                    'email' => auth()->user()->email,
                    'amount' => $amountInKobo,
                    'reference' => $reference,
                    'callback_url' => route('jobs.escrow.callback'),
                    'metadata' => [
                        'application_id' => $application->id,
                        'job_id' => $application->job_id,
                        'employer_id' => $application->job->employer_id,
                        'worker_id' => $application->applicant_id,
                        'type' => 'job_escrow',
                    ],
                ]);
        } catch (\Throwable $e) {
            Log::error('Paystack job escrow initialize request failed', [
                'application_id' => $application->id,
                'reference' => $reference,
                'error' => $e->getMessage(),
            ]);

            return back()->with(
                'error',
                'Paystack is taking too long to respond. Please check your internet connection and try again.'
            );
        }

        if (!$response->successful()) {
            Log::error('Paystack job escrow initialize failed', [
                'application_id' => $application->id,
                'reference' => $reference,
                'status' => $response->status(),
                'body' => $response->json(),
            ]);

            return back()->with(
                'error',
                $response->json('message') ?? 'Unable to start job escrow payment.'
            );
        }

        $data = $response->json();

        if (!isset($data['data']['authorization_url'])) {
            Log::error('Paystack authorization URL missing', [
                'application_id' => $application->id,
                'reference' => $reference,
                'body' => $data,
            ]);

            return back()->with('error', 'Paystack did not return a payment link. Please try again.');
        }

        $application->update([
            'paystack_reference' => $reference,
        ]);

        return redirect($data['data']['authorization_url']);
    }

    public function jobEscrowCallback(Request $request)
    {
        $reference = $request->query('reference');

        if (!$reference) {
            return redirect()->route('applications.received')
                ->with('error', 'Payment reference missing.');
        }

        try {
            $response = Http::withToken(config('paystack.secret_key'))
                ->connectTimeout(10)
                ->timeout(20)
                ->get(config('paystack.payment_url') . '/transaction/verify/' . $reference);
        } catch (\Throwable $e) {
            Log::error('Paystack job escrow verification request failed', [
                'reference' => $reference,
                'error' => $e->getMessage(),
            ]);

            return redirect()->route('applications.received')
                ->with('error', 'Payment verification timed out. Please refresh your received applications page in a moment.');
        }

        if (!$response->successful()) {
            Log::error('Paystack job escrow verification failed', [
                'reference' => $reference,
                'status' => $response->status(),
                'body' => $response->json(),
            ]);

            return redirect()->route('applications.received')
                ->with('error', $response->json('message') ?? 'Job escrow payment verification failed.');
        }

        $data = $response->json();

        if (($data['data']['status'] ?? null) !== 'success') {
            return redirect()->route('applications.received')
                ->with('error', 'Payment was not successful.');
        }

        $application = JobApplication::with(['job', 'applicant'])
            ->where('paystack_reference', $reference)
            ->first();

        if (!$application) {
            Log::error('Job application not found for Paystack reference', [
                'reference' => $reference,
            ]);

            return redirect()->route('applications.received')
                ->with('error', 'Payment was successful, but the application record was not found. Please contact admin.');
        }

        if ($application->escrow_status === 'funded') {
            return redirect()->route('applications.received')
                ->with('success', 'Job escrow payment was already verified.');
        }

        $amount = (float) $application->job->salary;

        $feePercent = 5;
        $platformFee = round(($amount * $feePercent) / 100, 2);
        $workerPayout = round($amount - $platformFee, 2);

        $application->update([
            'escrow_status' => 'funded',
            'escrow_paid_at' => now(),
            'escrow_amount' => $amount,
            'platform_fee_percent' => $feePercent,
            'platform_fee' => $platformFee,
            'worker_payout' => $workerPayout,
        ]);

        Notification::createNotification(
            $application->applicant_id,
            'job_escrow_funded',
            'Job Escrow Payment Secured',
            'Payment has been secured for the job "' . $application->job->title . '". You may begin work.',
            route('applications.mine')
        );

        Notification::createNotification(
            $application->job->employer_id,
            'job_escrow_funded',
            'Job Escrow Payment Successful',
            'Your job payment has been secured in escrow.',
            route('applications.received')
        );

        return redirect()->route('applications.received')
            ->with('success', 'Job escrow payment successful. Worker can now begin work.');
    }
}

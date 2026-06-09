<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class PaystackTestController extends Controller
{
    public function index()
    {
        return view('paystack-test.index');
    }

    public function initialize(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'amount' => 'required|numeric|min:100',
        ]);

        $reference = 'TEST-' . strtoupper(Str::random(12));

        $response = Http::withToken(env('PAYSTACK_SECRET_KEY'))
            ->post(env('PAYSTACK_PAYMENT_URL') . '/transaction/initialize', [
                'email' => $request->email,
                'amount' => $request->amount * 100,
                'reference' => $reference,
                'callback_url' => route('paystack.test.callback'),
            ]);

        if (!$response->successful()) {
            return back()->with('error', 'Could not initialize Paystack payment.');
        }

        $data = $response->json();

        return redirect($data['data']['authorization_url']);
    }

    public function callback(Request $request)
    {
        $reference = $request->query('reference');

        $response = Http::withToken(env('PAYSTACK_SECRET_KEY'))
            ->get(env('PAYSTACK_PAYMENT_URL') . '/transaction/verify/' . $reference);

        if (!$response->successful()) {
            return redirect()->route('paystack.test.index')
                ->with('error', 'Payment verification failed.');
        }

        $data = $response->json();

        return view('paystack-test.result', [
            'payment' => $data['data'],
        ]);
    }
}
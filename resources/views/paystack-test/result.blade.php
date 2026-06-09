@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-12">
    <div class="max-w-lg mx-auto bg-white p-8 rounded-2xl shadow">

        <h1 class="text-2xl font-bold text-green-700 mb-4">
            Payment Verified
        </h1>

        <div class="space-y-3 text-sm">
            <p><strong>Status:</strong> {{ $payment['status'] }}</p>
            <p><strong>Reference:</strong> {{ $payment['reference'] }}</p>
            <p><strong>Amount:</strong> ₦{{ number_format($payment['amount'] / 100) }}</p>
            <p><strong>Email:</strong> {{ $payment['customer']['email'] ?? 'N/A' }}</p>
        </div>

        <a href="{{ route('paystack.test.index') }}"
           class="block mt-6 text-center py-3 bg-[#1e3a8a] text-white rounded-xl">
            Test Again
        </a>
    </div>
</div>
@endsection
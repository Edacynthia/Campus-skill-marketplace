@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-12">
    <div class="max-w-lg mx-auto bg-white p-8 rounded-2xl shadow">

        <h1 class="text-2xl font-bold text-gray-800 mb-2">
            Paystack Test Payment
        </h1>

        <p class="text-gray-500 mb-6">
            This is only for testing Paystack. No real money will be charged in test mode.
        </p>

        @if(session('error'))
            <div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl">
                {{ session('error') }}
            </div>
        @endif

        <form method="POST" action="{{ route('paystack.test.pay') }}" class="space-y-5">
            @csrf

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Email
                </label>

                <input type="email"
                       name="email"
                       value="{{ auth()->user()->email ?? old('email') }}"
                       required
                       class="w-full px-4 py-3 border rounded-xl">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Amount ₦
                </label>

                <input type="number"
                       name="amount"
                       value="1000"
                       min="100"
                       required
                       class="w-full px-4 py-3 border rounded-xl">
            </div>

            <button type="submit"
                    class="w-full py-3 bg-[#1e3a8a] text-white rounded-xl font-semibold hover:bg-[#0f2b5e]">
                Pay with Paystack Test Mode
            </button>
        </form>

        <div class="mt-6 bg-blue-50 border border-blue-200 p-4 rounded-xl text-sm text-blue-800">
            <p class="font-semibold mb-2">Use this Paystack test card:</p>
            <p>Card: 4084 0840 8408 4081</p>
            <p>Expiry: Any future date</p>
            <p>CVV: 408</p>
        </div>
    </div>
</div>
@endsection
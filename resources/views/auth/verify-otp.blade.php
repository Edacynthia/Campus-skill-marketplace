@extends('layouts.guest')

@section('content')
<div class="min-h-screen bg-gray-50 flex items-center justify-center py-12 px-6">
    <div class="max-w-md w-full bg-white rounded-3xl shadow-xl overflow-hidden">

        <div class="bg-[#1e3a8a] text-white p-8">
            <div class="flex items-center gap-3">
                <i class="fa-solid fa-shield-halved text-2xl"></i>
                <div>
                    <h1 class="text-2xl font-bold">Verify Your Email</h1>
                    <p class="text-blue-100">Enter the OTP sent to {{ $email }}</p>
                </div>
            </div>
        </div>

        <div class="p-8">
            <div class="bg-blue-50 border border-blue-200 rounded-xl p-4 mb-6">
                <div class="flex items-start gap-3">
                    <i class="fa-solid fa-envelope text-blue-600 mt-1"></i>
                    <p class="text-sm text-blue-800">
                        We sent a 6-digit code to <strong>{{ $email }}</strong>.
                        It expires in <strong>10 minutes</strong>.
                    </p>
                </div>
            </div>

            @if (session('success'))
                <div class="mb-6 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-xl">
                    <i class="fa-solid fa-check-circle mr-2"></i>
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="mb-6 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-xl">
                    <i class="fa-solid fa-exclamation-circle mr-2"></i>
                    {{ session('error') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-6 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-xl">
                    <i class="fa-solid fa-exclamation-circle mr-2"></i>
                    Please correct the error below.
                </div>
            @endif

            <form method="POST" action="{{ route('otp.verify') }}" class="space-y-6">
                @csrf

                <input type="hidden" name="email" value="{{ $email }}" required>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">OTP CODE</label>

                    <div class="relative">
                        <input type="text"
                               name="otp"
                               value="{{ old('otp') }}"
                               required
                               maxlength="6"
                               inputmode="numeric"
                               autocomplete="one-time-code"
                               placeholder="Enter 6-digit code"
                               class="w-full px-4 py-4 border rounded-2xl text-center text-2xl font-mono tracking-widest focus:outline-none focus:ring-2
                               @error('otp')
                                   border-red-400 focus:border-red-500 focus:ring-red-200
                               @else
                                   border-gray-300 focus:border-[#1e3a8a] focus:ring-[#1e3a8a]/20
                               @enderror">

                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <i class="fa-solid fa-key text-gray-400"></i>
                        </div>
                    </div>

                    @error('otp')
                        <p class="text-red-600 text-sm mt-2">{{ $message }}</p>
                    @else
                        <p class="text-xs text-gray-500 mt-1">Enter the 6-digit code from your email</p>
                    @enderror
                </div>

                <div class="space-y-3">
                    <button type="submit"
                            class="w-full py-4 bg-[#1e3a8a] hover:bg-[#0f2b5e] text-white font-semibold rounded-2xl transition-all flex items-center justify-center gap-2">
                        <i class="fa-solid fa-check-circle"></i>
                        Verify & Login
                    </button>

                    <div class="text-center">
                        <a href="{{ route('otp.request.form') }}"
                           class="text-[#1e3a8a] hover:underline text-sm font-medium">
                            Didn't receive code? Resend OTP
                        </a>
                    </div>
                </div>
            </form>

            <div class="text-center pt-4 mt-6 border-t">
                <a href="{{ route('login') }}"
                   class="text-gray-500 hover:text-gray-700 text-sm flex items-center justify-center gap-2">
                    <i class="fa-solid fa-arrow-left"></i>
                    Back to Login
                </a>
            </div>
        </div>
    </div>
</div>

<style>
input[type="text"][name="otp"] {
    letter-spacing: 0.5em;
    font-weight: 600;
}

input[type="text"][name="otp"]:focus {
    letter-spacing: 0.3em;
}
</style>
@endsection
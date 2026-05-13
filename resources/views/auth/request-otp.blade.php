@extends('layouts.guest')

@section('content')
<div class="min-h-screen bg-gray-50 flex items-center justify-center py-12 px-6">
    <div class="max-w-md w-full bg-white rounded-3xl shadow-xl overflow-hidden">

        <div class="bg-[#1e3a8a] text-white p-8">
            <div class="flex items-center gap-3">
                <i class="fa-solid fa-envelope text-2xl"></i>
                <div>
                    <h1 class="text-2xl font-bold">Request OTP</h1>
                    <p class="text-blue-100">Enter your email to receive a verification code</p>
                </div>
            </div>
        </div>

        <div class="p-8">
            <div class="text-center mb-6">
                <a href="{{ route('home') }}" class="inline-flex items-center justify-center gap-2">
                    <span class="text-2xl font-bold text-[#1e3a8a]">Campus</span>
                    <span class="text-2xl font-bold text-emerald-600">Connect</span>
                </a>
                <p class="text-xs text-gray-500 mt-1">Secure campus marketplace access</p>
            </div>

            <div class="bg-blue-50 border border-blue-200 rounded-xl p-4 mb-6">
                <div class="flex items-start gap-3">
                    <i class="fa-solid fa-info-circle text-blue-600 mt-1"></i>
                    <div>
                        <p class="text-sm text-blue-800 font-medium">We'll send a 6-digit code to your email</p>
                        <p class="text-xs text-blue-600 mt-1">This adds an extra layer of security to your account.</p>
                    </div>
                </div>
            </div>

            @if (session('success'))
                <div class="mb-6 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-xl">
                    <div class="flex items-center gap-2">
                        <i class="fa-solid fa-check-circle"></i>
                        <span>{{ session('success') }}</span>
                    </div>
                </div>
            @endif

            @if (session('error'))
                <div class="mb-6 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-xl">
                    <div class="flex items-center gap-2">
                        <i class="fa-solid fa-exclamation-circle"></i>
                        <span>{{ session('error') }}</span>
                    </div>
                </div>
            @endif

            <form method="POST" action="{{ route('otp.send') }}" class="space-y-6 auth-submit-form">
                @csrf

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">EMAIL ADDRESS</label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">@</span>
                        <input type="email"
                               name="email"
                               required
                               placeholder="yourname@youruniversity.edu.ng or your@email.com"
                               value="{{ old('email') }}"
                               class="w-full pl-9 pr-4 py-4 border border-gray-300 rounded-2xl focus:outline-none focus:border-[#1e3a8a] transition-all">
                    </div>
                    <p class="text-xs text-gray-500 mt-1">Enter your registered email address</p>
                </div>

                <button type="submit"
                        data-loading-text="Sending OTP..."
                        class="auth-submit-btn w-full py-4 bg-[#1e3a8a] hover:bg-[#0f2b5e] text-white font-semibold rounded-2xl transition-all flex items-center justify-center gap-2">
                    <i class="fa-solid fa-paper-plane"></i>
                    <span>Send OTP Code</span>
                </button>
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

<script>
document.querySelectorAll('.auth-submit-form').forEach(form => {
    form.addEventListener('submit', function () {
        const button = form.querySelector('.auth-submit-btn');

        if (!button) return;

        button.disabled = true;
        button.classList.add('opacity-75', 'cursor-not-allowed');

        const loadingText = button.dataset.loadingText || 'Processing...';

        button.innerHTML = `
            <i class="fa-solid fa-spinner fa-spin"></i>
            <span>${loadingText}</span>
        `;
    });
});
</script>
@endsection
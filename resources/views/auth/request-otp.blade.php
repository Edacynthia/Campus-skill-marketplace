@extends('layouts.guest')

@section('content')
<div class="min-h-screen bg-gray-50 flex items-center justify-center py-12 px-6">
    <div class="max-w-md w-full bg-white rounded-3xl shadow-xl overflow-hidden">
        
        <!-- Header -->
        <div class="bg-[#1e3a8a] text-white p-8">
            <div class="flex items-center gap-3">
                <i class="fa-solid fa-envelope text-2xl"></i>
                <div>
                    <h1 class="text-2xl font-bold">Request OTP</h1>
                    <p class="text-blue-100">Enter your email to receive a verification code</p>
                </div>
            </div>
        </div>

        <!-- OTP Request Form -->
        <div class="p-8">
            <!-- Info Message -->
            <div class="bg-blue-50 border border-blue-200 rounded-xl p-4 mb-6">
                <div class="flex items-start gap-3">
                    <i class="fa-solid fa-info-circle text-blue-600 mt-1"></i>
                    <div>
                        <p class="text-sm text-blue-800 font-medium">We'll send a 6-digit code to your email</p>
                        <p class="text-xs text-blue-600 mt-1">This adds an extra layer of security to your account.</p>
                    </div>
                </div>
            </div>

            <!-- Success/Error Messages -->
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

            <!-- Form -->
            <form method="POST" action="{{ route('otp.send') }}" class="space-y-6">
                @csrf
                
                <!-- Email Input -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">EMAIL ADDRESS</label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">@</span>
                        <input type="email" name="email" required
                               placeholder="yourname@youruniversity.edu.ng or your@email.com"
                               value="{{ old('email') }}"
                               class="w-full pl-9 pr-4 py-4 border border-gray-300 rounded-2xl focus:outline-none focus:border-[#1e3a8a] transition-all">
                    </div>
                    <p class="text-xs text-gray-500 mt-1">Enter your registered email address</p>
                </div>

                <!-- Submit Button -->
                <button type="submit"
                        class="w-full py-4 bg-[#1e3a8a] hover:bg-[#0f2b5e] text-white font-semibold rounded-2xl transition-all flex items-center justify-center gap-2">
                    <i class="fa-solid fa-paper-plane"></i>
                    Send OTP Code
                </button>
            </form>

            <!-- Back to Login -->
            <div class="text-center pt-4 border-t">
                <a href="{{ route('login') }}" 
                   class="text-gray-500 hover:text-gray-700 text-sm flex items-center justify-center gap-2">
                    <i class="fa-solid fa-arrow-left"></i>
                    Back to Login
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

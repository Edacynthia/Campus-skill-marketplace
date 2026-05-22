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
            <div class="text-center mb-6">
                <a href="{{ route('home') }}" class="inline-flex items-center justify-center gap-2">
                    <span class="text-2xl font-bold text-[#1e3a8a]">Campus</span>
                    <span class="text-2xl font-bold text-emerald-600">Connect</span>
                </a>
                <p class="text-xs text-gray-500 mt-1">Secure campus marketplace access</p>
            </div>

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

            <form method="POST" action="{{ route('otp.verify') }}" class="space-y-6 auth-submit-form">
                @csrf

                <input type="hidden" name="email" value="{{ $email }}" required>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">OTP CODE</label>

                    <div class="flex justify-center gap-3">

    @for($i = 0; $i < 6; $i++)
        <input type="text"
               maxlength="1"
               inputmode="numeric"
               pattern="[0-9]*"
               class="otp-input w-14 h-16 text-center text-2xl font-bold border-2 border-gray-300 rounded-2xl focus:border-[#1e3a8a] focus:ring-4 focus:ring-[#1e3a8a]/10 outline-none transition-all"
               data-index="{{ $i }}">
    @endfor

</div>

<input type="hidden" name="otp" id="otp-hidden-input">

                    @error('otp')
                        <p class="text-red-600 text-sm mt-2">{{ $message }}</p>
                    @else
                        <p class="text-xs text-gray-500 mt-1">Enter the 6-digit code from your email</p>
                    @enderror
                </div>

                <div class="space-y-3">
                    <button type="submit"
                            data-loading-text="Verifying..."
                            class="auth-submit-btn w-full py-4 bg-[#1e3a8a] hover:bg-[#0f2b5e] text-white font-semibold rounded-2xl transition-all flex items-center justify-center gap-2">
                        <i class="fa-solid fa-check-circle"></i>
                        <span>Verify & Login</span>
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
.otp-input {
    box-shadow: 0 2px 10px rgba(0,0,0,0.04);
}

.otp-input:focus {
    transform: translateY(-2px);
}

.otp-input.filled {
    border-color: #1e3a8a;
    background-color: #eff6ff;
}
</style>

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

const otpInputs = document.querySelectorAll('.otp-input');
const hiddenOtpInput = document.getElementById('otp-hidden-input');

otpInputs.forEach((input, index) => {

    input.addEventListener('input', (e) => {

        input.value = input.value.replace(/[^0-9]/g, '');

        if (input.value !== '') {
            input.classList.add('filled');

            if (index < otpInputs.length - 1) {
                otpInputs[index + 1].focus();
            }
        } else {
            input.classList.remove('filled');
        }

        updateHiddenOTP();
    });

    input.addEventListener('keydown', (e) => {

        if (e.key === 'Backspace' && input.value === '') {

            if (index > 0) {
                otpInputs[index - 1].focus();
            }
        }
    });

    input.addEventListener('paste', (e) => {

        e.preventDefault();

        const pastedData = (e.clipboardData || window.clipboardData)
            .getData('text')
            .replace(/\D/g, '')
            .slice(0, 6);

        pastedData.split('').forEach((digit, i) => {

            if (otpInputs[i]) {
                otpInputs[i].value = digit;
                otpInputs[i].classList.add('filled');
            }
        });

        updateHiddenOTP();

        const nextEmpty = [...otpInputs].find(input => input.value === '');

        if (nextEmpty) {
            nextEmpty.focus();
        }
    });

});

function updateHiddenOTP() {

    let otp = '';

    otpInputs.forEach(input => {
        otp += input.value;
    });

    hiddenOtpInput.value = otp;
}

</script>
@endsection
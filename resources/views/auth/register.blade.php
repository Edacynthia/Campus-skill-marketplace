@extends('layouts.guest')

@section('content')
<div class="min-h-screen bg-gray-50 flex items-center justify-center py-12 px-6">
    <div class="max-w-6xl w-full grid md:grid-cols-2 gap-10 bg-white rounded-3xl shadow-xl overflow-hidden">

        <div class="bg-[#1e3a8a] text-white p-10 lg:p-16 flex flex-col justify-between">
            <div>
                <div class="flex items-center gap-2 mb-8">
                    <span class="text-3xl font-bold text-white">Campus</span>
                    <span class="text-3xl font-bold text-emerald-400">Connect</span>
                </div>

                <div class="inline-flex items-center gap-2 bg-white/20 px-4 py-2 rounded-full mb-8">
                    <i class="fa-solid fa-users"></i>
                    <span class="text-sm font-medium">Campus Community</span>
                </div>

                <h1 class="text-5xl font-bold leading-tight mb-6">
                    Create Your Account
                </h1>

                <p class="text-lg text-blue-100">
                    Join students and staff trading skills, services, and opportunities on campus.
                </p>
            </div>

            <div class="space-y-8 mt-12">
                <div id="university-note" class="flex gap-4">
                    <div class="text-4xl">✅</div>
                    <div>
                        <h4 class="font-semibold">University Email</h4>
                        <p class="text-blue-100 text-sm">Students & Staff with university email are automatically approved.</p>
                    </div>
                </div>

                <div id="external-note" class="flex gap-4 hidden">
                    <div class="text-4xl">📸</div>
                    <div>
                        <h4 class="font-semibold">External User</h4>
                        <p class="text-blue-100 text-sm">You need to upload a passport photo and wait for admin approval.</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="p-10 lg:p-16">
            <div class="mb-8">
                <h2 class="text-3xl font-semibold text-gray-800 mb-2">Get Started</h2>
                <p class="text-gray-600">Create your Campus Connect account</p>
            </div>

            @if ($errors->any())
                <div class="bg-red-100 text-red-700 p-4 rounded-xl mb-6">
                    <ul class="space-y-1">
                        @foreach ($errors->all() as $error)
                            <li class="text-sm">{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data" class="auth-submit-form">
                @csrf

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">First Name</label>
                        <input type="text"
                               name="first_name"
                               value="{{ old('first_name') }}"
                               required
                               class="w-full px-4 py-4 border border-gray-300 rounded-2xl focus:outline-none focus:border-[#1e3a8a]">

                        @error('first_name')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Last Name</label>
                        <input type="text"
                               name="last_name"
                               value="{{ old('last_name') }}"
                               required
                               class="w-full px-4 py-4 border border-gray-300 rounded-2xl focus:outline-none focus:border-[#1e3a8a]">

                        @error('last_name')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mt-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                    <input type="email"
                           name="email"
                           id="email"
                           value="{{ old('email') }}"
                           required
                           placeholder="yourname@youruniversity.edu.ng"
                           class="w-full px-4 py-4 border border-gray-300 rounded-2xl focus:outline-none focus:border-[#1e3a8a]">

                    @error('email')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror

                    <p id="email-feedback" class="text-xs mt-1.5 min-h-[20px]"></p>
                </div>

                <div id="passport-field" class="mt-6 hidden">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Passport Photograph <span class="text-red-500">*</span>
                    </label>

                    <input type="file"
                           name="passport_photo"
                           accept="image/*"
                           class="w-full px-4 py-4 border border-gray-300 rounded-2xl">

                    <p class="text-xs text-gray-500 mt-1">
                        Upload a clear photo so Campus Connect can verify your account.
                    </p>

                    @error('passport_photo')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mt-6 relative">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Password</label>

                    <div class="relative">
                        <input type="password"
                               name="password"
                               id="password"
                               required
                               class="w-full px-4 py-4 pr-12 border border-gray-300 rounded-2xl focus:outline-none focus:border-[#1e3a8a]">

                        <button type="button"
                                onclick="togglePasswordVisibility('password')"
                                aria-label="Show or hide password"
                                class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 focus:outline-none transition-colors">
                            <i id="password-toggle-icon" class="fa-solid fa-eye"></i>
                        </button>
                    </div>

                    @error('password')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror

                    <div id="password-requirements" class="hidden mt-3 text-sm">
                        <p class="text-gray-600 mb-2 font-medium">Password must contain:</p>
                        <ul class="space-y-1 text-gray-500 text-[13px]">
                            <li id="req-length">• At least 8 characters</li>
                            <li id="req-upper">• One uppercase letter</li>
                            <li id="req-lower">• One lowercase letter</li>
                            <li id="req-number">• One number</li>
                            <li id="req-symbol">• One special character</li>
                        </ul>
                    </div>
                </div>

                <div class="mt-6 relative">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Confirm Password</label>

                    <div class="relative">
                        <input type="password"
                               name="password_confirmation"
                               id="password_confirmation"
                               required
                               class="w-full px-4 py-4 pr-12 border border-gray-300 rounded-2xl focus:outline-none focus:border-[#1e3a8a]">

                        <button type="button"
                                onclick="togglePasswordVisibility('password_confirmation')"
                                aria-label="Show or hide confirm password"
                                class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 focus:outline-none transition-colors">
                            <i id="password_confirmation-toggle-icon" class="fa-solid fa-eye"></i>
                        </button>
                    </div>

                    @error('password_confirmation')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit"
                        data-loading-text="Creating Account..."
                        class="auth-submit-btn mt-8 w-full py-4 bg-[#1e3a8a] hover:bg-[#0f2b5e] text-white font-semibold rounded-2xl transition-all flex items-center justify-center gap-2">
                    <i class="fa-solid fa-user-plus"></i>
                    <span>Create Account</span>
                </button>
            </form>

            <div class="text-center mt-8">
                <p class="text-gray-600">
                    Already have an account?
                    <a href="{{ route('login') }}" class="text-[#1e3a8a] font-medium hover:underline">Sign in</a>
                </p>

                <a href="{{ route('home') }}"
                   class="mt-4 text-gray-500 hover:text-gray-700 text-sm inline-flex items-center justify-center gap-2">
                    <i class="fa-solid fa-arrow-left"></i>
                    Back to Campus Connect
                </a>
            </div>
        </div>
    </div>
</div>

<script>
function checkEmail() {
    const email = document.getElementById('email').value.trim().toLowerCase();
    const universityDomain = '@edouniversity.edu.ng';

    if (email.endsWith(universityDomain)) {
        document.getElementById('passport-field').classList.add('hidden');
        document.getElementById('university-note').classList.remove('hidden');
        document.getElementById('external-note').classList.add('hidden');
        document.getElementById('email-feedback').innerHTML = `<span class="text-emerald-600">✓ University email - Auto approved</span>`;
    } else if (email.includes('@')) {
        document.getElementById('passport-field').classList.remove('hidden');
        document.getElementById('university-note').classList.add('hidden');
        document.getElementById('external-note').classList.remove('hidden');
        document.getElementById('email-feedback').innerHTML = `<span class="text-amber-600">⚠ Non-university email - Passport required</span>`;
    } else {
        document.getElementById('passport-field').classList.add('hidden');
        document.getElementById('university-note').classList.remove('hidden');
        document.getElementById('external-note').classList.add('hidden');
        document.getElementById('email-feedback').innerHTML = '';
    }
}

document.getElementById('email').addEventListener('input', checkEmail);

document.addEventListener('DOMContentLoaded', function () {
    checkEmail();

    setTimeout(function () {
        const errorDiv = document.querySelector('.bg-red-100');
        if (errorDiv) {
            errorDiv.style.transition = 'opacity 0.5s';
            errorDiv.style.opacity = '0';

            setTimeout(function () {
                errorDiv.remove();
            }, 500);
        }
    }, 5000);
});

const passwordField = document.getElementById('password');
const requirementsDiv = document.getElementById('password-requirements');

passwordField.addEventListener('focus', () => requirementsDiv.classList.remove('hidden'));

passwordField.addEventListener('input', function () {
    const value = this.value;

    document.getElementById('req-length').style.color = value.length >= 8 ? '#10b981' : '#6b7280';
    document.getElementById('req-upper').style.color = /[A-Z]/.test(value) ? '#10b981' : '#6b7280';
    document.getElementById('req-lower').style.color = /[a-z]/.test(value) ? '#10b981' : '#6b7280';
    document.getElementById('req-number').style.color = /[0-9]/.test(value) ? '#10b981' : '#6b7280';
    document.getElementById('req-symbol').style.color = /[^A-Za-z0-9]/.test(value) ? '#10b981' : '#6b7280';
});

function togglePasswordVisibility(fieldId) {
    const field = document.getElementById(fieldId);
    const icon = document.getElementById(fieldId + '-toggle-icon');

    if (field.type === 'password') {
        field.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        field.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
}

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
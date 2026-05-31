@extends('layouts.guest')

@section('content')
<div class="min-h-screen bg-gray-50 flex items-center justify-center py-12 px-6">
    <div class="max-w-6xl w-full bg-white rounded-3xl shadow-xl overflow-hidden">

        <div class="md:hidden bg-[#1e3a8a] text-white p-8">
            <div class="flex items-center gap-2 mb-6">
                <span class="text-2xl font-bold text-white">Campus</span>
                <span class="text-2xl font-bold text-emerald-400">Connect</span>
            </div>

            <div class="inline-flex items-center gap-2 bg-white/20 px-4 py-2 rounded-full mb-6">
                <i class="fa-solid fa-shield-halved"></i>
                <span class="text-sm font-medium">Trusted University Marketplace</span>
            </div>

            <h1 class="text-3xl font-bold leading-tight mb-4">
                Welcome Back
            </h1>

            <p class="text-base text-blue-100 leading-relaxed mb-6">
                Sign in to connect with your campus community and unlock new opportunities.
            </p>

            <div class="grid grid-cols-2 gap-4 mb-6">
                <div class="bg-white/10 backdrop-blur-sm p-4 rounded-xl text-center">
                    <i class="fa-solid fa-graduation-cap text-2xl mb-2"></i>
                    <h4 class="font-semibold text-sm">Students & Faculty</h4>
                    <p class="text-blue-100 text-xs mt-1">Institutional email</p>
                </div>

                <div class="bg-white/10 backdrop-blur-sm p-4 rounded-xl text-center">
                    <i class="fa-solid fa-briefcase text-2xl mb-2"></i>
                    <h4 class="font-semibold text-sm">University Staff</h4>
                    <p class="text-blue-100 text-xs mt-1">Campus employees</p>
                </div>
            </div>

            <div class="bg-white/10 backdrop-blur-sm p-4 rounded-xl mt-auto">
                <p class="italic text-blue-100 text-sm">
                    "Empowering our university community through collaborative opportunities."
                </p>
                <p class="text-xs mt-2 text-blue-200">- The Registrar's Office</p>
            </div>
        </div>

        <div class="grid md:grid-cols-2">

            <div class="hidden md:block bg-[#1e3a8a] text-white p-10 lg:p-16 flex flex-col justify-between relative">
                <div>
                    <div class="flex items-center gap-2 mb-8">
                        <span class="text-3xl font-bold text-white">Campus</span>
                        <span class="text-3xl font-bold text-emerald-400">Connect</span>
                    </div>

                    <div class="inline-flex items-center gap-2 bg-white/20 px-4 py-2 rounded-full mb-8">
                        <i class="fa-solid fa-shield-halved"></i>
                        <span class="text-sm font-medium">Trusted University Marketplace</span>
                    </div>

                    <h1 class="text-5xl font-bold leading-tight mb-6">
                        Welcome Back
                    </h1>

                    <p class="text-lg text-blue-100 leading-relaxed">
                        Sign in to connect with your campus community and unlock new opportunities.
                    </p>
                </div>

                <div class="space-y-6 mt-12">
                    <div class="flex gap-4">
                        <div class="w-10 h-10 bg-white/20 rounded-2xl flex items-center justify-center flex-shrink-0">
                            <i class="fa-solid fa-graduation-cap text-xl"></i>
                        </div>

                        <div>
                            <h4 class="font-semibold">Students & Faculty</h4>
                            <p class="text-blue-100 text-sm">University members with institutional email addresses</p>
                        </div>
                    </div>

                    <div class="flex gap-4">
                        <div class="w-10 h-10 bg-white/20 rounded-2xl flex items-center justify-center flex-shrink-0">
                            <i class="fa-solid fa-briefcase text-xl"></i>
                        </div>

                        <div>
                            <h4 class="font-semibold">University Staff</h4>
                            <p class="text-blue-100 text-sm">Campus employees without institutional email access</p>
                        </div>
                    </div>
                </div>

                <div class="mt-16 bg-white/10 backdrop-blur-md p-6 rounded-2xl">
                    <p class="italic text-blue-100">
                        "Empowering our university community through collaborative opportunities."
                    </p>
                    <p class="text-xs mt-4 text-blue-200">- The Registrar's Office</p>
                </div>

                <div class="absolute bottom-8 right-8 opacity-10">
                    <i class="fa-solid fa-building-columns text-[180px]"></i>
                </div>
            </div>

            <div class="p-6 md:p-10 lg:p-16 flex flex-col justify-center">
                <div class="hidden md:block mb-8">
                    <h2 class="text-3xl font-semibold text-gray-800">Sign In</h2>
                    <p class="text-gray-600 mt-2">Access your Campus Connect account.</p>
                </div>

                            @if(session('error'))
                <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-2xl">
                    <div class="flex items-center gap-2">
                        <i class="fa-solid fa-exclamation-circle"></i>
                        <p class="text-sm">{{ session('error') }}</p>
                    </div>
                </div>
            @endif

            @if(session('success'))
                <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-2xl">
                    <div class="flex items-center gap-2">
                        <i class="fa-solid fa-check-circle"></i>
                        <p class="text-sm">{{ session('success') }}</p>
                    </div>
                </div>
            @endif

                @if ($errors->any())
                    <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-2xl">
                        <div class="flex items-center gap-2">
                            <i class="fa-solid fa-exclamation-circle"></i>
                            <div>
                                @foreach ($errors->all() as $error)
                                    <p class="text-sm">{{ $error }}</p>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}" class="space-y-6 auth-submit-form">
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
                        <p class="text-xs text-gray-500 mt-1">
                            Students & faculty: use your institutional email • Staff: use your registered email
                        </p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">PASSWORD</label>

                        <div class="relative">
                            <input type="password"
                                   name="password"
                                   id="password"
                                   required
                                   placeholder="••••••"
                                   class="w-full px-4 py-4 pr-12 border border-gray-300 rounded-2xl focus:outline-none focus:border-[#1e3a8a] transition-all">

                            <button type="button"
                                    onclick="togglePasswordVisibility('password')"
                                    aria-label="Show or hide password"
                                    class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 focus:outline-none transition-colors">
                                <i id="password-toggle-icon" class="fa-solid fa-eye"></i>
                            </button>
                        </div>
                    </div>

                    <button type="submit"
                            data-loading-text="Signing In..."
                            class="auth-submit-btn w-full py-4 bg-[#1e3a8a] hover:bg-[#0f2b5e] text-white font-semibold rounded-2xl transition-all flex items-center justify-center gap-2">
                        <span>Sign In</span>
                        <i class="fa-solid fa-arrow-right"></i>
                    </button>
                </form>

                <div class="mt-8 space-y-4 text-center">
                    <div class="text-sm text-gray-500">
                        First time here? We'll automatically verify your department and ID on your first successful login.
                    </div>

                    <div>
                        <a href="#" class="text-[#1e3a8a] hover:underline text-sm">
                            Need help? Contact Registrar Support
                        </a>
                    </div>

                    <div>
                        <a href="{{ route('register') }}" class="text-[#1e3a8a] hover:underline text-sm">
                            Don't have an account? Sign up
                        </a>
                    </div>

                    <div class="pt-4 border-t">
                        <a href="{{ route('home') }}"
                           class="text-gray-500 hover:text-gray-700 text-sm flex items-center justify-center gap-2">
                            <i class="fa-solid fa-arrow-left"></i>
                            Back to Campus Connect
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
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
@extends('layouts.guest')

@section('content')
<div class="min-h-screen w-full bg-gradient-to-br from-blue-50 to-indigo-50 flex items-center justify-center py-12 px-6">
    {{-- Full width container with max width for content readability --}}
    <div class="w-full max-w-7xl mx-auto">
        {{-- Main Card - Full width grid layout for better desktop utilization --}}
        <div class="bg-white rounded-3xl shadow-2xl overflow-hidden">
            <div class="grid md:grid-cols-2">
                {{-- Left Column: Brand & Hero Section --}}
                <div class="bg-[#1e3a8a] text-white p-8 md:p-12 flex flex-col justify-between">
                    <div>
                        <div class="flex items-center gap-3 mb-8">
                            <span class="text-3xl font-bold text-white">Campus</span>
                            <span class="text-3xl font-bold text-emerald-400">Connect</span>
                        </div>

                        <div class="inline-flex items-center gap-2 bg-white/20 px-4 py-2 rounded-full mb-12">
                            <i class="fa-solid fa-hourglass-end"></i>
                            <span class="text-sm font-medium">Account Pending Approval</span>
                        </div>

                        <h1 class="text-4xl md:text-5xl font-bold leading-tight mb-6">
                            Welcome to Campus Connect!
                        </h1>
                        <p class="text-blue-100 text-lg leading-relaxed mb-8">
                            Your account has been successfully created. We're excited to have you join our campus community!
                        </p>
                    </div>

                    {{-- Decorative Element --}}
                    <div class="hidden md:block">
                        <div class="w-32 h-32 bg-emerald-400/10 rounded-full flex items-center justify-center">
                            <i class="fa-solid fa-users text-emerald-300 text-5xl"></i>
                        </div>
                    </div>
                </div>

                {{-- Right Column: Content --}}
                <div class="p-8 md:p-12">
                    {{-- Status section --}}
                    <div class="mb-8">
                        <div class="flex items-center justify-center mb-6">
                            <div class="relative">
                                <div class="absolute inset-0 bg-emerald-400/20 rounded-full blur-xl"></div>
                                <div class="relative w-20 h-20 bg-gradient-to-br from-emerald-400 to-emerald-500 rounded-full flex items-center justify-center">
                                    <i class="fa-solid fa-check text-white text-2xl"></i>
                                </div>
                            </div>
                        </div>

                        <h2 class="text-2xl font-bold text-center text-gray-800 mb-4">
                            Your account has been created successfully!
                        </h2>
                    </div>

                    {{-- Info box --}}
                    <div class="bg-blue-50 border-l-4 border-[#1e3a8a] rounded-r-lg p-6 mb-6">
                        <div class="flex gap-4">
                            <div class="flex-shrink-0">
                                <i class="fa-solid fa-circle-info text-[#1e3a8a] text-xl mt-1"></i>
                            </div>
                            <div>
                                <h3 class="font-semibold text-[#1e3a8a] text-lg mb-2">What happens next?</h3>
                                <p class="text-gray-700 leading-relaxed">
                                    Your account is currently under review by our admin team. We verify all non-university users to maintain the security and integrity of our marketplace. This typically takes 24-48 hours.
                                </p>
                            </div>
                        </div>
                    </div>

                    {{-- What we check --}}
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-3">Our verification process includes:</h3>
                        <div class="space-y-2">
                            <div class="flex items-start gap-3">
                                <div class="flex-shrink-0 w-5 h-5 bg-emerald-400 rounded-full flex items-center justify-center mt-0.5">
                                    <i class="fa-solid fa-check text-white text-xs"></i>
                                </div>
                                <p class="text-gray-700 text-sm">Verification of your uploaded identification document</p>
                            </div>
                            <div class="flex items-start gap-3">
                                <div class="flex-shrink-0 w-5 h-5 bg-emerald-400 rounded-full flex items-center justify-center mt-0.5">
                                    <i class="fa-solid fa-check text-white text-xs"></i>
                                </div>
                                <p class="text-gray-700 text-sm">Confirmation of your identity and academic/staff status</p>
                            </div>
                            <div class="flex items-start gap-3">
                                <div class="flex-shrink-0 w-5 h-5 bg-emerald-400 rounded-full flex items-center justify-center mt-0.5">
                                    <i class="fa-solid fa-check text-white text-xs"></i>
                                </div>
                                <p class="text-gray-700 text-sm">Security check to ensure community guidelines compliance</p>
                            </div>
                        </div>
                    </div>

                    {{-- Why verification --}}
                    <div class="bg-emerald-50 border-l-4 border-emerald-500 rounded-r-lg p-4 mb-6">
                        <div class="flex gap-3">
                            <div class="flex-shrink-0">
                                <i class="fa-solid fa-shield-halved text-emerald-600 text-lg mt-0.5"></i>
                            </div>
                            <div>
                                <h3 class="font-semibold text-emerald-900 text-md mb-1">Why do we verify?</h3>
                                <p class="text-gray-700 text-sm leading-relaxed">
                                    Campus Connect is exclusively for our university community. Verification ensures a safe, trusted marketplace.
                                </p>
                            </div>
                        </div>
                    </div>

                    {{-- FAQ section --}}
                    <div class="mb-6">
                        <h3 class="text-md font-semibold text-gray-800 mb-3">Frequently Asked Questions</h3>
                        <div class="space-y-2">
                            <details class="group border border-gray-200 rounded-lg p-3 cursor-pointer hover:bg-gray-50">
                                <summary class="font-semibold text-gray-800 text-sm flex items-center justify-between">
                                    How long does approval usually take?
                                    <i class="fa-solid fa-chevron-down text-gray-600 group-open:rotate-180 transition text-xs"></i>
                                </summary>
                                <p class="text-gray-600 text-xs mt-2 leading-relaxed">
                                    Most accounts are approved within 24-48 hours. You'll receive an email notification once approved.
                                </p>
                            </details>

                            <details class="group border border-gray-200 rounded-lg p-3 cursor-pointer hover:bg-gray-50">
                                <summary class="font-semibold text-gray-800 text-sm flex items-center justify-between">
                                    Can I log in while waiting for approval?
                                    <i class="fa-solid fa-chevron-down text-gray-600 group-open:rotate-180 transition text-xs"></i>
                                </summary>
                                <p class="text-gray-600 text-xs mt-2 leading-relaxed">
                                    No, you won't be able to access the marketplace until your account is approved.
                                </p>
                            </details>

                            <details class="group border border-gray-200 rounded-lg p-3 cursor-pointer hover:bg-gray-50">
                                <summary class="font-semibold text-gray-800 text-sm flex items-center justify-between">
                                    What if my account is rejected?
                                    <i class="fa-solid fa-chevron-down text-gray-600 group-open:rotate-180 transition text-xs"></i>
                                </summary>
                                <p class="text-gray-600 text-xs mt-2 leading-relaxed">
                                    You'll receive an email explanation. Contact support to appeal or resubmit.
                                </p>
                            </details>
                        </div>
                    </div>

                    {{-- Call to action --}}
                    <div class="bg-gradient-to-r from-[#1e3a8a] to-blue-800 rounded-xl p-5 text-white text-center mb-6">
                        <i class="fa-solid fa-bell text-2xl mb-2"></i>
                        <h3 class="text-md font-semibold mb-1">We'll notify you soon!</h3>
                        <p class="text-blue-100 text-xs">
                            Keep an eye on your email inbox for our approval notification.
                        </p>
                    </div>

                    {{-- Action buttons --}}
                    <div class="flex flex-col sm:flex-row gap-3">
                        <a href="{{ route('home') }}" class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-800 font-semibold py-2.5 px-4 rounded-lg transition text-center text-sm">
                            <i class="fa-solid fa-arrow-left mr-2"></i>Back to Home
                        </a>
                        <a href="{{ route('login') }}" class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-800 font-semibold py-2.5 px-4 rounded-lg transition text-center text-sm">
                            <i class="fa-solid fa-sign-in-alt mr-2"></i>Back to Login
                        </a>
                    </div>

                    {{-- Support --}}
                    <div class="mt-6 pt-5 border-t border-gray-200 text-center">
                        <p class="text-gray-500 text-xs mb-2">
                            <i class="fa-solid fa-question-circle text-[#1e3a8a]"></i>
                        </p>
                        <p class="text-gray-500 text-xs">
                            Have questions?
                            <a href="mailto:support@campusconnect.edu.ng" class="text-[#1e3a8a] font-semibold hover:underline">
                                Contact our support team
                            </a>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Footer message --}}
        <div class="mt-6 text-center">
            <p class="text-gray-500 text-xs">
                <i class="fa-solid fa-lock text-[#1e3a8a] text-xs"></i>
                Your data is secure and only used for verification purposes.
            </p>
        </div>
    </div>
</div>
@endsection
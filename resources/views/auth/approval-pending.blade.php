@extends('layouts.guest')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-50 flex items-center justify-center py-12 px-6">
    <div class="max-w-2xl w-full">
        <!-- Animated pending card -->
        <div class="bg-white rounded-3xl shadow-2xl overflow-hidden">
            <!-- Header with navy blue background -->
            <div class="bg-[#1e3a8a] text-white p-8 md:p-12">
                <div class="flex items-center gap-3 mb-6">
                    <span class="text-3xl font-bold text-white">Campus</span>
                    <span class="text-3xl font-bold text-emerald-400">Connect</span>
                </div>
                
                <div class="inline-flex items-center gap-2 bg-white/20 px-4 py-2 rounded-full mb-8">
                    <i class="fa-solid fa-hourglass-end"></i>
                    <span class="text-sm font-medium">Account Pending Approval</span>
                </div>

                <h1 class="text-4xl font-bold leading-tight">
                    Welcome to Campus Connect!
                </h1>
            </div>

            <!-- Content -->
            <div class="p-8 md:p-12">
                <!-- Status section -->
                <div class="mb-8">
                    <div class="flex items-center justify-center mb-8">
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

                    <p class="text-center text-gray-600 text-lg leading-relaxed mb-6">
                        Thank you for registering with Campus Connect. We're excited to have you join our campus community!
                    </p>
                </div>

                <!-- Info box -->
                <div class="bg-blue-50 border-l-4 border-[#1e3a8a] rounded-r-lg p-6 mb-8">
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

                <!-- What we check -->
                <div class="mb-8">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Our verification process includes:</h3>
                    <div class="space-y-3">
                        <div class="flex items-start gap-3">
                            <div class="flex-shrink-0 w-6 h-6 bg-emerald-400 rounded-full flex items-center justify-center mt-0.5">
                                <i class="fa-solid fa-check text-white text-xs"></i>
                            </div>
                            <p class="text-gray-700">Verification of your uploaded identification document</p>
                        </div>
                        <div class="flex items-start gap-3">
                            <div class="flex-shrink-0 w-6 h-6 bg-emerald-400 rounded-full flex items-center justify-center mt-0.5">
                                <i class="fa-solid fa-check text-white text-xs"></i>
                            </div>
                            <p class="text-gray-700">Confirmation of your identity and academic/staff status</p>
                        </div>
                        <div class="flex items-start gap-3">
                            <div class="flex-shrink-0 w-6 h-6 bg-emerald-400 rounded-full flex items-center justify-center mt-0.5">
                                <i class="fa-solid fa-check text-white text-xs"></i>
                            </div>
                            <p class="text-gray-700">Security check to ensure community guidelines compliance</p>
                        </div>
                    </div>
                </div>

                <!-- Why verification -->
                <div class="bg-emerald-50 border-l-4 border-emerald-500 rounded-r-lg p-6 mb-8">
                    <div class="flex gap-4">
                        <div class="flex-shrink-0">
                            <i class="fa-solid fa-shield-halved text-emerald-600 text-xl mt-1"></i>
                        </div>
                        <div>
                            <h3 class="font-semibold text-emerald-900 text-lg mb-2">Why do we verify?</h3>
                            <p class="text-gray-700 leading-relaxed">
                                Campus Connect is exclusively for our university community. Verification ensures a safe, trusted marketplace where students and staff can confidently connect and collaborate.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- FAQ section -->
                <div class="mb-8">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Frequently Asked Questions</h3>
                    
                    <div class="space-y-3">
                        <details class="group border border-gray-200 rounded-lg p-4 cursor-pointer hover:bg-gray-50">
                            <summary class="font-semibold text-gray-800 flex items-center justify-between">
                                How long does approval usually take?
                                <i class="fa-solid fa-chevron-down text-gray-600 group-open:rotate-180 transition"></i>
                            </summary>
                            <p class="text-gray-600 mt-4 leading-relaxed">
                                Most accounts are approved within 24-48 hours. During peak times, it may take up to 72 hours. You'll receive an email notification once your account is approved.
                            </p>
                        </details>

                        <details class="group border border-gray-200 rounded-lg p-4 cursor-pointer hover:bg-gray-50">
                            <summary class="font-semibold text-gray-800 flex items-center justify-between">
                                Can I log in while waiting for approval?
                                <i class="fa-solid fa-chevron-down text-gray-600 group-open:rotate-180 transition"></i>
                            </summary>
                            <p class="text-gray-600 mt-4 leading-relaxed">
                                No, you won't be able to access the marketplace until your account is approved. However, you can check your profile and other settings once you log in after approval.
                            </p>
                        </details>

                        <details class="group border border-gray-200 rounded-lg p-4 cursor-pointer hover:bg-gray-50">
                            <summary class="font-semibold text-gray-800 flex items-center justify-between">
                                What if my account is rejected?
                                <i class="fa-solid fa-chevron-down text-gray-600 group-open:rotate-180 transition"></i>
                            </summary>
                            <p class="text-gray-600 mt-4 leading-relaxed">
                                If your account is rejected, you'll receive an email explanation. You can contact our support team to appeal the decision or resubmit with corrected information.
                            </p>
                        </details>
                    </div>
                </div>

                <!-- Call to action -->
                <div class="bg-gradient-to-r from-[#1e3a8a] to-blue-800 rounded-xl p-8 text-white text-center mb-8">
                    <i class="fa-solid fa-bell text-3xl mb-4"></i>
                    <h3 class="text-xl font-semibold mb-2">We'll notify you soon!</h3>
                    <p class="text-blue-100">
                        Keep an eye on your email inbox for our approval notification. Once approved, you can dive right into Campus Connect!
                    </p>
                </div>

                <!-- Action buttons -->
                <div class="flex flex-col sm:flex-row gap-4">
                    <a href="{{ route('home') }}" class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-800 font-semibold py-3 px-6 rounded-lg transition text-center">
                        <i class="fa-solid fa-arrow-left mr-2"></i>Back to Home
                    </a>
                  <a href="{{ route('login') }}" class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-800 font-semibold py-3 px-6 rounded-lg transition text-center">
                    <i class="fa-solid fa-sign-in-alt mr-2"></i>Back to Login
                  </a>
                </div>

                <!-- Support -->
                <div class="mt-8 pt-8 border-t border-gray-200 text-center">
                    <p class="text-gray-600 mb-3">
                        <i class="fa-solid fa-question-circle text-[#1e3a8a]"></i>
                    </p>
                    <p class="text-gray-600">
                        Have questions? 
                        <a href="mailto:support@campusconnect.edu.ng" class="text-[#1e3a8a] font-semibold hover:underline">
                            Contact our support team
                        </a>
                    </p>
                </div>
            </div>
        </div>

        <!-- Footer message -->
        <p class="text-center text-gray-600 text-sm mt-8">
            <i class="fa-solid fa-lock text-[#1e3a8a]"></i>
            Your data is secure and only used for verification purposes.
        </p>
    </div>
</div>
@endsection

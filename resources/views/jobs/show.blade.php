@extends('layouts.guest')

@section('content')
    <x-navbar />
    

    <!-- Breadcrumb -->
    <div class="bg-gray-50 border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-6 py-4">
            <nav class="flex items-center space-x-2 text-sm">
                <a href="{{ route('home') }}" class="text-gray-500 hover:text-gray-700">Home</a>
                <span class="text-gray-400">/</span>
                <a href="{{ route('jobs.index') }}" class="text-gray-500 hover:text-gray-700">Browse Jobs</a>
                <span class="text-gray-400">/</span>
                <span class="text-gray-900 font-medium">{{ $job->title }}</span>
            </nav>
        </div>
    </div>

    <!-- ==================== FLASH MESSAGES ==================== -->
    <div class="max-w-7xl mx-auto px-6 pt-6" id="flash-container">
        @if (session('success'))
            <div id="flash-success"
                class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-6 py-4 rounded-2xl flex items-center justify-between gap-3 mb-8 shadow-sm">
                <div class="flex items-center gap-3">
                    <i class="fa-solid fa-circle-check text-emerald-600 text-xl flex-shrink-0"></i>
                    <p class="font-medium">{{ session('success') }}</p>
                </div>
                <button onclick="dismissFlash('flash-success')"
                    class="text-emerald-400 hover:text-emerald-700 transition-colors p-1 rounded-full hover:bg-emerald-100 flex-shrink-0"
                    aria-label="Dismiss">
                    <i class="fa-solid fa-xmark text-base"></i>
                </button>
            </div>
        @endif

        @if (session('error'))
            <div id="flash-error"
                class="bg-red-50 border border-red-200 text-red-800 px-6 py-4 rounded-2xl flex items-center justify-between gap-3 mb-8 shadow-sm">
                <div class="flex items-center gap-3">
                    <i class="fa-solid fa-circle-exclamation text-red-600 text-xl flex-shrink-0"></i>
                    <p class="font-medium">{{ session('error') }}</p>
                </div>
                <button onclick="dismissFlash('flash-error')"
                    class="text-red-400 hover:text-red-700 transition-colors p-1 rounded-full hover:bg-red-100 flex-shrink-0"
                    aria-label="Dismiss">
                    <i class="fa-solid fa-xmark text-base"></i>
                </button>
            </div>
        @endif

        @if ($errors->any())
            <div id="flash-validation"
                class="bg-red-50 border border-red-200 text-red-800 px-6 py-4 rounded-2xl flex items-start justify-between gap-3 mb-8 shadow-sm">
                <div class="flex items-start gap-3">
                    <i class="fa-solid fa-triangle-exclamation text-red-600 text-xl flex-shrink-0 mt-0.5"></i>
                    <div>
                        @foreach ($errors->all() as $error)
                            <p class="font-medium">{{ $error }}</p>
                        @endforeach
                    </div>
                </div>
                <button onclick="dismissFlash('flash-validation')"
                    class="text-red-400 hover:text-red-700 transition-colors p-1 rounded-full hover:bg-red-100 flex-shrink-0"
                    aria-label="Dismiss">
                    <i class="fa-solid fa-xmark text-base"></i>
                </button>
            </div>
        @endif
    </div>

    <!-- Job Details -->
    <div class="max-w-7xl mx-auto px-6 py-12">
        <div class="grid lg:grid-cols-3 gap-12">

            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-8">

                <!-- Job Header -->
                <div>
                    <div class="flex items-center gap-3 mb-4">
                        @if (auth()->check() && $job->employer_id === auth()->id())
                            <span class="px-3 py-1 bg-emerald-50 text-emerald-700 text-sm font-semibold rounded-full">
                                YOUR JOB
                            </span>
                        @endif
                        <span class="px-3 py-1 bg-[#1e3a8a]/10 text-[#1e3a8a] text-sm font-semibold rounded-full">
                            {{ $job->category }}
                        </span>
                        @if ($job->urgency === 'urgent')
                            <span class="px-3 py-1 bg-red-50 text-red-600 text-sm font-semibold rounded-full">
                                URGENT
                            </span>
                        @endif
                        @if ($job->type === 'remote')
                            <span class="px-3 py-1 bg-green-50 text-green-600 text-sm font-semibold rounded-full">
                                REMOTE
                            </span>
                        @elseif($job->type === 'off_campus')
                            <span class="px-3 py-1 bg-amber-50 text-amber-600 text-sm font-semibold rounded-full">
                                OFF CAMPUS
                            </span>
                        @endif
                    </div>

                    <h1 class="text-4xl font-bold text-gray-900 mb-4">{{ $job->title }}</h1>

                    <div class="flex items-center gap-6 text-gray-600">
                        <div class="flex items-center gap-2">
                            <i class="fa-solid fa-eye text-gray-400"></i>
                            <span>{{ $job->views_count ?? 0 }} views</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <i class="fa-solid fa-users text-gray-400"></i>
                            <span>{{ $job->applications_count ?? 0 }} applications</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <i class="fa-solid fa-clock text-gray-400"></i>
                            <span>Posted {{ $job->created_at->diffForHumans() }}</span>
                        </div>
                    </div>
                </div>

                <!-- Job Image -->
                @if ($job->image)
                    <div class="rounded-2xl overflow-hidden shadow-lg">
                        <img src="{{ asset('storage/' . $job->image) }}" alt="{{ $job->title }}"
                            class="w-full h-96 object-cover">
                    </div>
                @else
                    <div
                        class="w-full h-96 bg-gradient-to-br from-[#1e3a8a] to-blue-700 rounded-2xl flex items-center justify-center">
                        <i class="fa-solid fa-briefcase text-white text-6xl opacity-50"></i>
                    </div>
                @endif

                <!-- Job Description -->
                <div>
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">Job Description</h2>
                    <div class="prose prose-lg max-w-none text-gray-600">
                        <p>{{ $job->description }}</p>
                    </div>
                </div>

                <!-- Requirements -->
                @if ($job->requirements && is_array($job->requirements) && count($job->requirements) > 0)
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900 mb-4">Requirements</h2>
                        <div class="space-y-3">
                            @foreach ($job->requirements as $requirement)
                                <div class="flex items-start gap-3">
                                    <i class="fa-solid fa-check text-green-500 mt-1"></i>
                                    <p class="text-gray-600">{{ $requirement }}</p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Salary & Location -->
                <div class="bg-gray-50 rounded-2xl p-6">
                    <h3 class="text-xl font-bold text-gray-900 mb-4">Compensation & Location</h3>
                    <div class="grid md:grid-cols-2 gap-6">
                        <div>
                            <p class="text-sm text-gray-500 mb-2">Salary</p>
                            <div class="flex items-baseline gap-2">
                                <span class="text-3xl font-bold text-[#1e3a8a]">{{ $job->formatted_salary }}</span>
                                @if ($job->salary_type === 'hourly')
                                    <span class="text-gray-600">per hour</span>
                                @elseif($job->salary_type === 'monthly')
                                    <span class="text-gray-600">per month</span>
                                @else
                                    <span class="text-gray-600">fixed</span>
                                @endif
                            </div>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 mb-2">Location</p>
                            <div class="flex items-center gap-2">
                                @if ($job->type === 'remote')
                                    <i class="fa-solid fa-globe text-[#1e3a8a]"></i>
                                    <span class="font-medium text-gray-900">Remote</span>
                                @elseif($job->type === 'off_campus')
                                    <i class="fa-solid fa-location-dot text-[#1e3a8a]"></i>
                                    <span class="font-medium text-gray-900">{{ $job->location ?: 'Off Campus' }}</span>
                                @else
                                    <i class="fa-solid fa-building text-[#1e3a8a]"></i>
                                    <span class="font-medium text-gray-900">On Campus</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    @if ($job->deadline)
                        <div class="mt-4 pt-4 border-t border-gray-200">

                            <div class="flex items-center justify-between mb-4">
                                <div>
                                    <p class="text-sm text-gray-500">Application Deadline</p>

                                    <p class="font-medium text-gray-900">
                                        {{ $job->deadline->format('F j, Y • g:i A') }}
                                    </p>
                                </div>

                                @if ($job->deadline->isPast())
                                    <span class="px-3 py-1 bg-red-100 text-red-700 rounded-full text-sm font-medium">
                                        Deadline Passed
                                    </span>
                                @endif
                            </div>

                            @if (!$job->deadline->isPast())
                                <div class="flex items-center gap-4" data-countdown="{{ $job->deadline->timestamp }}">

                                    {{-- Days --}}
                                    <div class="text-center">
                                        <div
                                            class="bg-white shadow-md rounded-xl border border-gray-200 w-24 h-24 flex items-center justify-center">
                                            <span class="countdown-days text-5xl font-black text-gray-900">
                                                00
                                            </span>
                                        </div>

                                        <p class="mt-2 text-sm font-semibold text-gray-700 tracking-wide">
                                            DAYS
                                        </p>
                                    </div>

                                    {{-- Hours --}}
                                    <div class="text-center">
                                        <div
                                            class="bg-white shadow-md rounded-xl border border-gray-200 w-24 h-24 flex items-center justify-center">
                                            <span class="countdown-hours text-5xl font-black text-gray-900">
                                                00
                                            </span>
                                        </div>

                                        <p class="mt-2 text-sm font-semibold text-gray-700 tracking-wide">
                                            HOURS
                                        </p>
                                    </div>

                                    {{-- Minutes --}}
                                    <div class="text-center">
                                        <div
                                            class="bg-white shadow-md rounded-xl border border-gray-200 w-24 h-24 flex items-center justify-center">
                                            <span class="countdown-minutes text-5xl font-black text-gray-900">
                                                00
                                            </span>
                                        </div>

                                        <p class="mt-2 text-sm font-semibold text-gray-700 tracking-wide">
                                            MINUTES
                                        </p>
                                    </div>

                                </div>
                            @endif

                        </div>
                    @endif
                </div>

                <!-- Application Status -->
                @if (auth()->check() && $userApplication)
                    <div class="bg-blue-50 border border-blue-200 rounded-xl p-6">
                        <div class="flex items-center gap-3 mb-3">
                            <i class="fa-solid fa-info-circle text-blue-600"></i>
                            <span class="font-medium text-blue-900">Application Status</span>
                        </div>
                        <p class="text-blue-800">
                            You have already applied for this job on {{ $userApplication->created_at->format('F j, Y') }}.
                            Your application is currently <span
                                class="font-semibold">{{ $userApplication->status }}</span>.
                        </p>
                    </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">

                <!-- Employer Info -->
                <div class="bg-white border border-gray-200 rounded-2xl p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Posted By</h3>

                    @if ($job->employer)
                        <div class="flex items-center gap-4 mb-4">
                            @if ($job->employer->passport_photo)
                                <img src="{{ asset('storage/' . $job->employer->passport_photo) }}"
                                    alt="{{ $job->employer->first_name }}"
                                    class="w-16 h-16 rounded-full object-cover border-2 border-gray-200">
                            @else
                                <div
                                    class="w-16 h-16 bg-gradient-to-br from-blue-100 to-blue-200 rounded-full flex items-center justify-center">
                                    <span
                                        class="text-lg font-bold text-[#1e3a8a]">{{ substr($job->employer->first_name, 0, 1) }}{{ substr($job->employer->last_name, 0, 1) }}</span>
                                </div>
                            @endif
                            <div>
                                <p class="font-semibold text-gray-900">{{ $job->employer->first_name }}
                                    {{ $job->employer->last_name }}</p>
                                <p class="text-sm text-gray-500">{{ $job->employer->department ?? 'University Staff' }}
                                </p>
                                <p class="text-sm text-gray-500">{{ $job->employer->role ?? 'Member' }}</p>
                            </div>
                        </div>
                    @endif

                    <div class="space-y-3">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Member Since</span>
                            <span class="font-medium text-gray-900">{{ $job->employer->created_at->format('M Y') }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Response Time</span>
                            <span class="font-medium text-gray-900">Within 24 hours</span>
                        </div>
                    </div>
                </div>

                <!-- Apply Button -->
                @if (auth()->check())
                    @if ($job->employer_id === auth()->id())
                        <!-- Job Owner - Show Manage Button -->
                        <div class="bg-emerald-50 border border-emerald-200 rounded-xl p-4">
                            <div class="flex items-center gap-3 mb-3">
                                <i class="fa-solid fa-crown text-emerald-600"></i>
                                <span class="font-medium text-emerald-900">This is your job posting</span>
                            </div>
                            <p class="text-sm text-emerald-800 mb-4">
                                You cannot apply for your own job. Manage applications from your dashboard.
                            </p>
                            <a href="{{ route('applications.received') }}"
                                class="w-full px-4 py-2 bg-emerald-600 text-white font-semibold rounded-lg hover:bg-emerald-700 transition-all text-center block">
                                <i class="fa-solid fa-tachometer-alt mr-2"></i>
                                Manage Job
                            </a>
                        </div>
                    @elseif($userApplication)
                        <div
                            class="rounded-xl border p-4

@if ($userApplication->status === 'accepted') bg-green-50 border-green-200
@else
    bg-gray-50 border-gray-200 @endif
">

                            @if ($userApplication->status === 'pending')
                                <p class="font-medium text-gray-700">
                                    Application Submitted
                                </p>

                                <p class="text-sm text-gray-600 mt-1">
                                    Waiting for employer review.
                                </p>
                            @elseif($userApplication->status === 'rejected')
                                <p class="font-medium text-red-700">
                                    Application Rejected
                                </p>
                            @elseif($userApplication->status === 'accepted')
                                @if ($userApplication->escrow_status === 'not_funded')
                                    <p class="font-medium text-amber-700">
                                        Application Accepted
                                    </p>

                                    <p class="text-sm text-amber-600 mt-1">
                                        Waiting for employer to fund escrow.
                                    </p>
                                @elseif($userApplication->escrow_status === 'funded')
                                    <p class="font-medium text-green-700">
                                        Escrow Secured
                                    </p>

                                    <p class="text-sm text-green-600 mt-1">
                                        Payment has been secured.
                                        You may begin work.
                                    </p>
                                @elseif($userApplication->progress === 'in_progress')
                                    <p class="font-medium text-blue-700">
                                        Work In Progress
                                    </p>
                                @elseif($userApplication->progress === 'completed')
                                    <p class="font-medium text-amber-700">
                                        Awaiting Employer Confirmation
                                    </p>
                                @elseif($userApplication->escrow_status === 'released')
                                    <p class="font-medium text-green-700">
                                        Payment Released
                                    </p>

                                    <p class="text-sm text-green-600">
                                        Job completed successfully.
                                    </p>
                                @endif
                            @endif

                        </div>
                    @elseif($job->deadline && $job->deadline->isPast())
                        <div class="bg-red-50 border border-red-200 rounded-xl p-4">
                            <p class="text-center text-red-600 font-medium">Application Deadline Passed</p>
                        </div>
                    @else
                        <button onclick="showApplicationForm()"
                            class="w-full px-6 py-3 bg-[#1e3a8a] text-white font-semibold rounded-xl hover:bg-[#0f2b5e] transition-all shadow-sm hover:shadow-md">
                            <i class="fa-solid fa-paper-plane mr-2"></i>
                            Apply for This Job
                        </button>
                    @endif
                @else
                    <div class="bg-blue-50 border border-blue-200 rounded-xl p-4">
                        <div class="flex items-center gap-3 mb-3">
                            <i class="fa-solid fa-info-circle text-blue-600"></i>
                            <span class="font-medium text-blue-900">Sign in required</span>
                        </div>
                        <p class="text-sm text-blue-800 mb-4">
                            You need to be logged in to apply for jobs.
                        </p>
                        <a href="{{ route('login') }}"
                            class="w-full px-4 py-2 bg-[#1e3a8a] text-white font-semibold rounded-lg hover:bg-[#0f2b5e] transition-all text-center block">
                            Sign In to Apply
                        </a>
                    </div>
                @endif

                <!-- Related Jobs -->
                @if ($relatedJobs && $relatedJobs->count() > 0)
                    <div class="bg-white border border-gray-200 rounded-2xl p-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-4">Similar Opportunities</h3>
                        <div class="space-y-3">
                            @foreach ($relatedJobs as $relatedJob)
                                <a href="{{ route('jobs.show', $relatedJob->id) }}"
                                    class="block p-3 border border-gray-200 rounded-lg hover:bg-gray-50 transition-all">
                                    <div class="flex items-center gap-3">
                                        @if ($relatedJob->image)
                                            <img src="{{ asset('storage/' . $relatedJob->image) }}"
                                                alt="{{ $relatedJob->title }}" class="w-12 h-12 rounded-lg object-cover">
                                        @else
                                            <div class="w-12 h-12 bg-gray-200 rounded-lg flex items-center justify-center">
                                                <i class="fa-solid fa-briefcase text-gray-500"></i>
                                            </div>
                                        @endif
                                        <div class="flex-1">
                                            <p class="font-medium text-gray-900 text-sm">
                                                {{ Str::limit($relatedJob->title, 30) }}</p>
                                            <p class="text-sm text-[#1e3a8a] font-semibold">
                                                {{ $relatedJob->formatted_salary }}</p>
                                        </div>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Application Form Modal -->
    @if (auth()->check() &&
            !$userApplication &&
            (!$job->deadline || !$job->deadline->isPast()) &&
            $job->employer_id !== auth()->id())
        <div id="applicationModal"
            class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center p-4">
            <div class="bg-white rounded-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
                <div class="p-6 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <h3 class="text-xl font-bold text-gray-900">Apply for {{ $job->title }}</h3>
                        <button onclick="hideApplicationForm()" class="text-gray-400 hover:text-gray-600">
                            <i class="fa-solid fa-times text-xl"></i>
                        </button>
                    </div>
                </div>

                <form action="{{ route('jobs.apply', $job->id) }}" method="POST" class="p-6">
                    @csrf
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Cover Letter *
                        </label>
                        <textarea name="cover_letter" rows="6" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-[#1e3a8a] focus:ring-2 focus:ring-[#1e3a8a]/20"
                            placeholder="Tell us why you're interested in this position and why you'd be a great fit..."></textarea>
                        <p class="text-xs text-gray-500 mt-1">Minimum 50 characters</p>
                    </div>

                    <div class="flex gap-3">
                        <button type="submit"
                            class="flex-1 px-6 py-3 bg-[#1e3a8a] text-white font-semibold rounded-lg hover:bg-[#0f2b5e] transition-all">
                            Submit Application
                        </button>
                        <button type="button" onclick="hideApplicationForm()"
                            class="px-6 py-3 border border-gray-300 text-gray-700 font-semibold rounded-lg hover:bg-gray-50 transition-all">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <!-- Footer -->
    <x-footer />

    <script>
        // ====================== FLASH DISMISS ======================
        function dismissFlash(id) {
            const el = document.getElementById(id);
            if (!el) return;
            el.style.transition =
                'opacity 0.4s ease, transform 0.4s ease, max-height 0.5s ease, margin 0.5s ease, padding 0.5s ease';
            el.style.opacity = '0';
            el.style.transform = 'translateY(-8px)';
            el.style.overflow = 'hidden';
            el.style.maxHeight = el.scrollHeight + 'px';
            setTimeout(() => {
                el.style.maxHeight = '0';
                el.style.marginBottom = '0';
                el.style.paddingTop = '0';
                el.style.paddingBottom = '0';
            }, 350);
            setTimeout(() => el.remove(), 850);
        }

        // Auto-dismiss success and error after 20 seconds
        @if (session('success'))
            setTimeout(() => dismissFlash('flash-success'), 20000);
        @endif
        @if (session('error'))
            setTimeout(() => dismissFlash('flash-error'), 20000);
        @endif

        // ====================== MODAL ======================
        @if (auth()->check() &&
                !$userApplication &&
                (!$job->deadline || !$job->deadline->isPast()) &&
                $job->employer_id !== auth()->id())
            function showApplicationForm() {
                document.getElementById('applicationModal').classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            }

            function hideApplicationForm() {
                document.getElementById('applicationModal').classList.add('hidden');
                document.body.style.overflow = 'auto';
            }

            document.getElementById('applicationModal').addEventListener('click', function(e) {
                if (e.target === this) hideApplicationForm();
            });
        @endif

        document.addEventListener('DOMContentLoaded', function() {

            const countdowns = document.querySelectorAll('[data-countdown]');

            countdowns.forEach(countdown => {

                const deadline = parseInt(countdown.dataset.countdown) * 1000;

                const daysElement = countdown.querySelector('.countdown-days');
                const hoursElement = countdown.querySelector('.countdown-hours');
                const minutesElement = countdown.querySelector('.countdown-minutes');

                function updateCountdown() {

                    const now = new Date().getTime();
                    const distance = deadline - now;

                    if (distance <= 0) {

                        daysElement.textContent = '00';
                        hoursElement.textContent = '00';
                        minutesElement.textContent = '00';

                        return;
                    }

                    const days = Math.floor(distance / (1000 * 60 * 60 * 24));

                    const hours = Math.floor(
                        (distance % (1000 * 60 * 60 * 24)) /
                        (1000 * 60 * 60)
                    );

                    const minutes = Math.floor(
                        (distance % (1000 * 60 * 60)) /
                        (1000 * 60)
                    );

                    daysElement.textContent = String(days).padStart(2, '0');
                    hoursElement.textContent = String(hours).padStart(2, '0');
                    minutesElement.textContent = String(minutes).padStart(2, '0');
                }

                updateCountdown();

                setInterval(updateCountdown, 60000);
            });

        });
    </script>
@endsection

@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-gradient-to-b from-gray-50 to-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-10">

            <!-- Greeting - Cleaner spacing and responsive -->
            <div class="mb-8 sm:mb-12">
                <h1 class="text-3xl sm:text-4xl font-bold text-gray-800 tracking-tight">
                    Hello, {{ auth()->user()->first_name ?? 'Student' }}! 👋
                </h1>
                <p class="text-gray-500 mt-1 text-sm sm:text-base">Here's what's happening on campus today •
                    {{ now()->format('l, jS F Y') }}</p>
            </div>

            <!-- Stats Cards - Improved grid and hover effect -->
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-4 sm:gap-6 mb-10 sm:mb-14">
                <div
                    class="bg-white rounded-2xl p-4 sm:p-5 shadow-sm hover:shadow-md transition-shadow duration-200 border border-gray-100">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs sm:text-sm text-gray-500 font-medium">Active Skills</p>
                            <p class="text-2xl sm:text-3xl font-bold text-[#1e3a8a] mt-1">
                                {{ number_format($stats['active_skills']) }}</p>
                        </div>
                        <div class="text-2xl sm:text-3xl opacity-80">⭐</div>
                    </div>
                </div>

                <div
                    class="bg-white rounded-2xl p-4 sm:p-5 shadow-sm hover:shadow-md transition-shadow duration-200 border border-gray-100">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs sm:text-sm text-gray-500 font-medium">My Applications</p>
                            <p class="text-2xl sm:text-3xl font-bold text-[#1e3a8a] mt-1">
                                {{ number_format($stats['job_applications']) }}</p>
                        </div>
                        <div class="text-2xl sm:text-3xl opacity-80">📤</div>
                    </div>
                </div>

                <div
                    class="bg-white rounded-2xl p-4 sm:p-5 shadow-sm hover:shadow-md transition-shadow duration-200 border border-gray-100">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs sm:text-sm text-gray-500 font-medium">Posted Jobs</p>
                            <p class="text-2xl sm:text-3xl font-bold text-[#1e3a8a] mt-1">
                                {{ number_format($stats['posted_jobs']) }}</p>
                        </div>
                        <div class="text-2xl sm:text-3xl opacity-80">📋</div>
                    </div>
                </div>

                <div
                    class="bg-white rounded-2xl p-4 sm:p-5 shadow-sm hover:shadow-md transition-shadow duration-200 border border-gray-100">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs sm:text-sm text-gray-500 font-medium">Applications Received</p>
                            <p class="text-2xl sm:text-3xl font-bold text-[#1e3a8a] mt-1">
                                {{ number_format($stats['received_applications']) }}</p>
                        </div>
                        <div class="text-2xl sm:text-3xl opacity-80">📬</div>
                    </div>
                </div>

            </div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 sm:gap-8">

                <!-- Quick Actions -->
                <div class="lg:col-span-4 space-y-6">
                    <div>
                        <h2 class="font-semibold text-gray-800 text-base sm:text-lg mb-4">Quick Actions</h2>
                        <div class="space-y-3">
                            <a href="{{ route('skills.index') }}"
                                class="group flex items-center justify-between bg-[#1e3a8a] text-white p-4 sm:p-5 rounded-xl hover:bg-[#0f2b5e] transition-colors duration-200">
                                <span class="font-medium text-sm sm:text-base">Browse Skills</span>
                                <i
                                    class="fa-solid fa-arrow-right group-hover:translate-x-1 transition-transform duration-200"></i>
                            </a>
                            <a href="{{ route('jobs.create') }}"
                                class="group flex items-center justify-between border border-gray-200 bg-white p-4 sm:p-5 rounded-xl hover:border-gray-300 hover:bg-gray-50 transition-all duration-200">
                                <span class="font-medium text-sm sm:text-base text-gray-700">Post a Job</span>
                                <i
                                    class="fa-solid fa-arrow-right text-gray-400 group-hover:translate-x-1 group-hover:text-gray-600 transition-all duration-200"></i>
                            </a>
                            <a href="{{ route('skills.create') }}"
                                class="group flex items-center justify-between border border-gray-200 bg-white p-4 sm:p-5 rounded-xl hover:border-gray-300 hover:bg-gray-50 transition-all duration-200">
                                <span class="font-medium text-sm sm:text-base text-gray-700">Post a Skill</span>
                                <i
                                    class="fa-solid fa-arrow-right text-gray-400 group-hover:translate-x-1 group-hover:text-gray-600 transition-all duration-200"></i>
                            </a>
                            <a href="{{ route('profile.show', auth()->id()) }}"
                                class="group flex items-center justify-between border border-gray-200 bg-white p-4 sm:p-5 rounded-xl hover:border-gray-300 hover:bg-gray-50 transition-all duration-200">
                                <span class="font-medium text-sm sm:text-base text-gray-700">View My Profile</span>
                                <i
                                    class="fa-solid fa-arrow-right text-gray-400 group-hover:translate-x-1 group-hover:text-gray-600 transition-all duration-200"></i>
                            </a>
                        </div>
                    </div>

                    <!-- Academic Tip - Subtle hover effect -->
                    <div
                        class="bg-gradient-to-br from-emerald-700 to-emerald-800 text-white p-5 sm:p-6 rounded-2xl shadow-sm hover:shadow transition-shadow duration-200">
                        <div class="flex items-start gap-3">
                            <div class="text-2xl">🎓</div>
                            <div>
                                <h4 class="font-semibold text-sm sm:text-base mb-1">Academic Tip</h4>
                                <p class="text-xs sm:text-sm leading-relaxed opacity-90">
                                    Students with complete profiles are 3x more likely to be hired for peer tutoring roles.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tabbed Content -->
                <div class="lg:col-span-8">
                    <!-- Tabs - Cleaner design -->
                    <div class="border-b border-gray-200 mb-5">
                        <nav class="flex flex-wrap gap-1 sm:gap-0">
                            <button onclick="showTab('my-skills')" id="my-skills-tab"
                                class="tab-button px-3 sm:px-4 py-2 text-sm font-medium rounded-t-lg transition-all duration-200">
                                My Skills
                            </button>
                            <button onclick="showTab('my-jobs')" id="my-jobs-tab"
                                class="tab-button px-3 sm:px-4 py-2 text-sm font-medium rounded-t-lg transition-all duration-200">
                                My Jobs
                            </button>
                            @php
    $pendingMyApplicationActions = \App\Models\JobApplication::where('applicant_id', auth()->id())
        ->where(function ($query) {
            $query->where(function ($q) {
                    $q->where('status', 'accepted')
                      ->whereIn('progress', ['pending', 'in_progress']);
                })
                ->orWhereNotNull('revision_note')
                ->orWhere(function ($q) {
                    $q->where('progress', 'confirmed')
                      ->whereDoesntHave('ratings', function ($ratingQuery) {
                          $ratingQuery->where('reviewer_id', auth()->id());
                      });
                });
        })
        ->count();
@endphp
                            <button onclick="showTab('my-applications')" id="my-applications-tab" class="tab-button px-3 sm:px-4 py-2 text-sm font-medium rounded-t-lg transition-all duration-200 relative">
    My Applications

    @if($pendingMyApplicationActions > 0)
        <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs px-1.5 py-0.5 rounded-full min-w-[18px] text-center">
            {{ $pendingMyApplicationActions > 9 ? '9+' : $pendingMyApplicationActions }}
        </span>
    @endif
</button>
                            @php
                                $pendingReceivedApplications = \App\Models\JobApplication::whereHas('job', function (
                                    $query,
                                ) {
                                    $query->where('employer_id', auth()->id());
                                })
                                    ->where('status', 'pending')
                                    ->count();
                            @endphp

                            <button onclick="showTab('applications')" id="applications-tab"
                                class="tab-button px-3 sm:px-4 py-2 text-sm font-medium rounded-t-lg transition-all duration-200 relative">
                                Applications Received

                                @if ($pendingReceivedApplications > 0)
                                    <span
                                        class="absolute -top-1 -right-1 bg-red-500 text-white text-xs px-1.5 py-0.5 rounded-full min-w-[18px] text-center">
                                        {{ $pendingReceivedApplications > 9 ? '9+' : $pendingReceivedApplications }}
                                    </span>
                                @endif
                            </button>

                            @php
                                $pendingSkillBookings = \App\Models\Booking::where('provider_id', auth()->id())
                                    ->where('status', 'interested')
                                    ->count();

                                $pendingServiceActions = \App\Models\Booking::where('client_id', auth()->id())
                                    ->where(function ($query) {
                                        $query
                                            ->where(function ($q) {
                                                $q->where('status', 'completed_waiting_payment')->where('payment_status', 'unpaid');
                                            })
                                            ->orWhere(function ($q) {
                                                $q->where('status', 'done')->whereDoesntHave('ratings', function (
                                                    $ratingQuery,
                                                ) {
                                                    $ratingQuery->where('reviewer_id', auth()->id());
                                                });
                                            });
                                    })
                                    ->count();

                                $totalPendingBookingActions = $pendingSkillBookings + $pendingServiceActions;
                            @endphp

                            <button onclick="showTab('bookings')" id="bookings-tab"
                                class="tab-button px-3 sm:px-4 py-2 text-sm font-medium rounded-t-lg transition-all duration-200 relative">
                                My Bookings

                                @if ($totalPendingBookingActions > 0)
                                    <span
                                        class="absolute -top-1 -right-1 bg-red-500 text-white text-xs px-1.5 py-0.5 rounded-full min-w-[18px] text-center">
                                        {{ $totalPendingBookingActions > 9 ? '9+' : $totalPendingBookingActions }}
                                    </span>
                                @endif
                            </button>
                            <button onclick="showTab('messages')" id="messages-tab"
                                class="tab-button px-3 sm:px-4 py-2 text-sm font-medium rounded-t-lg transition-all duration-200 relative">
                                Messages
                                @if (auth()->user()->unreadCount() > 0)
                                    <span
                                        class="absolute -top-1 -right-1 bg-red-500 text-white text-xs px-1.5 py-0.5 rounded-full min-w-[18px] text-center">{{ auth()->user()->unreadCount() }}</span>
                                @endif
                            </button>
                        </nav>
                    </div>

                    <!-- Tab Content -->
                    <div id="tab-content" class="min-h-[400px]">
                        <!-- My Skills Tab -->
                        <div id="my-skills-content" class="tab-content">
                            <div class="bg-white rounded-xl border border-gray-100 p-6">
                                <div class="flex items-center justify-between mb-4">
                                    <h3 class="text-lg font-semibold text-gray-800">Your Skills</h3>
                                    <a href="{{ route('skills.mine') }}"
                                        class="text-sm text-[#1e3a8a] hover:text-[#0f2b5e] font-medium">
                                        Manage Skills <i class="fa-solid fa-arrow-right ml-1"></i>
                                    </a>
                                </div>
                                <div class="text-center py-8">
                                    <div class="text-4xl text-gray-200 mb-3">
                                        <i class="fa-solid fa-wrench"></i>
                                    </div>
                                    <p class="text-gray-600 mb-4">Manage your skills and bookings on the dedicated page</p>
                                    <a href="{{ route('skills.mine') }}"
                                        class="inline-flex items-center gap-2 px-4 py-2 bg-[#1e3a8a] text-white text-sm rounded-lg hover:bg-[#0f2b5e] transition-all">
                                        <i class="fa-solid fa-eye"></i>
                                        View My Skills
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- My Jobs Tab -->
                        <div id="my-jobs-content" class="tab-content hidden">
                            <div class="bg-white rounded-xl border border-gray-100 p-6">
                                <div class="flex items-center justify-between mb-4">
                                    <h3 class="text-lg font-semibold text-gray-800">Your Job Postings</h3>
                                    <a href="{{ route('jobs.mine') }}"
                                        class="text-sm text-[#1e3a8a] hover:text-[#0f2b5e] font-medium">
                                        Manage Jobs <i class="fa-solid fa-arrow-right ml-1"></i>
                                    </a>
                                </div>
                                <div class="text-center py-8">
                                    <div class="text-4xl text-gray-200 mb-3">
                                        <i class="fa-solid fa-briefcase"></i>
                                    </div>
                                    <p class="text-gray-600 mb-4">Manage your job postings and applications on the
                                        dedicated page</p>
                                    <a href="{{ route('jobs.mine') }}"
                                        class="inline-flex items-center gap-2 px-4 py-2 bg-[#1e3a8a] text-white text-sm rounded-lg hover:bg-[#0f2b5e] transition-all">
                                        <i class="fa-solid fa-eye"></i>
                                        View My Jobs
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- My Applications Tab -->
                        <div id="my-applications-content" class="tab-content hidden">
                            <div class="bg-white rounded-xl border border-gray-100 p-6">
                                <div class="flex items-center justify-between mb-4">
                                    <h3 class="text-lg font-semibold text-gray-800">My Applications</h3>

                                    <a href="{{ route('applications.mine') }}"
                                        class="text-sm text-[#1e3a8a] hover:text-[#0f2b5e] font-medium">
                                        Manage Applications <i class="fa-solid fa-arrow-right ml-1"></i>
                                    </a>
                                </div>

                                <div class="text-center py-8">
                                    <div class="text-4xl text-gray-200 mb-3">
                                        <i class="fa-solid fa-paper-plane"></i>
                                    </div>

                                    <p class="text-gray-600 mb-4">
                                        View and manage all your job applications
                                    </p>

                                    <a href="{{ route('applications.mine') }}"
                                        class="inline-flex items-center gap-2 px-4 py-2 bg-[#1e3a8a] text-white text-sm rounded-lg hover:bg-[#0f2b5e] transition-all">
                                        <i class="fa-solid fa-eye"></i>
                                        View Applications
                                    </a>
                                </div>
                            </div>
                        </div>


                        <!-- Applications Tab -->
                        <div id="applications-content" class="tab-content hidden">
                            <div class="bg-white rounded-xl border border-gray-100 p-6">
                                <div class="flex items-center justify-between mb-4">
                                    <h3 class="text-lg font-semibold text-gray-800">Applications Received</h3>

                                    <a href="{{ route('applications.received') }}"
                                        class="text-sm text-[#1e3a8a] hover:text-[#0f2b5e] font-medium">
                                        Manage Applications <i class="fa-solid fa-arrow-right ml-1"></i>
                                    </a>
                                </div>

                                <div class="text-center py-8">
                                    <div class="text-4xl text-gray-200 mb-3">
                                        <i class="fa-solid fa-inbox"></i>
                                    </div>

                                    <p class="text-gray-600 mb-4">
                                        Review and manage applications to your job postings
                                    </p>

                                    <a href="{{ route('applications.received') }}"
                                        class="inline-flex items-center gap-2 px-4 py-2 bg-[#1e3a8a] text-white text-sm rounded-lg hover:bg-[#0f2b5e] transition-all">
                                        <i class="fa-solid fa-eye"></i>
                                        View Applications
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Bookings Tab -->
                        @php
                            $serviceRequestsUnread = auth()
                                ->user()
                                ->unreadNotificationsByTypes([
                                    'booking_confirmed',
                                    'booking_declined',
                                    'booking_completed',
                                    'rating_received',
                                ]);

                            $skillBookingsUnread = auth()
                                ->user()
                                ->unreadNotificationsByTypes([
                                    'booking_request',
                                    'booking_completed',
                                    'rating_received',
                                ]);
                        @endphp

                        <div id="bookings-content" class="tab-content hidden">
                            <div class="bg-white rounded-xl border border-gray-100 p-6">

                                <h3 class="text-lg font-semibold text-gray-800 mb-6">
                                    My Bookings
                                </h3>

                                <div class="grid md:grid-cols-2 gap-4">

                                    <!-- Services I Requested -->
                                    <div class="border border-gray-200 rounded-xl p-5">
                                        <div class="text-4xl text-blue-200 mb-3">
                                            <i class="fa-solid fa-hand-point-up"></i>
                                        </div>

                                        <h4 class="font-semibold text-gray-800 mb-2">
                                            Services I Requested
                                        </h4>

                                        <p class="text-sm text-gray-600 mb-4">
                                            View services you requested from other users
                                        </p>

                                        <a href="{{ route('bookings.requests') }}"
                                            class="relative inline-flex items-center gap-2 px-4 py-2 bg-[#1e3a8a] text-white text-sm rounded-lg hover:bg-[#0f2b5e] transition-all">
                                            <i class="fa-solid fa-eye"></i>
                                            View Requests

                                            @if ($pendingServiceActions > 0)
                                                <span
                                                    class="absolute -top-2 -right-2 bg-red-500 text-white text-xs px-1.5 py-0.5 rounded-full min-w-[18px] text-center">
                                                    {{ $pendingServiceActions > 9 ? '9+' : $pendingServiceActions }}
                                                </span>
                                            @endif
                                        </a>
                                    </div>

                                    <!-- Bookings For My Skills -->
                                    <div class="border border-gray-200 rounded-xl p-5">
                                        <div class="text-4xl text-emerald-200 mb-3">
                                            <i class="fa-solid fa-calendar-check"></i>
                                        </div>

                                        <h4 class="font-semibold text-gray-800 mb-2">
                                            Bookings For My Skills
                                        </h4>

                                        <p class="text-sm text-gray-600 mb-4">
                                            Manage bookings made on your skills
                                        </p>

                                        <a href="{{ route('bookings.skills') }}"
                                            class="relative inline-flex items-center gap-2 px-4 py-2 bg-emerald-600 text-white text-sm rounded-lg hover:bg-emerald-700 transition-all">
                                            <i class="fa-solid fa-eye"></i>
                                            View Bookings

                                            @if ($pendingSkillBookings > 0)
                                                <span
                                                    class="absolute -top-2 -right-2 bg-red-500 text-white text-xs px-1.5 py-0.5 rounded-full min-w-[18px] text-center">
                                                    {{ $pendingSkillBookings > 9 ? '9+' : $pendingSkillBookings }}
                                                </span>
                                            @endif
                                        </a>
                                    </div>

                                </div>
                            </div>
                        </div>

                        <!-- Messages Tab -->
                        <div id="messages-content" class="tab-content hidden">
                            <div class="bg-white rounded-xl border border-gray-100 p-6">
                                <div class="flex items-center justify-between mb-4">
                                    <h3 class="text-lg font-semibold text-gray-800">Messages</h3>
                                    <a href="{{ route('messages.index') }}"
                                    aria-label="View all messages"
                                        class="text-sm text-[#1e3a8a] hover:text-[#0f2b5e] font-medium">
                                        View All <i class="fa-solid fa-arrow-right ml-1"></i>
                                    </a>
                                </div>

                                <div class="text-center py-8">
                                    <div class="text-4xl text-gray-200 mb-3">
                                        <i class="fa-solid fa-envelope"></i>
                                    </div>

                                    <p class="text-gray-600 mb-4">
                                        View and manage your messages
                                    </p>

                                    @if (auth()->user()->unreadCount() > 0)
                                        <p class="text-sm text-blue-700 mb-4">
                                            You have <strong>{{ auth()->user()->unreadCount() }}</strong> unread
                                            message{{ auth()->user()->unreadCount() > 1 ? 's' : '' }}.
                                        </p>
                                    @endif

                                    <a href="{{ route('messages.index') }}"
                                        class="inline-flex items-center gap-2 px-4 py-2 bg-[#1e3a8a] text-white text-sm rounded-lg hover:bg-[#0f2b5e] transition-all">
                                        <i class="fa-solid fa-eye"></i>
                                        View Messages
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

<!-- Edit Application Modal -->
@if (auth()->check() && $recentApplications->count() > 0)
    <div id="editApplicationModal"
        class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h3 class="text-xl font-bold text-gray-900">Edit Job Application</h3>
                   <button type="button" onclick="hideEditApplicationModal()" aria-label="Close edit application modal"
    class="text-gray-400 hover:text-gray-600">
                        <i class="fa-solid fa-times text-xl"></i>
                    </button>
                </div>
            </div>

            <form id="editApplicationForm" method="POST" class="p-6">
                @csrf
                @method('PATCH')
                <input type="hidden" id="editApplicationId" name="application_id">

                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Cover Letter *
                    </label>
                    <textarea id="editCoverLetter" name="cover_letter" rows="6" required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-[#1e3a8a] focus:ring-2 focus:ring-[#1e3a8a]/20"
                        placeholder="Tell us why you're interested in this position and why you'd be a great fit..."></textarea>
                    <p class="text-xs text-gray-500 mt-1">Minimum 50 characters</p>
                </div>

                <div class="flex gap-3">
                    <button type="submit"
                        class="flex-1 px-6 py-3 bg-[#1e3a8a] text-white font-semibold rounded-lg hover:bg-[#0f2b5e] transition-all">
                        Update Application
                    </button>
                    <button type="button" onclick="hideEditApplicationModal()"
                        class="px-6 py-3 border border-gray-300 text-gray-700 font-semibold rounded-lg hover:bg-gray-50 transition-all">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>
@endif

<!-- Revision Modal -->
<div id="revisionModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden  opacity-0 pointer-events-none flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-lg w-full">
        <div class="p-6 border-b border-gray-200 flex items-center justify-between">
            <h3 class="text-xl font-bold text-gray-900">Request Revision</h3>
            <button type="button" onclick="hideRevisionModal()" aria-label="Close revision modal"
    class="text-gray-400 hover:text-gray-600">
                <i class="fa-solid fa-times text-xl"></i>
            </button>
        </div>
        <form id="revisionForm" class="p-6">
            @csrf
            <input type="hidden" id="revisionApplicationId">
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    What needs to be revised? *
                </label>
                <textarea id="revisionNote" name="revision_note" rows="4" required
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-amber-500 focus:ring-2 focus:ring-amber-500/20"
                    placeholder="Describe clearly what changes or corrections are needed..."></textarea>
            </div>
            <div class="flex gap-3">
                <button type="submit"
                    class="flex-1 px-6 py-3 bg-amber-500 text-white font-semibold rounded-lg hover:bg-amber-600 transition-all">
                    Send Revision Request
                </button>
                <button type="button" onclick="hideRevisionModal()"
                    class="px-6 py-3 border border-gray-300 text-gray-700 font-semibold rounded-lg hover:bg-gray-50 transition-all">
                    Cancel
                </button>
            </div>
        </form>
    </div>
</div>

@push('styles')
    <style>
        /* Smooth transitions */
        .tab-button {
            transition: all 0.2s ease;
            border-bottom: 2px solid transparent;
            color: #6B7280;
        }

        .tab-button:hover {
            color: #374151;
            background-color: #F9FAFB;
        }

        .tab-button.active {
            color: #1e3a8a;
            border-bottom-color: #1e3a8a;
            background-color: transparent;
        }

        .tab-content {
            transition: opacity 0.2s ease;
        }

        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        /* Subtle card hover */
        .hover-shadow {
            transition: box-shadow 0.2s ease;
        }
    </style>
    @endpush

    @push('scripts')
    <script>
        // Tab functionality
        function showTab(tabName) {
            // Hide all tab contents
            document.querySelectorAll('.tab-content').forEach(content => {
                content.classList.add('hidden');
            });

            // Remove active class from all tabs
            document.querySelectorAll('.tab-button').forEach(tab => {
                tab.classList.remove('active');
            });

            // Show selected tab content
            const selectedContent = document.getElementById(tabName + '-content');
            if (selectedContent) {
                selectedContent.classList.remove('hidden');
            }

            // Add active class to selected tab
            const selectedTab = document.getElementById(tabName + '-tab');
            if (selectedTab) {
                selectedTab.classList.add('active');
            }
        }

        // Application status update
        function updateApplicationStatus(applicationId, status) {
            if (!confirm('Are you sure you want to ' + status.toUpperCase() + ' this application?')) {
                return;
            }

            // Map status to the correct route
            const urlMap = {
                'accepted': `/applications/${applicationId}/accept`,
                'rejected': `/applications/${applicationId}/reject`
            };

            const url = urlMap[status];
            if (!url) {
                alert('Unknown status: ' + status);
                return;
            }

            fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showSuccessMessage(data.message);
                        setTimeout(() => window.location.reload(), 1500);
                    } else {
                        alert(data.message || 'Error updating application status');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error updating application status');
                });
        }
        // Edit Application Modal functions
        function showEditApplicationModal(applicationId) {
            // Get the application data from the page
            const applications = @json($recentApplications);
            const appData = applications.find(app => app.id == applicationId);

            if (appData) {
                document.getElementById('editApplicationId').value = applicationId;
                document.getElementById('editCoverLetter').value = appData.cover_letter;
                document.getElementById('editApplicationForm').action = `/applications/${applicationId}`;
                document.getElementById('editApplicationModal').classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            }
        }

        function hideEditApplicationModal() {
            document.getElementById('editApplicationModal').classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        function withdrawApplication(applicationId) {
            if (!confirm('Are you sure you want to withdraw this application? This action cannot be undone.')) {
                return;
            }

            fetch(`/applications/${applicationId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Show success message and reload
                        const successDiv = document.createElement('div');
                        successDiv.className =
                            'fixed top-4 right-4 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg flex items-center gap-3 z-50';
                        successDiv.innerHTML = '<i class="fa-solid fa-check-circle"></i><span>' + data.message +
                            '</span>';
                        document.body.appendChild(successDiv);

                        setTimeout(() => {
                            window.location.reload();
                        }, 1500);
                    } else {
                        alert(data.message || 'Error withdrawing application');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error withdrawing application');
                });
        }

        // Initialize with first tab active when DOM is ready
        document.addEventListener('DOMContentLoaded', function() {
            const activeTab = localStorage.getItem('activeDashboardTab') || 'my-skills';
            showTab(activeTab);

            // Save active tab on click
            document.querySelectorAll('.tab-button').forEach(btn => {
                btn.addEventListener('click', function() {
                    const id = this.id;
                    if (id) {
                        localStorage.setItem('activeDashboardTab', id.replace('-tab', ''));
                    }
                });
            });

            // Edit Application Form submission
            const editForm = document.getElementById('editApplicationForm');
            if (editForm) {
                editForm.addEventListener('submit', function(e) {
                    e.preventDefault();

                    const formData = new FormData(this);
                    const applicationId = document.getElementById('editApplicationId').value;

                    fetch(`/applications/${applicationId}`, {
                            method: 'PATCH',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                    ?.getAttribute('content') || '',
                                'Accept': 'application/json'
                            },
                            body: formData
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                const successDiv = document.createElement('div');
                                successDiv.className =
                                    'fixed top-4 right-4 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg flex items-center gap-3 z-50';
                                successDiv.innerHTML =
                                    '<i class="fa-solid fa-check-circle"></i><span>' + data.message +
                                    '</span>';
                                document.body.appendChild(successDiv);

                                setTimeout(() => {
                                    hideEditApplicationModal();
                                    window.location.reload();
                                }, 1500);
                            } else {
                                alert(data.message || 'Error updating application');
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            alert('Error updating application');
                        });
                });
            }

            // Revision Form submission
            const revisionForm = document.getElementById('revisionForm');
            if (revisionForm) {
                revisionForm.addEventListener('submit', function(e) {
                    e.preventDefault();

                    const formData = new FormData(this);
                    const applicationId = document.getElementById('revisionApplicationId').value;

                    fetch(`/applications/${applicationId}/revision`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                    .getAttribute('content'),
                                'Accept': 'application/json'
                            },
                            body: formData
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                showSuccessMessage(data.message);
                                setTimeout(() => {
                                    hideRevisionModal();
                                    window.location.reload();
                                }, 1500);
                            } else {
                                alert(data.message || 'Error requesting revision');
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            alert('Error requesting revision');
                        });
                });
            }
        });

        // Guest redirect protection - simplified
        if (document.querySelector('meta[name="user-authenticated"]')?.content === 'true') {
            setInterval(function() {
                fetch(window.location.href, {
                    method: 'HEAD'
                }).catch(() => {
                    window.location.href = '{{ route('login') }}';
                });
            }, 60000);
        }

        // Success message function
        function showSuccessMessage(message) {
            const successDiv = document.createElement('div');
            successDiv.className =
                'fixed top-4 right-4 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg flex items-center gap-3 z-50';
            successDiv.innerHTML = '<i class="fa-solid fa-check-circle"></i><span>' + message + '</span>';
            document.body.appendChild(successDiv);

            setTimeout(() => {
                successDiv.style.transition =
                    'opacity 0.4s ease, transform 0.4s ease, max-height 0.5s ease, margin 0.5s ease, padding 0.5s ease';
                successDiv.style.opacity = '0';
                successDiv.style.transform = 'translateY(-8px)';
                successDiv.style.maxHeight = '0';
                successDiv.style.marginBottom = '0';
                successDiv.style.paddingTop = '0';
                successDiv.style.paddingBottom = '0';

                setTimeout(() => successDiv.remove(), 850);
            }, 20);
        }

        // Progress tracking functions
        function startWork(applicationId) {
            fetch(`/applications/${applicationId}/start`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showSuccessMessage(data.message);
                        setTimeout(() => {
                            window.location.reload();
                        }, 1500);
                    } else {
                        alert(data.message || 'Error starting work');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error starting work');
                });
        }

        function markComplete(applicationId) {
            fetch(`/applications/${applicationId}/complete`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showSuccessMessage(data.message);
                        setTimeout(() => {
                            window.location.reload();
                        }, 1500);
                    } else {
                        alert(data.message || 'Error marking work as complete');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error marking work as complete');
                });
        }

        function showRevisionModal(applicationId) {
            const modal = document.getElementById('revisionModal');
            const applicationIdInput = document.getElementById('revisionApplicationId');
            const revisionNoteInput = document.getElementById('revisionNote');

            applicationIdInput.value = applicationId;
            revisionNoteInput.value = '';
            modal.classList.remove('hidden', 'opacity-0', 'pointer-events-none');
            document.body.style.overflow = 'hidden';
        }

        function hideRevisionModal() {
            document.getElementById('revisionModal').classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        function confirmComplete(applicationId) {
            if (!confirm(
                    'Are you sure you want to confirm this job as complete? This will mark the job as completed and unlock ratings.'
                )) {
                return;
            }

            fetch(`/applications/${applicationId}/confirm`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showSuccessMessage(data.message);
                        setTimeout(() => {
                            window.location.reload();
                        }, 1500);
                    } else {
                        alert(data.message || 'Error confirming completion');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error confirming completion');
                });
        }

        // Rating functions
        function setRating(applicationId, rating) {
            const container = document.getElementById(`rating-stars-${applicationId}`);
            if (!container) return;

            // Store selected rating on the container
            container.setAttribute('data-selected', rating);

            // Update star colours
            const stars = container.querySelectorAll('button');
            stars.forEach((star, index) => {
                star.classList.remove('text-yellow-400', 'text-gray-300');
                if (index < rating) {
                    star.classList.add('text-yellow-400');
                } else {
                    star.classList.add('text-gray-300');
                }
            });
        }

        function setEmployerRating(applicationId, rating) {
            const container = document.getElementById(`rating-stars-${applicationId}-employer`);
            if (!container) return;

            // Store selected rating on the container
            container.setAttribute('data-selected', rating);

            // Update star colours
            const stars = container.querySelectorAll('button');
            stars.forEach((star, index) => {
                star.classList.remove('text-yellow-400', 'text-gray-300');
                if (index < rating) {
                    star.classList.add('text-yellow-400');
                } else {
                    star.classList.add('text-gray-300');
                }
            });
        }

        function submitRating(applicationId, isEmployer = false) {
            const containerId = isEmployer ?
                `rating-stars-${applicationId}-employer` :
                `rating-stars-${applicationId}`;

            const container = document.getElementById(containerId);
            const rating = container ? container.getAttribute('data-selected') : null;

            if (!rating) {
                alert('Please select a rating before submitting');
                return;
            }

            const reviewTextId = isEmployer ?
                `employer-review-text-${applicationId}` :
                `review-text-${applicationId}`;
            const reviewText = document.getElementById(reviewTextId)?.value || '';

            fetch(`/applications/${applicationId}/rate`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        rating: parseInt(rating),
                        review: reviewText
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showSuccessMessage(data.message);
                        setTimeout(() => window.location.reload(), 1500);
                    } else {
                        alert(data.message || 'Error submitting rating');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error submitting rating');
                });
        }

        // Booking functions
        function confirmBooking(bookingId) {
            if (!confirm('Are you sure you want to confirm this booking?')) return;

            fetch(`/bookings/${bookingId}/confirm`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showSuccessMessage(data.message);
                        setTimeout(() => window.location.reload(), 1500);
                    } else {
                        alert(data.message || 'Error confirming booking');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error confirming booking');
                });
        }

        function declineBooking(bookingId) {
            if (!confirm('Are you sure you want to decline this booking?')) return;

            fetch(`/bookings/${bookingId}/decline`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showSuccessMessage(data.message);
                        setTimeout(() => window.location.reload(), 1500);
                    } else {
                        alert(data.message || 'Error declining booking');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error declining booking');
                });
        }

        function clientConfirmBooking(bookingId) {
            if (!confirm('Are you sure you want to mark this service as completed?')) return;

            fetch(`/bookings/${bookingId}/client-confirm`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showSuccessMessage(data.message);
                        setTimeout(() => window.location.reload(), 1500);
                    } else {
                        alert(data.message || 'Error confirming completion');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error confirming completion');
                });
        }

        function providerConfirmBooking(bookingId) {
            if (!confirm('Are you sure you want to mark this service as completed?')) return;

            fetch(`/bookings/${bookingId}/provider-confirm`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showSuccessMessage(data.message);
                        setTimeout(() => window.location.reload(), 1500);
                    } else {
                        alert(data.message || 'Error confirming completion');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error confirming completion');
                });
        }

        function showBookingRatingWidget(bookingId) {
            // Simple prompt-based rating for bookings
            const rating = prompt('Rate this service (1-5):');
            if (!rating || rating < 1 || rating > 5) {
                alert('Please enter a valid rating between 1 and 5');
                return;
            }

            const review = prompt('Leave a review (optional):') || '';

            fetch(`/bookings/${bookingId}/rate`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        rating: parseInt(rating),
                        review: review
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showSuccessMessage(data.message);
                        setTimeout(() => window.location.reload(), 1500);
                    } else {
                        alert(data.message || 'Error submitting rating');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error submitting rating');
                });
        }
    </script>
@endpush

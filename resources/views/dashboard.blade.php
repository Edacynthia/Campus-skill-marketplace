@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

            <div class="mb-8">
                <div class="flex items-center justify-between flex-wrap gap-4">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900 tracking-tight">
                            Hello, {{ auth()->user()->first_name ?? 'Student' }}! 👋
                        </h1>
                        <p class="text-gray-500 mt-1">
                            Here's what's happening on campus today • {{ now()->format('l, jS F Y') }}
                        </p>
                    </div>

                    <div class="flex items-center gap-2">
                        <span class="px-3 py-1 bg-blue-50 text-[#1e3a8a] text-sm rounded-full">
                            ● Live
                        </span>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-5 mb-10">
                <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100 hover:shadow-md transition-all">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm font-medium text-gray-500">Active Skills</span>
                        <span class="text-2xl">⭐</span>
                    </div>
                    <p class="text-3xl font-bold text-[#1e3a8a]">{{ number_format($stats['active_skills']) }}</p>
                    <p class="text-xs text-gray-400 mt-1">Skills you currently offer</p>
                </div>

                <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100 hover:shadow-md transition-all">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm font-medium text-gray-500">My Applications</span>
                        <span class="text-2xl">📤</span>
                    </div>
                    <p class="text-3xl font-bold text-[#1e3a8a]">{{ number_format($stats['job_applications']) }}</p>
                    <p class="text-xs text-gray-400 mt-1">Jobs you applied for</p>
                </div>

                <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100 hover:shadow-md transition-all">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm font-medium text-gray-500">Posted Jobs</span>
                        <span class="text-2xl">📋</span>
                    </div>
                    <p class="text-3xl font-bold text-[#1e3a8a]">{{ number_format($stats['posted_jobs']) }}</p>
                    <p class="text-xs text-gray-400 mt-1">Open positions</p>
                </div>

                <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100 hover:shadow-md transition-all">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm font-medium text-gray-500">Applications Received</span>
                        <span class="text-2xl">📬</span>
                    </div>
                    <p class="text-3xl font-bold text-[#1e3a8a]">{{ number_format($stats['received_applications']) }}</p>
                    <p class="text-xs text-gray-400 mt-1">Pending review: {{ $pendingReceivedApplications }}</p>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                <div class="lg:col-span-1 space-y-6">
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                        <div class="px-5 py-4 border-b border-gray-100">
                            <h2 class="font-semibold text-gray-800">Quick Actions</h2>
                        </div>

                        <div class="p-2">
                            <a href="{{ route('jobs.create') }}" class="flex items-center justify-between p-3 rounded-lg hover:bg-gray-50 transition-colors">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 bg-blue-50 rounded-lg flex items-center justify-center">
                                        <i class="fa-solid fa-plus text-[#1e3a8a] text-sm"></i>
                                    </div>
                                    <span class="text-sm text-gray-700">Post a Job</span>
                                </div>
                                <i class="fa-solid fa-arrow-right text-gray-300 text-sm"></i>
                            </a>

                            <a href="{{ route('skills.create') }}" class="flex items-center justify-between p-3 rounded-lg hover:bg-gray-50 transition-colors">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 bg-blue-50 rounded-lg flex items-center justify-center">
                                        <i class="fa-solid fa-plus text-[#1e3a8a] text-sm"></i>
                                    </div>
                                    <span class="text-sm text-gray-700">Post a Skill</span>
                                </div>
                                <i class="fa-solid fa-arrow-right text-gray-300 text-sm"></i>
                            </a>

                            <a href="{{ route('profile.show', auth()->id()) }}" class="flex items-center justify-between p-3 rounded-lg hover:bg-gray-50 transition-colors">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 bg-blue-50 rounded-lg flex items-center justify-center">
                                        <i class="fa-solid fa-user text-[#1e3a8a] text-sm"></i>
                                    </div>
                                    <span class="text-sm text-gray-700">View My Profile</span>
                                </div>
                                <i class="fa-solid fa-arrow-right text-gray-300 text-sm"></i>
                            </a>
                        </div>
                    </div>

                    <div class="bg-gradient-to-br from-[#1e3a8a] to-[#0f2b5e] rounded-xl p-5 text-white">
                        <div class="flex gap-3">
                            <div class="text-3xl">🎓</div>
                            <div>
                                <h4 class="font-semibold text-sm mb-1">Academic Tip</h4>
                                <p class="text-xs opacity-90 leading-relaxed">
                                    Students with complete profiles are more likely to be trusted for campus jobs and services.
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
                        <div class="flex items-center justify-between mb-3">
                            <span class="text-sm font-medium text-gray-500">Platform Activity</span>
                            <span class="text-xs text-[#1e3a8a]">▲ Active</span>
                        </div>

                        <div class="flex items-end gap-1 h-16">
                            <div class="flex-1 bg-blue-100 rounded-t h-8"></div>
                            <div class="flex-1 bg-blue-200 rounded-t h-12"></div>
                            <div class="flex-1 bg-[#1e3a8a] rounded-t h-16"></div>
                            <div class="flex-1 bg-blue-300 rounded-t h-10"></div>
                            <div class="flex-1 bg-blue-200 rounded-t h-6"></div>
                            <div class="flex-1 bg-blue-100 rounded-t h-4"></div>
                        </div>

                        <div class="flex justify-between mt-2 text-[10px] text-gray-400">
                            <span>M</span><span>T</span><span>W</span><span>T</span><span>F</span><span>S</span>
                        </div>
                    </div>
                </div>

                <div class="lg:col-span-2">
                    <div class="border-b border-gray-200 mb-6">
                        <nav class="flex flex-wrap gap-1 -mb-px">
                            <button onclick="showTab('my-skills')" id="my-skills-tab"
                                class="tab-button px-4 py-2.5 text-sm font-medium text-gray-500 hover:text-[#1e3a8a] border-b-2 border-transparent transition-all">
                                My Skills
                            </button>

                            <button onclick="showTab('my-jobs')" id="my-jobs-tab"
                                class="tab-button px-4 py-2.5 text-sm font-medium text-gray-500 hover:text-[#1e3a8a] border-b-2 border-transparent transition-all">
                                My Jobs
                            </button>

                            <button onclick="showTab('my-applications')" id="my-applications-tab"
                                class="tab-button px-4 py-2.5 text-sm font-medium text-gray-500 hover:text-[#1e3a8a] border-b-2 border-transparent transition-all relative">
                                My Applications

                                @if($pendingMyApplicationActions > 0)
                                    <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs px-1.5 py-0.5 rounded-full min-w-[18px] text-center">
                                        {{ $pendingMyApplicationActions > 9 ? '9+' : $pendingMyApplicationActions }}
                                    </span>
                                @endif
                            </button>

                            <button onclick="showTab('applications')" id="applications-tab"
                                class="tab-button px-4 py-2.5 text-sm font-medium text-gray-500 hover:text-[#1e3a8a] border-b-2 border-transparent transition-all relative">
                                Applications Received

                                @if($pendingReceivedApplications > 0)
                                    <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs px-1.5 py-0.5 rounded-full min-w-[18px] text-center">
                                        {{ $pendingReceivedApplications > 9 ? '9+' : $pendingReceivedApplications }}
                                    </span>
                                @endif
                            </button>

                            <button onclick="showTab('bookings')" id="bookings-tab"
                                class="tab-button px-4 py-2.5 text-sm font-medium text-gray-500 hover:text-[#1e3a8a] border-b-2 border-transparent transition-all relative">
                                My Bookings

                                @if($totalPendingBookingActions > 0)
                                    <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs px-1.5 py-0.5 rounded-full min-w-[18px] text-center">
                                        {{ $totalPendingBookingActions > 9 ? '9+' : $totalPendingBookingActions }}
                                    </span>
                                @endif
                            </button>

                            <button onclick="showTab('messages')" id="messages-tab"
                                class="tab-button px-4 py-2.5 text-sm font-medium text-gray-500 hover:text-[#1e3a8a] border-b-2 border-transparent transition-all relative">
                                Messages

                                @if(auth()->user()->unreadCount() > 0)
                                    <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs px-1.5 py-0.5 rounded-full min-w-[18px] text-center">
                                        {{ auth()->user()->unreadCount() }}
                                    </span>
                                @endif
                            </button>
                        </nav>
                    </div>

                    <div id="tab-content">
                        <div id="my-skills-content" class="tab-content">
                            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 text-center">
                                <div class="w-16 h-16 bg-blue-50 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <i class="fa-solid fa-wrench text-2xl text-[#1e3a8a]"></i>
                                </div>
                                <h3 class="text-lg font-medium text-gray-800 mb-2">Your Skills</h3>
                                <p class="text-gray-500 text-sm mb-5">Manage your skills and track bookings</p>
                                <a href="{{ route('skills.mine') }}"
                                   class="inline-flex items-center gap-2 px-5 py-2.5 bg-[#1e3a8a] text-white text-sm rounded-lg hover:bg-[#0f2b5e] transition-all">
                                    <i class="fa-solid fa-eye"></i>
                                    View My Skills
                                </a>
                            </div>
                        </div>

                        <div id="my-jobs-content" class="tab-content hidden">
                            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 text-center">
                                <div class="w-16 h-16 bg-blue-50 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <i class="fa-solid fa-briefcase text-2xl text-[#1e3a8a]"></i>
                                </div>
                                <h3 class="text-lg font-medium text-gray-800 mb-2">Your Job Postings</h3>
                                <p class="text-gray-500 text-sm mb-5">Manage your job listings and applicants</p>
                                <a href="{{ route('jobs.mine') }}"
                                   class="inline-flex items-center gap-2 px-5 py-2.5 bg-[#1e3a8a] text-white text-sm rounded-lg hover:bg-[#0f2b5e] transition-all">
                                    <i class="fa-solid fa-eye"></i>
                                    View My Jobs
                                </a>
                            </div>
                        </div>

                        <div id="my-applications-content" class="tab-content hidden">
                            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 text-center">
                                <div class="w-16 h-16 bg-blue-50 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <i class="fa-solid fa-paper-plane text-2xl text-[#1e3a8a]"></i>
                                </div>
                                <h3 class="text-lg font-medium text-gray-800 mb-2">My Applications</h3>
                                <p class="text-gray-500 text-sm mb-5">Track your job application status</p>
                                <a href="{{ route('applications.mine') }}"
                                   class="inline-flex items-center gap-2 px-5 py-2.5 bg-[#1e3a8a] text-white text-sm rounded-lg hover:bg-[#0f2b5e] transition-all">
                                    <i class="fa-solid fa-eye"></i>
                                    View Applications
                                </a>
                            </div>
                        </div>

                        <div id="applications-content" class="tab-content hidden">
                            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 text-center">
                                <div class="w-16 h-16 bg-blue-50 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <i class="fa-solid fa-inbox text-2xl text-[#1e3a8a]"></i>
                                </div>
                                <h3 class="text-lg font-medium text-gray-800 mb-2">Applications Received</h3>
                                <p class="text-gray-500 text-sm mb-5">Review applicants for your job postings</p>
                                <a href="{{ route('applications.received') }}"
                                   class="inline-flex items-center gap-2 px-5 py-2.5 bg-[#1e3a8a] text-white text-sm rounded-lg hover:bg-[#0f2b5e] transition-all">
                                    <i class="fa-solid fa-eye"></i>
                                    View Applications
                                </a>
                            </div>
                        </div>

                        <div id="bookings-content" class="tab-content hidden">
                            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                                <h3 class="text-lg font-medium text-gray-800 mb-5">My Bookings</h3>

                                <div class="grid md:grid-cols-2 gap-4">
                                    <div class="border border-gray-100 rounded-xl p-5 text-center bg-gray-50/30">
                                        <div class="w-12 h-12 bg-blue-50 rounded-full flex items-center justify-center mx-auto mb-3">
                                            <i class="fa-solid fa-hand-point-up text-xl text-[#1e3a8a]"></i>
                                        </div>
                                        <h4 class="font-medium text-gray-800 mb-1">Services I Requested</h4>
                                        <p class="text-xs text-gray-500 mb-4">Services you requested from others</p>

                                        <a href="{{ route('bookings.requests') }}"
                                           class="relative inline-flex items-center gap-2 px-4 py-2 bg-[#1e3a8a] text-white text-sm rounded-lg hover:bg-[#0f2b5e] transition-all">
                                            <i class="fa-solid fa-eye"></i>
                                            View Requests

                                            @if($pendingServiceActions > 0)
                                                <span class="absolute -top-2 -right-2 bg-red-500 text-white text-xs px-1.5 py-0.5 rounded-full">
                                                    {{ $pendingServiceActions > 9 ? '9+' : $pendingServiceActions }}
                                                </span>
                                            @endif
                                        </a>
                                    </div>

                                    <div class="border border-gray-100 rounded-xl p-5 text-center bg-gray-50/30">
                                        <div class="w-12 h-12 bg-blue-50 rounded-full flex items-center justify-center mx-auto mb-3">
                                            <i class="fa-solid fa-calendar-check text-xl text-[#1e3a8a]"></i>
                                        </div>
                                        <h4 class="font-medium text-gray-800 mb-1">Bookings For My Skills</h4>
                                        <p class="text-xs text-gray-500 mb-4">Bookings made on your skills</p>

                                        <a href="{{ route('bookings.skills') }}"
                                           class="relative inline-flex items-center gap-2 px-4 py-2 bg-[#1e3a8a] text-white text-sm rounded-lg hover:bg-[#0f2b5e] transition-all">
                                            <i class="fa-solid fa-eye"></i>
                                            View Bookings

                                            @if($pendingSkillBookings > 0)
                                                <span class="absolute -top-2 -right-2 bg-red-500 text-white text-xs px-1.5 py-0.5 rounded-full">
                                                    {{ $pendingSkillBookings > 9 ? '9+' : $pendingSkillBookings }}
                                                </span>
                                            @endif
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div id="messages-content" class="tab-content hidden">
                            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 text-center">
                                <div class="w-16 h-16 bg-blue-50 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <i class="fa-solid fa-envelope text-2xl text-[#1e3a8a]"></i>
                                </div>
                                <h3 class="text-lg font-medium text-gray-800 mb-2">Messages</h3>
                                <p class="text-gray-500 text-sm mb-5">Communicate with other campus members</p>

                                @if(auth()->user()->unreadCount() > 0)
                                    <p class="text-sm text-[#1e3a8a] mb-4">
                                        You have <strong>{{ auth()->user()->unreadCount() }}</strong>
                                        unread message{{ auth()->user()->unreadCount() > 1 ? 's' : '' }}
                                    </p>
                                @endif

                                <a href="{{ route('messages.index') }}"
                                   class="inline-flex items-center gap-2 px-5 py-2.5 bg-[#1e3a8a] text-white text-sm rounded-lg hover:bg-[#0f2b5e] transition-all">
                                    <i class="fa-solid fa-eye"></i>
                                    View Messages
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if(auth()->check() && isset($recentApplications) && $recentApplications->count() > 0)
            <div id="editApplicationModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center p-4">
                <div class="bg-white rounded-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
                    <div class="p-6 border-b border-gray-200">
                        <div class="flex items-center justify-between">
                            <h3 class="text-xl font-bold text-gray-900">Edit Job Application</h3>
                            <button type="button" onclick="hideEditApplicationModal()" aria-label="Close edit application modal" class="text-gray-400 hover:text-gray-600">
                                <i class="fa-solid fa-times text-xl"></i>
                            </button>
                        </div>
                    </div>

                    <form id="editApplicationForm" method="POST" class="p-6">
                        @csrf
                        @method('PATCH')

                        <input type="hidden" id="editApplicationId" name="application_id">

                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Cover Letter *</label>
                            <textarea id="editCoverLetter" name="cover_letter" rows="6" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-[#1e3a8a] focus:ring-2 focus:ring-[#1e3a8a]/20"
                                placeholder="Tell us why you're interested in this position..."></textarea>
                            <p class="text-xs text-gray-500 mt-1">Minimum 50 characters</p>
                        </div>

                        <div class="flex gap-3">
                            <button type="submit" class="flex-1 px-6 py-3 bg-[#1e3a8a] text-white font-semibold rounded-lg hover:bg-[#0f2b5e] transition-all">
                                Update Application
                            </button>
                            <button type="button" onclick="hideEditApplicationModal()" class="px-6 py-3 border border-gray-300 text-gray-700 font-semibold rounded-lg hover:bg-gray-50 transition-all">
                                Cancel
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        @endif

        <div id="revisionModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden opacity-0 pointer-events-none flex items-center justify-center p-4">
            <div class="bg-white rounded-2xl max-w-lg w-full">
                <div class="p-6 border-b border-gray-200 flex items-center justify-between">
                    <h3 class="text-xl font-bold text-gray-900">Request Revision</h3>
                    <button type="button" onclick="hideRevisionModal()" aria-label="Close revision modal" class="text-gray-400 hover:text-gray-600">
                        <i class="fa-solid fa-times text-xl"></i>
                    </button>
                </div>

                <form id="revisionForm" class="p-6">
                    @csrf
                    <input type="hidden" id="revisionApplicationId">

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">What needs to be revised? *</label>
                        <textarea id="revisionNote" name="revision_note" rows="4" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-[#1e3a8a] focus:ring-2 focus:ring-[#1e3a8a]/20"
                            placeholder="Describe clearly what changes or corrections are needed..."></textarea>
                    </div>

                    <div class="flex gap-3">
                        <button type="submit" class="flex-1 px-6 py-3 bg-[#1e3a8a] text-white font-semibold rounded-lg hover:bg-[#0f2b5e] transition-all">
                            Send Revision Request
                        </button>

                        <button type="button" onclick="hideRevisionModal()" class="px-6 py-3 border border-gray-300 text-gray-700 font-semibold rounded-lg hover:bg-gray-50 transition-all">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('styles')
<style>
    .tab-button.active {
        color: #1e3a8a;
        border-bottom-color: #1e3a8a;
    }

    .tab-button {
        transition: all 0.2s ease;
    }

    .tab-content {
        transition: opacity 0.2s ease;
    }
</style>
@endpush

@push('scripts')
<script>
    function showTab(tabName) {
        document.querySelectorAll('.tab-content').forEach(content => {
            content.classList.add('hidden');
        });

        document.querySelectorAll('.tab-button').forEach(tab => {
            tab.classList.remove('active');
        });

        const selectedContent = document.getElementById(tabName + '-content');
        if (selectedContent) {
            selectedContent.classList.remove('hidden');
        }

        const selectedTab = document.getElementById(tabName + '-tab');
        if (selectedTab) {
            selectedTab.classList.add('active');
        }

        localStorage.setItem('activeDashboardTab', tabName);
    }

    document.addEventListener('DOMContentLoaded', function () {
        const activeTab = localStorage.getItem('activeDashboardTab') || 'my-skills';
        showTab(activeTab);

        document.querySelectorAll('.tab-button').forEach(btn => {
            btn.addEventListener('click', function () {
                const id = this.id;

                if (id) {
                    localStorage.setItem('activeDashboardTab', id.replace('-tab', ''));
                }
            });
        });

        const editForm = document.getElementById('editApplicationForm');

        if (editForm) {
            editForm.addEventListener('submit', function (e) {
                e.preventDefault();

                const formData = new FormData(this);
                const applicationId = document.getElementById('editApplicationId').value;

                fetch(`/applications/${applicationId}`, {
                    method: 'PATCH',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                        'Accept': 'application/json'
                    },
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showSuccessMessage(data.message || 'Application updated successfully.');
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

        const revisionForm = document.getElementById('revisionForm');

        if (revisionForm) {
            revisionForm.addEventListener('submit', function (e) {
                e.preventDefault();

                const formData = new FormData(this);
                const applicationId = document.getElementById('revisionApplicationId').value;

                fetch(`/applications/${applicationId}/revision`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                        'Accept': 'application/json'
                    },
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showSuccessMessage(data.message || 'Revision request sent successfully.');
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

    function showEditApplicationModal(applicationId) {
        const applications = @json($recentApplications ?? []);
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
        const modal = document.getElementById('editApplicationModal');

        if (modal) {
            modal.classList.add('hidden');
        }

        document.body.style.overflow = 'auto';
    }

    function showRevisionModal(applicationId) {
        const modal = document.getElementById('revisionModal');
        const applicationIdInput = document.getElementById('revisionApplicationId');
        const revisionNoteInput = document.getElementById('revisionNote');

        if (!modal || !applicationIdInput || !revisionNoteInput) return;

        applicationIdInput.value = applicationId;
        revisionNoteInput.value = '';
        modal.classList.remove('hidden', 'opacity-0', 'pointer-events-none');
        document.body.style.overflow = 'hidden';
    }

    function hideRevisionModal() {
        const modal = document.getElementById('revisionModal');

        if (modal) {
            modal.classList.add('hidden', 'opacity-0', 'pointer-events-none');
        }

        document.body.style.overflow = 'auto';
    }

    function showSuccessMessage(message) {
        const successDiv = document.createElement('div');
        successDiv.className = 'fixed top-4 right-4 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg flex items-center gap-3 z-50';
        successDiv.innerHTML = '<i class="fa-solid fa-check-circle"></i><span>' + message + '</span>';

        document.body.appendChild(successDiv);

        setTimeout(() => {
            successDiv.remove();
        }, 3000);
    }

    function updateApplicationStatus(applicationId, status) {
        if (!confirm('Are you sure you want to ' + status.toUpperCase() + ' this application?')) {
            return;
        }

        const urlMap = {
            accepted: `/applications/${applicationId}/accept`,
            rejected: `/applications/${applicationId}/reject`
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
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showSuccessMessage(data.message || 'Application updated successfully.');
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

    function withdrawApplication(applicationId) {
        if (!confirm('Are you sure you want to withdraw this application? This action cannot be undone.')) {
            return;
        }

        fetch(`/applications/${applicationId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showSuccessMessage(data.message || 'Application withdrawn successfully.');
                setTimeout(() => window.location.reload(), 1500);
            } else {
                alert(data.message || 'Error withdrawing application');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error withdrawing application');
        });
    }

    function startWork(applicationId) {
        fetch(`/applications/${applicationId}/start`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showSuccessMessage(data.message || 'Work started successfully.');
                setTimeout(() => window.location.reload(), 1500);
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
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showSuccessMessage(data.message || 'Marked complete successfully.');
                setTimeout(() => window.location.reload(), 1500);
            } else {
                alert(data.message || 'Error marking complete');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error marking complete');
        });
    }

    function confirmComplete(applicationId) {
        if (!confirm('Are you sure you want to confirm this job as complete? This will unlock ratings.')) {
            return;
        }

        fetch(`/applications/${applicationId}/confirm`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showSuccessMessage(data.message || 'Job confirmed successfully.');
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

    function setRating(applicationId, rating) {
        const container = document.getElementById(`rating-stars-${applicationId}`);

        if (!container) return;

        container.setAttribute('data-selected', rating);

        container.querySelectorAll('button').forEach((star, index) => {
            star.classList.remove('text-yellow-400', 'text-gray-300');
            star.classList.add(index < rating ? 'text-yellow-400' : 'text-gray-300');
        });
    }

    function setEmployerRating(applicationId, rating) {
        const container = document.getElementById(`rating-stars-${applicationId}-employer`);

        if (!container) return;

        container.setAttribute('data-selected', rating);

        container.querySelectorAll('button').forEach((star, index) => {
            star.classList.remove('text-yellow-400', 'text-gray-300');
            star.classList.add(index < rating ? 'text-yellow-400' : 'text-gray-300');
        });
    }

    function submitRating(applicationId, isEmployer = false) {
        const containerId = isEmployer
            ? `rating-stars-${applicationId}-employer`
            : `rating-stars-${applicationId}`;

        const container = document.getElementById(containerId);
        const rating = container ? container.getAttribute('data-selected') : null;

        if (!rating) {
            alert('Please select a rating before submitting.');
            return;
        }

        const reviewTextId = isEmployer
            ? `employer-review-text-${applicationId}`
            : `review-text-${applicationId}`;

        const reviewText = document.getElementById(reviewTextId)?.value || '';

        fetch(`/applications/${applicationId}/rate`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                rating: parseInt(rating),
                review: reviewText
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showSuccessMessage(data.message || 'Rating submitted successfully.');
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

    function confirmBooking(bookingId) {
        if (!confirm('Are you sure you want to confirm this booking?')) return;

        fetch(`/bookings/${bookingId}/confirm`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showSuccessMessage(data.message || 'Booking confirmed successfully.');
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
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showSuccessMessage(data.message || 'Booking declined successfully.');
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
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showSuccessMessage(data.message || 'Service confirmed successfully.');
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
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showSuccessMessage(data.message || 'Service marked as completed.');
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
        const rating = prompt('Rate this service (1-5):');

        if (!rating || rating < 1 || rating > 5) {
            alert('Please enter a valid rating between 1 and 5.');
            return;
        }

        const review = prompt('Leave a review (optional):') || '';

        fetch(`/bookings/${bookingId}/rate`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
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
                showSuccessMessage(data.message || 'Rating submitted successfully.');
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
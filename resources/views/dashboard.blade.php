@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-gray-50">
        <div class="max-w-7xl mx-auto px-6 py-10">

            <!-- Greeting -->
            <div class="mb-10">
                <h1 class="text-4xl font-bold text-gray-800">
                    Hello, {{ auth()->user()->first_name ?? 'Student' }}! 👋
                </h1>
                <p class="text-gray-600 mt-1">Here's what's happening on campus today • {{ now()->format('l, jS F Y') }}</p>
            </div>

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-5 gap-6 mb-12">
                <div class="bg-white rounded-3xl p-6 shadow-sm">
                    <div class="flex justify-between">
                        <div>
                            <p class="text-sm text-gray-500">Active Skills</p>
                            <p class="text-3xl font-bold text-[#1e3a8a] mt-2">{{ $stats['active_skills'] }}</p>
                        </div>
                        <div class="text-3xl">⭐</div>
                    </div>
                </div>

                <div class="bg-white rounded-3xl p-6 shadow-sm">
                    <div class="flex justify-between">
                        <div>
                            <p class="text-sm text-gray-500">My Applications</p>
                            <p class="text-3xl font-bold text-[#1e3a8a] mt-2">{{ $stats['job_applications'] }}</p>
                        </div>
                        <div class="text-3xl">📤</div>
                    </div>
                </div>

                <div class="bg-white rounded-3xl p-6 shadow-sm">
                    <div class="flex justify-between">
                        <div>
                            <p class="text-sm text-gray-500">Posted Jobs</p>
                            <p class="text-3xl font-bold text-[#1e3a8a] mt-2">{{ $stats['posted_jobs'] }}</p>
                        </div>
                        <div class="text-3xl">📋</div>
                    </div>
                </div>

                <div class="bg-white rounded-3xl p-6 shadow-sm">
                    <div class="flex justify-between">
                        <div>
                            <p class="text-sm text-gray-500">Applications Received</p>
                            <p class="text-3xl font-bold text-[#1e3a8a] mt-2">{{ $stats['received_applications'] }}</p>
                        </div>
                        <div class="text-3xl">�</div>
                    </div>
                </div>

                <div class="bg-white rounded-3xl p-6 shadow-sm">
                    <div class="flex justify-between">
                        <div>
                            <p class="text-sm text-gray-500">Total Earnings</p>
                            <p class="text-3xl font-bold text-[#1e3a8a] mt-2">₦{{ number_format($stats['total_earnings'], 0) }}</p>
                        </div>
                        <div class="text-3xl">💰</div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                
                <!-- Quick Actions -->
                <div class="lg:col-span-4">
                    <h3 class="font-semibold text-lg mb-5">Quick Actions</h3>
                    <div class="space-y-4">
                        <a href="{{ route('skills.index') }}" class="block bg-[#1e3a8a] text-white p-5 rounded-3xl hover:bg-[#0f2b5e] transition-all flex items-center justify-between">
                            <span class="font-medium">Browse Skills</span>
                            <i class="fa-solid fa-arrow-right"></i>
                        </a>
                        <a href="{{ route('jobs.create') }}" class="block border border-gray-300 p-5 rounded-3xl hover:bg-gray-50 transition-all flex items-center justify-between">
                            <span class="font-medium">Post a Job</span>
                            <i class="fa-solid fa-arrow-right"></i>
                        </a>
                        <a href="{{ route('skills.create') }}" class="block border border-gray-300 p-5 rounded-3xl hover:bg-gray-50 transition-all flex items-center justify-between">
                            <span class="font-medium">Post a Skill</span>
                            <i class="fa-solid fa-arrow-right"></i>
                        </a>
                        <a href="{{ route('profile.edit') }}" class="block border border-gray-300 p-5 rounded-3xl hover:bg-gray-50 transition-all flex items-center justify-between">
                            <span class="font-medium">View My Profile</span>
                            <i class="fa-solid fa-arrow-right"></i>
                        </a>
                    </div>

                    <!-- Academic Tip -->
                    <div class="mt-10 bg-emerald-700 text-white p-7 rounded-3xl">
                        <h4 class="font-semibold mb-3">Academic Tip</h4>
                        <p class="text-sm leading-relaxed">
                            Students with complete profiles are 3x more likely to be hired for peer tutoring roles.
                        </p>
                    </div>
                </div>

                <!-- Tabbed Content -->
                <div class="lg:col-span-8">
                    <!-- Tabs -->
                    <div class="border-b border-gray-200 mb-6">
                        <nav class="flex space-x-8">
                            <button onclick="showTab('my-skills')" id="my-skills-tab" class="tab-button py-2 px-1 border-b-2 border-[#1e3a8a] text-[#1e3a8a] font-medium text-sm">
                                My Skills
                            </button>
                            <button onclick="showTab('my-jobs')" id="my-jobs-tab" class="tab-button py-2 px-1 border-b-2 border-transparent text-gray-500 font-medium text-sm hover:text-gray-700">
                                My Jobs
                            </button>
                            <button onclick="showTab('applications')" id="applications-tab" class="tab-button py-2 px-1 border-b-2 border-transparent text-gray-500 font-medium text-sm hover:text-gray-700">
                                Applications
                            </button>
                            <button onclick="showTab('orders')" id="orders-tab" class="tab-button py-2 px-1 border-b-2 border-transparent text-gray-500 font-medium text-sm hover:text-gray-700">
                                Orders
                            </button>
                            <button onclick="showTab('messages')" id="messages-tab" class="tab-button py-2 px-1 border-b-2 border-transparent text-gray-500 font-medium text-sm hover:text-gray-700">
                                Messages
                                @if(auth()->user()->unreadCount() > 0)
                                    <span class="ml-2 bg-red-500 text-white text-xs px-2 py-0.5 rounded-full">{{ auth()->user()->unreadCount() }}</span>
                                @endif
                            </button>
                        </nav>
                    </div>

                    <!-- Tab Content -->
                    <div id="tab-content">
                        <!-- My Skills Tab -->
                        <div id="my-skills-content" class="tab-content">
                            @if($userSkills->count() > 0)
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    @foreach($userSkills as $skill)
                                        <div class="bg-white p-4 rounded-2xl border border-gray-200 hover:shadow-md transition-all">
                                            <div class="flex justify-between items-start mb-3">
                                                <h4 class="font-semibold text-gray-800">{{ $skill->title }}</h4>
                                                <span class="bg-emerald-100 text-emerald-700 text-xs px-2 py-1 rounded-full">{{ $skill->status }}</span>
                                            </div>
                                            <p class="text-sm text-gray-600 mb-3 line-clamp-2">{{ $skill->description }}</p>
                                            <div class="flex justify-between items-center text-sm">
                                                <span class="text-[#1e3a8a] font-medium">{{ $skill->formatted_price }}</span>
                                                <div class="flex gap-4 text-gray-500">
                                                    <span>⭐ {{ $skill->reviews_count }}</span>
                                                    <span>📦 {{ $skill->orders_count }}</span>
                                                    <span>👁 {{ $skill->views_count }}</span>
                                                </div>
                                            </div>
                                            <div class="mt-3 pt-3 border-t flex justify-between items-center">
                                                <a href="{{ route('skills.show', $skill->id) }}" class="text-[#1e3a8a] text-sm font-medium hover:underline">View Details</a>
                                                <div class="flex gap-2">
                                                    <a href="{{ route('skills.edit', $skill->id) }}" class="text-blue-600 text-sm font-medium hover:text-blue-700">
                                                        <i class="fa-solid fa-edit"></i> Edit
                                                    </a>
                                                    <form action="{{ route('skills.destroy', $skill->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this skill?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="text-red-600 text-sm font-medium hover:text-red-700">
                                                            <i class="fa-solid fa-trash"></i> Delete
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="bg-white p-8 rounded-2xl text-center">
                                    <div class="text-6xl mb-4">📚</div>
                                    <h3 class="text-xl font-semibold text-gray-800 mb-2">No Skills Posted Yet</h3>
                                    <p class="text-gray-600 mb-6">Share your talents with the campus community and start earning!</p>
                                    <a href="{{ route('skills.create') }}" class="inline-flex items-center gap-2 bg-[#1e3a8a] text-white px-6 py-3 rounded-xl hover:bg-[#0f2b5e] transition-all">
                                        <i class="fa-solid fa-plus"></i>
                                        Post Your First Skill
                                    </a>
                                </div>
                            @endif
                        </div>

                        <!-- My Jobs Tab -->
                        <div id="my-jobs-content" class="tab-content hidden">
                            @if($userJobs->count() > 0)
                                <div class="space-y-4">
                                    @foreach($userJobs as $job)
                                        <div class="bg-white p-4 rounded-2xl border border-gray-200 hover:shadow-md transition-all">
                                            <div class="flex justify-between items-start mb-3">
                                                <h4 class="font-semibold text-gray-800">{{ $job->title }}</h4>
                                                <span class="bg-blue-100 text-blue-700 text-xs px-2 py-1 rounded-full">{{ $job->status }}</span>
                                            </div>
                                            <p class="text-sm text-gray-600 mb-3 line-clamp-2">{{ $job->description }}</p>
                                            <div class="flex justify-between items-center text-sm">
                                                <span class="text-[#1e3a8a] font-medium">{{ $job->formatted_salary }}</span>
                                                <div class="flex gap-4 text-gray-500">
                                                    <span>📥 {{ $job->applications_count }} applications</span>
                                                    <span>👁 {{ $job->views_count }}</span>
                                                    @if($job->deadline)
                                                        <span>⏰ {{ $job->deadline_days }}</span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="mt-3 pt-3 border-t flex justify-between items-center">
                                                <a href="{{ route('jobs.show', $job->id) }}" class="text-[#1e3a8a] text-sm font-medium hover:underline">View Details</a>
                                                <div class="flex gap-2">
                                                    <a href="{{ route('jobs.edit', $job->id) }}" class="text-blue-600 text-sm font-medium hover:text-blue-700">
                                                        <i class="fa-solid fa-edit"></i> Edit
                                                    </a>
                                                    <form action="{{ route('jobs.destroy', $job->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this job?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="text-red-600 text-sm font-medium hover:text-red-700">
                                                            <i class="fa-solid fa-trash"></i> Delete
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="bg-white p-8 rounded-2xl text-center">
                                    <div class="text-6xl mb-4">💼</div>
                                    <h3 class="text-xl font-semibold text-gray-800 mb-2">No Jobs Posted Yet</h3>
                                    <p class="text-gray-600 mb-6">Find talented students and faculty for your campus needs!</p>
                                    <a href="{{ route('jobs.create') }}" class="inline-flex items-center gap-2 bg-[#1e3a8a] text-white px-6 py-3 rounded-xl hover:bg-[#0f2b5e] transition-all">
                                        <i class="fa-solid fa-plus"></i>
                                        Post Your First Job
                                    </a>
                                </div>
                            @endif
                        </div>

                        <!-- Applications Tab -->
                        <div id="applications-content" class="tab-content hidden">
                            @if($jobApplications->count() > 0)
                                <div class="space-y-4">
                                    @foreach($jobApplications as $application)
                                        <div class="bg-white p-4 rounded-2xl border border-gray-200 hover:shadow-md transition-all">
                                            <div class="flex justify-between items-start mb-3">
                                                <div>
                                                    <h4 class="font-semibold text-gray-800">{{ $application->job->title }}</h4>
                                                    <p class="text-sm text-gray-600">Applied by: {{ $application->applicant->fullName() }}</p>
                                                </div>
                                                <span class="bg-{{ $application->status === 'pending' ? 'amber' : ($application->status === 'accepted' ? 'emerald' : 'red') }}-100 text-{{ $application->status === 'pending' ? 'amber' : ($application->status === 'accepted' ? 'emerald' : 'red') }}-700 text-xs px-2 py-1 rounded-full capitalize">{{ $application->status }}</span>
                                            </div>
                                            <p class="text-sm text-gray-600 mb-3">{{ $application->cover_letter }}</p>
                                            <div class="flex justify-between items-center text-sm">
                                                <span class="text-gray-500">Applied: {{ $application->applied_at->format('M j, Y') }}</span>
                                                <div class="flex gap-2">
                                                    <a href="{{ route('profile.show', $application->applicant->id) }}" class="text-[#1e3a8a] hover:underline">View Profile</a>
                                                    <button onclick="updateApplicationStatus({{ $application->id }}, 'accepted')" class="text-emerald-600 hover:underline">Accept</button>
                                                    <button onclick="updateApplicationStatus({{ $application->id }}, 'rejected')" class="text-red-600 hover:underline">Reject</button>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="bg-white p-8 rounded-2xl text-center">
                                    <div class="text-6xl mb-4">📥</div>
                                    <h3 class="text-xl font-semibold text-gray-800 mb-2">No Applications Received</h3>
                                    <p class="text-gray-600 mb-6">Applications for your posted jobs will appear here.</p>
                                    <a href="{{ route('jobs.create') }}" class="inline-flex items-center gap-2 bg-[#1e3a8a] text-white px-6 py-3 rounded-xl hover:bg-[#0f2b5e] transition-all">
                                        <i class="fa-solid fa-briefcase"></i>
                                        Post a Job
                                    </a>
                                </div>
                            @endif
                        </div>

                        <!-- Orders Tab -->
                        <div id="orders-content" class="tab-content hidden">
                            @if($myOrders->count() > 0)
                                <div class="space-y-4">
                                    @foreach($myOrders as $order)
                                        <div class="bg-white p-4 rounded-2xl border border-gray-200 hover:shadow-md transition-all">
                                            <div class="flex justify-between items-start mb-3">
                                                <div>
                                                    <h4 class="font-semibold text-gray-800">{{ $order->skill->title }}</h4>
                                                    <p class="text-sm text-gray-600">From: {{ $order->vendor->fullName() }}</p>
                                                </div>
                                                <span class="bg-emerald-100 text-emerald-700 text-xs px-2 py-1 rounded-full">{{ $order->status }}</span>
                                            </div>
                                            <div class="flex justify-between items-center text-sm">
                                                <span class="text-[#1e3a8a] font-medium">₦{{ number_format($order->total_amount, 0) }}</span>
                                                <span class="text-gray-500">{{ $order->created_at->format('M j, Y') }}</span>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="bg-white p-8 rounded-2xl text-center">
                                    <div class="text-6xl mb-4">🛒</div>
                                    <h3 class="text-xl font-semibold text-gray-800 mb-2">No Orders Yet</h3>
                                    <p class="text-gray-600 mb-6">Your skill purchases will appear here.</p>
                                    <a href="{{ route('skills.index') }}" class="inline-flex items-center gap-2 bg-[#1e3a8a] text-white px-6 py-3 rounded-xl hover:bg-[#0f2b5e] transition-all">
                                        <i class="fa-solid fa-search"></i>
                                        Browse Skills
                                    </a>
                                </div>
                            @endif
                        </div>

                        <!-- Messages Tab -->
                        <div id="messages-content" class="tab-content hidden">
                            @if(auth()->user()->unreadCount() > 0)
                                <div class="mb-6 p-4 bg-blue-50 border border-blue-200 rounded-xl">
                                    <div class="flex items-center gap-2">
                                        <i class="fa-solid fa-envelope text-blue-600"></i>
                                        <span class="text-blue-800 font-medium">You have {{ auth()->user()->unreadCount() }} unread message{{ auth()->user()->unreadCount() > 1 ? 's' : '' }}</span>
                                        <a href="{{ route('messages.index') }}" class="ml-auto text-blue-600 hover:text-blue-800 font-medium">
                                            View All Messages
                                        </a>
                                    </div>
                                </div>
                            @endif

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="bg-white border border-gray-200 rounded-xl p-6">
                                    <div class="flex items-center gap-3 mb-4">
                                        <i class="fa-solid fa-envelope text-2xl text-[#1e3a8a]"></i>
                                        <div>
                                            <h3 class="font-bold text-gray-900">Messages</h3>
                                            <p class="text-sm text-gray-600">Communicate with service providers</p>
                                        </div>
                                    </div>
                                    <div class="text-center">
                                        <a href="{{ route('messages.index') }}" class="inline-flex items-center px-6 py-3 bg-[#1e3a8a] text-white font-semibold rounded-xl hover:bg-[#0f2b5e] transition-all">
                                            <i class="fa-solid fa-message mr-2"></i>
                                            View All Messages
                                        </a>
                                    </div>
                                </div>

                                <div class="bg-white border border-gray-200 rounded-xl p-6">
                                    <div class="flex items-center gap-3 mb-4">
                                        <i class="fa-solid fa-paper-plane text-2xl text-green-600"></i>
                                        <div>
                                            <h3 class="font-bold text-gray-900">Sent Messages</h3>
                                            <p class="text-sm text-gray-600">Messages you've sent to providers</p>
                                        </div>
                                    </div>
                                    <div class="text-center">
                                        <a href="{{ route('messages.index') }}?tab=sent" class="inline-flex items-center px-6 py-3 bg-green-600 text-white font-semibold rounded-xl hover:bg-green-700 transition-all">
                                            <i class="fa-solid fa-paper-plane mr-2"></i>
                                            View Sent Messages
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<style>
.tab-button {
    transition: all 0.2s ease-in-out;
}

.tab-button:hover {
    color: #374151;
}

.tab-content {
    animation: fadeIn 0.3s ease-in-out;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}

.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>

<script>
// Tab functionality
function showTab(tabName) {
    // Hide all tab contents
    const allContents = document.querySelectorAll('.tab-content');
    allContents.forEach(content => {
        content.classList.add('hidden');
    });
    
    // Remove active state from all tabs
    const allTabs = document.querySelectorAll('.tab-button');
    allTabs.forEach(tab => {
        tab.classList.remove('border-[#1e3a8a]', 'text-[#1e3a8a]');
        tab.classList.add('border-transparent', 'text-gray-500');
    });
    
    // Show selected tab content
    const selectedContent = document.getElementById(tabName + '-content');
    if (selectedContent) {
        selectedContent.classList.remove('hidden');
    }
    
    // Add active state to selected tab
    const selectedTab = document.getElementById(tabName + '-tab');
    if (selectedTab) {
        selectedTab.classList.remove('border-transparent', 'text-gray-500');
        selectedTab.classList.add('border-[#1e3a8a]', 'text-[#1e3a8a]');
    }
}

// Application status update
function updateApplicationStatus(applicationId, status) {
    if (!confirm('Are you sure you want to ' + status + ' this application?')) {
        return;
    }
    
    fetch(`/applications/${applicationId}/status`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ status: status })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Reload the page to show updated status
            window.location.reload();
        } else {
            alert('Error updating application status: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error updating application status');
    });
}

// Initialize with first tab active
document.addEventListener('DOMContentLoaded', function() {
    showTab('my-skills');
});

// Prevent back button caching and redirect guests to login
window.addEventListener('pageshow', function(event) {
    // Check if page is loaded from cache
    if (event.persisted) {
        // Force reload if user is not authenticated
        fetch('{{ route("dashboard") }}', {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        }).then(response => {
            if (!response.ok) {
                // User is not authenticated, redirect to login
                window.location.href = '{{ route("login") }}';
            } else {
                // User is authenticated, reload page to get fresh content
                window.location.reload();
            }
        }).catch(() => {
            // On error, redirect to login for safety
            window.location.href = '{{ route("login") }}';
        });
    }
});

// Additional protection: check authentication status periodically
setInterval(function() {
    fetch('{{ route("dashboard") }}', {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    }).then(response => {
        if (!response.ok) {
            window.location.href = '{{ route("login") }}';
        }
    }).catch(() => {
        // On error, redirect to login for safety
        window.location.href = '{{ route("login") }}';
    });
}, 30000); // Check every 30 seconds
</script>
@endpush
@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Received Applications</h1>
                    <p class="text-gray-600 mt-2">Manage applications for your job postings</p>
                </div>
                <a href="{{ route('jobs.create') }}" class="px-4 py-2 bg-[#1e3a8a] text-white rounded-lg hover:bg-[#0f2b5e] transition-colors">
                    <i class="fa-solid fa-plus mr-2"></i>Post New Job
                </a>
            </div>
        </div>

        <!-- Applications List -->
        @if($applications->count() > 0)
            <div class="space-y-6">
                @foreach($applications as $application)
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-shadow">
                        <div class="p-6">
                            <!-- Job Title and Applicant -->
                            <div class="flex items-start justify-between mb-4">
                                <div class="flex-1">
                                    <h3 class="text-lg font-semibold text-gray-900 mb-2">
                                        <a href="{{ route('jobs.show', $application->job_id) }}" class="hover:text-[#1e3a8a] transition-colors">
                                            {{ $application->job->title }}
                                        </a>
                                    </h3>
                                    <div class="flex items-center gap-4 text-sm text-gray-600">
                                        <span class="flex items-center gap-1">
                                            <i class="fa-solid fa-user text-gray-400"></i>
                                            {{ $application->applicant->first_name }} {{ $application->applicant->last_name }}
                                        </span>
                                        <span class="flex items-center gap-1">
                                            <i class="fa-solid fa-calendar text-gray-400"></i>
                                            Applied {{ $application->created_at->diffForHumans() }}
                                        </span>
                                    </div>
                                </div>
                                
                                <!-- Status Badge -->
                                <div class="flex flex-col items-end gap-2">
                                    @switch($application->status)
                                        @case('pending')
                                            <span class="px-3 py-1 bg-yellow-100 text-yellow-800 text-sm font-medium rounded-full">
                                                <i class="fa-solid fa-clock mr-1"></i>Pending
                                            </span>
                                            @break
                                        @case('accepted')
                                            <span class="px-3 py-1 bg-green-100 text-green-800 text-sm font-medium rounded-full">
                                                <i class="fa-solid fa-check mr-1"></i>Accepted
                                            </span>
                                            @break
                                        @case('rejected')
                                            <span class="px-3 py-1 bg-red-100 text-red-800 text-sm font-medium rounded-full">
                                                <i class="fa-solid fa-times mr-1"></i>Rejected
                                            </span>
                                            @break
                                    @endswitch
                                    
                                    <!-- Progress Status -->
                                    @if($application->progress)
                                        <span class="text-xs text-gray-500">
                                            {{ ucfirst(str_replace('_', ' ', $application->progress)) }}
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <!-- Cover Letter -->
                            @if($application->cover_letter)
                                <div class="mb-4">
                                    <h4 class="text-sm font-medium text-gray-700 mb-2">Cover Letter:</h4>
                                    <p class="text-sm text-gray-600 bg-gray-50 p-3 rounded-lg">{{ $application->cover_letter }}</p>
                                </div>
                            @endif

                            <!-- Progress Tracker (if accepted) -->
                            @if($application->status === 'accepted' && $application->progress)
                               @include('applications.partials.employer-progress-tracker', ['application' => $application])
                            @endif

                            <!-- Action Buttons -->
                            <div class="flex items-center justify-between pt-4 border-t">
                                <div class="flex gap-2">
                                    @if($application->status === 'pending')
                                        <button type="button"
        onclick="acceptApplication({{ $application->id }})"
        class="px-3 py-1 bg-green-600 text-white text-sm rounded-lg hover:bg-green-700 transition-colors">
    <i class="fa-solid fa-check mr-1"></i>Accept
</button>
                                        <button type="button"
        onclick="rejectApplication({{ $application->id }})"
        class="px-3 py-1 bg-red-600 text-white text-sm rounded-lg hover:bg-red-700 transition-colors">

    <i class="fa-solid fa-times mr-1"></i>Reject
</button>
                                    @endif
                                </div>
                                
                                <a href="{{ route('applications.show', $application->id) }}" class="text-sm text-[#1e3a8a] hover:text-[#0f2b5e] font-medium">
                                    View Details <i class="fa-solid fa-arrow-right ml-1"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-8">
                {{ $applications->links() }}
            </div>
        @else
            <div class="text-center py-12">
                <div class="text-6xl mb-4 text-gray-300">
                    <i class="fa-solid fa-inbox"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">No Applications Received</h3>
                <p class="text-gray-600 mb-6">Post more jobs to receive applications from talented students.</p>
                <a href="{{ route('jobs.create') }}" class="px-6 py-3 bg-[#1e3a8a] text-white rounded-lg hover:bg-[#0f2b5e] transition-colors">
                    <i class="fa-solid fa-plus mr-2"></i>Post New Job
                </a>
            </div>
        @endif
    </div>
</div>

<script>
function acceptApplication(applicationId) {
    if (!confirm('Accept this application?')) return;

    fetch(`/applications/${applicationId}/accept`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.message || 'Application accepted successfully!');
            location.reload();
        } else {
            alert(data.message || 'Error accepting application');
        }
    })
    .catch(() => alert('Error accepting application'));
}

function rejectApplication(applicationId) {
    if (!confirm('Are you sure you want to reject this application?')) return;

    fetch(`/applications/${applicationId}/reject`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.message || 'Application rejected successfully!');
            location.reload();
        } else {
            alert(data.message || 'Error rejecting application');
        }
    })
    .catch(() => alert('Error rejecting application'));
}
</script>
@endsection
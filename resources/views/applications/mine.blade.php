@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">My Applications</h1>
                    <p class="text-gray-600 mt-2">Track your job applications and their status</p>
                </div>
                <a href="{{ route('jobs.index') }}" class="px-4 py-2 bg-[#1e3a8a] text-white rounded-lg hover:bg-[#0f2b5e] transition-colors">
                    <i class="fa-solid fa-plus mr-2"></i>Browse Jobs
                </a>
            </div>
        </div>

        <!-- Applications List -->
        @if($applications->count() > 0)
            <div class="space-y-6">
                @foreach($applications as $application)
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-shadow">
                        <div class="p-6">
                            <!-- Job Title and Employer -->
                            <div class="flex items-start justify-between mb-4">
                                <div class="flex-1">
                                    <h3 class="text-lg font-semibold text-gray-900 mb-2">
                                        <a href="{{ route('jobs.show', $application->job_id) }}" class="hover:text-[#1e3a8a] transition-colors">
                                            {{ $application->job->title }}
                                        </a>
                                    </h3>
                                    <div class="flex items-center gap-4 text-sm text-gray-600">
                                        <span class="flex items-center gap-1">
                                            <i class="fa-solid fa-building text-gray-400"></i>
                                            {{ $application->job->employer->first_name }} {{ $application->job->employer->last_name }}
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

                            <!-- Cover Letter Preview -->
                            @if($application->cover_letter)
                                <div class="mb-4">
                                    <p class="text-sm text-gray-600 line-clamp-2">{{ Str::limit($application->cover_letter, 150) }}</p>
                                </div>
                            @endif

                            <!-- Progress Tracker (if accepted) -->
                            @if($application->status === 'accepted' && $application->progress)
                               @include('applications.partials.worker-progress-tracker', ['application' => $application])
                            @endif

                            <!-- Action Buttons -->
                            <div class="flex items-center justify-between pt-4 border-t">
                                <div class="flex gap-2">
                                    @if($application->status === 'pending')
                                        <a href="{{ route('applications.edit', $application->id) }}" class="text-sm text-blue-600 hover:text-blue-800">
                                            <i class="fa-solid fa-edit mr-1"></i>Edit
                                        </a>
                                        <form action="{{ route('applications.withdraw', $application->id) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-sm text-red-600 hover:text-red-800" onclick="return confirm('Are you sure you want to withdraw this application?')">
                                                <i class="fa-solid fa-times mr-1"></i>Withdraw
                                            </button>
                                        </form>
                                    @endif
                                </div>
                                
                               @if($application->status === 'pending')
    <a href="{{ route('applications.show', $application->id) }}" class="text-sm text-[#1e3a8a] hover:text-[#0f2b5e] font-medium">
        View Details <i class="fa-solid fa-arrow-right ml-1"></i>
    </a>
@endif
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
                    <i class="fa-solid fa-file-alt"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">No Applications Yet</h3>
                <p class="text-gray-600 mb-6">Start applying for jobs to track your applications here.</p>
                <a href="{{ route('jobs.index') }}" class="px-6 py-3 bg-[#1e3a8a] text-white rounded-lg hover:bg-[#0f2b5e] transition-colors">
                    <i class="fa-solid fa-search mr-2"></i>Browse Jobs
                </a>
            </div>
        @endif
    </div>
</div>

<script>
function startWork(applicationId) {
    fetch(`/applications/${applicationId}/start`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.message || 'Job started successfully!');
            location.reload();
        } else {
            alert(data.message || 'Error starting job');
        }
    })
    .catch(() => alert('Error starting job'));
}

function markComplete(applicationId) {
    fetch(`/applications/${applicationId}/complete`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.message || 'Work submitted successfully!');
            location.reload();
        } else {
            alert(data.message || 'Error submitting work');
        }
    })
    .catch(() => alert('Error submitting work'));
}
</script>
@endsection

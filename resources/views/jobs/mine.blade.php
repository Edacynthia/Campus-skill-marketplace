@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">My Job Postings</h1>
                    <p class="text-gray-600 mt-2">Manage your job listings and track applications</p>
                </div>
                <a href="{{ route('jobs.create') }}" class="px-4 py-2 bg-[#1e3a8a] text-white rounded-lg hover:bg-[#0f2b5e] transition-colors">
                    <i class="fa-solid fa-plus mr-2"></i>Post New Job
                </a>
            </div>
        </div>

        <!-- Jobs List -->
        @if($jobs->count() > 0)
            <div class="space-y-6">
                @foreach($jobs as $job)
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-shadow">
                        <div class="p-6">
                            <!-- Job Header -->
                            <div class="flex items-start justify-between mb-4">
                                <div class="flex-1">
                                    <h3 class="text-lg font-semibold text-gray-900 mb-2">
                                        <a href="{{ route('jobs.show', $job->id) }}" class="hover:text-[#1e3a8a] transition-colors">
                                            {{ $job->title }}
                                        </a>
                                    </h3>
                                    <div class="flex items-center gap-4 text-sm text-gray-600">
                                        <span class="flex items-center gap-1">
                                            <i class="fa-solid fa-tag text-gray-400"></i>
                                            {{ $job->category }}
                                        </span>
                                        <span class="flex items-center gap-1">
                                            <i class="fa-solid fa-money-bill text-gray-400"></i>
                                            ₦{{ number_format($job->salary, 2) }}
                                        </span>
                                        <span class="flex items-center gap-1">
                                            <i class="fa-solid fa-calendar text-gray-400"></i>
                                            Posted {{ $job->created_at->diffForHumans() }}
                                        </span>
                                    </div>
                                </div>
                                
                                <!-- Status Badge -->
                                <div class="flex flex-col items-end gap-2">
                                    @switch($job->status)
                                        @case('active')
                                            <span class="px-3 py-1 bg-green-100 text-green-800 text-sm font-medium rounded-full">
                                                <i class="fa-solid fa-check mr-1"></i>Active
                                            </span>
                                            @break
                                        @case('closed')
                                            <span class="px-3 py-1 bg-gray-100 text-gray-800 text-sm font-medium rounded-full">
                                                <i class="fa-solid fa-lock mr-1"></i>Closed
                                            </span>
                                            @break
                                        @case('cancelled')
                                            <span class="px-3 py-1 bg-red-100 text-red-800 text-sm font-medium rounded-full">
                                                <i class="fa-solid fa-times mr-1"></i>Cancelled
                                            </span>
                                            @break
                                    @endswitch
                                    
                                    <!-- Stats -->
                                    <div class="text-xs text-gray-500 text-center">
                                        <div>{{ $job->applications_count ?? 0 }} applications</div>
                                        <div>{{ $job->views_count ?? 0 }} views</div>
                                    </div>
                                </div>
                            </div>

                            <!-- Description -->
                            <div class="mb-4">
                                <p class="text-sm text-gray-600 line-clamp-2">{{ Str::limit($job->description, 150) }}</p>
                            </div>

                            <!-- Recent Applications -->
                            @if($job->applications && $job->applications->count() > 0)
                                <div class="mb-4">
                                    <h4 class="text-sm font-medium text-gray-700 mb-2">Recent Applications:</h4>
                                    <div class="space-y-2">
                                        @foreach($job->applications->take(3) as $application)
                                            <div class="flex items-center justify-between text-sm bg-gray-50 p-2 rounded">
                                                <span class="text-gray-600">
                                                    {{ $application->applicant->first_name }} {{ $application->applicant->last_name }}
                                                </span>
                                                <span class="px-2 py-1 text-xs rounded-full
                                                    @switch($application->status)
                                                        @case('pending')
                                                            bg-yellow-100 text-yellow-800
                                                            @break
                                                        @case('accepted')
                                                            bg-green-100 text-green-800
                                                            @break
                                                        @case('rejected')
                                                            bg-red-100 text-red-800
                                                            @break
                                                    @endswitch">
                                                    {{ $application->status }}
                                                </span>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            <!-- Action Buttons -->
                            <div class="flex items-center justify-between pt-4 border-t">
                                <div class="flex gap-2">
                                    <a href="{{ route('jobs.edit', $job->id) }}" class="text-sm text-blue-600 hover:text-blue-800">
                                        <i class="fa-solid fa-edit mr-1"></i>Edit
                                    </a>
                                    @if($job->status === 'active')
                                        <form action="{{ route('jobs.close', $job->id) }}" method="POST" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="text-sm text-gray-600 hover:text-gray-800">
                                                <i class="fa-solid fa-lock mr-1"></i>Close
                                            </button>
                                        </form>
                                    @else
                                        <form action="{{ route('jobs.reopen', $job->id) }}" method="POST" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="text-sm text-green-600 hover:text-green-800">
                                                <i class="fa-solid fa-unlock mr-1"></i>Reopen
                                            </button>
                                        </form>
                                    @endif
                                    <form action="{{ route('jobs.destroy', $job->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-sm text-red-600 hover:text-red-800" onclick="return confirm('Are you sure you want to delete this job?')">
                                            <i class="fa-solid fa-trash mr-1"></i>Delete
                                        </button>
                                    </form>
                                </div>
                                
                                <div class="flex gap-3">
                                    <a href="{{ route('applications.received') }}" class="text-sm text-[#1e3a8a] hover:text-[#0f2b5e] font-medium">
                                        View Applications <i class="fa-solid fa-arrow-right ml-1"></i>
                                    </a>
                                    <a href="{{ route('jobs.show', $job->id) }}" class="text-sm text-[#1e3a8a] hover:text-[#0f2b5e] font-medium">
                                        View Details <i class="fa-solid fa-arrow-right ml-1"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-8">
                {{ $jobs->links() }}
            </div>
        @else
            <div class="text-center py-12">
                <div class="text-6xl mb-4 text-gray-300">
                    <i class="fa-solid fa-briefcase"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">No Jobs Posted Yet</h3>
                <p class="text-gray-600 mb-6">Post your first job to start receiving applications from talented students.</p>
                <a href="{{ route('jobs.create') }}" class="px-6 py-3 bg-[#1e3a8a] text-white rounded-lg hover:bg-[#0f2b5e] transition-colors">
                    <i class="fa-solid fa-plus mr-2"></i>Post Your First Job
                </a>
            </div>
        @endif
    </div>
</div>
@endsection

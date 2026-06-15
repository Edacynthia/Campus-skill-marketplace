{{-- Improved Applications Page with Better Visual Hierarchy and Reduced Clutter --}}
@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-gradient-to-b from-gray-50 to-white">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-6 md:py-10">
            <!-- Simplified Header with better spacing -->
            <div class="mb-8 md:mb-10">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <h1 class="text-2xl md:text-3xl font-bold tracking-tight text-gray-900">My Applications</h1>
                        <p class="text-gray-500 text-sm mt-1">Track and manage your job applications</p>
                    </div>
                    <a href="{{ route('jobs.index') }}"
                        class="inline-flex items-center justify-center px-4 py-2 bg-[#1e3a8a] text-white text-sm font-medium rounded-xl hover:bg-[#152e6b] transition-all duration-200 shadow-sm hover:shadow-md">
                        <i class="fa-solid fa-plus mr-2 text-xs"></i>Browse Jobs
                    </a>
                </div>
            </div>

            <!-- Applications Grid / List with cleaner cards -->
            @if ($applications->count() > 0)
                <div class="space-y-4">
                    @foreach ($applications as $application)
                        <div
                            class="group bg-white rounded-2xl border border-gray-100 shadow-sm hover:shadow-md transition-all duration-200 overflow-hidden">
                            <div class="p-5 md:p-6">
                                <!-- Top Row: Title, Employer, Status -->
                                <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-3 mb-3">
                                    <div class="flex-1 min-w-0">
                                        <h3 class="text-lg font-semibold text-gray-900 truncate">
                                            <a href="{{ route('jobs.show', $application->job_id) }}"
                                                class="hover:text-[#1e3a8a] transition-colors">
                                                {{ $application->job->title }}
                                            </a>
                                        </h3>
                                        <div class="flex flex-wrap items-center gap-x-4 gap-y-1 mt-1.5 text-sm text-gray-500">
                                            <span class="inline-flex items-center gap-1.5">
                                                <i class="fa-solid fa-building text-gray-400 text-xs"></i>
                                                {{ $application->job->employer->first_name }} {{ $application->job->employer->last_name }}
                                            </span>
                                            <span class="inline-flex items-center gap-1.5">
                                                <i class="fa-regular fa-calendar text-gray-400 text-xs"></i>
                                                Applied {{ $application->created_at->diffForHumans() }}
                                            </span>
                                        </div>
                                    </div>

                                    <!-- Status Badge - Simplified -->
                                    <div class="flex items-center gap-2">
                                        @switch($application->status)
                                            @case('pending')
                                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-amber-50 text-amber-700 border border-amber-200">
                                                    <i class="fa-solid fa-clock mr-1.5 text-xs"></i>Pending
                                                </span>
                                                @break
                                            @case('accepted')
                                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-emerald-50 text-emerald-700 border border-emerald-200">
                                                    <i class="fa-solid fa-check mr-1.5 text-xs"></i>Accepted
                                                </span>
                                                @break
                                            @case('rejected')
                                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-red-50 text-red-700 border border-red-200">
                                                    <i class="fa-solid fa-times mr-1.5 text-xs"></i>Rejected
                                                </span>
                                                @break
                                        @endswitch

                                        @if ($application->progress && $application->status === 'accepted')
                                            <span class="text-xs text-gray-400 bg-gray-50 px-2 py-1 rounded-full">
                                                {{ ucfirst(str_replace('_', ' ', $application->progress)) }}
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <!-- Cover Letter Preview - More compact -->
                                @if ($application->cover_letter)
                                    <div class="mt-2 mb-3">
                                        <p class="text-sm text-gray-500 line-clamp-1">
                                            <i class="fa-regular fa-file-lines mr-1.5 text-gray-400"></i>
                                            {{ Str::limit($application->cover_letter, 100) }}
                                        </p>
                                    </div>
                                @endif

                                <!-- Progress / Escrow Status Section - Cleaner cards -->
                                @if ($application->status === 'accepted')
                                    <div class="mt-4 mb-4">
                                        @if ($application->escrow_status === 'not_funded')
                                            <div class="bg-amber-50/60 border border-amber-200 rounded-xl p-3.5">
                                                <div class="flex flex-col sm:flex-row sm:items-center gap-2">
                                                    <div class="flex items-center gap-2">
                                                        <i class="fa-solid fa-hourglass-half text-amber-600 text-sm"></i>
                                                        <span class="font-medium text-amber-800 text-sm">Waiting for Escrow</span>
                                                    </div>
                                                    <p class="text-amber-700 text-xs sm:ml-auto">Funds not yet secured — don't start work</p>
                                                </div>
                                            </div>
                                        @elseif($application->escrow_status === 'funded')
                                            <div class="bg-emerald-50/60 border border-emerald-200 rounded-xl p-3.5">
                                                <div class="flex flex-col sm:flex-row sm:items-center gap-2">
                                                    <div class="flex items-center gap-2">
                                                        <i class="fa-solid fa-shield-halved text-emerald-600 text-sm"></i>
                                                        <span class="font-medium text-emerald-800 text-sm">Escrow Secured</span>
                                                    </div>
                                                    <p class="text-emerald-700 text-xs sm:ml-auto">Payment secured — ready to work</p>
                                                </div>
                                            </div>
                                        @elseif($application->escrow_status === 'released')
                                            <div class="bg-emerald-50/80 border border-emerald-200 rounded-xl p-3.5">
                                                <div class="flex flex-col gap-2">
                                                    <div class="flex items-center gap-2">
                                                        <i class="fa-solid fa-money-bill-wave text-emerald-600 text-sm"></i>
                                                        <span class="font-medium text-emerald-800 text-sm">Payment Released</span>
                                                    </div>
                                                    <div class="grid grid-cols-2 gap-2 text-xs bg-white/60 rounded-lg p-2.5 mt-1">
                                                        <div>Job Amount: <strong>₦{{ number_format($application->escrow_amount ?? $application->job->salary, 2) }}</strong></div>
                                                        <div>Platform Fee: <strong>₦{{ number_format($application->platform_fee ?? 0, 2) }}</strong></div>
                                                        <div class="col-span-2 text-emerald-700 font-medium">Released to you: ₦{{ number_format($application->worker_payout ?? $application->job->salary, 2) }}</div>
                                                    </div>
                                                    <p class="text-xs text-emerald-600 mt-1">Job complete — ratings now open</p>
                                                </div>
                                            </div>
                                        @endif

                                        <!-- Worker Progress Tracker -->
                                        @include('applications.partials.worker-progress-tracker', [
                                            'application' => $application,
                                        ])
                                    </div>
                                @endif

                                <!-- Action Buttons - Clean & minimal -->
                                <div class="flex items-center justify-between pt-3 mt-1 border-t border-gray-100">
                                    <div class="flex gap-3">
                                        @if ($application->status === 'pending')
                                            <a href="{{ route('applications.edit', $application->id) }}"
                                                class="inline-flex items-center text-sm text-gray-600 hover:text-[#1e3a8a] transition-colors">
                                                <i class="fa-regular fa-pen-to-square mr-1.5 text-xs"></i>Edit
                                            </a>
                                            <form action="{{ route('applications.withdraw', $application->id) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                    class="inline-flex items-center text-sm text-gray-500 hover:text-red-600 transition-colors"
                                                    onclick="return confirm('Withdraw this application?')">
                                                    <i class="fa-regular fa-trash-alt mr-1.5 text-xs"></i>Withdraw
                                                </button>
                                            </form>
                                        @endif
                                    </div>

                                    @if ($application->status === 'pending')
                                        <a href="{{ route('applications.show', $application->id) }}"
                                            class="inline-flex items-center text-sm font-medium text-[#1e3a8a] hover:text-[#152e6b] transition-colors">
                                            Details <i class="fa-solid fa-arrow-right ml-1.5 text-xs"></i>
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-8 flex justify-center">
                    {{ $applications->links() }}
                </div>
            @else
                <!-- Empty State - More inviting -->
                <div class="text-center py-12 md:py-16 bg-white rounded-2xl border border-gray-100 shadow-sm">
                    <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fa-regular fa-file-alt text-gray-400 text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-1">No applications yet</h3>
                    <p class="text-gray-500 text-sm mb-6">Ready to find your next opportunity?</p>
                    <a href="{{ route('jobs.index') }}"
                        class="inline-flex items-center px-5 py-2.5 bg-[#1e3a8a] text-white text-sm font-medium rounded-xl hover:bg-[#152e6b] transition-all duration-200 shadow-sm">
                        <i class="fa-solid fa-search mr-2 text-xs"></i>Browse available jobs
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
    </script>
@endsection
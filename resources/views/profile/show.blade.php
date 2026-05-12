@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-gray-50 py-12">
        <div class="max-w-6xl mx-auto px-6">
            <!-- Profile Header -->
            <div class="bg-white rounded-3xl shadow-sm p-8 mb-8">
                <div class="flex flex-col md:flex-row gap-6 items-start">
                    <!-- Profile Image -->
                    <div class="flex-shrink-0">
                        @if($user->passport_photo)
                            <img src="{{ asset('storage/' . $user->passport_photo) }}" 
                                 alt="{{ $user->fullName() }}" 
                                 class="w-24 h-24 rounded-full object-cover border-4 border-white shadow-lg">
                        @else
                            <div class="w-24 h-24 bg-gradient-to-br from-[#1e3a8a] to-emerald-500 rounded-full flex items-center justify-center text-white text-3xl font-bold shadow-lg">
                                {{ strtoupper(substr($user->first_name, 0, 1)) }}{{ strtoupper(substr($user->last_name, 0, 1)) }}
                            </div>
                        @endif
                    </div>
                    
                    <!-- Profile Info -->
                    <div class="flex-1">
                        <div class="flex justify-between items-start mb-2">
                            <h1 class="text-3xl font-bold text-gray-800">{{ $user->fullName() }}</h1>
                            @if(auth()->check() && auth()->id() === $user->id)
                                <a href="{{ route('profile.edit') }}" 
                                   class="px-4 py-2 bg-[#1e3a8a] text-white text-sm font-medium rounded-lg hover:bg-[#0f2b5e] transition-colors">
                                    <i class="fa-solid fa-edit mr-2"></i>Edit Profile
                                </a>
                            @endif
                        </div>
                        <p class="text-gray-600 mb-4">{{ $user->email }}</p>
                        
                        @if($user->bio)
                            <p class="text-gray-700 mb-4 leading-relaxed">{{ $user->bio }}</p>
                        @endif
                        
                        @if($user->department)
                            <div class="flex flex-wrap gap-2 mb-4">
                                <span class="bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-sm">
                                    {{ $user->department }}
                                </span>
                                @if($user->hasUniversityEmail())
                                    <span class="bg-emerald-100 text-emerald-700 px-3 py-1 rounded-full text-sm">
                                        <i class="fa-solid fa-check-circle"></i> Verified Student
                                    </span>
                                @endif
                            </div>
                        @endif
                        
                        <!-- Rating Section -->
                        <div class="flex items-center gap-6 mb-6">
                            <div class="flex items-center gap-2">
                                <div class="flex">
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= round($avgRating))
                                            <i class="fa-solid fa-star text-yellow-400"></i>
                                        @else
                                            <i class="fa-regular fa-star text-gray-300"></i>
                                        @endif
                                    @endfor
                                </div>
                                <span class="text-2xl font-bold text-gray-800">{{ number_format($avgRating, 1) }}</span>
                            </div>
                            <div class="text-gray-600">
                                <span class="font-medium">{{ $totalReviews }}</span> reviews
                            </div>
                        </div>

                        <!-- Stats -->
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                            <div class="text-center">
                                <p class="text-2xl font-bold text-[#1e3a8a]">{{ $skills->count() }}</p>
                                <p class="text-sm text-gray-500">Skills</p>
                            </div>
                            <div class="text-center">
                                <p class="text-2xl font-bold text-[#1e3a8a]">{{ $jobs->count() }}</p>
                                <p class="text-sm text-gray-500">Jobs Posted</p>
                            </div>
                            <div class="text-center">
                                <p class="text-2xl font-bold text-[#1e3a8a]">{{ $user->jobApplications()->count() }}</p>
                                <p class="text-sm text-gray-500">Applications</p>
                            </div>
                            <div class="text-center">
                                <p class="text-2xl font-bold text-[#1e3a8a]">{{ $user->myServiceBookings()->where('status', 'done')->count() }}</p>
                                <p class="text-sm text-gray-500">Services Completed</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Skills Section -->
                <div>
                    <h2 class="text-xl font-semibold text-gray-800 mb-4 flex items-center gap-2">
                        <i class="fa-solid fa-star text-[#1e3a8a]"></i>
                        Skills & Services
                    </h2>
                    
                    @if($skills->count() > 0)
                        <div class="space-y-4">
                            @foreach($skills as $skill)
                                <div class="bg-white p-5 rounded-2xl border border-gray-200 hover:shadow-md transition-all">
                                    <div class="flex justify-between items-start mb-3">
                                        <h3 class="font-semibold text-gray-800">{{ $skill->title }}</h3>
                                        <span class="bg-emerald-100 text-emerald-700 text-xs px-2 py-1 rounded-full">{{ $skill->status }}</span>
                                    </div>
                                    <p class="text-sm text-gray-600 mb-3 line-clamp-2">{{ $skill->description }}</p>
                                    <div class="flex justify-between items-center text-sm">
                                        <span class="text-[#1e3a8a] font-medium">{{ $skill->formatted_price }}</span>
                                        <div class="flex gap-3 text-gray-500">
                                            <span>⭐ {{ $skill->reviews_count }}</span>
                                            <span>📦 {{ $skill->orders_count }}</span>
                                        </div>
                                    </div>
                                    <div class="mt-3 pt-3 border-t">
                                        <a href="{{ route('skills.show', $skill->id) }}" class="text-[#1e3a8a] text-sm font-medium hover:underline">View Details →</a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="bg-white p-8 rounded-2xl text-center border border-gray-200">
                            <div class="text-5xl mb-3">📚</div>
                            <h3 class="text-lg font-semibold text-gray-800 mb-2">No Skills Posted</h3>
                            <p class="text-gray-600">{{ $user->first_name }} hasn't posted any skills yet.</p>
                        </div>
                    @endif
                </div>

                <!-- Jobs Section -->
                <div>
                    <h2 class="text-xl font-semibold text-gray-800 mb-4 flex items-center gap-2">
                        <i class="fa-solid fa-briefcase text-[#1e3a8a]"></i>
                        Job Postings
                    </h2>
                    
                    @if($jobs->count() > 0)
                        <div class="space-y-4">
                            @foreach($jobs as $job)
                                <div class="bg-white p-5 rounded-2xl border border-gray-200 hover:shadow-md transition-all">
                                    <div class="flex justify-between items-start mb-3">
                                        <h3 class="font-semibold text-gray-800">{{ $job->title }}</h3>
                                        <span class="bg-blue-100 text-blue-700 text-xs px-2 py-1 rounded-full">{{ $job->status }}</span>
                                    </div>
                                    <p class="text-sm text-gray-600 mb-3 line-clamp-2">{{ $job->description }}</p>
                                    <div class="flex justify-between items-center text-sm">
                                        <span class="text-[#1e3a8a] font-medium">{{ $job->formatted_salary }}</span>
                                        <div class="flex gap-3 text-gray-500">
                                            <span>📥 {{ $job->applications_count }}</span>
                                            <span>👁 {{ $job->views_count }}</span>
                                        </div>
                                    </div>
                                    <div class="mt-3 pt-3 border-t">
                                        <a href="{{ route('jobs.show', $job->id) }}" class="text-[#1e3a8a] text-sm font-medium hover:underline">View Details →</a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="bg-white p-8 rounded-2xl text-center border border-gray-200">
                            <div class="text-5xl mb-3">💼</div>
                            <h3 class="text-lg font-semibold text-gray-800 mb-2">No Jobs Posted</h3>
                            <p class="text-gray-600">{{ $user->first_name }} hasn't posted any jobs yet.</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Reviews Section -->
            @if($reviews->count() > 0)
                <div class="mt-8">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4 flex items-center gap-2">
                        <i class="fa-solid fa-comments text-[#1e3a8a]"></i>
                        Recent Reviews
                    </h2>
                    <div class="space-y-4">
                        @foreach($reviews as $review)
                            <div class="bg-white p-5 rounded-2xl border border-gray-200">
                                <div class="flex justify-between items-start mb-3">
                                    <div class="flex items-center gap-3">
                                        @if($review->reviewer->passport_photo)
                                            <img src="{{ asset('storage/' . $review->reviewer->passport_photo) }}" 
                                                 alt="{{ $review->reviewer->fullName() }}" 
                                                 class="w-10 h-10 rounded-full object-cover border-2 border-gray-200">
                                        @else
                                            <div class="w-10 h-10 bg-gradient-to-br from-blue-100 to-blue-200 rounded-full flex items-center justify-center text-sm font-bold text-[#1e3a8a]">
                                                {{ strtoupper(substr($review->reviewer->first_name, 0, 1)) }}{{ strtoupper(substr($review->reviewer->last_name, 0, 1)) }}
                                            </div>
                                        @endif
                                        <div>
                                            <p class="font-semibold text-gray-800">{{ $review->reviewer->fullName() }}</p>
                                            <p class="text-xs text-gray-500">{{ $review->created_at->format('M j, Y') }}</p>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-1">
                                        @for($i = 1; $i <= 5; $i++)
                                            @if($i <= $review->rating)
                                                <i class="fa-solid fa-star text-yellow-400 text-sm"></i>
                                            @else
                                                <i class="fa-regular fa-star text-gray-300 text-sm"></i>
                                            @endif
                                        @endfor
                                    </div>
                                </div>
                                <p class="text-gray-700 mb-2">{{ $review->comment }}</p>
                                <p class="text-sm text-gray-500">
                                    <i class="fa-solid fa-briefcase text-xs"></i>
                                    <a href="{{ route('skills.show', $review->skill->id) }}" class="text-[#1e3a8a] hover:underline">
                                        {{ $review->skill->title }}
                                    </a>
                                </p>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Ratings Sections -->
            <div class="mt-8 space-y-8">
                <!-- Reviews as a Worker/Provider -->
                <div class="bg-white rounded-2xl shadow-sm p-6">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4 flex items-center gap-2">
                        <i class="fa-solid fa-star text-[#1e3a8a]"></i>
                        Reviews as a Worker/Provider
                    </h2>
                    @php
                        // Combine employer_to_worker ratings from jobs AND client_to_provider ratings from bookings
                        $workerRatings = $jobApplicationRatings->where('type', 'employer_to_worker')
                            ->merge($bookingRatings->where('type', 'client_to_provider'))
                            ->sortByDesc('created_at');
                        
                        $workerAvgRating = $workerRatings->count() > 0 
                            ? $workerRatings->sum('rating') / $workerRatings->count() 
                            : 0;
                        $workerTotalReviews = $workerRatings->count();
                    @endphp
                    @if($workerRatings->count() > 0)
                        <div class="mb-4 p-4 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl border border-blue-100">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <div class="flex gap-1">
                                        @for($i = 1; $i <= 5; $i++)
                                            @if($i <= floor($workerAvgRating))
                                                <i class="fa-solid fa-star text-yellow-400"></i>
                                            @else
                                                <i class="fa-regular fa-star text-gray-300"></i>
                                            @endif
                                        @endfor
                                    </div>
                                    <span class="text-lg font-semibold text-gray-800">{{ number_format($workerAvgRating, 1) }}</span>
                                </div>
                                <span class="text-sm text-gray-600">{{ $workerTotalReviews }} review{{ $workerTotalReviews > 1 ? 's' : '' }}</span>
                            </div>
                        </div>
                        
                        <div class="space-y-4">
                            @foreach($workerRatings as $rating)
                                <div class="border-l-4 border-blue-200 pl-4">
                                    <div class="flex justify-between items-start mb-2">
                                        <div class="flex items-center gap-3">
                                            @if($rating->reviewer->passport_photo)
                                                <img src="{{ asset('storage/' . $rating->reviewer->passport_photo) }}" 
                                                     alt="{{ $rating->reviewer->fullName() }}" 
                                                     class="w-10 h-10 rounded-full object-cover border-2 border-gray-200">
                                            @else
                                                <div class="w-10 h-10 bg-gradient-to-br from-blue-100 to-blue-200 rounded-full flex items-center justify-center text-sm font-bold text-[#1e3a8a]">
                                                    {{ strtoupper(substr($rating->reviewer->first_name, 0, 1)) }}{{ strtoupper(substr($rating->reviewer->last_name, 0, 1)) }}
                                                </div>
                                            @endif
                                            <div>
                                                <p class="font-semibold text-gray-800">{{ $rating->reviewer->fullName() }}</p>
                                                <p class="text-xs text-gray-500">{{ $rating->created_at->format('M j, Y') }}</p>
                                                <p class="text-xs text-gray-400">
                                                    @if($rating->type === 'employer_to_worker')
                                                        <i class="fa-solid fa-briefcase text-xs"></i> Job Work
                                                    @else
                                                        <i class="fa-solid fa-handshake text-xs"></i> Service Provided
                                                    @endif
                                                </p>
                                            </div>
                                        </div>
                                        <div class="flex gap-1">
                                            @for($i = 1; $i <= 5; $i++)
                                                @if($i <= $rating->rating)
                                                    <i class="fa-solid fa-star text-yellow-400 text-sm"></i>
                                                @else
                                                    <i class="fa-regular fa-star text-gray-300 text-sm"></i>
                                                @endif
                                            @endfor
                                        </div>
                                    </div>
                                    @if($rating->review)
                                        <p class="text-gray-700 mt-2 leading-relaxed">{{ $rating->review }}</p>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <div class="text-5xl mb-4 opacity-70">⭐</div>
                            <h3 class="text-lg font-semibold text-gray-800 mb-2">No Worker/Provider Reviews Yet</h3>
                            <p class="text-gray-600">This user hasn't received any reviews for their work or services yet.</p>
                        </div>
                    @endif
                </div>

                <!-- Reviews as a Client/Employer -->
                <div class="bg-white rounded-2xl shadow-sm p-6">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4 flex items-center gap-2">
                        <i class="fa-solid fa-briefcase text-[#1e3a8a]"></i>
                        Reviews as a Client/Employer
                    </h2>
                    @php
                        // Combine worker_to_employer ratings from jobs AND provider_to_client ratings from bookings
                        $employerRatings = $jobApplicationRatings->where('type', 'worker_to_employer')
                            ->merge($bookingRatings->where('type', 'provider_to_client'))
                            ->sortByDesc('created_at');
                        
                        $employerAvgRating = $employerRatings->count() > 0 
                            ? $employerRatings->sum('rating') / $employerRatings->count() 
                            : 0;
                        $employerTotalReviews = $employerRatings->count();
                    @endphp
                    @if($employerRatings->count() > 0)
                        <div class="mb-4 p-4 bg-gradient-to-r from-emerald-50 to-green-50 rounded-xl border border-emerald-100">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <div class="flex gap-1">
                                        @for($i = 1; $i <= 5; $i++)
                                            @if($i <= floor($employerAvgRating))
                                                <i class="fa-solid fa-star text-yellow-400"></i>
                                            @else
                                                <i class="fa-regular fa-star text-gray-300"></i>
                                            @endif
                                        @endfor
                                    </div>
                                    <span class="text-lg font-semibold text-gray-800">{{ number_format($employerAvgRating, 1) }}</span>
                                </div>
                                <span class="text-sm text-gray-600">{{ $employerTotalReviews }} review{{ $employerTotalReviews > 1 ? 's' : '' }}</span>
                            </div>
                        </div>
                        
                        <div class="space-y-4">
                            @foreach($employerRatings as $rating)
                                <div class="border-l-4 border-emerald-200 pl-4">
                                    <div class="flex justify-between items-start mb-2">
                                        <div class="flex items-center gap-3">
                                            @if($rating->reviewer->passport_photo)
                                                <img src="{{ asset('storage/' . $rating->reviewer->passport_photo) }}" 
                                                     alt="{{ $rating->reviewer->fullName() }}" 
                                                     class="w-10 h-10 rounded-full object-cover border-2 border-gray-200">
                                            @else
                                                <div class="w-10 h-10 bg-gradient-to-br from-emerald-100 to-emerald-200 rounded-full flex items-center justify-center text-sm font-bold text-emerald-700">
                                                    {{ strtoupper(substr($rating->reviewer->first_name, 0, 1)) }}{{ strtoupper(substr($rating->reviewer->last_name, 0, 1)) }}
                                                </div>
                                            @endif
                                            <div>
                                                <p class="font-semibold text-gray-800">{{ $rating->reviewer->fullName() }}</p>
                                                <p class="text-xs text-gray-500">{{ $rating->created_at->format('M j, Y') }}</p>
                                                <p class="text-xs text-gray-400">
                                                    @if($rating->type === 'worker_to_employer')
                                                        <i class="fa-solid fa-briefcase text-xs"></i> Job Posted
                                                    @else
                                                        <i class="fa-solid fa-handshake text-xs"></i> Service Received
                                                    @endif
                                                </p>
                                            </div>
                                        </div>
                                        <div class="flex gap-1">
                                            @for($i = 1; $i <= 5; $i++)
                                                @if($i <= $rating->rating)
                                                    <i class="fa-solid fa-star text-yellow-400 text-sm"></i>
                                                @else
                                                    <i class="fa-regular fa-star text-gray-300 text-sm"></i>
                                                @endif
                                            @endfor
                                        </div>
                                    </div>
                                    @if($rating->review)
                                        <p class="text-gray-700 mt-2 leading-relaxed">{{ $rating->review }}</p>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <div class="text-5xl mb-4 opacity-70">💼</div>
                            <h3 class="text-lg font-semibold text-gray-800 mb-2">No Client/Employer Reviews Yet</h3>
                            <p class="text-gray-600">This user hasn't received any reviews as a client or employer yet.</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Back to Dashboard -->
            <div class="mt-8 text-center">
                <a href="{{ route('dashboard') }}" 
                   class="inline-flex items-center gap-2 px-6 py-3 border border-gray-300 text-gray-700 rounded-xl font-medium hover:bg-gray-50 transition-all">
                    <i class="fa-solid fa-arrow-left"></i>
                    Back to Dashboard
                </a>
            </div>
        </div>
    </div>

    <style>
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    </style>
@endsection

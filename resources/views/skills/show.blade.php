@extends('layouts.guest')

@section('content')
    <x-navbar />

    <!-- Breadcrumb -->
    <div class="bg-gray-50 border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-6 py-4">
            <nav class="flex items-center space-x-2 text-sm">
                <a href="{{ route('home') }}" class="text-gray-500 hover:text-gray-700">Home</a>
                <span class="text-gray-400">/</span>
                <a href="{{ route('skills.index') }}" class="text-gray-500 hover:text-gray-700">Browse Skills</a>
                <span class="text-gray-400">/</span>
                <span class="text-gray-900 font-medium">{{ $skill->title }}</span>
            </nav>
        </div>
    </div>


    
    <!-- Success Messages -->
    @if(session('success'))
        <div class="max-w-7xl mx-auto px-6 pt-6">
            <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg flex items-center gap-3">
                <i class="fa-solid fa-check-circle"></i>
                <span>{{ session('success') }}</span>
            </div>
        </div>
    @endif

    <!-- Skill Details -->
    <div class="max-w-7xl mx-auto px-6 py-12">
        <div class="grid lg:grid-cols-3 gap-12">
            
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-8">
                
                <!-- Skill Header -->
                <div>
                    <div class="flex items-center gap-3 mb-4">
                        <span class="px-3 py-1 bg-[#1e3a8a]/10 text-[#1e3a8a] text-sm font-semibold rounded-full">
                            {{ $skill->category }}
                        </span>
                        <div class="flex items-center gap-1 text-sm">
                            <i class="fa-solid fa-star text-yellow-400"></i>
                            <span class="font-semibold">{{ $skill->rating ?: '5.0' }}</span>
                            <span class="text-gray-400">({{ $skill->reviews_count ?? 0 }} reviews)</span>
                        </div>
                    </div>
                    
                    <h1 class="text-4xl font-bold text-gray-900 mb-4">{{ $skill->title }}</h1>
                    
                    <div class="flex items-center gap-6 text-gray-600">
                        <div class="flex items-center gap-2">
                            <i class="fa-solid fa-eye text-gray-400"></i>
                            <span>{{ $skill->views_count ?? 0 }} views</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <i class="fa-solid fa-shopping-bag text-gray-400"></i>
                            <span>{{ $skill->orders_count ?? 0 }} orders</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <i class="fa-solid fa-clock text-gray-400"></i>
                            <span>Posted {{ $skill->created_at->diffForHumans() }}</span>
                        </div>
                    </div>
                </div>

                <!-- Skill Image -->
                @if($skill->image)
                    <div class="rounded-2xl overflow-hidden shadow-lg">
                        <img src="{{ asset('storage/' . $skill->image) }}" alt="{{ $skill->title }}" 
                             class="w-full h-96 object-cover">
                    </div>
                @else
                    <div class="w-full h-96 bg-gradient-to-br from-[#1e3a8a] to-blue-700 rounded-2xl flex items-center justify-center">
                        <i class="fa-solid fa-briefcase text-white text-6xl opacity-50"></i>
                    </div>
                @endif

                <!-- Description -->
                <div>
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">About This Service</h2>
                    <div class="prose prose-lg max-w-none text-gray-600">
                        <p>{{ $skill->description }}</p>
                    </div>
                </div>

                <!-- Pricing -->
                <div class="bg-gray-50 rounded-2xl p-6">
                    <h3 class="text-xl font-bold text-gray-900 mb-4">Pricing</h3>
                    <div class="flex items-baseline gap-2">
                        <span class="text-3xl font-bold text-[#1e3a8a]">{{ $skill->formatted_price }}</span>
                        @if($skill->price_unit)
                            <span class="text-gray-600">per {{ $skill->price_unit }}</span>
                        @endif
                    </div>
                    <p class="text-sm text-gray-500 mt-2">{{ $skill->price_type ?? 'Fixed price' }}</p>
                </div>

               
                <!-- Reviews Section -->
@php
    $skillReviews = \App\Models\Rating::with('reviewer')
        ->whereHas('booking', function ($query) use ($skill) {
            $query->where('skill_id', $skill->id);
        })
        ->latest()
        ->take(3)
        ->get();

    $totalReviews = \App\Models\Rating::whereHas('booking', function ($query) use ($skill) {
            $query->where('skill_id', $skill->id);
        })
        ->count();

    $averageRating = \App\Models\Rating::whereHas('booking', function ($query) use ($skill) {
            $query->where('skill_id', $skill->id);
        })
        ->avg('rating');
@endphp

<div>
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Reviews</h2>
            <p class="text-sm text-gray-500 mt-1">
                {{ $totalReviews }} review{{ $totalReviews == 1 ? '' : 's' }}
                @if($averageRating)
                    • {{ number_format($averageRating, 1) }} average rating
                @endif
            </p>
        </div>
    </div>

    @if($skillReviews->count() > 0)
        <div class="space-y-4">
            @foreach($skillReviews as $review)
                <div class="bg-white border border-gray-200 rounded-xl p-6">
                    <div class="flex items-center justify-between mb-3">
                        <div class="flex items-center gap-3">
                            @if($review->reviewer && $review->reviewer->passport_photo)
                                <img src="{{ asset('storage/' . $review->reviewer->passport_photo) }}"
                                     class="w-10 h-10 rounded-full object-cover">
                            @else
                                <div class="w-10 h-10 bg-gray-200 rounded-full flex items-center justify-center">
                                    <i class="fa-solid fa-user text-gray-500"></i>
                                </div>
                            @endif

                            <div>
                                <p class="font-semibold text-gray-900">
                                    {{ $review->reviewer->first_name ?? 'User' }}
                                    {{ isset($review->reviewer->last_name) ? substr($review->reviewer->last_name, 0, 1) . '.' : '' }}
                                </p>

                                <div class="flex items-center gap-1 text-sm">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="fa-solid fa-star {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300' }}"></i>
                                    @endfor
                                </div>
                            </div>
                        </div>

                        <span class="text-sm text-gray-500">
                            {{ $review->created_at->diffForHumans() }}
                        </span>
                    </div>

                    @if($review->review)
                        <p class="text-gray-600">{{ $review->review }}</p>
                    @endif
                </div>
            @endforeach
        </div>

        @if($totalReviews > 3)
            <div class="text-center mt-6">
                <a href="{{ route('bookings.index') }}"
                   class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-all">
                    View More Reviews
                </a>
            </div>
        @endif
    @else
        <div class="text-center py-12 bg-gray-50 rounded-xl">
            <i class="fa-solid fa-star text-gray-300 text-4xl mb-4"></i>
            <p class="text-gray-500">No reviews yet</p>
            <p class="text-sm text-gray-400 mt-2">Reviews will appear after completed bookings are rated.</p>
        </div>
    @endif
</div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                
                <!-- Provider Info -->
                <div class="bg-white border border-gray-200 rounded-2xl p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Service Provider</h3>
                    
                    @if($skill->user)
                        <div class="flex items-center gap-4 mb-4">
                            @if($skill->user->passport_photo)
                                <img src="{{ asset('storage/' . $skill->user->passport_photo) }}" alt="{{ $skill->user->first_name }}" 
                                     class="w-16 h-16 rounded-full object-cover border-2 border-gray-200">
                            @else
                                <div class="w-16 h-16 bg-gradient-to-br from-blue-100 to-blue-200 rounded-full flex items-center justify-center">
                                    <span class="text-lg font-bold text-[#1e3a8a]">{{ substr($skill->user->first_name, 0, 1) }}{{ substr($skill->user->last_name, 0, 1) }}</span>
                                </div>
                            @endif
                            <div>
                                <p class="font-semibold text-gray-900">{{ $skill->user->first_name }} {{ $skill->user->last_name }}</p>
                                <p class="text-sm text-gray-500">{{ $skill->user->department ?? 'University Staff' }}</p>
                                <p class="text-sm text-gray-500">{{ $skill->user->role ?? 'Member' }}</p>
                            </div>
                        </div>
                    @endif
                    
                    <div class="space-y-3">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Response Time</span>
                            <span class="font-medium text-gray-900">Within 1 hour</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Languages</span>
                            <span class="font-medium text-gray-900">English</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Member Since</span>
                            <span class="font-medium text-gray-900">{{ $skill->user->created_at->format('M Y') }}</span>
                        </div>
                    </div>
                </div>

                <!-- Booking Actions -->
                @if(auth()->check())
                    @php
                        $activeBooking = auth()->check() && auth()->id() != $skill->user_id 
                            ? \App\Models\Booking::where('skill_id', $skill->id)
                                ->where('client_id', auth()->id())
                                ->where('status', '!=', 'done')
                                ->first()
                            : null;
                        $mySkillBookings = auth()->id() == $skill->user_id 
                            ? \App\Models\Booking::where('skill_id', $skill->id)
                                ->with('client')
                                ->latest()
                                ->take(1)
                                ->get()
                            : collect();
                    @endphp
                    
                    <!-- If viewer is NOT the skill owner -->
                    @if(auth()->id() != $skill->user_id)
                        @if(!$activeBooking)
                            <button onclick="showBookingInterestModal()" class="w-full px-6 py-3 bg-[#1e3a8a] text-white font-semibold rounded-xl hover:bg-[#0f2b5e] transition-all shadow-sm hover:shadow-md">
                                <i class="fa-solid fa-hand-point-up mr-2"></i>
                                I'm Interested
                            </button>
                        @elseif($activeBooking->status === 'interested')
                            <div class="w-full px-6 py-3 bg-gray-100 text-gray-500 font-semibold rounded-xl border border-gray-200 text-center">
                                <i class="fa-solid fa-clock mr-2"></i>
                                Waiting for provider confirmation
                            </div>
                       @elseif($activeBooking->status === 'confirmed')
    <div class="space-y-3">
        <div class="w-full px-6 py-3 bg-emerald-100 text-emerald-700 font-semibold rounded-xl border border-emerald-200 text-center">
            <i class="fa-solid fa-check-circle mr-2"></i>
            Booking Confirmed
        </div>
        @if($activeBooking->client_confirmed_at)
            {{-- Client already confirmed, waiting for provider --}}
            <div class="w-full px-6 py-3 bg-amber-50 text-amber-700 font-semibold rounded-xl border border-amber-200 text-center">
                <i class="fa-solid fa-clock mr-2"></i>
                You confirmed — waiting for provider to confirm
            </div>
        @else
            <button onclick="clientConfirmBooking({{ $activeBooking->id }})" class="w-full px-6 py-3 bg-blue-500 text-white font-semibold rounded-xl hover:bg-blue-600 transition-all">
                <i class="fa-solid fa-check mr-2"></i>
                Mark as Done — Service Received
            </button>
        @endif
    </div>
                      @elseif($activeBooking->status === 'done')
    <div class="w-full px-6 py-3 bg-green-100 text-green-700 font-semibold rounded-xl border border-green-200 text-center mb-3">
        <i class="fa-solid fa-check-double mr-2"></i>
        Service Completed ✓
    </div>
    @php
        $hasRated = \App\Models\Rating::where('booking_id', $activeBooking->id)
            ->where('reviewer_id', auth()->id())
            ->exists();
    @endphp
    @if(!$hasRated)
        <div class="p-4 bg-white border border-gray-200 rounded-xl">
            <h6 class="font-semibold text-gray-800 mb-3">Rate Your Experience</h6>
            <div class="flex gap-1 mb-3" id="booking-rating-stars-{{ $activeBooking->id }}">
                @for($i = 1; $i <= 5; $i++)
                    <button type="button"
                            onclick="setBookingInlineRating({{ $activeBooking->id }}, {{ $i }})"
                            class="text-3xl text-gray-300 hover:text-yellow-400 transition-colors">★</button>
                @endfor
            </div>
            <textarea id="booking-review-{{ $activeBooking->id }}"
                      placeholder="Share your experience (optional)"
                      class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:border-[#1e3a8a] focus:ring-2 focus:ring-[#1e3a8a]/20 mb-3"
                      rows="3" maxlength="500"></textarea>
            <button onclick="submitBookingInlineRating({{ $activeBooking->id }})"
                    class="w-full px-4 py-2 bg-[#1e3a8a] text-white font-semibold rounded-lg hover:bg-[#0f2b5e] transition-all text-sm">
                <i class="fa-solid fa-star mr-2"></i>Submit Rating
            </button>
        </div>
    @else
        <div class="p-3 bg-emerald-50 border border-emerald-200 rounded-xl">
            <p class="text-sm text-emerald-800 text-center">
                <i class="fa-solid fa-check-circle mr-2"></i>You've already rated this service
            </p>
        </div>
    @endif
                        @endif
                    @else
                        <!-- If viewer IS the skill owner -->
                        <div class="w-full px-6 py-3 bg-gray-100 text-gray-500 font-semibold rounded-xl border border-gray-200 text-center">
                            <i class="fa-solid fa-crown mr-2"></i>
                            This is your skill
                        </div>
                        
                        @if($mySkillBookings->count() > 0)
                            <div class="mt-4 space-y-3">
                                <h4 class="font-semibold text-gray-800">Recent Bookings</h4>
                                @foreach($mySkillBookings as $booking)
                                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-3">
                                        <div class="flex justify-between items-start mb-2">
                                            <div>
                                                <p class="font-medium text-gray-800">{{ $booking->client->fullName() }}</p>
                                                @if($booking->message)
                                                    <p class="text-xs text-gray-600 italic">"{{ $booking->message }}"</p>
                                                @endif
                                            </div>
                                            <span class="text-xs px-2 py-1 rounded-full capitalize
                                                @if($booking->status === 'interested') bg-amber-50 text-amber-700
                                                @elseif($booking->status === 'confirmed') bg-emerald-50 text-emerald-700
                                                @else bg-blue-50 text-blue-700 @endif">
                                                {{ $booking->statusLabel() }}
                                            </span>
                                        </div>
                                        <div class="flex gap-2">
                                            @if($booking->status === 'interested')
                                                <button onclick="confirmBooking({{ $booking->id }})" class="flex-1 px-3 py-1 bg-emerald-500 text-white text-xs rounded-lg hover:bg-emerald-600 transition-colors">
                                                    Confirm
                                                </button>
                                                <button onclick="declineBooking({{ $booking->id }})" class="flex-1 px-3 py-1 bg-red-500 text-white text-xs rounded-lg hover:bg-red-600 transition-colors">
                                                    Decline
                                                </button>
                                           @elseif($booking->status === 'confirmed')
    @if($booking->provider_confirmed_at)
        <div class="flex-1 px-3 py-1 bg-amber-50 text-amber-700 text-xs rounded-lg border border-amber-200 text-center">
            <i class="fa-solid fa-clock mr-1"></i>
            You confirmed — waiting for client
        </div>
    @else
        <button onclick="providerConfirmBooking({{ $booking->id }})" class="flex-1 px-3 py-1 bg-blue-500 text-white text-xs rounded-lg hover:bg-blue-600 transition-colors">
            <i class="fa-solid fa-check mr-1"></i>
            Mark as Done — Service Delivered
        </button>
    @endif
             @elseif($booking->status === 'done')
    @php
        $hasRated = \App\Models\Rating::where('booking_id', $booking->id)
            ->where('reviewer_id', auth()->id())
            ->exists();
    @endphp
    @if(!$hasRated)
        <div class="w-full mt-2 p-3 bg-white border border-gray-200 rounded-lg">
            <p class="text-xs font-semibold text-gray-700 mb-2">Rate {{ $booking->client->first_name }}</p>
            <div class="flex gap-1 mb-2" id="booking-rating-stars-{{ $booking->id }}">
                @for($i = 1; $i <= 5; $i++)
                    <button type="button"
                            onclick="setBookingInlineRating({{ $booking->id }}, {{ $i }})"
                            class="text-2xl text-gray-300 hover:text-yellow-400 transition-colors">★</button>
                @endfor
            </div>
            <textarea id="booking-review-{{ $booking->id }}"
                      placeholder="Share your experience (optional)"
                      class="w-full px-2 py-1 border border-gray-300 rounded text-xs focus:outline-none focus:border-[#1e3a8a] mb-2"
                      rows="2" maxlength="500"></textarea>
            <button onclick="submitBookingInlineRating({{ $booking->id }})"
                    class="w-full px-3 py-1 bg-[#1e3a8a] text-white text-xs rounded-lg hover:bg-[#0f2b5e] transition-colors">
                <i class="fa-solid fa-star mr-1"></i>Submit Rating
            </button>
        </div>
    @else
        <div class="w-full mt-2 p-2 bg-emerald-50 border border-emerald-200 rounded-lg">
            <p class="text-xs text-emerald-800 text-center">
                <i class="fa-solid fa-check-circle mr-1"></i>Already rated
            </p>
        </div>
    @endif
                                            @endif
                                        </div>
                                    </div>
                                @endforeach

                                <div class="pt-2">
                                    <a href="{{ route('bookings.skills') }}"
                                       class="block w-full text-center px-4 py-2 bg-[#1e3a8a] text-white text-sm rounded-lg hover:bg-[#0f2b5e] transition-all">
                                        View All Bookings
                                    </a>
                                </div>
                            </div>
                        @endif
                    @endif
                @else
                    <div class="bg-blue-50 border border-blue-200 rounded-xl p-4">
                        <div class="flex items-center gap-3 mb-3">
                            <i class="fa-solid fa-info-circle text-blue-600"></i>
                            <span class="font-medium text-blue-900">Sign in required</span>
                        </div>
                        <p class="text-sm text-blue-800 mb-4">
                            You need to be logged in to book services and contact providers.
                        </p>
                        <a href="{{ route('login') }}" class="w-full px-4 py-2 bg-[#1e3a8a] text-white font-semibold rounded-lg hover:bg-[#0f2b5e] transition-all text-center block">
                            Sign In to Continue
                        </a>
                    </div>
                @endif

                <!-- Related Skills -->
                @if($relatedSkills && $relatedSkills->count() > 0)
                    <div class="bg-white border border-gray-200 rounded-2xl p-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-4">Similar Services</h3>
                        <div class="space-y-3">
                            @foreach($relatedSkills as $relatedSkill)
                                <a href="{{ route('skills.show', $relatedSkill->id) }}" class="block p-3 border border-gray-200 rounded-lg hover:bg-gray-50 transition-all">
                                    <div class="flex items-center gap-3">
                                        @if($relatedSkill->image)
                                            <img src="{{ asset('storage/' . $relatedSkill->image) }}" alt="{{ $relatedSkill->title }}" 
                                                 class="w-12 h-12 rounded-lg object-cover">
                                        @else
                                            <div class="w-12 h-12 bg-gray-200 rounded-lg flex items-center justify-center">
                                                <i class="fa-solid fa-briefcase text-gray-500"></i>
                                            </div>
                                        @endif
                                        <div class="flex-1">
                                            <p class="font-medium text-gray-900 text-sm">{{ Str::limit($relatedSkill->title, 30) }}</p>
                                            <p class="text-sm text-[#1e3a8a] font-semibold">{{ $relatedSkill->formatted_price }}</p>
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

<!-- Contact Modal -->
<div id="contactModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-2xl max-w-md w-full max-h-[90vh] overflow-y-auto">
            <div class="sticky top-0 bg-white border-b border-gray-200 p-6 rounded-t-2xl">
                <div class="flex items-center justify-between">
                    <h3 class="text-xl font-bold text-gray-900">Contact {{ $skill->user->first_name }}</h3>
                    <button onclick="closeContactModal()" class="text-gray-400 hover:text-gray-600">
                        <i class="fa-solid fa-times text-xl"></i>
                    </button>
                </div>
            </div>
            <form action="{{ route('messages.store') }}" method="POST" class="p-6">
                @csrf
                <input type="hidden" name="receiver_id" value="{{ $skill->user->id }}">
                <input type="hidden" name="skill_id" value="{{ $skill->id }}">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Your Message</label>
                    <textarea name="message" rows="4" required
                              class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:border-[#1e3a8a] resize-none"
                              placeholder="Hi {{ $skill->user->first_name }}, I'm interested in your {{ $skill->title }} service..."></textarea>
                </div>
                <div class="flex gap-3">
                    <button type="submit" class="flex-1 px-4 py-3 bg-[#1e3a8a] text-white font-semibold rounded-lg hover:bg-[#0f2b5e] transition-all">
                        <i class="fa-solid fa-paper-plane mr-2"></i>Send Message
                    </button>
                    <button type="button" onclick="closeContactModal()" class="px-4 py-3 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition-all">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Booking Interest Modal — ONLY modal kept, rating modal completely removed -->
<div id="bookingInterestModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-2xl max-w-md w-full max-h-[90vh] overflow-y-auto">
            <div class="sticky top-0 bg-white border-b border-gray-200 p-6 rounded-t-2xl">
                <div class="flex items-center justify-between">
                    <h3 class="text-xl font-bold text-gray-900">Express Interest</h3>
                    <button onclick="closeBookingInterestModal()" class="text-gray-400 hover:text-gray-600">
                        <i class="fa-solid fa-times text-xl"></i>
                    </button>
                </div>
            </div>
            <form id="bookingInterestForm" class="p-6">
                @csrf
                <input type="hidden" name="skill_id" value="{{ $skill->id }}">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Message to provider *
                        <span class="text-gray-400 font-normal">(min 10 characters)</span>
                    </label>
                    <textarea name="message" rows="4" maxlength="500" required minlength="10"
                              class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:border-[#1e3a8a] resize-none"
                              placeholder="Tell {{ $skill->user->first_name }} why you're interested..."></textarea>
                </div>
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-3 mb-4">
                    <p class="text-sm text-blue-800">
                        <i class="fa-solid fa-info-circle mr-2"></i>
                        This sends a booking request to {{ $skill->user->first_name }}. They can confirm or decline.
                    </p>
                </div>
                <div class="flex gap-3">
                    <button type="submit" class="flex-1 px-4 py-3 bg-[#1e3a8a] text-white font-semibold rounded-lg hover:bg-[#0f2b5e] transition-all">
                        <i class="fa-solid fa-paper-plane mr-2"></i>Send Request
                    </button>
                    <button type="button" onclick="closeBookingInterestModal()" class="px-4 py-3 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition-all">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// ====================== MODAL FUNCTIONS ======================
function openContactModal() {
    document.getElementById('contactModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}
function closeContactModal() {
    document.getElementById('contactModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
}
function showBookingInterestModal() {
    document.getElementById('bookingInterestModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}
function closeBookingInterestModal() {
    document.getElementById('bookingInterestModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
}

// Close modals when clicking outside
document.getElementById('contactModal').addEventListener('click', function(e) {
    if (e.target === this) closeContactModal();
});
document.getElementById('bookingInterestModal').addEventListener('click', function(e) {
    if (e.target === this) closeBookingInterestModal();
});

// ====================== BOOKING INTEREST FORM ======================
document.getElementById('bookingInterestForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    const skillId = formData.get('skill_id');

    fetch(`/skills/${skillId}/book`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json'
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            closeBookingInterestModal();
            
            // Show success message before reload
            const successDiv = document.createElement('div');
            successDiv.className = 'fixed top-4 right-4 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg flex items-center gap-3 z-50';
            successDiv.innerHTML = '<i class="fa-solid fa-check-circle"></i><span>Booking request sent successfully!</span>';
            document.body.appendChild(successDiv);
            
            // Reload after 2 seconds so user can see the message
            setTimeout(() => location.reload(), 2000);
        } else {
            alert(data.message || 'Error submitting booking request');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error submitting booking request');
    });
});

// ====================== BOOKING ACTION FUNCTIONS ======================
function confirmBooking(bookingId) {
    if (!confirm('Confirm this booking?')) return;
    fetch(`/bookings/${bookingId}/confirm`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json'
        }
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) location.reload();
        else alert(data.message || 'Error confirming booking');
    })
    .catch(() => alert('Error confirming booking'));
}

function declineBooking(bookingId) {
    if (!confirm('Decline this booking?')) return;
    fetch(`/bookings/${bookingId}/decline`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json'
        }
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) location.reload();
        else alert(data.message || 'Error declining booking');
    })
    .catch(() => alert('Error declining booking'));
}

function clientConfirmBooking(bookingId) {
    if (!confirm('Mark this service as received and done?')) return;

    const btn = document.querySelector(`[onclick="clientConfirmBooking(${bookingId})"]`);
    if (btn) {
        btn.disabled = true;
        btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin mr-2"></i>Processing...';
    }

    fetch(`/bookings/${bookingId}/client-confirm`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json'
        }
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            // Replace the button area with a clear message immediately
            const buttonContainer = btn ? btn.closest('.space-y-3') : null;
            if (buttonContainer) {
                if (data.bothConfirmed) {
                    // Both confirmed — service is fully done
                    buttonContainer.innerHTML = `
                        <div class="w-full px-6 py-3 bg-green-100 text-green-700 font-semibold rounded-xl border border-green-200 text-center">
                            <i class="fa-solid fa-check-double mr-2"></i>
                            Service Completed ✓
                        </div>
                        <div class="p-4 bg-white border border-gray-200 rounded-xl mt-3">
                            <p class="text-sm text-gray-600 text-center mb-2">Page is refreshing to load your rating form...</p>
                        </div>
                    `;
                } else {
                    // Client confirmed, waiting for provider
                    buttonContainer.innerHTML = `
                        <div class="w-full px-6 py-3 bg-emerald-100 text-emerald-700 font-semibold rounded-xl border border-emerald-200 text-center">
                            <i class="fa-solid fa-check-circle mr-2"></i>
                            Booking Confirmed
                        </div>
                        <div class="w-full px-6 py-3 bg-amber-50 text-amber-700 font-semibold rounded-xl border border-amber-200 text-center">
                            <i class="fa-solid fa-clock mr-2"></i>
                            You confirmed — waiting for provider to confirm
                        </div>
                    `;
                }
            }
            // Reload after 2.5 seconds so the message is visible
            setTimeout(() => location.reload(), 2500);
        } else {
            if (btn) {
                btn.disabled = false;
                btn.innerHTML = '<i class="fa-solid fa-check mr-2"></i>Mark as Done — Service Received';
            }
            alert(data.message || 'Error confirming');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        // The browser extension error fires here even on success
        // So reload anyway since we know 200 means it worked
        setTimeout(() => location.reload(), 1000);
    });
}

function providerConfirmBooking(bookingId) {
    if (!confirm('Mark this service as delivered and done?')) return;
    fetch(`/bookings/${bookingId}/provider-confirm`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json'
        }
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) location.reload();
        else alert(data.message || 'Error confirming');
    })
    .catch(() => alert('Error confirming'));
}

// ====================== INLINE STAR RATING ======================
function setBookingInlineRating(bookingId, rating) {
    const container = document.getElementById(`booking-rating-stars-${bookingId}`);
    if (!container) return;
    container.setAttribute('data-selected', rating);
    container.querySelectorAll('button').forEach((star, index) => {
        star.classList.remove('text-yellow-400', 'text-gray-300');
        star.classList.add(index < rating ? 'text-yellow-400' : 'text-gray-300');
    });
}

function submitBookingInlineRating(bookingId) {
    const container = document.getElementById(`booking-rating-stars-${bookingId}`);
    const rating = container ? container.getAttribute('data-selected') : null;

    if (!rating) {
        alert('Please select a star rating before submitting');
        return;
    }

    const review = document.getElementById(`booking-review-${bookingId}`)?.value || '';

    // Show loading state on the button
    const btn = document.querySelector(`[onclick="submitBookingInlineRating(${bookingId})"]`);
    if (btn) {
        btn.disabled = true;
        btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin mr-2"></i>Submitting...';
    }

    fetch(`/bookings/${bookingId}/rate`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            rating: parseInt(rating),
            review: review
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Replace the entire rating widget with a success message in place
            const ratingWidget = document.getElementById(`booking-rating-stars-${bookingId}`)
                ?.closest('.p-4, .p-3, .w-full');

            if (ratingWidget) {
                ratingWidget.style.transition = 'all 0.3s ease';
                ratingWidget.innerHTML = `
                    <div class="flex flex-col items-center gap-2 py-2">
                        <i class="fa-solid fa-check-circle text-emerald-500 text-3xl"></i>
                        <p class="text-sm font-semibold text-emerald-800">Rating submitted! Thank you.</p>
                        <div class="flex gap-1">
                            ${'★'.repeat(parseInt(rating)).split('').map(() =>
                                '<i class="fa-solid fa-star text-yellow-400"></i>'
                            ).join('')}
                            ${'★'.repeat(5 - parseInt(rating)).split('').map(() =>
                                '<i class="fa-regular fa-star text-gray-300"></i>'
                            ).join('')}
                        </div>
                        ${review ? `<p class="text-xs text-gray-600 italic text-center">"${review}"</p>` : ''}
                    </div>
                `;
                ratingWidget.className = 'p-4 bg-emerald-50 border border-emerald-200 rounded-xl mt-2';
            }

            // Reload after 5 seconds so user can see the confirmation
            setTimeout(() => location.reload(), 5000);
        } else {
            if (btn) {
                btn.disabled = false;
                btn.innerHTML = '<i class="fa-solid fa-star mr-2"></i>Submit Rating';
            }
            alert(data.message || 'Error submitting rating');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        if (btn) {
            btn.disabled = false;
            btn.innerHTML = '<i class="fa-solid fa-star mr-2"></i>Submit Rating';
        }
        alert('Error submitting rating');
    });
}
</script>

<!-- Footer -->
<x-footer />
@endsection
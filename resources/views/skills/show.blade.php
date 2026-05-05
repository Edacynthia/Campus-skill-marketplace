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
                <div>
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">Reviews</h2>
                    
                    @if($skill->reviews && $skill->reviews->count() > 0)
                        <div class="space-y-4">
                            @foreach($skill->reviews->take(3) as $review)
                                <div class="bg-white border border-gray-200 rounded-xl p-6">
                                    <div class="flex items-center justify-between mb-3">
                                        <div class="flex items-center gap-3">
                                            @if($review->user->passport_photo)
                                                <img src="{{ asset('storage/' . $review->user->passport_photo) }}" alt="{{ $review->user->first_name }}" 
                                                     class="w-10 h-10 rounded-full object-cover">
                                            @else
                                                <div class="w-10 h-10 bg-gray-200 rounded-full flex items-center justify-center">
                                                    <i class="fa-solid fa-user text-gray-500"></i>
                                                </div>
                                            @endif
                                            <div>
                                                <p class="font-semibold text-gray-900">{{ $review->user->first_name }} {{ substr($review->user->last_name, 0, 1) }}.</p>
                                                <div class="flex items-center gap-1 text-sm">
                                                    @for($i = 1; $i <= 5; $i++)
                                                        <i class="fa-solid fa-star {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300' }}"></i>
                                                    @endfor
                                                </div>
                                            </div>
                                        </div>
                                        <span class="text-sm text-gray-500">{{ $review->created_at->diffForHumans() }}</span>
                                    </div>
                                    @if($review->comment)
                                        <p class="text-gray-600">{{ $review->comment }}</p>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                        
                        @if($skill->reviews->count() > 3)
                            <div class="text-center mt-6">
                                <button class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-all">
                                    Load More Reviews
                                </button>
                            </div>
                        @endif
                    @else
                        <div class="text-center py-12 bg-gray-50 rounded-xl">
                            <i class="fa-solid fa-star text-gray-300 text-4xl mb-4"></i>
                            <p class="text-gray-500">No reviews yet</p>
                            <p class="text-sm text-gray-400 mt-2">Be the first to review this service</p>
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

                <!-- Action Buttons -->
                @if(auth()->check())
                    <div class="space-y-3">
                        <!-- Contact Provider Button - Only show if not user's own skill -->
                        @if(auth()->id() != $skill->user_id)
                            <button onclick="openContactModal()" class="w-full px-6 py-3 bg-[#1e3a8a] text-white font-semibold rounded-xl hover:bg-[#0f2b5e] transition-all shadow-sm hover:shadow-md">
                                <i class="fa-solid fa-message mr-2"></i>
                                Contact Provider
                            </button>
                        @else
                            <div class="w-full px-6 py-3 bg-gray-100 text-gray-500 font-semibold rounded-xl border border-gray-200 text-center">
                                <i class="fa-solid fa-message mr-2"></i>
                                This is your skill
                            </div>
                        @endif
                        <button class="w-full px-6 py-3 border-2 border-[#1e3a8a] text-[#1e3a8a] font-semibold rounded-xl hover:bg-[#1e3a8a] hover:text-white transition-all">
                            <i class="fa-solid fa-bookmark mr-2"></i>
                            Save Service
                        </button>
                    </div>
                @else
                    <div class="bg-blue-50 border border-blue-200 rounded-xl p-4">
                        <div class="flex items-center gap-3 mb-3">
                            <i class="fa-solid fa-info-circle text-blue-600"></i>
                            <span class="font-medium text-blue-900">Sign in required</span>
                        </div>
                        <p class="text-sm text-blue-800 mb-4">
                            You need to be logged in to contact providers and save services.
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
                        <i class="fa-solid fa-paper-plane mr-2"></i>
                        Send Message
                    </button>
                    <button type="button" onclick="closeContactModal()" class="px-4 py-3 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition-all">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openContactModal() {
    document.getElementById('contactModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeContactModal() {
    document.getElementById('contactModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
}

// Close modal when clicking outside
document.getElementById('contactModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeContactModal();
    }
});
</script>

<!-- Footer -->
    <x-footer />
@endsection

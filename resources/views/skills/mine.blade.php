@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
    <!-- Simple Header -->
    <div class="mb-6">
        <div class="flex items-center justify-between flex-wrap gap-4">
            <div>
                <h1 class="text-2xl font-semibold text-gray-900">My Skills</h1>
                <p class="text-gray-500 text-sm mt-0.5">Manage your skill offerings</p>
            </div>
            <a href="{{ route('skills.create') }}" 
               class="inline-flex items-center gap-2 px-4 py-2 bg-[#1e3a8a] text-white text-sm rounded-lg hover:bg-[#0f2b5e] transition-colors">
                <i class="fa-solid fa-plus text-xs"></i>
                <span>Add New Skill</span>
            </a>
        </div>
    </div>

    <!-- Skills List -->
    @if($skills->count() > 0)
        <div class="space-y-4">
            @foreach($skills as $skill)
                <div class="bg-white rounded-xl border border-gray-200 hover:border-gray-300 transition-all">
                    <div class="p-5">
                        <!-- Row 1: Title + Status + Price -->
                        <div class="flex flex-wrap items-start justify-between gap-3 mb-3">
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2 flex-wrap mb-1">
                                    <span class="text-xs text-gray-500 bg-gray-100 px-2 py-0.5 rounded">
                                        {{ $skill->category }}
                                    </span>
                                    @if($skill->status === 'active')
                                        <span class="text-xs text-green-700 bg-green-50 px-2 py-0.5 rounded">
                                            ● Active
                                        </span>
                                    @else
                                        <span class="text-xs text-gray-500 bg-gray-100 px-2 py-0.5 rounded">
                                            ● Inactive
                                        </span>
                                    @endif
                                </div>
                                <h3 class="text-lg font-medium text-gray-900">
                                    <a href="{{ route('skills.show', $skill->id) }}" class="hover:text-[#1e3a8a]">
                                        {{ $skill->title }}
                                    </a>
                                </h3>
                            </div>
                            <div class="text-right">
                                <p class="text-lg font-semibold text-[#1e3a8a]">₦{{ number_format($skill->price, 0) }}</p>
                                <p class="text-xs text-gray-400">per {{ $skill->price_type }}</p>
                            </div>
                        </div>

                        <!-- Row 2: Description -->
                        <p class="text-sm text-gray-500 mb-3 line-clamp-2">
                            {{ Str::limit($skill->description, 120) }}
                        </p>

                        <!-- Row 3: Metadata + Actions -->
                        <div class="flex flex-wrap items-center justify-between gap-3 pt-2 border-t border-gray-100">
                            <div class="flex items-center gap-4 text-xs text-gray-400">
                                <span class="flex items-center gap-1">
                                    <i class="fa-regular fa-calendar"></i>
                                    {{ $skill->created_at->format('M j, Y') }}
                                </span>
                                <span class="flex items-center gap-1">
                                    <i class="fa-regular fa-bookmark"></i>
                                    {{ $skill->bookings_count ?? 0 }} bookings
                                </span>
                                <span class="flex items-center gap-1">
                                    <i class="fa-regular fa-star"></i>
                                    {{ $skill->ratings_count ?? 0 }} reviews
                                </span>
                            </div>
                            
                            <div class="flex items-center gap-3">
                                <a href="{{ route('skills.edit', $skill->id) }}" 
                                   class="text-sm text-gray-500 hover:text-[#1e3a8a] transition-colors">
                                    Edit
                                </a>
                                
                                @if($skill->status === 'active')
                                    <form action="{{ route('skills.deactivate', $skill->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" 
                                                class="text-sm text-gray-500 hover:text-gray-700 transition-colors"
                                                onclick="return confirm('Deactivate this skill?')">
                                            Deactivate
                                        </button>
                                    </form>
                                @else
                                    <form action="{{ route('skills.activate', $skill->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" 
                                                class="text-sm text-gray-500 hover:text-green-600 transition-colors"
                                                onclick="return confirm('Activate this skill?')">
                                            Activate
                                        </button>
                                    </form>
                                @endif
                                
                                <a href="{{ route('skills.show', $skill->id) }}" 
                                   class="text-sm text-[#1e3a8a] hover:underline">
                                    View →
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Bookings (inline, minimal) -->
                    @if($skill->bookings && $skill->bookings->count() > 0)
                        <div class="bg-gray-50 px-5 py-3 rounded-b-xl border-t border-gray-100">
                            <div class="flex flex-wrap items-center gap-x-6 gap-y-2 text-xs">
                                <span class="text-gray-500 font-medium">Recent bookings:</span>
                                @foreach($skill->bookings->take(3) as $booking)
                                    <span class="text-gray-600">
                                        {{ $booking->client->first_name }}
                                        <span class="text-gray-400">•</span>
                                        {{ $booking->created_at->diffForHumans() }}
                                    </span>
                                @endforeach
                                @if($skill->bookings->count() > 3)
                                    <a href="{{ route('skills.show', $skill->id) }}" class="text-[#1e3a8a] hover:underline">
                                        +{{ $skill->bookings->count() - 3 }} more
                                    </a>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $skills->links() }}
        </div>
    @else
        <!-- Simple Empty State -->
        <div class="bg-white rounded-xl border border-gray-200 py-12 px-4 text-center">
            <div class="text-5xl mb-3 text-gray-300">
                <i class="fa-regular fa-lightbulb"></i>
            </div>
            <h3 class="text-lg font-medium text-gray-800 mb-1">No skills yet</h3>
            <p class="text-gray-500 text-sm mb-4">Share what you can teach others on campus.</p>
            <a href="{{ route('skills.create') }}" 
               class="inline-flex items-center gap-2 px-4 py-2 bg-[#1e3a8a] text-white text-sm rounded-lg hover:bg-[#0f2b5e] transition-colors">
                <i class="fa-solid fa-plus text-xs"></i>
                Add your first skill
            </a>
        </div>
    @endif
</div>
@endsection
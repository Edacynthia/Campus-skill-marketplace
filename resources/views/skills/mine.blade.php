@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">My Skills</h1>
                    <p class="text-gray-600 mt-2">Manage your skill offerings and track bookings</p>
                </div>
                <a href="{{ route('skills.create') }}" class="px-4 py-2 bg-[#1e3a8a] text-white rounded-lg hover:bg-[#0f2b5e] transition-colors">
                    <i class="fa-solid fa-plus mr-2"></i>Add New Skill
                </a>
            </div>
        </div>

        <!-- Skills List -->
        @if($skills->count() > 0)
            <div class="space-y-6">
                @foreach($skills as $skill)
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-shadow">
                        <div class="p-6">
                            <!-- Skill Header -->
                            <div class="flex items-start justify-between mb-4">
                                <div class="flex-1">
                                    <h3 class="text-lg font-semibold text-gray-900 mb-2">
                                        <a href="{{ route('skills.show', $skill->id) }}" class="hover:text-[#1e3a8a] transition-colors">
                                            {{ $skill->title }}
                                        </a>
                                    </h3>
                                    <div class="flex items-center gap-4 text-sm text-gray-600">
                                        <span class="flex items-center gap-1">
                                            <i class="fa-solid fa-tag text-gray-400"></i>
                                            {{ $skill->category }}
                                        </span>
                                        <span class="flex items-center gap-1">
                                            <i class="fa-solid fa-money-bill text-gray-400"></i>
                                            ₦{{ number_format($skill->price, 2) }}/{{ $skill->price_type }}
                                        </span>
                                        <span class="flex items-center gap-1">
                                            <i class="fa-solid fa-calendar text-gray-400"></i>
                                            Posted {{ $skill->created_at->diffForHumans() }}
                                        </span>
                                    </div>
                                </div>
                                
                                <!-- Status Badge -->
                                <div class="flex flex-col items-end gap-2">
                                    @switch($skill->status)
                                        @case('active')
                                            <span class="px-3 py-1 bg-green-100 text-green-800 text-sm font-medium rounded-full">
                                                <i class="fa-solid fa-check mr-1"></i>Active
                                            </span>
                                            @break
                                        @case('inactive')
                                            <span class="px-3 py-1 bg-gray-100 text-gray-800 text-sm font-medium rounded-full">
                                                <i class="fa-solid fa-pause mr-1"></i>Inactive
                                            </span>
                                            @break
                                    @endswitch
                                    
                                    <!-- Stats -->
                                    <div class="text-xs text-gray-500 text-center">
                                        <div>{{ $skill->bookings_count ?? 0 }} bookings</div>
                                        <div>{{ $skill->ratings_count ?? 0 }} reviews</div>
                                    </div>
                                </div>
                            </div>

                            <!-- Description -->
                            <div class="mb-4">
                                <p class="text-sm text-gray-600 line-clamp-2">{{ Str::limit($skill->description, 150) }}</p>
                            </div>

                            <!-- Recent Bookings -->
                            @if($skill->bookings && $skill->bookings->count() > 0)
                                <div class="mb-4">
                                    <h4 class="text-sm font-medium text-gray-700 mb-2">Recent Bookings:</h4>
                                    <div class="space-y-2">
                                        @foreach($skill->bookings->take(3) as $booking)
                                            <div class="flex items-center justify-between text-sm bg-gray-50 p-2 rounded">
                                                <span class="text-gray-600">
                                                    {{ $booking->client->first_name }} {{ $booking->client->last_name }}
                                                </span>
                                                <span class="text-gray-500">{{ $booking->created_at->diffForHumans() }}</span>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            <!-- Action Buttons -->
                            <div class="flex items-center justify-between pt-4 border-t">
                                <div class="flex gap-2">
                                    <a href="{{ route('skills.edit', $skill->id) }}" class="text-sm text-blue-600 hover:text-blue-800">
                                        <i class="fa-solid fa-edit mr-1"></i>Edit
                                    </a>
                                    @if($skill->status === 'active')
                                        <form action="{{ route('skills.deactivate', $skill->id) }}" method="POST" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="text-sm text-gray-600 hover:text-gray-800">
                                                <i class="fa-solid fa-pause mr-1"></i>Deactivate
                                            </button>
                                        </form>
                                    @else
                                        <form action="{{ route('skills.activate', $skill->id) }}" method="POST" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="text-sm text-green-600 hover:text-green-800">
                                                <i class="fa-solid fa-play mr-1"></i>Activate
                                            </button>
                                        </form>
                                    @endif
                                </div>
                                
                                <a href="{{ route('skills.show', $skill->id) }}" class="text-sm text-[#1e3a8a] hover:text-[#0f2b5e] font-medium">
                                    View Details <i class="fa-solid fa-arrow-right ml-1"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-8">
                {{ $skills->links() }}
            </div>
        @else
            <div class="text-center py-12">
                <div class="text-6xl mb-4 text-gray-300">
                    <i class="fa-solid fa-tools"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">No Skills Posted Yet</h3>
                <p class="text-gray-600 mb-6">Share your skills with the campus community and start earning.</p>
                <a href="{{ route('skills.create') }}" class="px-6 py-3 bg-[#1e3a8a] text-white rounded-lg hover:bg-[#0f2b5e] transition-colors">
                    <i class="fa-solid fa-plus mr-2"></i>Add Your First Skill
                </a>
            </div>
        @endif
    </div>
</div>
@endsection

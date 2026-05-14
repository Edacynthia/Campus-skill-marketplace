@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between mb-2">
            <h1 class="text-4xl font-bold text-gray-800">
                <i class="fa-solid fa-history text-[#1e3a8a] mr-3"></i>
                Approval History
            </h1>
            <a href="{{ route('admin.users.pending') }}" class="text-[#1e3a8a] hover:text-blue-900 font-medium transition">
                Back to Pending
                <i class="fa-solid fa-arrow-left ml-2"></i>
            </a>
        </div>
        <p class="text-gray-600">
            View all user approvals, rejections, and their approval history.
        </p>
    </div>

    <!-- Filter Section -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-8">
        <form method="GET" class="grid md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#1e3a8a] focus:border-transparent">
                    <option value="">All Statuses</option>
                    <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approved</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Search</label>
                <input type="text" 
                       name="search" 
                       placeholder="Name or email..." 
                       value="{{ request('search') }}"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#1e3a8a] focus:border-transparent">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Sort By</label>
                <select name="sort" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#1e3a8a] focus:border-transparent">
                    <option value="latest" {{ request('sort') === 'latest' ? 'selected' : '' }}>Latest First</option>
                    <option value="oldest" {{ request('sort') === 'oldest' ? 'selected' : '' }}>Oldest First</option>
                    <option value="name" {{ request('sort') === 'name' ? 'selected' : '' }}>Name (A-Z)</option>
                </select>
            </div>

            <div class="flex items-end gap-2">
                <button type="submit" class="flex-1 bg-[#1e3a8a] hover:bg-blue-900 text-white font-medium py-2 px-4 rounded-lg transition">
                    <i class="fa-solid fa-search mr-2"></i>Filter
                </button>
                <a href="{{ route('admin.users.all') }}" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium rounded-lg transition">
                    <i class="fa-solid fa-rotate-left"></i>
                </a>
            </div>
        </form>
    </div>

    <!-- Users Table -->
    @if($allUsers->count() > 0)
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <!-- Table Header -->
            <div class="grid grid-cols-1 md:grid-cols-7 gap-4 p-6 bg-gradient-to-r from-[#1e3a8a] to-blue-800 text-white font-semibold text-sm">
                <div>User</div>
                <div>Email</div>
                <div>Created</div>
                <div>Status</div>
                <div>Approved By</div>
                <div>Approval Date</div>
                <div class="text-right">Actions</div>
            </div>

            <!-- Table Body -->
            <div class="divide-y divide-gray-200">
                @foreach($allUsers as $user)
                    <div class="grid grid-cols-1 md:grid-cols-7 gap-4 p-6 hover:bg-gray-50 transition items-center">
                        <!-- User Info -->
                        <div>
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-gradient-to-br from-[#1e3a8a] to-blue-700 rounded-full flex items-center justify-center text-white font-bold text-sm">
                                    {{ strtoupper(substr($user->first_name, 0, 1) . substr($user->last_name, 0, 1)) }}
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-900">{{ $user->fullName() }}</p>
                                    <p class="text-xs text-gray-500">
                                        @if($user->hasUniversityEmail())
                                            <i class="fa-solid fa-check text-emerald-600"></i> University
                                        @else
                                            <i class="fa-solid fa-file text-orange-600"></i> External
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Email -->
                        <div class="truncate">
                            <p class="font-medium text-gray-900 truncate">{{ $user->email }}</p>
                        </div>

                        <!-- Created Date -->
                        <div>
                            <p class="font-medium text-gray-900 text-sm">{{ $user->created_at->format('M d, Y') }}</p>
                            <p class="text-xs text-gray-500">{{ $user->created_at->diffForHumans() }}</p>
                        </div>

                        <!-- Status Badge -->
                        <div>
                            <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-sm font-semibold
                                @if($user->approval_status === 'pending')
                                    bg-yellow-100 text-yellow-800
                                @elseif($user->approval_status === 'approved')
                                    bg-emerald-100 text-emerald-800
                                @else
                                    bg-red-100 text-red-800
                                @endif
                            ">
                                @if($user->approval_status === 'pending')
                                    <i class="fa-solid fa-clock"></i>
                                    Pending
                                @elseif($user->approval_status === 'approved')
                                    <i class="fa-solid fa-check"></i>
                                    Approved
                                @else
                                    <i class="fa-solid fa-ban"></i>
                                    Rejected
                                @endif
                            </span>
                        </div>

                        <!-- Approved By -->
                        <div>
                            @if($user->approvedByUser)
                                <p class="font-medium text-gray-900 text-sm">{{ $user->approvedByUser->fullName() }}</p>
                                <p class="text-xs text-gray-500">Admin</p>
                            @else
                                <span class="text-gray-400 text-sm italic">-</span>
                            @endif
                        </div>

                        <!-- Approval Date -->
                        <div>
                            @if($user->approved_at)
                                <p class="font-medium text-gray-900 text-sm">{{ $user->approved_at->format('M d, Y') }}</p>
                                <p class="text-xs text-gray-500">{{ $user->approved_at->diffForHumans() }}</p>
                            @else
                                <span class="text-gray-400 text-sm italic">-</span>
                            @endif
                        </div>

                        <!-- Actions -->
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('profile.show', $user) }}" class="inline-flex items-center gap-2 px-3 py-2 bg-gray-100 hover:bg-gray-200 text-gray-800 rounded-lg font-medium text-sm transition">
                                <i class="fa-solid fa-user"></i>
                                View
                            </a>

                            @if($user->passport_photo && !$user->hasUniversityEmail())
                                <a href="{{ asset('storage/' . $user->passport_photo) }}" 
                                   target="_blank" 
                                   rel="noopener noreferrer"
                                   class="inline-flex items-center gap-2 px-3 py-2 bg-blue-50 hover:bg-blue-100 text-[#1e3a8a] rounded-lg font-medium text-sm transition">
                                    <i class="fa-solid fa-image"></i>
                                    Photo
                                </a>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Pagination -->
        <div class="mt-8 flex justify-center">
            {{ $allUsers->links('pagination::tailwind') }}
        </div>
    @else
        <!-- Empty State -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-12 text-center">
            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                <i class="fa-solid fa-inbox text-gray-400 text-3xl"></i>
            </div>
            <h3 class="text-xl font-bold text-gray-800 mb-2">No users found</h3>
            <p class="text-gray-600 mb-6">
                No users match your filters. Try adjusting your search criteria.
            </p>
            <a href="{{ route('admin.users.all') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-[#1e3a8a] hover:bg-blue-900 text-white rounded-lg font-medium transition">
                <i class="fa-solid fa-rotate-left"></i>
                Clear Filters
            </a>
        </div>
    @endif
</div>

<style>
    .transition {
        transition: all 0.3s ease;
    }

    @media (max-width: 768px) {
        .grid {
            grid-template-columns: 1fr !important;
        }
    }
</style>
@endsection

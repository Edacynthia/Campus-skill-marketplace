@extends('layouts.app')

@section('content')

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between mb-2">
            <h1 class="text-4xl font-bold text-gray-800">
                <i class="fa-solid fa-users-check text-[#1e3a8a] mr-3"></i>
                User Approvals
            </h1>
            <a href="{{ route('admin.users.all') }}" class="text-[#1e3a8a] hover:text-blue-900 font-medium transition">
                View History
                <i class="fa-solid fa-arrow-right ml-2"></i>
            </a>
        </div>
        <p class="text-gray-600">
            Review and approve non-university email users for Campus Connect access.
        </p>
    </div>

    <!-- Stats Cards -->
    <div class="grid md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium">Pending Review</p>
                    <p class="text-3xl font-bold text-[#1e3a8a] mt-2">
                        {{ $pendingUsers->total() }}
                    </p>
                </div>
                <div class="w-14 h-14 bg-blue-100 rounded-full flex items-center justify-center">
                    <i class="fa-solid fa-hourglass-half text-[#1e3a8a] text-2xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium">Total Pages</p>
                    <p class="text-3xl font-bold text-emerald-600 mt-2">
                        {{ $pendingUsers->lastPage() }}
                    </p>
                </div>
                <div class="w-14 h-14 bg-emerald-100 rounded-full flex items-center justify-center">
                    <i class="fa-solid fa-list-check text-emerald-600 text-2xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium">Current Page</p>
                    <p class="text-3xl font-bold text-orange-600 mt-2">
                        {{ $pendingUsers->currentPage() }}
                    </p>
                </div>
                <div class="w-14 h-14 bg-orange-100 rounded-full flex items-center justify-center">
                    <i class="fa-solid fa-book text-orange-600 text-2xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Users Table -->
    @if($pendingUsers->count() > 0)
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <!-- Table Header -->
            <div class="grid grid-cols-1 md:grid-cols-6 gap-4 p-6 bg-gradient-to-r from-[#1e3a8a] to-blue-800 text-white font-semibold text-sm">
                <div>User</div>
                <div>Email</div>
                <div>Created</div>
                <div>Passport Photo</div>
                <div>Status</div>
                <div class="text-right">Actions</div>
            </div>

            <!-- Table Body -->
            <div class="divide-y divide-gray-200">
                @foreach($pendingUsers as $user)
                    <div class="grid grid-cols-1 md:grid-cols-6 gap-4 p-6 hover:bg-gray-50 transition items-center">
                        <!-- User Info -->
                        <div>
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-gradient-to-br from-[#1e3a8a] to-blue-700 rounded-full flex items-center justify-center text-white font-bold text-sm">
                                    {{ strtoupper(substr($user->first_name, 0, 1) . substr($user->last_name, 0, 1)) }}
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-900">{{ $user->fullName() }}</p>
                                    <p class="text-xs text-gray-500">ID: {{ $user->id }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Email -->
                        <div>
                            <p class="font-medium text-gray-900">{{ $user->email }}</p>
                            <p class="text-xs text-gray-500">
                                @if($user->hasUniversityEmail())
                                    <i class="fa-solid fa-check text-emerald-600"></i> University
                                @else
                                    <i class="fa-solid fa-file text-orange-600"></i> External
                                @endif
                            </p>
                        </div>

                        <!-- Created Date -->
                        <div>
                            <p class="font-medium text-gray-900">{{ $user->created_at->format('M d, Y') }}</p>
                            <p class="text-xs text-gray-500">{{ $user->created_at->diffForHumans() }}</p>
                        </div>

                        <!-- Passport Photo -->
                        <div>
                            @if($user->passport_photo)
                                <div class="flex items-center gap-2">
                                    <i class="fa-solid fa-file-image text-emerald-600"></i>
                                    <a href="{{ asset('storage/' . $user->passport_photo) }}" 
                                       target="_blank" 
                                       rel="noopener noreferrer"
                                       class="text-[#1e3a8a] hover:underline font-medium text-sm">
                                        View
                                    </a>
                                </div>
                            @else
                                <span class="text-gray-400 text-sm italic">No file</span>
                            @endif
                        </div>

                        <!-- Status Badge -->
                        <div class="flex flex-col gap-2">

                            <!-- Approval Status -->
                            <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-sm font-semibold w-fit
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

                            <!-- OTP Verification Status -->
                            @if($user->otp_verified)
                                <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-700 w-fit">
                                    <i class="fa-solid fa-shield-check"></i>
                                    OTP Verified
                                </span>
                            @else
                                <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-700 w-fit">
                                    <i class="fa-solid fa-triangle-exclamation"></i>
                                    OTP Not Verified
                                </span>
                            @endif

                        </div>

                        <!-- Actions -->
                        <div class="flex items-center justify-end gap-2">
                            @if($user->approval_status !== 'approved')
                                <form method="POST" action="{{ route('admin.users.approve', $user) }}" class="inline">
                                    @csrf
                                    <button type="submit" 
                                            class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg font-medium text-sm transition"
                                            onclick="return confirm('Approve {{ $user->fullName() }}?')">
                                        <i class="fa-solid fa-check"></i>
                                        Approve
                                    </button>
                                </form>
                            @endif

                            @if($user->approval_status !== 'rejected')
                                <form method="POST" action="{{ route('admin.users.reject', $user) }}" class="inline">
                                    @csrf
                                    <button type="submit" 
                                            class="inline-flex items-center gap-2 px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg font-medium text-sm transition"
                                            onclick="return confirm('Reject {{ $user->fullName() }}? They will be notified.')">
                                        <i class="fa-solid fa-times"></i>
                                        Reject
                                    </button>
                                </form>
                            @endif

                            @if($user->approval_status !== 'pending')
                                <div class="text-xs text-gray-500 whitespace-nowrap">
                                    @if($user->approved_by)
                                        By: {{ $user->approvedByUser?->first_name ?? 'Admin' }}
                                        @if($user->approved_at)
                                            <br>{{ $user->approved_at->format('M d, Y') }}
                                        @endif
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Pagination -->
        <div class="mt-8 flex justify-center">
            {{ $pendingUsers->links('pagination::tailwind') }}
        </div>
    @else
        <!-- Empty State -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-12 text-center">
            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                <i class="fa-solid fa-check-double text-emerald-600 text-3xl"></i>
            </div>
            <h3 class="text-xl font-bold text-gray-800 mb-2">All caught up!</h3>
            <p class="text-gray-600 mb-6">
                There are no pending user approvals at the moment. All external users have been reviewed.
            </p>
            <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-[#1e3a8a] hover:bg-blue-900 text-white rounded-lg font-medium transition">
                <i class="fa-solid fa-arrow-left"></i>
                Back to Dashboard
            </a>
        </div>
    @endif
</div>

<style>
    /* Smooth transitions */
    .transition {
        transition: all 0.3s ease;
    }

    /* Responsive table adjustments */
    @media (max-width: 768px) {
        .grid {
            grid-template-columns: 1fr !important;
        }
    }
</style>
@endsection
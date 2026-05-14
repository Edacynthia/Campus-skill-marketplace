@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-gray-50 py-12">
        <div class="max-w-6xl mx-auto px-6">
            <!-- Page Header -->
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-800 mb-2">Notifications</h1>
                <p class="text-gray-600">Stay updated with your latest activities and messages</p>
            </div>

            <!-- Success Messages -->
            @if(session('success'))
                <div class="mb-6 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg flex items-center gap-3">
                    <i class="fa-solid fa-check-circle"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if($notifications->count() > 0)
                <!-- Header Actions -->
                <div class="mb-6 flex items-center justify-between">
                    <div class="text-sm text-gray-600">
                        {{ $notifications->total() }} notifications
                        @if(auth()->user()->unreadNotificationCount() > 0)
                            ({{ auth()->user()->unreadNotificationCount() }} unread)
                        @endif
                    </div>
                    @if(auth()->user()->unreadNotificationCount() > 0)
                        <form action="{{ route('notifications.readAll') }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="px-4 py-2 bg-[#1e3a8a] text-white text-sm font-medium rounded-lg hover:bg-[#0f2b5e] transition-all">
                                Mark All Read
                            </button>
                        </form>
                    @endif
                </div>

                <!-- Notifications List -->
                <div class="space-y-4">
                    @foreach($notifications as $notification)
                        <div class="bg-white rounded-xl border border-gray-200 hover:shadow-md transition-all overflow-hidden {{ !$notification->is_read ? 'border-l-4 border-l-blue-500' : '' }}">
                            <div class="p-6">
                                <div class="flex items-start justify-between">
                                    <div class="flex-1">
                                        <div class="flex items-center gap-3 mb-2">
                                            <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                                                <i class="fa-solid fa-bell text-blue-600"></i>
                                            </div>
                                            <div>
                                                <h3 class="font-semibold text-gray-900">{{ $notification->title }}</h3>
                                                <p class="text-sm text-gray-600">{{ $notification->created_at->format('M j, Y \a\t g:i A') }}</p>
                                            </div>
                                            @if(!$notification->is_read)
                                                <span class="bg-blue-100 text-blue-600 text-xs px-2 py-1 rounded-full">New</span>
                                            @endif
                                        </div>
                                        <p class="text-gray-700 leading-relaxed">{{ $notification->message }}</p>
                                        
                                        @if($notification->url)
                                            <div class="mt-4">
                                                <a href="{{ $notification->url }}" class="inline-flex items-center px-4 py-2 bg-[#1e3a8a] text-white text-sm font-medium rounded-lg hover:bg-[#0f2b5e] transition-all">
                                                    <i class="fa-solid fa-arrow-right mr-2"></i>
                                                    View Details
                                                </a>
                                            </div>
                                        @endif
                                    </div>
                                    
                                    <div class="ml-4">
                                        @if(!$notification->is_read)
                                            <form action="{{ route('notifications.read', $notification->id) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                                    Mark as read
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="flex justify-center mt-8">
                    {{ $notifications->links() }}
                </div>
            @else
                <div class="text-center py-16">
                    <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="fa-solid fa-bell text-gray-400 text-3xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-800 mb-2">No Notifications</h3>
                    <p class="text-gray-500 mb-6">You're all caught up! No new notifications to show.</p>
                    <a href="{{ auth()->user()->role === 'admin' 
                            ? route('admin.dashboard') 
                            : route('dashboard') }}"
                    class="inline-flex items-center px-6 py-3 bg-[#1e3a8a] text-white font-semibold rounded-full hover:bg-[#0f2b5e] transition-all">
                        Back to Dashboard
                    </a>
                </div>
            @endif
        </div>
    </div>
@endsection

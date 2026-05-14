@php
    $currentUser = auth()->user();

    $showGuestNavbar = !auth()->check()
        || ($currentUser && $currentUser->isPendingApproval())
        || ($currentUser && $currentUser->isRejected());

    $isAdmin = auth()->check()
        && !$showGuestNavbar
        && ($currentUser->hasRole('admin') || $currentUser->role === 'admin');

    $logoRoute = $showGuestNavbar
        ? route('home')
        : ($isAdmin ? route('admin.dashboard') : route('dashboard'));
@endphp

<nav class="bg-white border-b sticky top-0 z-50 relative">
    <div class="max-w-7xl mx-auto px-6 py-5 flex items-center justify-between">

        <!-- Logo -->
        <a href="{{ $logoRoute }}" class="flex items-center gap-2">
            <span class="text-2xl md:text-3xl font-bold text-[#1e3a8a]">Campus</span>
            <span class="text-2xl md:text-3xl font-bold text-emerald-600">Connect</span>
        </a>

        <!-- Desktop Menu -->
        <div class="hidden md:flex items-center gap-8 text-sm font-medium">
            <a href="{{ route('skills.index') }}"
               class="hover:text-[#1e3a8a] {{ request()->routeIs('skills.index') ? 'text-[#1e3a8a] font-semibold' : '' }}">
                Browse Skills
            </a>

            <a href="{{ route('jobs.index') }}"
               class="hover:text-[#1e3a8a] {{ request()->routeIs('jobs.index') ? 'text-[#1e3a8a] font-semibold' : '' }}">
                Browse Jobs
            </a>

            @if($isAdmin)
             {{-- <a href="{{ route('admin.dashboard') }}"
                class="hover:text-[#1e3a8a] {{ request()->routeIs('admin.dashboard') ? 'text-[#1e3a8a] font-semibold' : '' }}">
                    Admin Dashboard
                </a> --}}

                <a href="{{ route('admin.users.pending') }}"
                   class="hover:text-[#1e3a8a] {{ request()->routeIs('admin.users.pending') ? 'text-[#1e3a8a] font-semibold' : '' }}">
                    Pending Approvals
                </a>

                <a href="{{ route('admin.users.all') }}"
                   class="hover:text-[#1e3a8a] {{ request()->routeIs('admin.users.all') ? 'text-[#1e3a8a] font-semibold' : '' }}">
                    All Approvals
                </a>
            @endif
        </div>

        <!-- Right Side -->
        <div class="flex items-center gap-4">
            @if($showGuestNavbar)
                @if(auth()->check())
                    <form method="POST" action="{{ route('logout') }}" class="hidden md:block">
                        @csrf
                        <button type="submit"
                                class="px-6 py-2.5 text-sm font-semibold border-2 border-[#1e3a8a] text-[#1e3a8a] rounded-2xl hover:bg-[#1e3a8a] hover:text-white transition-all">
                            Logout
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}"
                       class="hidden md:block px-6 py-2.5 text-sm font-semibold border-2 border-[#1e3a8a] text-[#1e3a8a] rounded-2xl hover:bg-[#1e3a8a] hover:text-white transition-all">
                        Login
                    </a>
                @endif
            @else
                <div class="hidden md:flex items-center gap-4">

                    <!-- Notification Bell -->
                    <div class="relative">
                        <button onclick="toggleNotificationDropdown(event)"
                                class="relative hover:text-[#1e3a8a] transition-colors"
                                aria-label="Open notifications">
                            <i class="fa-solid fa-bell text-xl"></i>

                            @if($currentUser->unreadNotificationCount() > 0)
                                <span class="absolute -top-1 -right-1 bg-red-500 text-white text-[10px] w-4 h-4 flex items-center justify-center rounded-full">
                                    {{ $currentUser->unreadNotificationCount() > 9 ? '9+' : $currentUser->unreadNotificationCount() }}
                                </span>
                            @endif
                        </button>

                        <div id="notificationDropdown"
                             class="absolute right-0 mt-3 w-80 bg-white rounded-2xl shadow-xl border border-gray-100 py-2 opacity-0 invisible transition-all duration-200 z-50">

                            <div class="px-4 py-3 border-b border-gray-100">
                                <div class="flex items-center justify-between">
                                    <h3 class="font-semibold text-gray-900">Notifications</h3>

                                    @if($currentUser->unreadNotificationCount() > 0)
                                        <form action="{{ route('notifications.readAll') }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit"
                                                    class="text-xs text-[#1e3a8a] hover:text-[#0f2b5e] font-medium">
                                                Mark all read
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>

                            <div class="max-h-96 overflow-y-auto">
                                @php
                                    $recentNotifications = $currentUser->notifications()
                                        ->latest()
                                        ->take(5)
                                        ->get();
                                @endphp

                                @if($recentNotifications->count() > 0)
                                    @foreach($recentNotifications as $notification)
                                        <div class="px-4 py-3 hover:bg-gray-50 border-b border-gray-50 transition-all {{ !$notification->is_read ? 'bg-blue-50' : '' }}">
                                            <div class="flex items-start gap-3">
                                                <div class="w-9 h-9 rounded-full bg-gray-100 flex items-center justify-center shrink-0">
                                                    @if($notification->type === 'new_pending_user')
                                                        <i class="fa-solid fa-user-clock text-orange-600"></i>

                                                    @elseif($notification->type === 'admin_alert')
                                                        <i class="fa-solid fa-triangle-exclamation text-red-600"></i>

                                                    @elseif($notification->type === 'message_received')
                                                        <i class="fa-solid fa-envelope text-blue-600"></i>

                                                    @elseif($notification->type === 'message')
                                                        <i class="fa-solid fa-envelope text-blue-600"></i>

                                                    @elseif($notification->type === 'booking_request')
                                                        <i class="fa-solid fa-calendar-check text-emerald-600"></i>

                                                    @elseif($notification->type === 'new_skill')
                                                        <i class="fa-solid fa-graduation-cap text-purple-600"></i>

                                                    @elseif($notification->type === 'new_job')
                                                        <i class="fa-solid fa-briefcase text-amber-600"></i>

                                                    @elseif($notification->type === 'rating')
                                                        <i class="fa-solid fa-star text-yellow-500"></i>

                                                    @elseif($notification->type === 'application')
                                                        <i class="fa-solid fa-file-lines text-indigo-600"></i>

                                                    @else
                                                        <i class="fa-solid fa-bell text-blue-600"></i>
                                                    @endif

                                                </div>  

                                                <div class="flex-1 min-w-0">
                                                    <div class="flex items-start justify-between gap-2">
                                                        <div>
                                                            <p class="text-sm font-semibold text-gray-900 leading-tight">
                                                                {{ $notification->title }}
                                                            </p>

                                                            <p class="text-xs text-gray-600 mt-1 leading-relaxed">
                                                                {{ $notification->message }}
                                                            </p>

                                                            <p class="text-[11px] text-gray-400 mt-2">
                                                                {{ $notification->created_at->diffForHumans() }}
                                                            </p>
                                                        </div>

                                                        @if(!$notification->is_read)
                                                            <form action="{{ route('notifications.read', $notification->id) }}" method="POST">
                                                                @csrf
                                                                <button type="submit" class="text-blue-600 hover:text-blue-800 mt-1">
                                                                    <i class="fa-solid fa-circle text-[10px]"></i>
                                                                </button>
                                                            </form>
                                                        @endif
                                                    </div>

                                                    @if($notification->url)
                                                        <a href="{{ route('notifications.open', $notification->id) }}"
                                                           class="inline-flex items-center gap-1 mt-3 text-xs font-medium text-[#1e3a8a] hover:text-[#0f2b5e]">
                                                            View Details
                                                            <i class="fa-solid fa-arrow-right text-[10px]"></i>
                                                        </a>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="px-6 py-10 text-center">
                                        <div class="w-14 h-14 mx-auto bg-gray-100 rounded-full flex items-center justify-center mb-3">
                                            <i class="fa-solid fa-bell text-gray-400 text-xl"></i>
                                        </div>

                                        <p class="text-sm font-medium text-gray-700">No notifications yet</p>
                                        <p class="text-xs text-gray-500 mt-1">You'll see updates here when activity happens.</p>
                                    </div>
                                @endif
                            </div>

                            <div class="px-4 py-3 border-t border-gray-100">
                                <a href="{{ route('notifications.index') }}"
                                   class="block text-center text-sm font-medium text-[#1e3a8a] hover:text-[#0f2b5e] transition-colors">
                                    View all notifications
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Messages Button -->
                    <a href="{{ route('messages.index') }}" class="relative hover:text-[#1e3a8a]" aria-label="Messages">
                        <i class="fa-solid fa-envelope text-xl"></i>

                        @if($currentUser->unreadCount() > 0)
                            <span class="absolute -top-1 -right-1 bg-red-500 text-white text-[10px] w-4 h-4 flex items-center justify-center rounded-full">
                                {{ $currentUser->unreadCount() > 9 ? '9+' : $currentUser->unreadCount() }}
                            </span>
                        @endif
                    </a>

                    <!-- Avatar Dropdown -->
                    <div class="relative group">
                        <button class="flex items-center gap-3" onclick="toggleDropdown(event)" aria-label="Open profile menu">
                            <div class="w-9 h-9 bg-gray-200 rounded-2xl overflow-hidden border flex items-center justify-center">
                                @if($currentUser->passport_photo)
                                    <img src="{{ asset('storage/' . $currentUser->passport_photo) }}"
                                         alt="Profile"
                                         class="w-full h-full object-cover">
                                @else
                                    <i class="fa-solid fa-circle-user text-2xl text-gray-500"></i>
                                @endif
                            </div>

                            <div class="text-left">
                                <p class="font-semibold text-sm">{{ $currentUser->first_name }}</p>
                                <p class="text-xs text-gray-500">{{ ucfirst($currentUser->role) }}</p>
                            </div>
                        </button>

                        <div id="profile-dropdown"
                             class="absolute right-0 mt-3 w-56 bg-white rounded-2xl shadow-xl border py-2 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all z-50">

                            @if($isAdmin)
                                <a href="{{ route('admin.users.pending') }}" class="flex items-center gap-3 px-5 py-3 hover:bg-gray-50">
                                    <i class="fa-solid fa-user-check"></i>
                                    <span>Pending Approvals</span>
                                </a>

                                <a href="{{ route('admin.users.all') }}" class="flex items-center gap-3 px-5 py-3 hover:bg-gray-50">
                                    <i class="fa-solid fa-users-gear"></i>
                                    <span>All Approvals</span>
                                </a>
                            @else
                                <a href="{{ route('profile.show', auth()->id()) }}" class="flex items-center gap-3 px-5 py-3 hover:bg-gray-50">
                                    <i class="fa-solid fa-user"></i>
                                    <span>My Profile</span>
                                </a>
                            @endif

                            <a href="#" class="flex items-center gap-3 px-5 py-3 hover:bg-gray-50">
                                <i class="fa-solid fa-cog"></i>
                                <span>Settings</span>
                            </a>

                            <a href="{{ route('notifications.index') }}" class="flex items-center gap-3 px-5 py-3 hover:bg-gray-50">
                                <i class="fa-solid fa-bell"></i>
                                <span>Notifications</span>

                                @if($currentUser->unreadNotificationCount() > 0)
                                    <span class="bg-red-500 text-white text-xs px-2 py-0.5 rounded-full">
                                        {{ $currentUser->unreadNotificationCount() > 9 ? '9+' : $currentUser->unreadNotificationCount() }}
                                    </span>
                                @endif
                            </a>

                            <a href="{{ route('messages.index') }}" class="flex items-center gap-3 px-5 py-3 hover:bg-gray-50">
                                <i class="fa-solid fa-envelope"></i>
                                <span>Messages</span>

                                @if($currentUser->unreadCount() > 0)
                                    <span class="bg-red-500 text-white text-xs px-2 py-0.5 rounded-full">
                                        {{ $currentUser->unreadCount() > 9 ? '9+' : $currentUser->unreadCount() }}
                                    </span>
                                @endif
                            </a>

                            <div class="border-t my-2"></div>

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                        class="flex items-center gap-3 px-5 py-3 text-red-600 hover:bg-red-50 w-full text-left">
                                    <i class="fa-solid fa-arrow-right-from-bracket"></i>
                                    <span>Logout</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Mobile Hamburger Menu -->
            <button type="button"
                    onclick="toggleMobileMenu()"
                    class="md:hidden p-2 rounded-lg hover:bg-gray-100 transition-colors"
                    aria-label="Open menu">
                <i id="hamburger-icon" class="fa-solid fa-bars text-xl text-gray-700"></i>
                <i id="close-icon" class="fa-solid fa-times text-xl text-gray-700 hidden"></i>
            </button>
        </div>
    </div>
</nav>

<!-- Mobile Navigation Slide-out -->
<div id="mobile-menu" class="fixed inset-0 z-30 md:hidden hidden">
    <div class="absolute inset-0 bg-black bg-opacity-50" onclick="toggleMobileMenu()"></div>

    <div class="absolute right-0 top-0 h-full w-80 bg-white shadow-none transform translate-x-full transition-transform duration-300 ease-in-out">
        <div class="flex flex-col h-full">
            <div class="flex items-center justify-between p-6 border-b">
                <h2 class="text-lg font-semibold text-gray-900">Menu</h2>

                <button onclick="toggleMobileMenu()" class="p-2 rounded-lg hover:bg-gray-100" aria-label="Close menu">
                    <i class="fa-solid fa-times text-xl text-gray-700"></i>
                </button>
            </div>

            <div class="flex-1 overflow-y-auto p-6">
                @if($showGuestNavbar)
                    <div class="space-y-4">
                        <a href="{{ route('skills.index') }}"
                           class="flex items-center gap-3 p-3 rounded-lg hover:bg-gray-50 transition-colors {{ request()->routeIs('skills.index') ? 'bg-[#1e3a8a]/10 text-[#1e3a8a]' : 'text-gray-700' }}"
                           onclick="toggleMobileMenu()">
                            <i class="fa-solid fa-graduation-cap text-lg"></i>
                            <div>
                                <p class="font-medium">Browse Skills</p>
                                <p class="text-sm text-gray-500">Find talented students</p>
                            </div>
                        </a>

                        <a href="{{ route('jobs.index') }}"
                           class="flex items-center gap-3 p-3 rounded-lg hover:bg-gray-50 transition-colors {{ request()->routeIs('jobs.index') ? 'bg-[#1e3a8a]/10 text-[#1e3a8a]' : 'text-gray-700' }}"
                           onclick="toggleMobileMenu()">
                            <i class="fa-solid fa-briefcase text-lg"></i>
                            <div>
                                <p class="font-medium">Browse Jobs</p>
                                <p class="text-sm text-gray-500">Find opportunities</p>
                            </div>
                        </a>

                        <div class="border-t pt-4">
                            @if(auth()->check())
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit"
                                            class="w-full flex items-center justify-center gap-2 px-6 py-3 bg-[#1e3a8a] text-white font-semibold rounded-xl hover:bg-[#0f2b5e] transition-colors">
                                        <i class="fa-solid fa-arrow-right-from-bracket"></i>
                                        Logout
                                    </button>
                                </form>
                            @else
                                <a href="{{ route('login') }}"
                                   class="w-full flex items-center justify-center gap-2 px-6 py-3 bg-[#1e3a8a] text-white font-semibold rounded-xl hover:bg-[#0f2b5e] transition-colors"
                                   onclick="toggleMobileMenu()">
                                    <i class="fa-solid fa-sign-in-alt"></i>
                                    Login
                                </a>

                                <div class="text-center mt-4">
                                    <p class="text-sm text-gray-500">Don't have an account?</p>
                                    <a href="{{ route('register') }}"
                                       class="text-sm font-medium text-[#1e3a8a] hover:underline"
                                       onclick="toggleMobileMenu()">
                                        Sign up
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                @else
                    <div class="space-y-4">
                        <div class="flex items-center gap-4 p-4 bg-gray-50 rounded-xl">
                            <div class="w-12 h-12 bg-gray-200 rounded-full overflow-hidden border flex items-center justify-center">
                                @if($currentUser->passport_photo)
                                    <img src="{{ asset('storage/' . $currentUser->passport_photo) }}"
                                         alt="Profile"
                                         class="w-full h-full object-cover">
                                @else
                                    <i class="fa-solid fa-circle-user text-2xl text-gray-500"></i>
                                @endif
                            </div>

                            <div>
                                <p class="font-semibold text-gray-900">{{ $currentUser->first_name }} {{ $currentUser->last_name }}</p>
                                <p class="text-sm text-gray-500">{{ ucfirst($currentUser->role) }}</p>
                            </div>
                        </div>

                        @if($isAdmin)
                        {{-- <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-5 py-3 hover:bg-gray-50">
            <i class="fa-solid fa-gauge"></i>
            <span>Admin Dashboard</span>
        </a> --}}

                            <a href="{{ route('admin.users.pending') }}"
                               class="flex items-center gap-3 p-3 rounded-lg hover:bg-gray-50 transition-colors {{ request()->routeIs('admin.users.pending') ? 'bg-[#1e3a8a]/10 text-[#1e3a8a]' : '' }}"
                               onclick="toggleMobileMenu()">
                                <i class="fa-solid fa-user-check text-lg"></i>
                                <span class="font-medium">Pending Approvals</span>
                            </a>

                            <a href="{{ route('admin.users.all') }}"
                               class="flex items-center gap-3 p-3 rounded-lg hover:bg-gray-50 transition-colors {{ request()->routeIs('admin.users.all') ? 'bg-[#1e3a8a]/10 text-[#1e3a8a]' : '' }}"
                               onclick="toggleMobileMenu()">
                                <i class="fa-solid fa-users-gear text-lg"></i>
                                <span class="font-medium">All Approvals</span>
                            </a>
                        @else
                            <a href="{{ route('skills.index') }}"
                               class="flex items-center gap-3 p-3 rounded-lg hover:bg-gray-50 transition-colors {{ request()->routeIs('skills.index') ? 'bg-[#1e3a8a]/10 text-[#1e3a8a]' : '' }}"
                               onclick="toggleMobileMenu()">
                                <i class="fa-solid fa-graduation-cap text-lg"></i>
                                <span class="font-medium">Browse Skills</span>
                            </a>

                            <a href="{{ route('jobs.index') }}"
                               class="flex items-center gap-3 p-3 rounded-lg hover:bg-gray-50 transition-colors {{ request()->routeIs('jobs.index') ? 'bg-[#1e3a8a]/10 text-[#1e3a8a]' : '' }}"
                               onclick="toggleMobileMenu()">
                                <i class="fa-solid fa-briefcase text-lg"></i>
                                <span class="font-medium">Browse Jobs</span>
                            </a>
                        @endif

                        <div class="border-t pt-4">
                            <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-3">Account</h3>

                            @if(!$isAdmin)
                                <a href="{{ route('profile.show', auth()->id()) }}"
                                   class="flex items-center gap-3 p-3 rounded-lg hover:bg-gray-50 transition-colors"
                                   onclick="toggleMobileMenu()">
                                    <i class="fa-solid fa-user text-lg"></i>
                                    <span class="font-medium">My Profile</span>
                                </a>
                            @endif

                            <a href="{{ route('notifications.index') }}"
                               class="flex items-center gap-3 p-3 rounded-lg hover:bg-gray-50 transition-colors">
                                <i class="fa-solid fa-bell text-lg"></i>
                                <span class="font-medium">Notifications</span>

                                @if($currentUser->unreadNotificationCount() > 0)
                                    <span class="ml-auto bg-red-500 text-white text-xs px-2 py-1 rounded-full">
                                        {{ $currentUser->unreadNotificationCount() > 9 ? '9+' : $currentUser->unreadNotificationCount() }}
                                    </span>
                                @endif
                            </a>

                            <a href="{{ route('messages.index') }}"
                               class="flex items-center gap-3 p-3 rounded-lg hover:bg-gray-50 transition-colors">
                                <i class="fa-solid fa-envelope text-lg"></i>
                                <span class="font-medium">Messages</span>

                                @if($currentUser->unreadCount() > 0)
                                    <span class="ml-auto bg-red-500 text-white text-xs px-2 py-1 rounded-full">
                                        {{ $currentUser->unreadCount() > 9 ? '9+' : $currentUser->unreadCount() }}
                                    </span>
                                @endif
                            </a>

                            <form method="POST" action="{{ route('logout') }}" class="pt-4">
                                @csrf
                                <button type="submit"
                                        class="w-full flex items-center gap-3 p-3 text-red-600 hover:bg-red-50 rounded-lg transition-colors">
                                    <i class="fa-solid fa-arrow-right-from-bracket text-lg"></i>
                                    <span class="font-medium">Logout</span>
                                </button>
                            </form>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
function toggleDropdown(event) {
    event.stopPropagation();

    const dropdown = document.getElementById('profile-dropdown');

    if (!dropdown) return;

    dropdown.classList.toggle('opacity-100');
    dropdown.classList.toggle('opacity-0');
    dropdown.classList.toggle('invisible');
}

function toggleNotificationDropdown(event) {
    event.stopPropagation();

    const dropdown = document.getElementById('notificationDropdown');

    if (!dropdown) return;

    dropdown.classList.toggle('opacity-100');
    dropdown.classList.toggle('visible');
    dropdown.classList.toggle('opacity-0');
    dropdown.classList.toggle('invisible');
}

function toggleMobileMenu() {
    const mobileMenu = document.getElementById('mobile-menu');
    const hamburgerIcon = document.getElementById('hamburger-icon');
    const closeIcon = document.getElementById('close-icon');
    const slidePanel = mobileMenu.querySelector('.transform');

    if (mobileMenu.classList.contains('hidden')) {
        mobileMenu.classList.remove('hidden');
        hamburgerIcon.classList.add('hidden');
        closeIcon.classList.remove('hidden');
        slidePanel.classList.remove('translate-x-full');
        document.body.style.overflow = 'hidden';
    } else {
        mobileMenu.classList.add('hidden');
        hamburgerIcon.classList.remove('hidden');
        closeIcon.classList.add('hidden');
        slidePanel.classList.add('translate-x-full');
        document.body.style.overflow = 'auto';
    }
}

document.addEventListener('click', function(event) {
    const profileDropdown = document.getElementById('profile-dropdown');
    const notificationDropdown = document.getElementById('notificationDropdown');

    const profileButton = event.target.closest('[onclick^="toggleDropdown"]');
    const bellButton = event.target.closest('[onclick^="toggleNotificationDropdown"]');

    if (profileDropdown && !profileDropdown.contains(event.target) && !profileButton) {
        profileDropdown.classList.remove('opacity-100');
        profileDropdown.classList.add('opacity-0', 'invisible');
    }

    if (notificationDropdown && !notificationDropdown.contains(event.target) && !bellButton) {
        notificationDropdown.classList.remove('opacity-100', 'visible');
        notificationDropdown.classList.add('opacity-0', 'invisible');
    }
});

document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        const profileDropdown = document.getElementById('profile-dropdown');
        const notificationDropdown = document.getElementById('notificationDropdown');
        const mobileMenu = document.getElementById('mobile-menu');

        if (profileDropdown) {
            profileDropdown.classList.remove('opacity-100');
            profileDropdown.classList.add('opacity-0', 'invisible');
        }

        if (notificationDropdown) {
            notificationDropdown.classList.remove('opacity-100', 'visible');
            notificationDropdown.classList.add('opacity-0', 'invisible');
        }

        if (mobileMenu && !mobileMenu.classList.contains('hidden')) {
            toggleMobileMenu();
        }
    }
});
</script>
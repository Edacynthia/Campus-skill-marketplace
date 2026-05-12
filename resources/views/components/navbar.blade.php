<nav class="bg-white border-b sticky top-0 z-50 relative">
    <div class="max-w-7xl mx-auto px-6 py-5 flex items-center justify-between">
        
        <!-- Logo -->
        @guest
            <a href="{{ route('home') }}" class="flex items-center gap-2">
                <span class="text-2xl md:text-3xl font-bold text-[#1e3a8a]">Campus</span>
                <span class="text-2xl md:text-3xl font-bold text-emerald-600">Connect</span>
            </a>
        @else
            <a href="{{ route('dashboard') }}" class="flex items-center gap-2">
                <span class="text-2xl md:text-3xl font-bold text-[#1e3a8a]">Campus</span>
                <span class="text-2xl md:text-3xl font-bold text-emerald-600">Connect</span>
            </a>
        @endguest

        <!-- Desktop Menu -->
        <div class="hidden md:flex items-center gap-8 text-sm font-medium">
            @guest
                <a href="{{ route('skills.index') }}" class="hover:text-[#1e3a8a] {{ request()->routeIs('skills.index') ? 'text-[#1e3a8a] font-semibold' : '' }}">Browse Skills</a>
                <a href="{{ route('jobs.index') }}" class="hover:text-[#1e3a8a] {{ request()->routeIs('jobs.index') ? 'text-[#1e3a8a] font-semibold' : '' }}">Browse Jobs</a>
            @else
                <a href="{{ route('skills.index') }}" class="hover:text-[#1e3a8a] {{ request()->routeIs('skills.index') ? 'text-[#1e3a8a] font-semibold' : '' }}">Browse Skills</a>
                <a href="{{ route('jobs.index') }}" class="hover:text-[#1e3a8a] {{ request()->routeIs('jobs.index') ? 'text-[#1e3a8a] font-semibold' : '' }}">Browse Jobs</a>
            @endguest
        </div>

        <!-- Right Side -->
        <div class="flex items-center gap-4">
            @guest
                <!-- Desktop Login Button -->
                <a href="{{ route('login') }}" 
                   class="hidden md:block px-6 py-2.5 text-sm font-semibold border-2 border-[#1e3a8a] text-[#1e3a8a] rounded-2xl hover:bg-[#1e3a8a] hover:text-white transition-all">
                    Login
                </a>
            @else
                <!-- Desktop Logged-in User View -->
                <div class="hidden md:flex items-center gap-4">
                    <!-- Notification Bell -->
                    <div class="relative group">
                        <button onclick="toggleNotificationDropdown()" class="relative hover:text-[#1e3a8a]">
                            <i class="fa-solid fa-bell text-xl"></i>
                            @if(auth()->user()->unreadNotificationCount() > 0)
                                <span class="absolute -top-1 -right-1 bg-red-500 text-white text-[10px] w-4 h-4 flex items-center justify-center rounded-full">
                                    {{ auth()->user()->unreadNotificationCount() > 9 ? '9+' : auth()->user()->unreadNotificationCount() }}
                                </span>
                            @endif
                        </button>
                        
                        <!-- Notification Dropdown -->
                        <div id="notificationDropdown" class="absolute right-0 mt-3 w-80 bg-white rounded-2xl shadow-xl border py-2 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all z-50">
                            <div class="px-4 py-3 border-b border-gray-100">
                                <div class="flex items-center justify-between">
                                    <h3 class="font-semibold text-gray-900">Notifications</h3>
                                    @if(auth()->user()->unreadNotificationCount() > 0)
                                        <form action="{{ route('notifications.readAll') }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="text-xs text-[#1e3a8a] hover:text-[#0f2b5e]">Mark all read</button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                            
                            <div class="max-h-80 overflow-y-auto">
                                @auth
                                    @php
                                        $recentNotifications = auth()->user()->notifications()->latest()->take(5)->get();
                                    @endphp
                                    @if($recentNotifications->count() > 0)
                                        @foreach($recentNotifications as $notification)
                                            <div class="px-4 py-3 hover:bg-gray-50 border-b border-gray-50 {{ !$notification->is_read ? 'bg-blue-50' : '' }}">
                                                <div class="flex items-start gap-3">
                                                    <div class="flex-1">
                                                        <p class="text-sm font-medium text-gray-900">{{ $notification->title }}</p>
                                                        <p class="text-xs text-gray-600 mt-1">{{ $notification->message }}</p>
                                                        <p class="text-xs text-gray-400 mt-1">{{ $notification->created_at->diffForHumans() }}</p>
                                                    </div>
                                                    @if(!$notification->is_read)
                                                        <form action="{{ route('notifications.read', $notification->id) }}" method="POST" class="inline">
                                                            @csrf
                                                            <button type="submit" class="text-blue-600 hover:text-blue-800">
                                                                <i class="fa-solid fa-circle text-xs"></i>
                                                            </button>
                                                        </form>
                                                    @endif
                                                </div>
                                                @if($notification->url)
                                                    <a href="{{ $notification->url }}" class="block mt-2 text-xs text-[#1e3a8a] hover:text-[#0f2b5e]">View →</a>
                                                @endif
                                            </div>
                                        @endforeach
                                    @else
                                        <div class="px-4 py-6 text-center text-gray-500">
                                            <i class="fa-solid fa-bell text-2xl mb-2"></i>
                                            <p class="text-sm">No notifications</p>
                                        </div>
                                    @endif
                                @endauth
                            </div>
                            
                            <div class="px-4 py-2 border-t border-gray-100">
                                <a href="{{ route('notifications.index') }}" class="block text-center text-sm text-[#1e3a8a] hover:text-[#0f2b5e]">View all notifications</a>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Messages Button -->
                    <a href="{{ route('messages.index') }}" class="relative hover:text-[#1e3a8a]">
                        <i class="fa-solid fa-envelope text-xl"></i>
                        @if(auth()->user()->unreadCount() > 0)
                            <span class="absolute -top-1 -right-1 bg-red-500 text-white text-[10px] w-4 h-4 flex items-center justify-center rounded-full">
                                {{ auth()->user()->unreadCount() > 9 ? '9+' : auth()->user()->unreadCount() }}
                            </span>
                        @endif
                    </a>

                    <!-- Avatar Dropdown -->
                    <div class="relative group">
                        <button class="flex items-center gap-3" onclick="toggleDropdown(event)">
                            <div class="w-9 h-9 bg-gray-200 rounded-2xl overflow-hidden border flex items-center justify-center">
                                @if(auth()->user()->passport_photo)
                                    <img src="{{ asset('storage/' . auth()->user()->passport_photo) }}" alt="Profile" class="w-full h-full object-cover">
                                @else
                                    <i class="fa-solid fa-circle-user text-2xl text-gray-500"></i>
                                @endif
                            </div>
                            <div class="text-left">
                                <p class="font-semibold text-sm">{{ auth()->user()->first_name }}</p>
                                <p class="text-xs text-gray-500">{{ ucfirst(auth()->user()->role) }}</p>
                            </div>
                        </button>

                        <div id="profile-dropdown" class="absolute right-0 mt-3 w-56 bg-white rounded-2xl shadow-xl border py-2 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all z-50">
                            <a href="{{ route('profile.show', auth()->id()) }}" class="flex items-center gap-3 px-5 py-3 hover:bg-gray-50">
                                <i class="fa-solid fa-user"></i>
                                <span>My Profile</span>
                            </a>
                            <a href="#" class="flex items-center gap-3 px-5 py-3 hover:bg-gray-50">
                                <i class="fa-solid fa-cog"></i>
                                <span>Settings</span>
                            </a>
                            <a href="{{ route('notifications.index') }}" class="flex items-center gap-3 px-5 py-3 hover:bg-gray-50">
                                <i class="fa-solid fa-bell"></i>
                                <span>Notifications</span>
                                @if(auth()->user()->unreadNotificationCount() > 0)
                                    <span class="bg-red-500 text-white text-xs px-2 py-0.5 rounded-full">
                                        {{ auth()->user()->unreadNotificationCount() > 9 ? '9+' : auth()->user()->unreadNotificationCount() }}
                                    </span>
                                @endif
                            </a>
                            <a href="{{ route('messages.index') }}" class="flex items-center gap-3 px-5 py-3 hover:bg-gray-50">
                                <i class="fa-solid fa-envelope"></i>
                                <span>Messages</span>
                                @if(auth()->user()->unreadCount() > 0)
                                    <span class="bg-red-500 text-white text-xs px-2 py-0.5 rounded-full">
                                        {{ auth()->user()->unreadCount() > 9 ? '9+' : auth()->user()->unreadCount() }}
                                    </span>
                                @endif
                            </a>
                            <div class="border-t my-2"></div>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="flex items-center gap-3 px-5 py-3 text-red-600 hover:bg-red-50 w-full text-left">
                                    <i class="fa-solid fa-arrow-right-from-bracket"></i>
                                    <span>Logout</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endguest

            <!-- Mobile Hamburger Menu -->
            <button type="button" onclick="toggleMobileMenu()" class="md:hidden p-2 rounded-lg hover:bg-gray-100 transition-colors">
                <i id="hamburger-icon" class="fa-solid fa-bars text-xl text-gray-700"></i>
                <i id="close-icon" class="fa-solid fa-times text-xl text-gray-700 hidden"></i>
            </button>
        </div>
    </div>
</nav>

<!-- Mobile Navigation Slide-out -->
<div id="mobile-menu" class="fixed inset-0 z-30 md:hidden hidden">
    <!-- Backdrop -->
    <div class="absolute inset-0 bg-black bg-opacity-50" onclick="toggleMobileMenu()"></div>
    
    <!-- Slide-out Panel -->
    <div class="absolute right-0 top-0 h-full w-80 bg-white shadow-none transform translate-x-full transition-transform duration-300 ease-in-out">
        <div class="flex flex-col h-full">
            <!-- Header -->
            <div class="flex items-center justify-between p-6 border-b">
                <h2 class="text-lg font-semibold text-gray-900">Menu</h2>
                <button onclick="toggleMobileMenu()" class="p-2 rounded-lg hover:bg-gray-100">
                    <i class="fa-solid fa-times text-xl text-gray-700"></i>
                </button>
            </div>
            
            <!-- Navigation Links -->
            <div class="flex-1 overflow-y-auto p-6">
                @guest
                    <!-- Guest Navigation -->
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
                            <a href="{{ route('login') }}" 
                               class="w-full flex items-center justify-center gap-2 px-6 py-3 bg-[#1e3a8a] text-white font-semibold rounded-xl hover:bg-[#0f2b5e] transition-colors"
                               onclick="toggleMobileMenu()">
                                <i class="fa-solid fa-sign-in-alt"></i>
                                Login
                            </a>
                        </div>
                        
                        <div class="text-center">
                            <p class="text-sm text-gray-500">Don't have an account?</p>
                            <a href="{{ route('register') }}" 
                               class="text-sm font-medium text-[#1e3a8a] hover:underline"
                               onclick="toggleMobileMenu()">
                                Sign up
                            </a>
                        </div>
                    </div>
                @else
                    <!-- Authenticated User Navigation -->
                    <div class="space-y-4">
                        <!-- User Profile Section -->
                        <div class="flex items-center gap-4 p-4 bg-gray-50 rounded-xl">
                            <div class="w-12 h-12 bg-gray-200 rounded-full overflow-hidden border flex items-center justify-center">
                                @if(auth()->user()->passport_photo)
                                    <img src="{{ asset('storage/' . auth()->user()->passport_photo) }}" alt="Profile" class="w-full h-full object-cover">
                                @else
                                    <i class="fa-solid fa-circle-user text-2xl text-gray-500"></i>
                                @endif
                            </div>
                            <div>
                                <p class="font-semibold text-gray-900">{{ auth()->user()->first_name }} {{ auth()->user()->last_name }}</p>
                                <p class="text-sm text-gray-500">{{ ucfirst(auth()->user()->role) }}</p>
                            </div>
                        </div>
                        
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
                        
                        <div class="border-t pt-4">
                            <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-3">Account</h3>
                            
                            <a href="{{ route('profile.show', auth()->id()) }}" 
                               class="flex items-center gap-3 p-3 rounded-lg hover:bg-gray-50 transition-colors"
                               onclick="toggleMobileMenu()">
                                <i class="fa-solid fa-user text-lg"></i>
                                <span class="font-medium">My Profile</span>
                            </a>
                            
                            <a href="#" class="flex items-center gap-3 p-3 rounded-lg hover:bg-gray-50 transition-colors">
                                <i class="fa-solid fa-cog text-lg"></i>
                                <span class="font-medium">Settings</span>
                            </a>
                            
                            <a href="#" class="flex items-center gap-3 p-3 rounded-lg hover:bg-gray-50 transition-colors">
                                <i class="fa-solid fa-bell text-lg"></i>
                                <span class="font-medium">Notifications</span>
                                <span class="ml-auto bg-red-500 text-white text-xs px-2 py-1 rounded-full">3</span>
                            </a>
                            
                            <a href="#" class="flex items-center gap-3 p-3 rounded-lg hover:bg-gray-50 transition-colors">
                                <i class="fa-solid fa-envelope text-lg"></i>
                                <span class="font-medium">Messages</span>
                            </a>
                            
                            <form method="POST" action="{{ route('logout') }}" class="pt-4">
                                @csrf
                                <button type="submit" class="w-full flex items-center gap-3 p-3 text-red-600 hover:bg-red-50 rounded-lg transition-colors">
                                    <i class="fa-solid fa-arrow-right-from-bracket text-lg"></i>
                                    <span class="font-medium">Logout</span>
                                </button>
                            </form>
                        </div>
                    </div>
                @endguest
            </div>
        </div>
    </div>
</div>

<script>
function toggleDropdown(event) {
    event.stopPropagation();
    const dropdown = document.getElementById('profile-dropdown');
    
    if (dropdown.classList.contains('opacity-100')) {
        dropdown.classList.remove('opacity-100');
        dropdown.classList.add('opacity-0', 'invisible');
    } else {
        dropdown.classList.remove('opacity-0', 'invisible');
        dropdown.classList.add('opacity-100');
    }
}

function toggleMobileMenu() {
    const mobileMenu = document.getElementById('mobile-menu');
    const hamburgerIcon = document.getElementById('hamburger-icon');
    const closeIcon = document.getElementById('close-icon');
    const slidePanel = mobileMenu.querySelector('.transform');
    
    if (mobileMenu.classList.contains('hidden')) {
        // Open menu
        mobileMenu.classList.remove('hidden');
        hamburgerIcon.classList.add('hidden');
        closeIcon.classList.remove('hidden');
        slidePanel.classList.remove('translate-x-full');
        document.body.style.overflow = 'hidden';
    } else {
        // Close menu
        mobileMenu.classList.add('hidden');
        hamburgerIcon.classList.remove('hidden');
        closeIcon.classList.add('hidden');
        slidePanel.classList.add('translate-x-full');
        document.body.style.overflow = 'auto';
    }
}

// Close dropdown when clicking outside
document.addEventListener('click', function(event) {
    const dropdown = document.getElementById('profile-dropdown');
    const button = event.target.closest('.relative.group');
    
    if (dropdown && !button && !dropdown.contains(event.target)) {
        dropdown.classList.remove('opacity-100');
        dropdown.classList.add('opacity-0', 'invisible');
    }
});

// Close dropdown with Escape key
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        const dropdown = document.getElementById('profile-dropdown');
        const mobileMenu = document.getElementById('mobile-menu');
        
        if (dropdown) {
            dropdown.classList.remove('opacity-100');
            dropdown.classList.add('opacity-0', 'invisible');
        }
        
        if (mobileMenu && !mobileMenu.classList.contains('hidden')) {
            toggleMobileMenu();
        }
    }
});
</script>
{{-- <nav class="bg-white border-b sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-6 py-5 flex items-center justify-between">
        <!-- Logo -->
        <a href="{{ route('home') }}" class="flex items-center gap-2">
            <span class="text-3xl font-bold text-[#1e3a8a]">Campus</span>
            <span class="text-3xl font-bold text-emerald-600">Connect</span>
        </a>

        <!-- Menu -->
        <div class="hidden md:flex items-center gap-8 text-sm font-medium">
            <a href="{{ route('skills.index') }}" class="text-[#1e3a8a] hover:text-blue-700 transition-colors">Browse Skills</a>
            <a href="{{ route('jobs.index') }}" class="text-[#1e3a8a] hover:text-blue-700 transition-colors">Browse Jobs</a>
        </div>

        <!-- Right side -->
        <div class="flex items-center gap-4">
            @guest
                <a href="{{ route('login') }}" 
                   class="px-6 py-2.5 text-sm font-semibold border-2 border-[#1e3a8a] text-[#1e3a8a] rounded-2xl hover:bg-[#1e3a8a] hover:text-white transition-all">
                    Login
                </a>
            @else
                <!-- Logged-in icons (will show later) -->
                <button class="w-9 h-9 flex items-center justify-center text-gray-600 hover:text-gray-900">
                    <i class="fa-solid fa-bell text-xl"></i>
                </button>
                <button class="w-9 h-9 flex items-center justify-center text-gray-600 hover:text-gray-900">
                    <i class="fa-solid fa-envelope text-xl"></i>
                </button>
                <a href="#" class="w-9 h-9 rounded-2xl bg-gray-100 flex items-center justify-center">
                    <i class="fa-solid fa-circle-user text-2xl text-gray-700"></i>
                </a>
            @endguest

            <!-- Mobile menu button (optional) -->
            <button class="md:hidden text-2xl text-gray-700">
                <i class="fa-solid fa-bars"></i>
            </button>
        </div>
    </div>
</nav> --}}
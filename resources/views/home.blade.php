@extends('layouts.guest')

@section('content')
    <x-navbar />

    <!-- HERO SECTION with enhanced animations -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 pt-10 sm:pt-14 pb-16 sm:pb-20">
        <div class="grid lg:grid-cols-2 gap-8 lg:gap-12 items-center">
            
            <!-- Left Content with fade-in animation -->
            <div class="space-y-6 sm:space-y-8 animate-fade-in-up">
                <div class="space-y-3">
                    <div class="inline-flex items-center px-3 py-1 rounded-full bg-blue-100 text-[#1e3a8a] text-sm font-semibold mb-2">
                        <i class="fa-solid fa-graduation-cap mr-2"></i>
                        Trusted Campus Community
                    </div>
                    <h1 class="text-4xl sm:text-5xl lg:text-6xl leading-tight font-bold">
                        <span class="text-[#1e3a8a]">Find a Skill.</span><br>
                        <span class="text-[#1e3a8a]">Land a Job.</span><br>
                        <span class="text-gray-500 text-3xl sm:text-4xl lg:text-5xl font-normal">All on Campus.</span>
                    </h1>
                </div>
                
                <p class="text-base sm:text-lg text-gray-600 max-w-md leading-relaxed">
                    Whether you need something done or you're ready to earn —
                    your university community has you covered.
                </p>
                
                <!-- Enhanced Search Bar with improved UX -->
                <div class="w-full max-w-lg space-y-4">
                    <!-- Improved Toggle with ARIA labels -->
                    <div class="flex gap-2 bg-gray-100 p-1 rounded-2xl w-fit" role="tablist">
                        <button id="tab-skills"
                                onclick="switchTab('skills')"
                                role="tab"
                                aria-selected="true"
                                class="px-5 py-2.5 rounded-xl text-sm font-semibold transition-all duration-200 bg-[#1e3a8a] text-white shadow-md">
                            <i class="fa-solid fa-magnifying-glass mr-2"></i>Find a Skill
                        </button>
                        <button id="tab-jobs"
                                onclick="switchTab('jobs')"
                                role="tab"
                                aria-selected="false"
                                class="px-5 py-2.5 rounded-xl text-sm font-semibold text-gray-600  transition-all duration-200">
                            <i class="fa-solid fa-briefcase mr-2"></i>Find a Job
                        </button>
                    </div>
                    
                    <!-- Enhanced Search form with loading state -->
                    <form id="search-form" action="{{ route('search') }}" method="GET" class="flex w-full gap-3">
                        <input type="hidden" name="type" id="search-type" value="skills">
                        <div class="flex-1 relative group">
                            <i class="fa-solid fa-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-[#1e3a8a] transition-colors"></i>
                            <input type="text" name="q"
                                   id="search-input"
                                   placeholder="Search skills, e.g. Graphic Design..."
                                   class="w-full pl-11 pr-4 py-4 bg-white border-2 border-gray-200 rounded-2xl focus:outline-none focus:border-[#1e3a8a] focus:ring-2 focus:ring-[#1e3a8a]/20 transition-all text-base">
                        </div>
                        <button type="submit"
                                id="search-button"
                                class="px-6 sm:px-7 py-4 bg-[#1e3a8a] text-white font-semibold rounded-2xl hover:bg-blue-900 transition-all duration-200 transform hover:scale-105 shadow-md">
                            Search
                        </button>
                    </form>
                </div>
                
                <!-- Enhanced CTAs with hover effects -->
                <div class="grid grid-cols-2 gap-4 max-w-lg">
                    <div class="bg-gradient-to-br from-blue-50 to-blue-100/50 border border-blue-200 rounded-2xl p-5 space-y-3 hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1">
                        <div class="w-11 h-11 bg-gradient-to-br from-[#1e3a8a] to-blue-700 rounded-xl flex items-center justify-center shadow-md">
                            <i class="fa-solid fa-briefcase text-white text-sm"></i>
                        </div>
                        <p class="text-sm text-gray-600 leading-snug">Need something done? Browse campus talent.</p>
                        <a href="{{ route('skills.index') }}"
                           class="inline-flex items-center text-sm font-semibold text-[#1e3a8a] hover:gap-2 transition-all group">
                            Browse Skills 
                            <i class="fa-solid fa-arrow-right ml-1 group-hover:ml-2 transition-all"></i>
                        </a>
                    </div>
                    
                    <div class="bg-gradient-to-br from-emerald-50 to-emerald-100/50 border border-emerald-200 rounded-2xl p-5 space-y-3 hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1">
                        <div class="w-11 h-11 bg-gradient-to-br from-emerald-700 to-emerald-600 rounded-xl flex items-center justify-center shadow-md">
                            <i class="fa-solid fa-file-lines text-white text-sm"></i>
                        </div>
                        <p class="text-sm text-gray-600 leading-snug">Ready to earn? Find jobs posted by the community.</p>
                        <a href="{{ route('jobs.index') }}"
                           class="inline-flex items-center text-sm font-semibold text-emerald-700 hover:gap-2 transition-all group">
                            Browse Jobs 
                            <i class="fa-solid fa-arrow-right ml-1 group-hover:ml-2 transition-all"></i>
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- Enhanced Right Image with lazy loading -->
            <div class="relative animate-fade-in-up animation-delay-200">
                <div class="relative rounded-3xl overflow-hidden shadow-2xl">
                    <img src="{{ asset('storage/images/campus-hero.png') }}"
                         alt="Students collaborating on Campus Connect platform"
                         class="w-full object-cover aspect-video lazy-load rounded-3xl shadow-2xl"
                     style="height: 680px;"
                         loading="lazy">
                    <div class="absolute inset-0 bg-gradient-to-tr from-[#1e3a8a]/20 to-transparent"></div>
                </div>
                
                <!-- Animated Verified Badge -->
                <div class="absolute bottom-6 right-6 bg-emerald-700 text-white text-sm font-medium px-4 py-2.5 rounded-2xl shadow-lg flex items-center gap-2 animate-pulse-slow">
                    <i class="fa-solid fa-circle-check fa-fw"></i>
                    <span>VERIFIED CAMPUS SERVICE</span>
                    {{-- <span class="font-semibold hidden sm:inline">Graphic Design</span> --}}
                </div>
                
                <!-- Decorative elements -->
                <div class="absolute -top-4 -left-4 w-20 h-20 bg-blue-400/20 rounded-full blur-2xl"></div>
                <div class="absolute -bottom-8 -right-8 w-32 h-32 bg-emerald-400/20 rounded-full blur-2xl"></div>
            </div>
        </div>
    </div>
    
    <!-- ENHANCED TWO SIDES BANNER -->
    <div class="bg-gradient-to-r from-[#1e3a8a] to-blue-800 py-12 relative overflow-hidden">
        <div class="absolute inset-0 bg-grid-white/5 bg-[length:20px_20px]"></div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 relative">
            <div class="grid md:grid-cols-2 divide-y md:divide-y-0 md:divide-x divide-blue-600/50">
                
                <div class="flex items-center gap-6 py-6 md:py-0 md:pr-12 group">
                    <div class="w-14 h-14 bg-white/10 rounded-2xl flex items-center justify-center shrink-0 group-hover:scale-110 transition-transform">
                        <i class="fa-solid fa-star text-yellow-300 text-2xl"></i>
                    </div>
                    <div>
                        <h3 class="text-white font-bold text-xl mb-1">Got a skill? Earn from it.</h3>
                        <p class="text-blue-200 text-sm leading-relaxed">Post your gig and start getting orders from students and staff around campus.</p>
                        <a href="{{ route('register') }}?role=vendor"
                           class="inline-flex items-center mt-3 text-sm font-semibold text-yellow-300 hover:text-yellow-200 transition-colors group-hover:gap-2">
                            Post a Skill 
                            <i class="fa-solid fa-arrow-right ml-1 group-hover:ml-2 transition-all"></i>
                        </a>
                    </div>
                </div>
                
                <div class="flex items-center gap-6 py-6 md:py-0 md:pl-12 group">
                    <div class="w-14 h-14 bg-white/10 rounded-2xl flex items-center justify-center shrink-0 group-hover:scale-110 transition-transform">
                        <i class="fa-solid fa-bullhorn text-emerald-300 text-2xl"></i>
                    </div>
                    <div>
                        <h3 class="text-white font-bold text-xl mb-1">Have a task? Post a job.</h3>
                        <p class="text-blue-200 text-sm leading-relaxed">Describe what you need, set your budget, and let campus talent come to you.</p>
                        <a href="#"
                           class="inline-flex items-center mt-3 text-sm font-semibold text-emerald-300 hover:text-emerald-200 transition-colors group-hover:gap-2">
                            Post a Job 
                            <i class="fa-solid fa-arrow-right ml-1 group-hover:ml-2 transition-all"></i>
                        </a>
                    </div>
                </div>
                
            </div>
        </div>
    </div>
    
    <!-- ENHANCED POPULAR CATEGORIES with carousel option -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 py-12 sm:py-16">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-end mb-8 sm:mb-10 gap-4">
            <div>
                <h2 class="text-3xl sm:text-4xl font-bold text-[#1e3a8a]">Popular Categories</h2>
                <p class="text-gray-600 mt-2">Discover the talent flourishing within your university.</p>
            </div>
            <a href="{{ route('skills.index') }}" class="inline-flex items-center text-sm font-semibold text-[#1e3a8a] hover:gap-2 transition-all group">
                View all categories 
                <i class="fa-solid fa-arrow-right ml-1 group-hover:ml-2 transition-all"></i>
            </a>
        </div>
        
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @php
                $categories = [
                    ['name' => 'Graphic Design', 'image' => 'graphic-design-homepage.png', 'desc' => 'Logo design, branding, and campus flyers', 'color' => 'from-purple-600 to-pink-600'],
                    ['name' => 'Hairdressing', 'image' => 'hairdressing-homepage.png', 'desc' => 'Get stylish cuts and treatments', 'color' => 'from-pink-600 to-rose-600'],
                    ['name' => 'Tailoring', 'image' => 'tailoring-homepage.png', 'desc' => 'Custom clothing alterations and designs', 'color' => 'from-indigo-600 to-blue-600'],
                    ['name' => 'Web Development', 'image' => 'web-development-homepage.png', 'desc' => 'Build responsive websites and applications', 'color' => 'from-blue-600 to-cyan-600'],
                    ['name' => 'Academic Tutoring', 'image' => 'Academic-Tutoring-homepage.png', 'desc' => 'Exams and course prep', 'color' => 'from-green-600 to-emerald-600'],
                    ['name' => 'Gadget Repair', 'image' => 'gadget-repair-homepage.png', 'desc' => 'Fix your devices with expert technicians', 'color' => 'from-orange-600 to-red-600']
                ];
            @endphp
            
            @foreach($categories as $category)
            <div class="group relative overflow-hidden rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-2">
                <img src="{{ asset('storage/images/' . $category['image']) }}" 
                     alt="{{ $category['name'] }} services on Campus Connect"
                     class="w-full h-64 object-cover group-hover:scale-110 transition-transform duration-700 lazy-load"
                     loading="lazy">
                <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/40 to-transparent"></div>
                <div class="absolute bottom-0 left-0 right-0 p-6">
                    <h3 class="text-white text-2xl font-bold mb-1">{{ $category['name'] }}</h3>
                    <p class="text-white/90 text-sm">{{ $category['desc'] }}</p>
                    <div class="mt-3 opacity-0 group-hover:opacity-100 transition-opacity">
                        <span class="inline-flex items-center text-white text-sm font-semibold">
                            Explore 
                            <i class="fa-solid fa-arrow-right ml-2"></i>
                        </span>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    
    <!-- ENHANCED HOW IT WORKS section -->
    <div class="bg-gradient-to-b from-gray-50 to-white py-16 sm:py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6">
            <div class="text-center mb-12 sm:mb-16">
                <div class="inline-flex items-center px-3 py-1 rounded-full bg-[#1e3a8a]/10 text-[#1e3a8a] text-sm font-semibold mb-4">
                    <i class="fa-solid fa-rocket mr-2"></i>
                    Get Started in 3 Steps
                </div>
                <h2 class="text-3xl sm:text-4xl font-bold text-[#1e3a8a] mb-3">Simple. Secure. Campus-Focused.</h2>
                <p class="text-gray-600 text-lg">Two ways to be part of the Campus Connect community.</p>
            </div>
            
            <div class="grid md:grid-cols-2 gap-6 lg:gap-8">
                
                <!-- Flow 1: I need something done -->
                <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-xl hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1 border border-gray-100">
                    <div class="flex items-center gap-3 mb-8">
                        <div class="w-12 h-12 bg-gradient-to-br from-[#1e3a8a] to-blue-700 rounded-xl flex items-center justify-center shadow-md">
                            <i class="fa-solid fa-magnifying-glass text-white text-lg"></i>
                        </div>
                        <h3 class="text-xl font-bold text-[#1e3a8a]">I need something done</h3>
                    </div>
                    <div class="space-y-6">
                        @foreach([
                            ['icon' => 'fa-envelope', 'title' => 'Sign up with your university email', 'desc' => 'Only verified students and staff can join.'],
                            ['icon' => 'fa-eye', 'title' => 'Browse skills or post a job', 'desc' => 'Find a gig that fits or describe what you need.'],
                            ['icon' => 'fa-credit-card', 'title' => 'Pay, track, and leave a review', 'desc' => 'Secure payments and full order tracking throughout.']
                        ] as $index => $step)
                        <div class="flex gap-4 items-start group">
                            <div class="relative">
                                <div class="w-8 h-8 bg-blue-100 text-[#1e3a8a] font-bold text-sm rounded-full flex items-center justify-center shrink-0 group-hover:scale-110 transition-transform">
                                    {{ $index + 1 }}
                                </div>
                                @if(!$loop->last)
                                <div class="absolute top-8 left-4 w-0.5 h-8 bg-blue-200"></div>
                                @endif
                            </div>
                            <div class="flex-1">
                                <p class="font-bold text-gray-800 mb-1">{{ $step['title'] }}</p>
                                <p class="text-sm text-gray-500">{{ $step['desc'] }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    <a href="{{ route('skills.index') }}"
                       class="mt-8 inline-flex items-center px-6 py-3 bg-gradient-to-r from-[#1e3a8a] to-blue-700 text-white text-sm font-semibold rounded-2xl hover:shadow-lg transition-all transform hover:scale-105">
                        Browse Skills
                        <i class="fa-solid fa-arrow-right ml-2"></i>
                    </a>
                </div>
                
                <!-- Flow 2: I want to earn -->
                <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-xl hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1 border border-gray-100">
                    <div class="flex items-center gap-3 mb-8">
                        <div class="w-12 h-12 bg-gradient-to-br from-emerald-700 to-emerald-600 rounded-xl flex items-center justify-center shadow-md">
                            <i class="fa-solid fa-coins text-white text-lg"></i>
                        </div>
                        <h3 class="text-xl font-bold text-emerald-700">I want to earn</h3>
                    </div>
                    <div class="space-y-6">
                        @foreach([
                            ['icon' => 'fa-user-plus', 'title' => 'Create your vendor profile', 'desc' => 'Set up your profile with your skills and experience.'],
                            ['icon' => 'fa-bullhorn', 'title' => 'Post a gig or apply for jobs', 'desc' => 'List your services with packages, or apply to job listings.'],
                            ['icon' => 'fa-trophy', 'title' => 'Deliver, get paid, build reputation', 'desc' => 'Complete orders, earn ratings, and grow your campus brand.']
                        ] as $index => $step)
                        <div class="flex gap-4 items-start group">
                            <div class="relative">
                                <div class="w-8 h-8 bg-emerald-100 text-emerald-700 font-bold text-sm rounded-full flex items-center justify-center shrink-0 group-hover:scale-110 transition-transform">
                                    {{ $index + 1 }}
                                </div>
                                @if(!$loop->last)
                                <div class="absolute top-8 left-4 w-0.5 h-8 bg-emerald-200"></div>
                                @endif
                            </div>
                            <div class="flex-1">
                                <p class="font-bold text-gray-800 mb-1">{{ $step['title'] }}</p>
                                <p class="text-sm text-gray-500">{{ $step['desc'] }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    <a href="{{ route('register') }}?role=vendor"
                       class="mt-8 inline-flex items-center px-6 py-3 bg-gradient-to-r from-emerald-700 to-emerald-600 text-white text-sm font-semibold rounded-2xl hover:shadow-lg transition-all transform hover:scale-105">
                        Start Earning
                        <i class="fa-solid fa-arrow-right ml-2"></i>
                    </a>
                </div>
                
            </div>
        </div>
    </div>
    
    <!-- ENHANCED TESTIMONIALS with hover effects -->
    <div class="bg-white py-16 sm:py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6">
            <div class="text-center mb-12">
                <h2 class="text-3xl sm:text-4xl font-bold text-[#1e3a8a] mb-3">Voices from Campus</h2>
                <p class="text-gray-600 text-lg">Trusted by students, faculty, and staff across campus</p>
            </div>
            
            <div class="grid md:grid-cols-3 gap-6 lg:gap-8">
                @php
                    $testimonials = [
                        ['name' => 'Amaka O.', 'role' => 'Computer Science Student', 'image' => 'Amaka-O.png', 'text' => 'Found a web developer for my final year project within 24 hours. The experience was seamless and highly professional.', 'rating' => 5],
                        ['name' => 'Tunde E.', 'role' => 'Engineering Staff', 'image' => 'Tunde-E.png', 'text' => 'I\'ve been offering graphic design services and it has helped me cover my materials costs. Highly recommend Campus Connect!', 'rating' => 5],
                        ['name' => 'Dr. Bello S.', 'role' => 'Faculty member', 'image' => 'Dr-Bello.png', 'text' => 'Excellent platform for finding trustworthy student helpers. I got my office gadgets repaired in no time.', 'rating' => 5]
                    ];
                @endphp
                
                @foreach($testimonials as $testimonial)
                <div class="bg-gradient-to-br from-gray-50 to-white p-6 sm:p-8 rounded-3xl shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 border border-gray-100">
                    <div class="flex gap-1 text-yellow-400 mb-4 text-lg">
                        @for($i = 0; $i < $testimonial['rating']; $i++)
                            <i class="fa-solid fa-star"></i>
                        @endfor
                    </div>
                    <p class="text-gray-700 italic mb-6 leading-relaxed">"{{ $testimonial['text'] }}"</p>
                    <div class="flex items-center gap-3">
                        <img src="{{ asset('storage/images/' . $testimonial['image']) }}"
                             alt="{{ $testimonial['name'] }}"
                             class="w-12 h-12 object-cover rounded-full border-2 border-[#1e3a8a]/20 lazy-load"
                             loading="lazy">
                        <div>
                            <p class="font-bold text-gray-800">{{ $testimonial['name'] }}</p>
                            <p class="text-sm text-gray-500">{{ $testimonial['role'] }}</p>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            
            <!-- Trust indicators -->
            <div class="mt-12 flex flex-wrap justify-center gap-8 items-center">
                <div class="flex items-center gap-2 text-gray-500">
                    <i class="fa-solid fa-users text-2xl text-[#1e3a8a]"></i>
                    <span class="font-semibold">500+ Active Users</span>
                </div>
                <div class="flex items-center gap-2 text-gray-500">
                    <i class="fa-solid fa-check-circle text-2xl text-emerald-500"></i>
                    <span class="font-semibold">98% Satisfaction Rate</span>
                </div>
                <div class="flex items-center gap-2 text-gray-500">
                    <i class="fa-solid fa-clock text-2xl text-[#1e3a8a]"></i>
                    <span class="font-semibold">24hr Response Time</span>
                </div>
            </div>
        </div>
    </div>
    
    <!-- ENHANCED CTA SECTION -->
    <div class="relative overflow-hidden bg-gradient-to-r from-[#1e3a8a] via-blue-800 to-[#1e3a8a] py-16 sm:py-20">
        <div class="absolute inset-0 bg-grid-white/10 bg-[length:30px_30px]"></div>
        <div class="absolute -top-40 -right-40 w-80 h-80 bg-blue-400/20 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-40 -left-40 w-80 h-80 bg-emerald-400/20 rounded-full blur-3xl"></div>
        
        <div class="max-w-4xl mx-auto text-center px-4 sm:px-6 relative">
            <h2 class="text-3xl sm:text-4xl lg:text-5xl font-bold text-white mb-4 animate-fade-in-up">
                Ready to get started?
            </h2>
            <p class="text-blue-200 text-base sm:text-lg mb-8 sm:mb-10 animate-fade-in-up animation-delay-200">
                Join hundreds of university members already trading skills and finding opportunities on campus.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center animate-fade-in-up animation-delay-400">
                <a href="{{ route('skills.index') }}"
                   class="group px-6 sm:px-8 py-4 bg-white text-[#1e3a8a] font-bold rounded-2xl hover:bg-gray-100 transition-all transform hover:scale-105 shadow-lg">
                    <i class="fa-solid fa-briefcase mr-2"></i>Find a Skill
                    <i class="fa-solid fa-arrow-right ml-2 opacity-0 group-hover:opacity-100 transition-all"></i>
                </a>
                <a href="{{ route('jobs.index') }}"
                   class="group px-6 sm:px-8 py-4 bg-emerald-500 text-white font-bold rounded-2xl hover:bg-emerald-400 transition-all transform hover:scale-105 shadow-lg">
                    <i class="fa-solid fa-file-lines mr-2"></i>Browse Jobs
                    <i class="fa-solid fa-arrow-right ml-2 opacity-0 group-hover:opacity-100 transition-all"></i>
                </a>
                <a href="{{ route('register') }}"
                   class="group px-6 sm:px-8 py-4 border-2 border-white/40 text-white font-bold rounded-2xl hover:border-white hover:bg-white/10 transition-all transform hover:scale-105">
                    <i class="fa-solid fa-user-plus mr-2"></i>Join Campus Connect
                    <i class="fa-solid fa-arrow-right ml-2 opacity-0 group-hover:opacity-100 transition-all"></i>
                </a>
            </div>
        </div>
    </div>
    
    <!-- FOOTER -->
    <x-footer />
    
    <!-- Enhanced Tab switch script with loading state -->
    <script>
        function switchTab(tab) {
            const skillsBtn = document.getElementById('tab-skills');
            const jobsBtn = document.getElementById('tab-jobs');
            const input = document.getElementById('search-input');
            const typeField = document.getElementById('search-type');
            const form = document.getElementById('search-form');
            
            if (tab === 'skills') {
                // Update buttons
                skillsBtn.classList.remove('text-gray-600');
                skillsBtn.classList.add('bg-[#1e3a8a]', 'text-white', 'shadow-md');
                skillsBtn.setAttribute('aria-selected', 'true');
                
                jobsBtn.classList.remove('bg-[#1e3a8a]', 'text-white', 'shadow-md');
                jobsBtn.classList.add('text-gray-600');
                jobsBtn.setAttribute('aria-selected', 'false');
                
                // Update input placeholder
                input.placeholder = 'Search skills, e.g. Graphic Design...';
                typeField.value = 'skills';
            } else {
                // Update buttons
                jobsBtn.classList.remove('text-gray-600');
                jobsBtn.classList.add('bg-[#1e3a8a]', 'text-white', 'shadow-md');
                jobsBtn.setAttribute('aria-selected', 'true');
                
                skillsBtn.classList.remove('bg-[#1e3a8a]', 'text-white', 'shadow-md');
                skillsBtn.classList.add('text-gray-600');
                skillsBtn.setAttribute('aria-selected', 'false');
                
                // Update input placeholder
                input.placeholder = 'Search jobs, e.g. Logo Design Needed...';
                typeField.value = 'jobs';
            }
            
            // Clear input when switching tabs
            input.value = '';
            input.focus();
        }
        
        // Reset search button state when page loads
        document.addEventListener('DOMContentLoaded', function() {
            const button = document.getElementById('search-button');
            if (button) {
                button.disabled = false;
                button.innerHTML = '<i class="fa-solid fa-search mr-2"></i>Search';
            }
        });

        // Reset search button state when user navigates back (pageshow event)
        window.addEventListener('pageshow', function(event) {
            const button = document.getElementById('search-button');
            if (button) {
                button.disabled = false;
                button.innerHTML = '<i class="fa-solid fa-search mr-2"></i>Search';
            }
        });

        // Add loading state to form submission
        document.getElementById('search-form')?.addEventListener('submit', function(e) {
            const button = document.getElementById('search-button');
            if (button) {
                button.disabled = true;
                button.innerHTML = '<i class="fa-solid fa-spinner fa-spin mr-2"></i>Searching...';
            }
        });
        
        // Lazy loading images
        if ('IntersectionObserver' in window) {
            const lazyImages = document.querySelectorAll('.lazy-load');
            const imageObserver = new IntersectionObserver((entries, observer) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const img = entry.target;
                        img.classList.remove('lazy-load');
                        imageObserver.unobserve(img);
                    }
                });
            });
            
            lazyImages.forEach(img => imageObserver.observe(img));
        }
    </script>
    
    <style>
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .animate-fade-in-up {
            animation: fadeInUp 0.6s ease-out forwards;
        }
        
        .animation-delay-200 {
            animation-delay: 0.2s;
            opacity: 0;
        }
        
        .animation-delay-400 {
            animation-delay: 0.4s;
            opacity: 0;
        }
        
        @keyframes pulse-slow {
            0%, 100% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.05);
            }
        }
        
        .animate-pulse-slow {
            animation: pulse-slow 2s ease-in-out infinite;
        }
        
        .bg-grid-white\/5 {
            background-image: linear-gradient(to right, rgba(255,255,255,0.05) 1px, transparent 1px),
                              linear-gradient(to bottom, rgba(255,255,255,0.05) 1px, transparent 1px);
        }
        
        .bg-grid-white\/10 {
            background-image: linear-gradient(to right, rgba(255,255,255,0.1) 1px, transparent 1px),
                              linear-gradient(to bottom, rgba(255,255,255,0.1) 1px, transparent 1px);
        }
    </style>
@endsection
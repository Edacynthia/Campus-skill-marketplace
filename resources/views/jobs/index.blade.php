@extends('layouts.guest')

@section('content')
    <x-navbar />

    <!-- HEADER / HERO -->
    <div class="bg-gradient-to-br from-[#1e3a8a]/5 via-white to-gray-50 border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-6 py-16 lg:py-24">
            <div class="max-w-3xl">
                <div class="inline-flex items-center gap-2 bg-[#1e3a8a]/10 px-4 py-2 rounded-full mb-6">
                    <i class="fa-solid fa-graduation-cap text-[#1e3a8a] text-sm"></i>
                    <span class="text-sm font-medium text-[#1e3a8a]">Campus Job Board</span>
                </div>
                <h1 class="text-5xl lg:text-6xl font-bold text-[#1e3a8a] leading-tight">
                    Academic Opportunities
                </h1>
                <p class="text-xl text-gray-600 mt-5 leading-relaxed">
                    Find student gigs, research assistance, campus services, and short-term jobs tailored for your university community.
                </p>
            </div>

            <!-- Search + Filters -->
            <div class="mt-12">
                <!-- Search Bar (Always Visible) -->
                <form method="GET" action="{{ route('jobs.index') }}" class="lg:hidden">
                    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
                        <div class="relative p-6">
                            <div class="relative">
                                <i class="fa-solid fa-search absolute left-5 top-1/2 -translate-y-1/2 text-gray-400 text-lg"></i>
                                <input type="text" 
                                       name="search"
                                       value="{{ request('search') }}"
                                       placeholder="Search jobs (e.g., Calculus tutor, Graphic design, Lab assistant)..."
                                       class="w-full pl-12 pr-32 py-4 bg-gray-50 border border-gray-200 rounded-full focus:outline-none focus:border-[#1e3a8a] focus:ring-2 focus:ring-[#1e3a8a]/20 text-lg transition-all">
                                <button type="submit" 
                                        class="absolute right-2 top-1/2 -translate-y-1/2 px-6 py-2.5 bg-[#1e3a8a] text-white text-sm font-semibold rounded-full hover:bg-[#0f2b5e] transition-all shadow-sm hover:shadow-md active:scale-95">
                                    Search
                                </button>
                            </div>
                        </div>
                    </div>
                </form>

                <!-- Desktop Full Filters -->
                <form method="GET" action="{{ route('jobs.index') }}" class="hidden lg:block">
                    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
                        <!-- Search Bar -->
                        <div class="relative p-6 pb-4">
                            <div class="max-w-2xl mx-auto">
                                <div class="relative">
                                    <i class="fa-solid fa-search absolute left-5 top-1/2 -translate-y-1/2 text-gray-400 text-lg"></i>
                                    <input type="text" 
                                           name="search"
                                           value="{{ request('search') }}"
                                           placeholder="Search jobs (e.g., Calculus tutor, Graphic design, Lab assistant)..."
                                           class="w-full pl-12 pr-32 py-4 bg-gray-50 border border-gray-200 rounded-full focus:outline-none focus:border-[#1e3a8a] focus:ring-2 focus:ring-[#1e3a8a]/20 text-lg transition-all">
                                    <button type="submit" 
                                            class="absolute right-2 top-1/2 -translate-y-1/2 px-6 py-2.5 bg-[#1e3a8a] text-white text-sm font-semibold rounded-full hover:bg-[#0f2b5e] transition-all shadow-sm hover:shadow-md active:scale-95">
                                        Search
                                    </button>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Categories & Filters -->
                        <div class="border-t border-gray-100 p-6 bg-gray-50/50">
                            <div class="max-w-6xl mx-auto">
                                <!-- Categories -->
                                <div class="mb-6">
                                    <h3 class="text-sm font-semibold text-gray-700 mb-3 uppercase tracking-wide">Categories</h3>
                                    <div class="flex flex-wrap gap-2">
                                        @foreach($categories as $category)
                                            <a href="{{ route('jobs.index') }}?{{ http_build_query(array_merge(request()->query(), ['category' => $category])) }}"
                                               class="px-4 py-2 {{ request('category') == $category ? 'bg-[#1e3a8a] text-white shadow-md' : 'bg-white border border-gray-300 hover:border-[#1e3a8a]' }} text-sm font-medium rounded-lg transition-all">
                                                {{ $category }}
                                            </a>
                                        @endforeach
                                    </div>
                                </div>
                                
                                <!-- Advanced Filters -->
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-6 gap-4 items-end">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Sort by</label>
                                        <select name="sort_by" class="w-full border border-gray-200 rounded-lg px-3 py-2.5 focus:outline-none focus:border-[#1e3a8a] bg-white">
                                            <option value="created_at" {{ request('sort_by') == 'created_at' ? 'selected' : '' }}>Latest</option>
                                            <option value="salary_low" {{ request('sort_by') == 'salary_low' ? 'selected' : '' }}>Salary: Low to High</option>
                                            <option value="salary_high" {{ request('sort_by') == 'salary_high' ? 'selected' : '' }}>Salary: High to Low</option>
                                            <option value="deadline" {{ request('sort_by') == 'deadline' ? 'selected' : '' }}>Deadline Soon</option>
                                            <option value="urgency" {{ request('sort_by') == 'urgency' ? 'selected' : '' }}>Urgent First</option>
                                        </select>
                                    </div>
                                    
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Type</label>
                                        <select name="type" class="w-full border border-gray-200 rounded-lg px-3 py-2.5 focus:outline-none focus:border-[#1e3a8a] bg-white">
                                            <option value="">All Types</option>
                                            <option value="on_campus" {{ request('type') == 'on_campus' ? 'selected' : '' }}>On Campus</option>
                                            <option value="off_campus" {{ request('type') == 'off_campus' ? 'selected' : '' }}>Off Campus</option>
                                            <option value="remote" {{ request('type') == 'remote' ? 'selected' : '' }}>Remote</option>
                                        </select>
                                    </div>
                                    
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Min Salary</label>
                                        <input type="number" name="min_salary" placeholder="Min" value="{{ request('min_salary') }}" 
                                               class="w-full border border-gray-200 rounded-lg px-3 py-2.5 focus:outline-none focus:border-[#1e3a8a] bg-white">
                                    </div>
                                    
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Max Salary</label>
                                        <input type="number" name="max_salary" placeholder="Max" value="{{ request('max_salary') }}" 
                                               class="w-full border border-gray-200 rounded-lg px-3 py-2.5 focus:outline-none focus:border-[#1e3a8a] bg-white">
                                    </div>
                                    
                                    <div class="lg:col-span-2 flex gap-3">
                                        <button type="submit" class="flex-1 px-6 py-2.5 bg-[#1e3a8a] text-white font-medium rounded-lg hover:bg-[#0f2b5e] transition-all shadow-sm">
                                            <i class="fa-solid fa-filter mr-2"></i>
                                            Apply Filters
                                        </button>
                                        
                                        @if(request()->hasAny(['search', 'category', 'min_salary', 'max_salary', 'type', 'sort_by']))
                                            <a href="{{ route('jobs.index') }}" class="px-6 py-2.5 border border-gray-300 text-gray-600 font-medium rounded-lg hover:bg-gray-50 transition-all">
                                                <i class="fa-solid fa-times mr-2"></i>
                                                Clear
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
                
                <!-- Mobile Filters Button -->
                <div class="lg:hidden mt-6">
                    <button type="button" onclick="openMobileFilters()" class="w-full px-4 py-3 bg-[#1e3a8a] text-white font-medium rounded-lg hover:bg-[#0f2b5e] transition-all flex items-center justify-center gap-2">
                        <i class="fa-solid fa-filter"></i>
                        Filters
                        @if(request()->hasAny(['search', 'category', 'min_salary', 'max_salary', 'type', 'sort_by']))
                            <span class="bg-white/20 px-2 py-1 rounded-full text-xs">Active</span>
                        @endif
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Mobile Filter Modal -->
    <div id="mobileFilterModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden lg:hidden">
        <div class="absolute inset-x-0 bottom-0 bg-white rounded-t-2xl max-h-[80vh] overflow-y-auto">
            <div class="sticky top-0 bg-white border-b border-gray-200 p-4">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900">Filters</h3>
                    <button onclick="closeMobileFilters()" class="text-gray-400 hover:text-gray-600">
                        <i class="fa-solid fa-times text-xl"></i>
                    </button>
                </div>
            </div>
            
            <form id="mobileFilterForm" class="p-4 space-y-6">
                <!-- Sort By -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Sort by</label>
                    <select id="mobile_sort_by" class="w-full border border-gray-200 rounded-lg px-3 py-2 focus:outline-none focus:border-[#1e3a8a]">
                        <option value="created_at" {{ request('sort_by') == 'created_at' ? 'selected' : '' }}>Latest</option>
                        <option value="salary_low" {{ request('sort_by') == 'salary_low' ? 'selected' : '' }}>Salary: Low to High</option>
                        <option value="salary_high" {{ request('sort_by') == 'salary_high' ? 'selected' : '' }}>Salary: High to Low</option>
                        <option value="deadline" {{ request('sort_by') == 'deadline' ? 'selected' : '' }}>Deadline Soon</option>
                        <option value="urgency" {{ request('sort_by') == 'urgency' ? 'selected' : '' }}>Urgent First</option>
                    </select>
                </div>
                
                <!-- Salary Range -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Salary range</label>
                    <div class="flex items-center gap-2">
                        <input type="number" id="mobile_min_salary" placeholder="Min" value="{{ request('min_salary') }}" 
                               class="flex-1 border border-gray-200 rounded-lg px-3 py-2 focus:outline-none focus:border-[#1e3a8a]">
                        <span class="text-gray-400">-</span>
                        <input type="number" id="mobile_max_salary" placeholder="Max" value="{{ request('max_salary') }}" 
                               class="flex-1 border border-gray-200 rounded-lg px-3 py-2 focus:outline-none focus:border-[#1e3a8a]">
                    </div>
                </div>
                
                <!-- Job Type -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Job type</label>
                    <select id="mobile_type" class="w-full border border-gray-200 rounded-lg px-3 py-2 focus:outline-none focus:border-[#1e3a8a]">
                        <option value="">All Types</option>
                        <option value="on_campus" {{ request('type') == 'on_campus' ? 'selected' : '' }}>On Campus</option>
                        <option value="off_campus" {{ request('type') == 'off_campus' ? 'selected' : '' }}>Off Campus</option>
                        <option value="remote" {{ request('type') == 'remote' ? 'selected' : '' }}>Remote</option>
                    </select>
                </div>
                
                <!-- Categories -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Categories</label>
                    <div class="flex flex-wrap gap-2">
                        @foreach($categories as $category)
                            <button type="button" onclick="toggleMobileCategory('{{ $category }}')"
                                    class="mobile-category-btn px-3 py-2 text-sm rounded-full border transition-all
                                           {{ request('category') == $category ? 'bg-[#1e3a8a] text-white border-[#1e3a8a]' : 'bg-white text-gray-700 border-gray-300' }}">
                                {{ $category }}
                            </button>
                        @endforeach
                    </div>
                </div>
                
                <!-- Action Buttons -->
                <div class="flex gap-3 pt-4 border-t border-gray-200">
                    <button type="button" onclick="applyMobileFilters()" 
                            class="flex-1 px-4 py-3 bg-[#1e3a8a] text-white font-medium rounded-lg hover:bg-[#0f2b5e] transition-all">
                        Apply Filters
                    </button>
                    <button type="button" onclick="clearMobileFilters()" 
                            class="px-4 py-3 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition-all">
                        Clear
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        let selectedCategory = '{{ request('category') }}';
        
        function openMobileFilters() {
            document.getElementById('mobileFilterModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }
        
        function closeMobileFilters() {
            document.getElementById('mobileFilterModal').classList.add('hidden');
            document.body.style.overflow = 'auto';
        }
        
        function toggleMobileCategory(category) {
            selectedCategory = selectedCategory === category ? '' : category;
            
            // Update button styles
            document.querySelectorAll('.mobile-category-btn').forEach(btn => {
                if (btn.textContent.trim() === category) {
                    if (selectedCategory === category) {
                        btn.className = 'mobile-category-btn px-3 py-2 text-sm rounded-full border transition-all bg-[#1e3a8a] text-white border-[#1e3a8a]';
                    } else {
                        btn.className = 'mobile-category-btn px-3 py-2 text-sm rounded-full border transition-all bg-white text-gray-700 border-gray-300';
                    }
                }
            });
        }
        
        function applyMobileFilters() {
            const sortBy = document.getElementById('mobile_sort_by').value;
            const minSalary = document.getElementById('mobile_min_salary').value;
            const maxSalary = document.getElementById('mobile_max_salary').value;
            const type = document.getElementById('mobile_type').value;
            
            // Build URL with current search + mobile filters
            const params = new URLSearchParams(window.location.search);
            
            // Add/update mobile filters
            if (sortBy) params.set('sort_by', sortBy);
            if (minSalary) params.set('min_salary', minSalary);
            if (maxSalary) params.set('max_salary', maxSalary);
            if (type) params.set('type', type);
            if (selectedCategory) params.set('category', selectedCategory);
            else params.delete('category');
            
            // Redirect to filtered page
            window.location.href = '{{ route('jobs.index') }}?' + params.toString();
        }
        
        function clearMobileFilters() {
            // Clear all filters and redirect
            const params = new URLSearchParams(window.location.search);
            params.delete('sort_by');
            params.delete('min_salary');
            params.delete('max_salary');
            params.delete('type');
            params.delete('category');
            
            window.location.href = '{{ route('jobs.index') }}?' + params.toString();
        }
        
        // Close modal when clicking outside
        document.getElementById('mobileFilterModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeMobileFilters();
            }
        });
    </script>

    <!-- JOBS GRID -->
    <div class="max-w-7xl mx-auto px-6 py-16 lg:py-24">
        <!-- Stats Bar -->
        <div class="flex flex-wrap justify-between items-center gap-4 mb-10 pb-4 border-b border-gray-100">
            <div>
                <h2 class="text-3xl font-bold text-gray-800">
                    {{ request('search') ? 'Search Results' : 'Available Opportunities' }}
                </h2>
                <p class="text-gray-500 mt-1">
                    {{ $jobs->total() }} jobs found
                    @if(request('search'))
                        for "{{ request('search') }}"
                    @endif
                </p>
            </div>
            <div class="flex items-center gap-2">
                <i class="fa-solid fa-briefcase text-[#1e3a8a]"></i>
                <span class="text-sm font-semibold text-[#1e3a8a] bg-[#1e3a8a]/10 px-4 py-2 rounded-full">
                    {{ $jobs->count() }} of {{ $jobs->total() }} active jobs
                </span>
            </div>
        </div>

        @if($jobs->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($jobs as $job)
                    <div class="bg-white rounded-xl border border-gray-100 hover:border-gray-200 hover:shadow-lg transition-all duration-300 overflow-hidden flex flex-col h-full">
                        <div class="relative h-40 overflow-hidden">
                            @if($job->image)
                                <img src="{{ asset('storage/' . $job->image) }}" alt="{{ $job->title }}" 
                                     class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full bg-gradient-to-br from-[#1e3a8a] to-blue-700 flex items-center justify-center">
                                    <i class="fa-solid fa-briefcase text-white text-4xl opacity-50"></i>
                                </div>
                            @endif
                            
                            @if($job->urgency === 'urgent')
                                <span class="absolute top-4 left-4 bg-red-500 text-white px-2 py-1 text-xs font-bold rounded">
                                    URGENT
                                </span>
                            @endif
                            
                            <span class="absolute bottom-4 left-4 bg-white/95 backdrop-blur-sm px-3 py-1 rounded-full text-xs font-semibold shadow-sm">
                                {{ $job->category }}
                            </span>
                        </div>
                        
                        <div class="p-5 flex flex-col flex-1">
                            <div class="flex items-start justify-between mb-3">
                                <div class="flex gap-2">
                                    @if($job->type === 'remote')
                                        <span class="px-2.5 py-1 bg-green-50 text-green-600 text-xs font-medium rounded">REMOTE</span>
                                    @elseif($job->type === 'off_campus')
                                        <span class="px-2.5 py-1 bg-amber-50 text-amber-600 text-xs font-medium rounded">OFF CAMPUS</span>
                                    @else
                                        <span class="px-2.5 py-1 bg-blue-50 text-blue-600 text-xs font-medium rounded">ON CAMPUS</span>
                                    @endif
                                    
                                    @if($job->deadline && $job->deadline->diffInDays(now()) <= 3)
                                        <span class="px-2.5 py-1 bg-red-50 text-red-600 text-xs font-medium rounded">DEADLINE SOON</span>
                                    @endif
                                </div>
                                <i class="fa-regular fa-bookmark text-gray-300 hover:text-[#1e3a8a] cursor-pointer transition-all"></i>
                            </div>
                            
                            <a href="{{ route('jobs.show', $job->id) }}" class="block">
                                <h3 class="font-bold text-lg text-gray-800 mb-2 leading-tight hover:text-[#1e3a8a] transition-colors">
                                    {{ Str::limit($job->title, 60) }}
                                </h3>
                            </a>
                            
                            <p class="text-gray-500 text-sm leading-relaxed mb-4 flex-1">
                                {{ Str::limit($job->description, 120) }}
                            </p>

                            @if($job->requirements && is_array($job->requirements))
                                <div class="flex flex-wrap gap-1.5 mb-5">
                                    @foreach(array_slice($job->requirements, 0, 3) as $requirement)
                                        <span class="px-2 py-0.5 bg-gray-100 text-gray-500 text-xs rounded">{{ $requirement }}</span>
                                    @endforeach
                                </div>
                            @endif

                            <div class="mt-auto pt-4 border-t border-gray-100">
                                <div class="flex items-center justify-between mb-5">
                                    <div class="flex items-center gap-2">
                                        @if($job->employer && $job->employer->passport_photo)
                                            <img src="{{ asset('storage/' . $job->employer->passport_photo) }}" alt="{{ $job->employer->first_name }}" 
                                                 class="w-9 h-9 rounded-full object-cover border-2 border-gray-200">
                                        @else
                                            <div class="w-9 h-9 bg-gradient-to-br from-blue-100 to-blue-200 rounded-full flex items-center justify-center font-bold text-[#1e3a8a] text-sm">
                                                {{ $job->employer ? substr($job->employer->first_name, 0, 1) . substr($job->employer->last_name, 0, 1) : 'UN' }}
                                            </div>
                                        @endif
                                        <div>
    @if($job->employer)
        <a href="{{ route('profile.show', $job->employer->id) }}"
           class="font-medium text-sm text-gray-800 hover:text-[#1e3a8a] hover:underline transition">
            {{ $job->employer->first_name . ' ' . substr($job->employer->last_name, 0, 1) }}
        </a>
    @else
        <p class="font-medium text-sm text-gray-800">University Staff</p>
    @endif

    <p class="text-xs text-gray-400">{{ $job->created_at->diffForHumans() }}</p>
</div>
                                    </div>
                                    <div class="text-right">
                                        <p class="font-bold text-xl text-gray-800">{{ $job->formatted_salary }}</p>
                                        @if($job->salary_type === 'hourly')
                                            <p class="text-xs text-gray-500">/hour</p>
                                        @elseif($job->salary_type === 'monthly')
                                            <p class="text-xs text-gray-500">/month</p>
                                        @endif
                                        @if($job->deadline)
                                            <p class="text-xs {{ $job->deadline->isPast() ? 'text-red-500' : 'text-gray-500' }}">
                                                {{ $job->deadline_days }}
                                            </p>
                                        @endif
                                    </div>
                                </div>
                                <a href="{{ route('jobs.show', $job->id) }}" 
                                   class="w-full py-3 bg-[#1e3a8a] text-white text-sm font-semibold rounded-lg hover:bg-[#0f2b5e] transition-all text-center block">
                                    View Details
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="flex justify-center mt-12">
                {{ $jobs->links() }}
            </div>
        @else
            <div class="text-center py-16">
                <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="fa-solid fa-briefcase text-gray-400 text-2xl"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-800 mb-2">No jobs found</h3>
                <p class="text-gray-500 mb-6">
                    @if(request('search'))
                        No jobs match your search for "{{ request('search') }}"
                    @else
                        No jobs are available at the moment
                    @endif
                </p>
                @if(request()->hasAny(['search', 'category', 'min_salary', 'max_salary', 'type', 'sort_by']))
                    <a href="{{ route('jobs.index') }}" class="inline-flex items-center px-6 py-3 bg-[#1e3a8a] text-white font-semibold rounded-full hover:bg-[#0f2b5e] transition-all">
                        Clear Filters
                    </a>
                @endif
            </div>
        @endif
        </div>
    </div>

    <x-footer />
@endsection
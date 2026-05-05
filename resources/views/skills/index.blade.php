@extends('layouts.guest')

@section('content')
    <x-navbar />

    <!-- HEADER / HERO -->
    <div class="bg-gradient-to-b from-white to-gray-50 border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-6 py-16 lg:py-20">
            <div class="max-w-3xl">
                <h1 class="text-5xl lg:text-6xl font-bold text-[#1e3a8a] leading-tight">
                    Discover Campus Expertise
                </h1>
                <p class="text-xl text-gray-600 mt-4 leading-relaxed">
                    Connect with talented students and staff providing professional services — from technical skills to creative work.
                </p>
            </div>

            <!-- Search + Filters -->
            <div class="mt-12">
                <!-- Search Bar (Always Visible) -->
                <form method="GET" action="{{ route('skills.index') }}" class="lg:hidden">
                    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
                        <div class="relative p-6">
                            <div class="relative">
                                <i class="fa-solid fa-search absolute left-5 top-1/2 -translate-y-1/2 text-gray-400"></i>
                                <input type="text" 
                                       name="search"
                                       value="{{ request('search') }}"
                                       placeholder="Search for skills (e.g., Tech, Braiding, Photography)..."
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
                <form method="GET" action="{{ route('skills.index') }}" class="hidden lg:block">
                    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
                        <!-- Search Bar -->
                        <div class="relative p-6 pb-4">
                            <div class="max-w-2xl mx-auto">
                                <div class="relative">
                                    <i class="fa-solid fa-search absolute left-5 top-1/2 -translate-y-1/2 text-gray-400"></i>
                                    <input type="text" 
                                           name="search"
                                           value="{{ request('search') }}"
                                           placeholder="Search for skills (e.g., Tech, Braiding, Photography)..."
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
                                            <button type="submit" name="category" value="{{ $category }}"
                                                    class="px-4 py-2 {{ request('category') == $category ? 'bg-[#1e3a8a] text-white shadow-md' : 'bg-white border border-gray-300 hover:border-[#1e3a8a]' }} text-sm font-medium rounded-lg transition-all">
                                                {{ $category }}
                                            </button>
                                        @endforeach
                                    </div>
                                </div>
                                
                                <!-- Advanced Filters -->
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4 items-end">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Sort by</label>
                                        <select name="sort_by" class="w-full border border-gray-200 rounded-lg px-3 py-2.5 focus:outline-none focus:border-[#1e3a8a] bg-white">
                                            <option value="created_at" {{ request('sort_by') == 'created_at' ? 'selected' : '' }}>Latest</option>
                                            <option value="price_low" {{ request('sort_by') == 'price_low' ? 'selected' : '' }}>Price: Low to High</option>
                                            <option value="price_high" {{ request('sort_by') == 'price_high' ? 'selected' : '' }}>Price: High to Low</option>
                                            <option value="rating" {{ request('sort_by') == 'rating' ? 'selected' : '' }}>Top Rated</option>
                                        </select>
                                    </div>
                                    
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Min Price</label>
                                        <input type="number" name="min_price" placeholder="Min" value="{{ request('min_price') }}" 
                                               class="w-full border border-gray-200 rounded-lg px-3 py-2.5 focus:outline-none focus:border-[#1e3a8a] bg-white">
                                    </div>
                                    
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Max Price</label>
                                        <input type="number" name="max_price" placeholder="Max" value="{{ request('max_price') }}" 
                                               class="w-full border border-gray-200 rounded-lg px-3 py-2.5 focus:outline-none focus:border-[#1e3a8a] bg-white">
                                    </div>
                                    
                                    <div class="lg:col-span-2 flex gap-3">
                                        <button type="submit" class="flex-1 px-6 py-2.5 bg-[#1e3a8a] text-white font-medium rounded-lg hover:bg-[#0f2b5e] transition-all shadow-sm">
                                            <i class="fa-solid fa-filter mr-2"></i>
                                            Apply Filters
                                        </button>
                                        
                                        @if(request()->hasAny(['search', 'category', 'min_price', 'max_price', 'sort_by']))
                                            <a href="{{ route('skills.index') }}" class="px-6 py-2.5 border border-gray-300 text-gray-600 font-medium rounded-lg hover:bg-gray-50 transition-all">
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
                        @if(request()->hasAny(['search', 'category', 'min_price', 'max_price', 'sort_by']))
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
                        <option value="price_low" {{ request('sort_by') == 'price_low' ? 'selected' : '' }}>Price: Low to High</option>
                        <option value="price_high" {{ request('sort_by') == 'price_high' ? 'selected' : '' }}>Price: High to Low</option>
                        <option value="rating" {{ request('sort_by') == 'rating' ? 'selected' : '' }}>Top Rated</option>
                    </select>
                </div>
                
                <!-- Price Range -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Price range</label>
                    <div class="flex items-center gap-2">
                        <input type="number" id="mobile_min_price" placeholder="Min" value="{{ request('min_price') }}" 
                               class="flex-1 border border-gray-200 rounded-lg px-3 py-2 focus:outline-none focus:border-[#1e3a8a]">
                        <span class="text-gray-400">-</span>
                        <input type="number" id="mobile_max_price" placeholder="Max" value="{{ request('max_price') }}" 
                               class="flex-1 border border-gray-200 rounded-lg px-3 py-2 focus:outline-none focus:border-[#1e3a8a]">
                    </div>
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
            const minPrice = document.getElementById('mobile_min_price').value;
            const maxPrice = document.getElementById('mobile_max_price').value;
            
            // Build URL with current search + mobile filters
            const params = new URLSearchParams(window.location.search);
            
            // Add/update mobile filters
            if (sortBy) params.set('sort_by', sortBy);
            if (minPrice) params.set('min_price', minPrice);
            if (maxPrice) params.set('max_price', maxPrice);
            if (selectedCategory) params.set('category', selectedCategory);
            else params.delete('category');
            
            // Redirect to filtered page
            window.location.href = '{{ route('skills.index') }}?' + params.toString();
        }
        
        function clearMobileFilters() {
            // Clear all filters and redirect
            const params = new URLSearchParams(window.location.search);
            params.delete('sort_by');
            params.delete('min_price');
            params.delete('max_price');
            params.delete('category');
            
            window.location.href = '{{ route('skills.index') }}?' + params.toString();
        }
        
        // Close modal when clicking outside
        document.getElementById('mobileFilterModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeMobileFilters();
            }
        });
    </script>

    <!-- SKILLS GRID -->
    <div class="max-w-7xl mx-auto px-6 py-16 lg:py-20">
        <!-- Section Header -->
        <div class="flex justify-between items-end mb-10">
            <div>
                <h2 class="text-3xl font-bold text-gray-800">
                    {{ request('search') ? 'Search Results' : 'Popular Skills' }}
                </h2>
                <p class="text-gray-500 mt-1">
                    {{ $skills->total() }} skills found
                    @if(request('search'))
                        for "{{ request('search') }}"
                    @endif
                </p>
            </div>
            <div class="text-sm text-[#1e3a8a] font-medium">
                {{ $skills->count() }} of {{ $skills->total() }} skills
            </div>
        </div>

        @if($skills->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-7">
                @foreach($skills as $skill)
                    <div class="group bg-white rounded-2xl shadow-md hover:shadow-xl transition-all duration-300 overflow-hidden border border-gray-100 hover:border-gray-200">
                        <div class="relative">
                            @if($skill->image)
                                <img src="{{ asset('storage/' . $skill->image) }}" alt="{{ $skill->title }}" 
                                     class="w-full h-52 object-cover group-hover:scale-105 transition-transform duration-500">
                            @else
                                <div class="w-full h-52 bg-gradient-to-br from-[#1e3a8a] to-blue-700 flex items-center justify-center">
                                    <i class="fa-solid fa-briefcase text-white text-4xl opacity-50"></i>
                                </div>
                            @endif
                            
                            <div class="absolute top-4 right-4 bg-white/95 backdrop-blur-sm px-3 py-1.5 rounded-full text-sm font-semibold shadow-md flex items-center gap-1">
                                <i class="fa-solid fa-star text-yellow-400 text-xs"></i>
                                <span>{{ $skill->rating ?: '5.0' }}</span>
                                <span class="text-gray-400 text-xs ml-0.5">({{ $skill->reviews_count ?? 0 }})</span>
                            </div>
                            <span class="absolute bottom-4 left-4 bg-white/95 backdrop-blur-sm px-3 py-1 rounded-full text-xs font-semibold shadow-sm">
                                {{ $skill->category }}
                            </span>
                        </div>
                        <div class="p-5">
                            <a href="{{ route('skills.show', $skill->id) }}" class="block">
                                <h3 class="font-bold text-xl text-gray-800 leading-tight mb-3 hover:text-[#1e3a8a] transition-colors">
                                    {{ Str::limit($skill->title, 50) }}
                                </h3>
                            </a>
                            
                            @if($skill->user)
                                <div class="flex items-center gap-3">
                                    @if($skill->user->passport_photo)
                                        <img src="{{ asset('storage/' . $skill->user->passport_photo) }}" alt="{{ $skill->user->first_name }}" 
                                             class="w-10 h-10 rounded-full object-cover border-2 border-gray-200">
                                    @else
                                        <div class="w-10 h-10 bg-gradient-to-br from-blue-100 to-blue-200 rounded-full flex items-center justify-center text-sm font-bold text-[#1e3a8a] shadow-sm">
                                            {{ substr($skill->user->first_name, 0, 1) }}{{ substr($skill->user->last_name, 0, 1) }}
                                        </div>
                                    @endif
                                    <div>
                                        <a href="{{ route('profile.show', $skill->user->id) }}" class="font-semibold text-gray-800 text-sm hover:text-[#1e3a8a] transition-colors">
                                            {{ $skill->user->first_name }} {{ substr($skill->user->last_name, 0, 1) }}.
                                        </a>
                                        <p class="text-xs text-gray-500">{{ $skill->user->department ?? 'University Staff' }}</p>
                                    </div>
                                </div>
                            @endif

                            <div class="mt-5 pt-3 border-t border-gray-100">
                                <div class="flex justify-between items-center mb-3">
                                    <div>
                                        <p class="text-xs text-gray-500">{{ $skill->price_type ?? 'Starting at' }}</p>
                                        <p class="font-bold text-xl text-gray-800">
                                            {{ $skill->formatted_price }}
                                            @if($skill->price_unit)
                                                <span class="text-sm font-normal text-gray-500">/{{ $skill->price_unit }}</span>
                                            @endif
                                        </p>
                                    </div>
                                    <div class="text-right">
                                        <div class="flex items-center gap-1 text-sm">
                                            <i class="fa-solid fa-star text-yellow-400"></i>
                                            <span class="font-semibold">{{ $skill->rating ?: '5.0' }}</span>
                                            <span class="text-gray-400 text-xs">({{ $skill->reviews_count ?? 0 }})</span>
                                        </div>
                                    </div>
                                </div>
                                <a href="{{ route('skills.show', $skill->id) }}" 
                                   class="w-full px-5 py-2.5 bg-[#1e3a8a] text-white text-sm font-semibold rounded-xl hover:bg-[#0f2b5e] transition-all shadow-sm hover:shadow-md active:scale-95 text-center block">
                                    View Details
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="flex justify-center mt-12">
                {{ $skills->links() }}
            </div>
        @else
            <div class="text-center py-16">
                <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="fa-solid fa-search text-gray-400 text-2xl"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-800 mb-2">No skills found</h3>
                <p class="text-gray-500 mb-6">
                    @if(request('search'))
                        No skills match your search for "{{ request('search') }}"
                    @else
                        No skills are available at the moment
                    @endif
                </p>
                @if(request()->hasAny(['search', 'category', 'min_price', 'max_price']))
                    <a href="{{ route('skills.index') }}" class="inline-flex items-center px-6 py-3 bg-[#1e3a8a] text-white font-semibold rounded-full hover:bg-[#0f2b5e] transition-all">
                        Clear Filters
                    </a>
                @endif
            </div>
        @endif

        <!-- Explore More Button -->
        <div class="flex justify-center mt-16">
            <button class="px-10 py-4 bg-[#1e3a8a] text-white font-semibold rounded-full hover:bg-[#0f2b5e] transition-all shadow-md hover:shadow-lg active:scale-95 flex items-center gap-2">
                Explore More Skills
                <i class="fa-solid fa-arrow-right text-sm"></i>
            </button>
        </div>
    </div>

    <!-- Reuse Footer -->
    <x-footer />
@endsection
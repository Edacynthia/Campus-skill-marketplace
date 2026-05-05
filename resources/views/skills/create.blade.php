@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-gray-50 py-12">
        <div class="max-w-4xl mx-auto px-6">
            <!-- Header -->
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-800 mb-2">Post a Skill</h1>
                <p class="text-gray-600">Share your talents with the campus community and start earning</p>
            </div>

            <!-- Form -->
            <div class="bg-white rounded-3xl shadow-sm p-8">
                <form method="POST" action="{{ route('skills.store') }}" class="space-y-6">
                    @csrf

                    <!-- Skill Title -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Skill Title *</label>
                        <input type="text" name="title" required
                               placeholder="e.g., Web Development, Math Tutoring, Graphic Design"
                               value="{{ old('title') }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:border-[#1e3a8a] transition-all">
                        @error('title')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Description *</label>
                        <textarea name="description" rows="6" required
                                  placeholder="Describe your skill, experience level, what you can help with, and any specializations..."
                                  class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:border-[#1e3a8a] transition-all">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <!-- Category -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Category *</label>
                            <select name="category" required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:border-[#1e3a8a] transition-all">
                                <option value="">Select category</option>
                                <option value="academic" {{ old('category') == 'academic' ? 'selected' : '' }}>Academic</option>
                                <option value="technical" {{ old('category') == 'technical' ? 'selected' : '' }}>Technical</option>
                                <option value="creative" {{ old('category') == 'creative' ? 'selected' : '' }}>Creative</option>
                                <option value="language" {{ old('category') == 'language' ? 'selected' : '' }}>Language</option>
                                <option value="music" {{ old('category') == 'music' ? 'selected' : '' }}>Music</option>
                                <option value="sports" {{ old('category') == 'sports' ? 'selected' : '' }}>Sports</option>
                                <option value="business" {{ old('category') == 'business' ? 'selected' : '' }}>Business</option>
                                <option value="other" {{ old('category') == 'other' ? 'selected' : '' }}>Other</option>
                            </select>
                            @error('category')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Price -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Price (₦) *</label>
                            <input type="number" name="price" required min="0" step="100"
                                   placeholder="5000"
                                   value="{{ old('price') }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:border-[#1e3a8a] transition-all">
                            @error('price')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Price Type -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Price Type *</label>
                            <select name="price_type" required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:border-[#1e3a8a] transition-all">
                                <option value="">Select type</option>
                                <option value="fixed" {{ old('price_type') == 'fixed' ? 'selected' : '' }}>Fixed Price</option>
                                <option value="negotiable" {{ old('price_type') == 'negotiable' ? 'selected' : '' }}>Negotiable</option>
                            </select>
                            @error('price_type')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Additional Information -->
                    <div class="bg-blue-50 p-6 rounded-xl">
                        <h3 class="font-semibold text-gray-800 mb-3">💡 Tips for Success</h3>
                        <ul class="space-y-2 text-sm text-gray-700">
                            <li class="flex items-start gap-2">
                                <i class="fa-solid fa-check text-emerald-600 mt-1"></i>
                                <span>Be specific about your experience and qualifications</span>
                            </li>
                            <li class="flex items-start gap-2">
                                <i class="fa-solid fa-check text-emerald-600 mt-1"></i>
                                <span>Include examples of your work or portfolio links</span>
                            </li>
                            <li class="flex items-start gap-2">
                                <i class="fa-solid fa-check text-emerald-600 mt-1"></i>
                                <span>Set competitive pricing based on campus standards</span>
                            </li>
                            <li class="flex items-start gap-2">
                                <i class="fa-solid fa-check text-emerald-600 mt-1"></i>
                                <span>Mention your availability and preferred contact methods</span>
                            </li>
                        </ul>
                    </div>

                    <!-- Submit Button -->
                    <div class="flex gap-4 pt-6">
                        <button type="submit" 
                                class="flex-1 bg-[#1e3a8a] text-white py-4 px-6 rounded-xl font-medium hover:bg-[#0f2b5e] transition-all">
                            Post Skill
                        </button>
                        <a href="{{ route('dashboard') }}" 
                           class="px-6 py-4 border border-gray-300 text-gray-700 rounded-xl font-medium hover:bg-gray-50 transition-all">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

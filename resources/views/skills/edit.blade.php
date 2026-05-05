@extends('layouts.app')

@section('content')

    <!-- Breadcrumb -->
    <div class="bg-gray-50 border-b border-gray-200">
        <div class="max-w-4xl mx-auto px-6 py-4">
            <nav class="flex items-center space-x-2 text-sm">
                <a href="{{ route('dashboard') }}" class="text-gray-500 hover:text-gray-700">Dashboard</a>
                <span class="text-gray-400">/</span>
                <a href="{{ route('skills.index') }}" class="text-gray-500 hover:text-gray-700">Skills</a>
                <span class="text-gray-400">/</span>
                <span class="text-gray-900 font-medium">Edit Skill</span>
            </nav>
        </div>
    </div>

    <!-- Edit Skill Form -->
    <div class="max-w-4xl mx-auto px-6 py-12">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-800 mb-2">Edit Skill</h1>
            <p class="text-gray-600">Update your skill information</p>
        </div>

        <!-- Form -->
        <div class="bg-white rounded-3xl shadow-sm p-8">
            <form method="POST" action="{{ route('skills.update', $skill->id) }}" class="space-y-6">
                @csrf
                @method('PUT')

                <!-- Skill Title -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Skill Title *</label>
                    <input type="text" name="title" required
                           value="{{ old('title', $skill->title) }}"
                           placeholder="e.g., Web Development, Graphic Design, Tutoring"
                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:border-[#1e3a8a] transition-all">
                    @error('title')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Description -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Description *</label>
                    <textarea name="description" rows="5" required
                              placeholder="Describe your skill, experience level, and what you can offer..."
                              class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:border-[#1e3a8a] transition-all resize-none">{{ old('description', $skill->description) }}</textarea>
                    @error('description')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Category and Price -->
                <div class="grid md:grid-cols-2 gap-6">
                    <!-- Category -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Category *</label>
                        <select name="category" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:border-[#1e3a8a] transition-all">
                            <option value="">Select category</option>
                            <option value="academic" {{ old('category', $skill->category) == 'academic' ? 'selected' : '' }}>Academic Support</option>
                            <option value="technical" {{ old('category', $skill->category) == 'technical' ? 'selected' : '' }}>Technical Skills</option>
                            <option value="creative" {{ old('category', $skill->category) == 'creative' ? 'selected' : '' }}>Creative Services</option>
                            <option value="business" {{ old('category', $skill->category) == 'business' ? 'selected' : '' }}>Business & Finance</option>
                            <option value="personal" {{ old('category', $skill->category) == 'personal' ? 'selected' : '' }}>Personal Development</option>
                            <option value="other" {{ old('category', $skill->category) == 'other' ? 'selected' : '' }}>Other</option>
                        </select>
                        @error('category')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Price -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Price *</label>
                        <input type="number" name="price" step="0.01" min="0" required
                               value="{{ old('price', $skill->price) }}"
                               placeholder="0.00"
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:border-[#1e3a8a] transition-all">
                        @error('price')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Price Type -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Price Type *</label>
                    <div class="flex gap-4">
                        <label class="flex items-center">
                            <input type="radio" name="price_type" value="fixed" required
                                   {{ old('price_type', $skill->price_type) == 'fixed' ? 'checked' : '' }}
                                   class="mr-2 text-[#1e3a8a] focus:ring-[#1e3a8a]">
                            <span class="text-gray-700">Fixed Price</span>
                        </label>
                        <label class="flex items-center">
                            <input type="radio" name="price_type" value="negotiable" required
                                   {{ old('price_type', $skill->price_type) == 'negotiable' ? 'checked' : '' }}
                                   class="mr-2 text-[#1e3a8a] focus:ring-[#1e3a8a]">
                            <span class="text-gray-700">Negotiable</span>
                        </label>
                    </div>
                    @error('price_type')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Form Actions -->
                <div class="flex gap-4 pt-6 border-t border-gray-200">
                    <button type="submit" class="flex-1 px-6 py-3 bg-[#1e3a8a] text-white font-semibold rounded-xl hover:bg-[#0f2b5e] transition-all shadow-sm hover:shadow-md">
                        <i class="fa-solid fa-save mr-2"></i>
                        Update Skill
                    </button>
                    <a href="{{ route('dashboard') }}" class="px-6 py-3 border border-gray-300 text-gray-700 font-semibold rounded-xl hover:bg-gray-50 transition-all">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Footer -->
    <x-footer />
@endsection

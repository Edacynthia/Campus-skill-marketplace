@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
    <!-- Simple Header with back link -->
    <div class="mb-6">
        <div class="flex items-center gap-2 text-sm text-gray-500 mb-2">
            <a href="{{ route('skills.mine') }}" class="hover:text-[#1e3a8a] flex items-center gap-1">
                <i class="fa-solid fa-arrow-left text-xs"></i>
                Back to Skills
            </a>
        </div>
        <h1 class="text-2xl font-semibold text-gray-900">Edit Skill</h1>
        <p class="text-gray-500 text-sm mt-0.5">Update your skill information</p>
    </div>

    <!-- Form Card -->
    <div class="bg-white rounded-xl border border-gray-200">
        <form method="POST" action="{{ route('skills.update', $skill->id) }}" class="p-6 space-y-5">
            @csrf
            @method('PUT')

            <!-- Skill Title -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Skill Title <span class="text-red-500">*</span></label>
                <input type="text" name="title" required
                       value="{{ old('title', $skill->title) }}"
                       placeholder="e.g., Web Development, Graphic Design, Tutoring"
                       class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:border-[#1e3a8a] focus:ring-1 focus:ring-[#1e3a8a] transition-all">
                @error('title')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Description -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Description <span class="text-red-500">*</span></label>
                <textarea name="description" rows="5" required
                          placeholder="Describe your skill, experience level, and what you can offer..."
                          class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:border-[#1e3a8a] focus:ring-1 focus:ring-[#1e3a8a] transition-all resize-none">{{ old('description', $skill->description) }}</textarea>
                <p class="text-xs text-gray-400 mt-1">Be specific about what you can help with</p>
                @error('description')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Category and Price Row -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <!-- Category -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Category <span class="text-red-500">*</span></label>
                    <select name="category" required
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:border-[#1e3a8a] focus:ring-1 focus:ring-[#1e3a8a] transition-all">
                        <option value="">Select category</option>
                        <option value="academic" {{ old('category', $skill->category) == 'academic' ? 'selected' : '' }}>📚 Academic Support</option>
                        <option value="technical" {{ old('category', $skill->category) == 'technical' ? 'selected' : '' }}>💻 Technical Skills</option>
                        <option value="creative" {{ old('category', $skill->category) == 'creative' ? 'selected' : '' }}>🎨 Creative Services</option>
                        <option value="business" {{ old('category', $skill->category) == 'business' ? 'selected' : '' }}>📊 Business & Finance</option>
                        <option value="personal" {{ old('category', $skill->category) == 'personal' ? 'selected' : '' }}>🌟 Personal Development</option>
                        <option value="other" {{ old('category', $skill->category) == 'other' ? 'selected' : '' }}>🔧 Other</option>
                    </select>
                    @error('category')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Price -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Price <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500">₦</span>
                        <input type="number" name="price" step="0.01" min="0" required
                               value="{{ old('price', $skill->price) }}"
                               placeholder="0.00"
                               class="w-full pl-8 pr-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:border-[#1e3a8a] focus:ring-1 focus:ring-[#1e3a8a] transition-all">
                    </div>
                    @error('price')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Price Type -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Pricing Model</label>
                <div class="flex gap-6">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="radio" name="price_type" value="fixed" required
                               {{ old('price_type', $skill->price_type) == 'fixed' ? 'checked' : '' }}
                               class="w-4 h-4 text-[#1e3a8a] focus:ring-[#1e3a8a]">
                        <span class="text-gray-700 text-sm">Fixed Price</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="radio" name="price_type" value="negotiable" required
                               {{ old('price_type', $skill->price_type) == 'negotiable' ? 'checked' : '' }}
                               class="w-4 h-4 text-[#1e3a8a] focus:ring-[#1e3a8a]">
                        <span class="text-gray-700 text-sm">Negotiable</span>
                    </label>
                </div>
                @error('price_type')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Status (if you want to allow status change during edit) -->
            {{-- <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Status</label>
                <div class="flex gap-6">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="radio" name="status" value="active"
                               {{ old('status', $skill->status) == 'active' ? 'checked' : '' }}
                               class="w-4 h-4 text-green-600 focus:ring-green-500">
                        <span class="text-gray-700 text-sm">Active (visible to others)</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="radio" name="status" value="inactive"
                               {{ old('status', $skill->status) == 'inactive' ? 'checked' : '' }}
                               class="w-4 h-4 text-gray-500 focus:ring-gray-400">
                        <span class="text-gray-700 text-sm">Inactive (hidden)</span>
                    </label>
                </div>
                @error('status')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div> --}}

            <!-- Form Actions -->
            <div class="flex gap-3 pt-4 border-t border-gray-200">
                <button type="submit" class="flex-1 px-4 py-2.5 bg-[#1e3a8a] text-white font-medium rounded-lg hover:bg-[#0f2b5e] transition-colors">
                    <i class="fa-solid fa-save mr-2"></i>
                    Save Changes
                </button>
                <a href="{{ route('skills.mine') }}" class="px-5 py-2.5 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition-colors text-center">
                    Cancel
                </a>
            </div>
        </form>
    </div>

    <!-- Delete Section (optional, for safety) -->
    <div class="mt-6 pt-4 border-t border-gray-200">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-sm font-medium text-gray-700">Delete this skill</h3>
                <p class="text-xs text-gray-400 mt-0.5">Once deleted, it cannot be recovered</p>
            </div>
            <form action="{{ route('skills.destroy', $skill->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this skill? This action cannot be undone.')">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-4 py-2 text-sm text-red-600 hover:text-red-700 hover:bg-red-50 rounded-lg transition-colors">
                    <i class="fa-solid fa-trash-can mr-1"></i>
                    Delete Skill
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
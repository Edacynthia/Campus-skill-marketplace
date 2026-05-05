@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-gray-50 py-12">
        <div class="max-w-4xl mx-auto px-6">
            <!-- Header -->
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-800 mb-2">Post a Job</h1>
                <p class="text-gray-600">Find talented students and faculty for your campus needs</p>
            </div>

            <!-- Form -->
            <div class="bg-white rounded-3xl shadow-sm p-8">
                <form method="POST" action="{{ route('jobs.store') }}" class="space-y-6">
                    @csrf

                    <!-- Job Title -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Job Title *</label>
                        <input type="text" name="title" required
                               placeholder="e.g., Research Assistant, Campus Photographer"
                               value="{{ old('title') }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:border-[#1e3a8a] transition-all">
                        @error('title')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Job Description *</label>
                        <textarea name="description" rows="5" required
                                  placeholder="Describe the job responsibilities, requirements, and what you're looking for..."
                                  class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:border-[#1e3a8a] transition-all">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Category -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Category *</label>
                            <select name="category" required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:border-[#1e3a8a] transition-all">
                                <option value="">Select category</option>
                                <option value="academic" {{ old('category') == 'academic' ? 'selected' : '' }}>Academic</option>
                                <option value="administrative" {{ old('category') == 'administrative' ? 'selected' : '' }}>Administrative</option>
                                <option value="technical" {{ old('category') == 'technical' ? 'selected' : '' }}>Technical</option>
                                <option value="creative" {{ old('category') == 'creative' ? 'selected' : '' }}>Creative</option>
                                <option value="research" {{ old('category') == 'research' ? 'selected' : '' }}>Research</option>
                                <option value="teaching" {{ old('category') == 'teaching' ? 'selected' : '' }}>Teaching</option>
                                <option value="other" {{ old('category') == 'other' ? 'selected' : '' }}>Other</option>
                            </select>
                            @error('category')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Type -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Job Type *</label>
                            <select name="type" required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:border-[#1e3a8a] transition-all">
                                <option value="">Select type</option>
                                <option value="on_campus" {{ old('type') == 'on_campus' ? 'selected' : '' }}>On Campus</option>
                                <option value="off_campus" {{ old('type') == 'off_campus' ? 'selected' : '' }}>Off Campus</option>
                                <option value="remote" {{ old('type') == 'remote' ? 'selected' : '' }}>Remote</option>
                            </select>
                            @error('type')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <!-- Salary -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Salary (₦) *</label>
                            <input type="number" name="salary" required min="0" step="100"
                                   placeholder="5000"
                                   value="{{ old('salary') }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:border-[#1e3a8a] transition-all">
                            @error('salary')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Salary Type -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Salary Type *</label>
                            <select name="salary_type" required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:border-[#1e3a8a] transition-all">
                                <option value="">Select type</option>
                                <option value="hourly" {{ old('salary_type') == 'hourly' ? 'selected' : '' }}>Hourly</option>
                                <option value="fixed" {{ old('salary_type') == 'fixed' ? 'selected' : '' }}>Fixed</option>
                            </select>
                            @error('salary_type')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Urgency -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Urgency *</label>
                            <select name="urgency" required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:border-[#1e3a8a] transition-all">
                                <option value="">Select urgency</option>
                                <option value="normal" {{ old('urgency') == 'normal' ? 'selected' : '' }}>Normal</option>
                                <option value="urgent" {{ old('urgency') == 'urgent' ? 'selected' : '' }}>Urgent</option>
                            </select>
                            @error('urgency')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Location -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Location *</label>
                        <input type="text" name="location" required
                               placeholder="e.g., Faculty of Engineering, Library, Main Campus"
                               value="{{ old('location') }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:border-[#1e3a8a] transition-all">
                        @error('location')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Deadline -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Application Deadline (Optional)</label>
                        <input type="date" name="deadline"
                               value="{{ old('deadline') }}"
                               min="{{ now()->format('Y-m-d') }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:border-[#1e3a8a] transition-all">
                        @error('deadline')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Requirements -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Requirements (Optional)</label>
                        <div class="space-y-2" id="requirements-container">
                            <div class="flex gap-2 requirement-item">
                                <input type="text" name="requirements[]" 
                                       placeholder="e.g., Must be enrolled in Engineering program"
                                       value="{{ old('requirements.0') ?? '' }}"
                                       class="flex-1 px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:border-[#1e3a8a] transition-all">
                                <button type="button" onclick="removeRequirement(this)" class="text-red-500 hover:text-red-700">
                                    <i class="fa-solid fa-times"></i>
                                </button>
                            </div>
                        </div>
                        <button type="button" onclick="addRequirement()" class="text-[#1e3a8a] hover:text-[#0f2b5e] text-sm font-medium">
                            <i class="fa-solid fa-plus"></i> Add Requirement
                        </button>
                    </div>

                    <!-- Submit Button -->
                    <div class="flex gap-4 pt-6">
                        <button type="submit" 
                                class="flex-1 bg-[#1e3a8a] text-white py-4 px-6 rounded-xl font-medium hover:bg-[#0f2b5e] transition-all">
                            Post Job
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

    @push('scripts')
    <script>
        function addRequirement() {
            const container = document.getElementById('requirements-container');
            const newItem = document.createElement('div');
            newItem.className = 'flex gap-2 requirement-item';
            newItem.innerHTML = `
                <input type="text" name="requirements[]" 
                       placeholder="Enter requirement"
                       class="flex-1 px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:border-[#1e3a8a] transition-all">
                <button type="button" onclick="removeRequirement(this)" class="text-red-500 hover:text-red-700">
                    <i class="fa-solid fa-times"></i>
                </button>
            `;
            container.appendChild(newItem);
        }

        function removeRequirement(button) {
            const container = document.getElementById('requirements-container');
            if (container.children.length > 1) {
                button.parentElement.remove();
            }
        }
    </script>
    @endpush
@endsection

@extends('layouts.app')

@section('content')

    <!-- Breadcrumb -->
    <div class="bg-gray-50 border-b border-gray-200">
        <div class="max-w-4xl mx-auto px-6 py-4">
            <nav class="flex items-center space-x-2 text-sm">
                <a href="{{ route('dashboard') }}" class="text-gray-500 hover:text-gray-700">Dashboard</a>
                <span class="text-gray-400">/</span>
                <a href="{{ route('jobs.index') }}" class="text-gray-500 hover:text-gray-700">Jobs</a>
                <span class="text-gray-400">/</span>
                <span class="text-gray-900 font-medium">Edit Job</span>
            </nav>
        </div>
    </div>

    <!-- Edit Job Form -->
    <div class="max-w-4xl mx-auto px-6 py-12">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-800 mb-2">Edit Job</h1>
            <p class="text-gray-600">Update your job posting details</p>
        </div>

        <!-- Form -->
        <div class="bg-white rounded-3xl shadow-sm p-8">
            <form method="POST" action="{{ route('jobs.update', $job->id) }}" class="space-y-6">
                @csrf
                @method('PUT')

                <!-- Job Title -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Job Title *</label>
                    <input type="text" name="title" required
                           value="{{ old('title', $job->title) }}"
                           placeholder="e.g., Web Developer Intern, Research Assistant, Campus Ambassador"
                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:border-[#1e3a8a] transition-all">
                    @error('title')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Description -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Job Description *</label>
                    <textarea name="description" rows="5" required
                              placeholder="Provide a detailed description of the role, responsibilities, and what you're looking for..."
                              class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:border-[#1e3a8a] transition-all resize-none">{{ old('description', $job->description) }}</textarea>
                    @error('description')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Category and Type -->
                <div class="grid md:grid-cols-2 gap-6">
                    <!-- Category -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Category *</label>
                        <select name="category" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:border-[#1e3a8a] transition-all">
                            <option value="">Select category</option>
                            <option value="technology" {{ old('category', $job->category) == 'technology' ? 'selected' : '' }}>Technology</option>
                            <option value="research" {{ old('category', $job->category) == 'research' ? 'selected' : '' }}>Research</option>
                            <option value="administrative" {{ old('category', $job->category) == 'administrative' ? 'selected' : '' }}>Administrative</option>
                            <option value="teaching" {{ old('category', $job->category) == 'teaching' ? 'selected' : '' }}>Teaching</option>
                            <option value="creative" {{ old('category', $job->category) == 'creative' ? 'selected' : '' }}>Creative</option>
                            <option value="service" {{ old('category', $job->category) == 'service' ? 'selected' : '' }}>Service</option>
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
                            <option value="on_campus" {{ old('type', $job->type) == 'on_campus' ? 'selected' : '' }}>On Campus</option>
                            <option value="off_campus" {{ old('type', $job->type) == 'off_campus' ? 'selected' : '' }}>Off Campus</option>
                            <option value="remote" {{ old('type', $job->type) == 'remote' ? 'selected' : '' }}>Remote</option>
                        </select>
                        @error('type')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Urgency and Salary -->
                <div class="grid md:grid-cols-2 gap-6">
                    <!-- Urgency -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Urgency *</label>
                        <select name="urgency" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:border-[#1e3a8a] transition-all">
                            <option value="">Select urgency</option>
                            <option value="normal" {{ old('urgency', $job->urgency) == 'normal' ? 'selected' : '' }}>Normal</option>
                            <option value="urgent" {{ old('urgency', $job->urgency) == 'urgent' ? 'selected' : '' }}>Urgent</option>
                        </select>
                        @error('urgency')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Salary -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Salary *</label>
                        <input type="number" name="salary" step="0.01" min="0" required
                               value="{{ old('salary', $job->salary) }}"
                               placeholder="0.00"
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:border-[#1e3a8a] transition-all">
                        @error('salary')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Salary Type and Location -->
                <div class="grid md:grid-cols-2 gap-6">
                    <!-- Salary Type -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Salary Type *</label>
                        <select name="salary_type" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:border-[#1e3a8a] transition-all">
                            <option value="">Select type</option>
                            <option value="hourly" {{ old('salary_type', $job->salary_type) == 'hourly' ? 'selected' : '' }}>Hourly</option>
                            <option value="fixed" {{ old('salary_type', $job->salary_type) == 'fixed' ? 'selected' : '' }}>Fixed</option>
                        </select>
                        @error('salary_type')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Location -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Location *</label>
                        <input type="text" name="location" required
                               value="{{ old('location', $job->location) }}"
                               placeholder="e.g., Faculty of Engineering, Library, Main Campus"
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:border-[#1e3a8a] transition-all">
                        @error('location')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Deadline -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Application Deadline (Optional)</label>
                    <input type="date" name="deadline"
                           value="{{ old('deadline', $job->deadline ? $job->deadline->format('Y-m-d') : '') }}"
                           min="{{ now()->format('Y-m-d') }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:border-[#1e3a8a] transition-all">
                    @error('deadline')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Requirements -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Requirements (Optional)</label>
                    <div class="space-y-3">
                        <div class="flex gap-3">
                            <input type="text" name="requirements[]" 
                                   value="{{ old('requirements.0', $job->requirements[0] ?? '') }}"
                                   placeholder="e.g., Experience with web development"
                                   class="flex-1 px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:border-[#1e3a8a] transition-all">
                            <button type="button" onclick="addRequirement()" class="px-4 py-3 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 transition-all">
                                <i class="fa-solid fa-plus"></i>
                            </button>
                        </div>
                        <div id="requirements-container"></div>
                    </div>
                    <p class="text-xs text-gray-500 mt-1">Add specific requirements or qualifications</p>
                </div>

                <!-- Form Actions -->
                <div class="flex gap-4 pt-6 border-t border-gray-200">
                    <button type="submit" class="flex-1 px-6 py-3 bg-[#1e3a8a] text-white font-semibold rounded-xl hover:bg-[#0f2b5e] transition-all shadow-sm hover:shadow-md">
                        <i class="fa-solid fa-save mr-2"></i>
                        Update Job
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

    <script>
        function addRequirement() {
            const container = document.getElementById('requirements-container');
            const newRequirement = document.createElement('div');
            newRequirement.className = 'flex gap-3';
            newRequirement.innerHTML = `
                <input type="text" name="requirements[]" placeholder="Add another requirement"
                       class="flex-1 px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:border-[#1e3a8a] transition-all">
                <button type="button" onclick="this.parentElement.remove()" class="px-4 py-3 bg-red-100 text-red-700 rounded-xl hover:bg-red-200 transition-all">
                    <i class="fa-solid fa-times"></i>
                </button>
            `;
            container.appendChild(newRequirement);
        }
    </script>
@endsection

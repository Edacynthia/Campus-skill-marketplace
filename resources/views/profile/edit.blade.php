@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-10">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Header -->
        <div class="mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Edit Profile</h1>
                <p class="text-gray-500 mt-1">Update your personal information and profile photo.</p>
            </div>

            <a href="{{ route('dashboard') }}"
               class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-gray-200 text-gray-700 rounded-xl hover:bg-gray-100 transition">
                <i class="fa-solid fa-arrow-left"></i>
                Back to Dashboard
            </a>
        </div>

        <!-- Errors -->
        @if ($errors->any())
            <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-5 py-4 rounded-2xl">
                <div class="flex items-start gap-3">
                    <i class="fa-solid fa-triangle-exclamation mt-1"></i>
                    <div>
                        <h3 class="font-semibold mb-1">Please fix the following:</h3>
                        <ul class="list-disc list-inside text-sm space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        <!-- Success -->
        @if (session('success'))
            <div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-700 px-5 py-4 rounded-2xl">
                <i class="fa-solid fa-circle-check mr-2"></i>
                {{ session('success') }}
            </div>
        @endif

        <form method="POST"
              action="{{ route('profile.update') }}"
              enctype="multipart/form-data"
              class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">

            @csrf
            @method('PUT')

            <!-- Profile Photo Section -->
            <div class="p-6 sm:p-8 border-b border-gray-100">
                <h2 class="text-lg font-semibold text-gray-900 mb-5">Profile Photo</h2>

                <div class="flex flex-col sm:flex-row items-start sm:items-center gap-6">
                    <div class="relative">
                        @if(auth()->user()->passport_photo)
                            <img id="photoPreview"
                                 src="{{ asset('storage/' . auth()->user()->passport_photo) }}"
                                 alt="Profile photo"
                                 class="w-28 h-28 rounded-2xl object-cover border border-gray-200 shadow-sm">
                        @else
                            <div id="photoPlaceholder"
                                 class="w-28 h-28 rounded-2xl bg-gray-100 border border-gray-200 flex items-center justify-center">
                                <i class="fa-solid fa-user text-4xl text-gray-400"></i>
                            </div>

                            <img id="photoPreview"
                                 src=""
                                 alt="Profile photo preview"
                                 class="hidden w-28 h-28 rounded-2xl object-cover border border-gray-200 shadow-sm">
                        @endif
                    </div>

                    <div class="flex-1 w-full">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Upload New Photo
                        </label>

                        <input type="file"
                               name="passport_photo"
                               id="passport_photo"
                               accept="image/*"
                               class="block w-full text-sm text-gray-600 border border-gray-300 rounded-xl cursor-pointer bg-white focus:outline-none focus:ring-2 focus:ring-[#1e3a8a]/30 focus:border-[#1e3a8a]">

                        <p class="text-xs text-gray-500 mt-2">
                            Recommended: JPG or PNG, clear face photo.
                        </p>

                        @error('passport_photo')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror

                    </div>
                </div>
            </div>

            <!-- Personal Information -->
            <div class="p-6 sm:p-8">
                <h2 class="text-lg font-semibold text-gray-900 mb-5">Personal Information</h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">First Name</label>
                        <input type="text"
                               name="first_name"
                               value="{{ old('first_name', auth()->user()->first_name) }}"
                               required
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#1e3a8a]/30 focus:border-[#1e3a8a]">
                        @error('first_name')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Last Name</label>
                        <input type="text"
                               name="last_name"
                               value="{{ old('last_name', auth()->user()->last_name) }}"
                               required
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#1e3a8a]/30 focus:border-[#1e3a8a]">
                        @error('last_name')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                        <input type="email"
                               name="email"
                               value="{{ old('email', auth()->user()->email) }}"
                               required
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#1e3a8a]/30 focus:border-[#1e3a8a]">
                        @error('email')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Department</label>
                        <input type="text"
                               name="department"
                               value="{{ old('department', auth()->user()->department) }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#1e3a8a]/30 focus:border-[#1e3a8a]">
                        @error('department')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div> -->

                    <!-- <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Matric Number</label>
                        <input type="text"
                               name="matric_number"
                               value="{{ old('matric_number', auth()->user()->matric_number) }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#1e3a8a]/30 focus:border-[#1e3a8a]">
                        @error('matric_number')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div> -->
                </div>
            </div>

            <!-- Footer Buttons -->
            <div class="px-6 sm:px-8 py-5 bg-gray-50 border-t border-gray-100 flex flex-col sm:flex-row justify-end gap-3">
                <a href="{{ route('dashboard') }}"
                   class="px-6 py-3 border border-gray-300 text-gray-700 text-center rounded-xl hover:bg-white transition">
                    Cancel
                </a>

                <button type="submit"
                        class="px-6 py-3 bg-[#1e3a8a] hover:bg-[#0f2b5e] text-white font-semibold rounded-xl transition">
                    <i class="fa-solid fa-save mr-2"></i>
                    Save Changes
                </button>
            </div>
        </form>
    </div>
</div>

<script>

document.getElementById('passport_photo')?.addEventListener('change', function(event) {
    const file = event.target.files[0];
    if (!file) return;

    const preview = document.getElementById('photoPreview');
    const placeholder = document.getElementById('photoPlaceholder');

    preview.src = URL.createObjectURL(file);
    preview.classList.remove('hidden');

    if (placeholder) {
        placeholder.classList.add('hidden');
    }
});
</script>
@endsection
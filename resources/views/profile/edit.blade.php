@extends('layouts.guest')

@section('content')
    <div class="max-w-4xl mx-auto py-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-8">Edit Profile</h1>

        @if ($errors->any())
            <div class="bg-red-100 text-red-700 p-4 rounded-xl mb-6">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">First Name</label>
                    <input type="text" name="first_name" value="{{ old('first_name', auth()->user()->first_name) }}" required 
                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:border-blue-500">
                    @error('first_name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Last Name</label>
                    <input type="text" name="last_name" value="{{ old('last_name', auth()->user()->last_name) }}" required 
                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:border-blue-500">
                    @error('last_name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                <input type="email" name="email" value="{{ old('email', auth()->user()->email) }}" required 
                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:border-blue-500">
                @error('email')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Profile Picture</label>
                <div class="flex items-center gap-6">
                    @if(auth()->user()->passport_photo)
                        <div class="relative">
                            <img src="{{ asset('storage/' . auth()->user()->passport_photo) }}" alt="Current profile" 
                                 class="w-24 h-24 rounded-xl object-cover border-4 border-gray-200">
                            <button type="button" onclick="removePassport()" 
                                    class="absolute -top-2 -right-2 bg-red-500 text-white w-6 h-6 rounded-full text-xs hover:bg-red-600">
                                ×
                            </button>
                        </div>
                    @else
                        <div class="w-24 h-24 rounded-xl bg-gray-200 border-4 border-gray-200 flex items-center justify-center">
                            <i class="fa-solid fa-user text-3xl text-gray-400"></i>
                        </div>
                    @endif
                    
                    <div class="flex-1">
                        <input type="file" name="passport_photo" accept="image/*" 
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:border-blue-500">
                        <p class="text-xs text-gray-500 mt-2">Upload a new profile picture (optional)</p>
                        @error('passport_photo')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="flex justify-end gap-4">
                <a href="{{ route('dashboard') }}" 
                   class="px-6 py-3 border border-gray-300 text-gray-700 rounded-xl hover:bg-gray-50">
                    Cancel
                </a>
                <button type="submit" 
                        class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-xl">
                    Update Profile
                </button>
            </div>
        </form>
    </div>

    <script>
        function removePassport() {
            if (confirm('Are you sure you want to remove your profile picture?')) {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'remove_passport';
                input.value = '1';
                document.querySelector('form').appendChild(input);
                document.querySelector('form').submit();
            }
        }
    </script>
@endsection

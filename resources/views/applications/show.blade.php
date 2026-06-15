@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-10">
    <div class="max-w-3xl mx-auto px-4">

        <!-- Header -->
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-800">
                Edit Application
            </h1>

            <p class="text-gray-500 mt-1">
                Update your application for this job.
            </p>
        </div>

        <!-- Card -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">

            <!-- Job Info -->
            <div class="p-6 border-b border-gray-100">
                <h2 class="text-2xl font-semibold text-gray-900">
                    {{ $application->job->title }}
                </h2>

                <p class="text-sm text-gray-500 mt-1">
                    Posted by
                    {{ $application->job->employer->first_name ?? '' }}
                    {{ $application->job->employer->last_name ?? '' }}
                </p>

                <div class="mt-4">
                    <span class="px-3 py-1 rounded-full text-sm font-semibold
                        @if($application->status === 'pending')
                            bg-yellow-100 text-yellow-700
                        @elseif($application->status === 'accepted')
                            bg-green-100 text-green-700
                        @elseif($application->status === 'rejected')
                            bg-red-100 text-red-700
                        @else
                            bg-gray-100 text-gray-700
                        @endif">
                        {{ ucfirst($application->status) }}
                    </span>
                </div>
            </div>

            <!-- Edit Form -->
            <form action="{{ route('applications.edit', $application->id) }}"
                  method="POST"
                  class="p-6 space-y-6">

                @csrf
                @method('PATCH')

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Cover Letter
                    </label>

                    <textarea
                        name="cover_letter"
                        rows="8"
                        required
                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#1e3a8a] focus:border-transparent">{{ old('cover_letter', $application->cover_letter) }}</textarea>

                    @error('cover_letter')
                        <p class="text-red-500 text-sm mt-2">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Buttons -->
                <div class="flex flex-wrap gap-3">

                    <button type="submit"
                            class="px-6 py-3 bg-[#1e3a8a] text-white font-semibold rounded-xl hover:bg-[#0f2b5e] transition-all">
                        Update Application
                    </button>

                    <a href="{{ route('applications.received') }}"
                       class="px-6 py-3 border border-gray-300 text-gray-700 font-semibold rounded-xl hover:bg-gray-50 transition-all">
                        Cancel
                    </a>

                </div>
            </form>
        </div>
    </div>
</div>
@endsection
@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-12">
    <div class="max-w-6xl mx-auto px-6">

        {{-- Header --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">

            <div>
                <h1 class="text-3xl font-bold text-gray-800">
                    Archived Messages
                </h1>

                <p class="text-gray-600">
                    View archived conversations
                </p>
            </div>

            <a href="{{ route('messages.index') }}"
               class="inline-flex items-center justify-center px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300">
                Back to Inbox
            </a>

        </div>

        {{-- Success Message --}}
        {{-- @if(session('success'))
            <div class="mb-6 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg flex items-center gap-3">
                <i class="fa-solid fa-check-circle"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif --}}

        {{-- Archived Messages --}}
        @if($messages->count() > 0)

            <div class="space-y-4">

                @foreach($messages as $message)

                    <div class="bg-white rounded-xl border border-gray-200 hover:shadow-md transition-all overflow-hidden">

                        {{-- Message Header --}}
                        <div class="p-4 border-b border-gray-100">

                            <div class="flex items-center justify-between">

                                <div class="flex items-center gap-3">

                                    @if($message->sender->passport_photo)
                                        <img src="{{ asset('storage/' . $message->sender->passport_photo) }}"
                                             alt="{{ $message->sender->first_name }}"
                                             class="w-10 h-10 rounded-full object-cover border-2 border-gray-200">
                                    @else
                                        <div class="w-10 h-10 bg-gradient-to-br from-blue-100 to-blue-200 rounded-full flex items-center justify-center font-bold text-[#1e3a8a] text-sm">
                                            {{ substr($message->sender->first_name, 0, 1) }}{{ substr($message->sender->last_name, 0, 1) }}
                                        </div>
                                    @endif

                                    <div>

                                        <div class="flex items-center gap-2">

                                            <p class="font-semibold text-gray-900">
                                                {{ $message->sender->id == auth()->id() ? 'You' : $message->sender->first_name . ' ' . $message->sender->last_name }}
                                            </p>

                                            @if($message->sender->id != auth()->id())
                                                <span class="text-xs text-gray-500">
                                                    → {{ $message->receiver->first_name }} {{ $message->receiver->last_name }}
                                                </span>
                                            @endif

                                        </div>

                                        <p class="text-xs text-gray-500">
                                            {{ $message->formatted_created_at }}
                                        </p>

                                    </div>

                                </div>

                                <span class="px-2 py-1 bg-gray-100 text-gray-600 text-xs rounded-full">
                                    Archived
                                </span>

                            </div>

                        </div>

                        {{-- Message Body --}}
                        <div class="p-4">

                            @if($message->skill_id && $message->skill)
                                <div class="mb-3 p-3 bg-blue-50 rounded-lg">
                                    <div class="flex items-center gap-2 text-sm">
                                        <i class="fa-solid fa-briefcase text-blue-600"></i>

                                        <span class="text-blue-800">
                                            Regarding:
                                            <strong>{{ $message->skill->title }}</strong>
                                        </span>
                                    </div>
                                </div>

                            @elseif($message->job_id && $message->job)

                                <div class="mb-3 p-3 bg-green-50 rounded-lg">
                                    <div class="flex items-center gap-2 text-sm">
                                        <i class="fa-solid fa-briefcase text-green-600"></i>

                                        <span class="text-green-800">
                                            Regarding:
                                            <strong>{{ $message->job->title }}</strong>
                                        </span>
                                    </div>
                                </div>

                            @endif

                            <p class="text-gray-700 leading-relaxed">
                                {{ $message->message }}
                            </p>

                        </div>

                        {{-- Actions --}}
                        <div class="p-4 border-t border-gray-100 bg-gray-50">

                            <div class="flex items-center justify-end gap-2">

                                {{-- Unarchive --}}
                                <form action="{{ route('messages.unarchive', $message->id) }}"
                                      method="POST">
                                    @csrf

                                    <button type="submit"
                                            class="text-xs px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-700 transition-all">
                                        Unarchive
                                    </button>
                                </form>

                                {{-- Delete --}}
                                @if($message->sender_id == auth()->id() || $message->receiver_id == auth()->id())

                                    <form action="{{ route('messages.destroy', $message->id) }}"
                                          method="POST"
                                          onsubmit="return confirm('Are you sure you want to permanently delete this message?')">

                                        @csrf
                                        @method('DELETE')

                                        <button type="submit"
                                                class="text-xs px-3 py-1 bg-red-600 text-white rounded hover:bg-red-700 transition-all">
                                            Delete
                                        </button>

                                    </form>

                                @endif

                            </div>

                        </div>

                    </div>

                @endforeach

                {{-- Pagination --}}
                <div class="flex justify-center mt-8">
                    {{ $messages->links() }}
                </div>

            </div>

        @else

            <div class="bg-white rounded-2xl border border-gray-200 p-12 text-center">

                <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="fa-solid fa-box-archive text-gray-400 text-3xl"></i>
                </div>

                <h3 class="text-2xl font-bold text-gray-800 mb-2">
                    No Archived Messages
                </h3>

                <p class="text-gray-500 mb-6">
                    Archived conversations will appear here.
                </p>

                <a href="{{ route('messages.index') }}"
                   class="inline-flex items-center px-6 py-3 bg-[#1e3a8a] text-white font-semibold rounded-full hover:bg-[#0f2b5e] transition-all">
                    Back to Inbox
                </a>

            </div>

        @endif

    </div>
</div>
@endsection
@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-gray-50 py-12">
        <div class="max-w-6xl mx-auto px-6">
            <!-- Page Header -->
            <div class="mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="text-3xl font-bold text-gray-800 mb-2">Messages</h1>
                    <p class="text-gray-600">Communicate with service providers and job applicants</p>
                </div>

                <a href="{{ route('messages.archived') }}"
                    class="inline-flex items-center justify-center px-4 py-2 bg-gray-800 text-white rounded-lg hover:bg-gray-900">
                    Archived Messages
                </a>
            </div>

            <!-- Success Messages -->
            {{-- @if (session('success'))
                <div class="mb-6 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg flex items-center gap-3">
                    <i class="fa-solid fa-check-circle"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif --}}

            @if ($messages->count() > 0)
                <div class="space-y-4">
                    @foreach ($messages as $message)
                        <div
                            class="bg-white rounded-xl border border-gray-200 hover:shadow-md transition-all overflow-hidden">
                            <!-- Message Header -->
                            <div class="p-4 border-b border-gray-100">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-3">
                                        <!-- Sender/Receiver Avatar -->
                                        @if ($message->sender->passport_photo)
                                            <img src="{{ asset('storage/' . $message->sender->passport_photo) }}"
                                                alt="{{ $message->sender->first_name }}"
                                                class="w-10 h-10 rounded-full object-cover border-2 border-gray-200">
                                        @else
                                            <div
                                                class="w-10 h-10 bg-gradient-to-br from-blue-100 to-blue-200 rounded-full flex items-center justify-center font-bold text-[#1e3a8a] text-sm">
                                                {{ substr($message->sender->first_name, 0, 1) }}{{ substr($message->sender->last_name, 0, 1) }}
                                            </div>
                                        @endif

                                        <div>
                                            <div class="flex items-center gap-2">
                                                <p class="font-semibold text-gray-900">
                                                    {{ $message->sender->id == auth()->id() ? 'You' : $message->sender->first_name . ' ' . $message->sender->last_name }}
                                                </p>
                                                @if ($message->sender->id != auth()->id())
                                                    <span class="text-xs text-gray-500">→
                                                        {{ $message->receiver->first_name }}
                                                        {{ $message->receiver->last_name }}</span>
                                                @endif
                                            </div>
                                            <p class="text-xs text-gray-500">{{ $message->formatted_created_at }}</p>
                                        </div>
                                    </div>

                                    <!-- Message Status -->
                                    <div class="flex items-center gap-2">
                                        @if ($message->status === 'sent' && $message->receiver_id == auth()->id())
                                            <span
                                                class="px-2 py-1 bg-blue-50 text-blue-600 text-xs font-medium rounded">New</span>
                                        @elseif($message->status === 'read')
                                            <span
                                                class="px-2 py-1 bg-gray-50 text-gray-600 text-xs font-medium rounded">Read</span>
                                        @elseif($message->status === 'replied')
                                            <span
                                                class="px-2 py-1 bg-green-50 text-green-600 text-xs font-medium rounded">Replied</span>
                                        @endif

                                        @if ($message->skill_id)
                                            <span class="text-xs text-gray-500">
                                                <i class="fa-solid fa-briefcase"></i> Skill
                                            </span>
                                        @elseif($message->job_id)
                                            <span class="text-xs text-gray-500">
                                                <i class="fa-solid fa-briefcase"></i> Job
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- Message Content -->
                            <div class="p-4">
                                <!-- Related Skill/Job Info -->
                                @if ($message->skill_id && $message->skill)
                                    <div class="mb-3 p-3 bg-blue-50 rounded-lg">
                                        <div class="flex items-center gap-2 text-sm">
                                            <i class="fa-solid fa-briefcase text-blue-600"></i>
                                            <span class="text-blue-800">Regarding:
                                                <strong>{{ $message->skill->title }}</strong></span>
                                        </div>
                                    </div>
                                @elseif($message->job_id && $message->job)
                                    <div class="mb-3 p-3 bg-green-50 rounded-lg">
                                        <div class="flex items-center gap-2 text-sm">
                                            <i class="fa-solid fa-briefcase text-green-600"></i>
                                            <span class="text-green-800">Regarding:
                                                <strong>{{ $message->job->title }}</strong></span>
                                        </div>
                                    </div>
                                @endif

                                <p class="text-gray-700 leading-relaxed">{{ $message->message }}</p>
                            </div>

                            <!-- Message Actions -->
                            <div class="p-4 border-t border-gray-100 bg-gray-50">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-2">
                                      @if ($message->receiver_id == auth()->id() && $message->status === 'sent')
                                            <form action="{{ route('messages.read', $message->id) }}" method="POST"
                                                class="inline">
                                                @csrf
                                                <button type="submit"
                                                    class="text-xs px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-700 transition-all">
                                                    Mark as Read
                                                </button>
                                            </form>
                                        @endif

                                       @if ($message->receiver_id == auth()->id())
                                           <button
    type="button"
    onclick='openReplyModal(
        {{ $message->id }},
        @json($message->sender->first_name),
        @json($message->message)
    )'
    class="text-xs px-3 py-1 bg-green-600 text-white rounded hover:bg-green-700 transition-all">
    Reply
</button>
                                        @endif
                                    </div>

                                    <div class="flex items-center gap-2">
                                        <form action="{{ route('messages.archive', $message->id) }}" method="POST"
                                            class="inline">
                                            @csrf

                                            <button type="submit"
                                                class="text-xs px-3 py-1 bg-gray-600 text-white rounded hover:bg-gray-700">
                                                Archive
                                            </button>
                                        </form>

                                        @if ($message->sender_id == auth()->id() || $message->receiver_id == auth()->id())
                                            <form action="{{ route('messages.destroy', $message->id) }}" method="POST"
                                                class="inline"
                                                onsubmit="return confirm('Are you sure you want to delete this message?')">
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
                        </div>
                    @endforeach

                    <!-- Pagination -->
                    <div class="flex justify-center mt-8">
                        {{ $messages->links() }}
                    </div>
                </div>
            @else
                <div class="text-center py-16">
                    <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="fa-solid fa-envelope text-gray-400 text-3xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-800 mb-2">No Messages</h3>
                    <p class="text-gray-500 mb-6">You haven't received or sent any messages yet.</p>
                    <a href="{{ route('skills.index') }}"
                        class="inline-flex items-center px-6 py-3 bg-[#1e3a8a] text-white font-semibold rounded-full hover:bg-[#0f2b5e] transition-all">
                        Browse Services
                    </a>
                </div>
            @endif
        </div>
    </div>

    <!-- Reply Modal -->
    <div id="replyModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-2xl max-w-md w-full max-h-[90vh] overflow-y-auto">
                <div class="sticky top-0 bg-white border-b border-gray-200 p-6 rounded-t-2xl">
                    <div class="flex items-center justify-between">
                        <h3 class="text-xl font-bold text-gray-900">Reply to <span id="replyToName"></span></h3>
                        <button onclick="closeReplyModal()" class="text-gray-400 hover:text-gray-600">
                            <i class="fa-solid fa-times text-xl"></i>
                        </button>
                    </div>
                </div>

                <form action="{{ route('messages.reply', '__id__') }}" method="POST" class="p-6" id="replyForm">
                    @csrf

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Original Message</label>
                        <div class="p-3 bg-gray-50 rounded-lg text-sm text-gray-600 italic" id="originalMessage"></div>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Your Reply</label>
                        <textarea name="reply" rows="4" required
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:border-[#1e3a8a] resize-none"></textarea>
                    </div>

                    <div class="flex gap-3">
                        <button type="submit"
                            class="flex-1 px-4 py-3 bg-[#1e3a8a] text-white font-semibold rounded-lg hover:bg-[#0f2b5e] transition-all">
                            <i class="fa-solid fa-paper-plane mr-2"></i>
                            Send Reply
                        </button>
                        <button type="button" onclick="closeReplyModal()"
                            class="px-4 py-3 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition-all">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
       function openReplyModal(messageId, senderName, originalMessage) {
    const modal = document.getElementById('replyModal');
    const name = document.getElementById('replyToName');
    const message = document.getElementById('originalMessage');
    const form = document.getElementById('replyForm');

    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';

    name.textContent = senderName;
    message.textContent = originalMessage;

    form.action = "{{ url('/messages') }}/" + messageId + "/reply";
}

        function closeReplyModal() {
            document.getElementById('replyModal').classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        document.getElementById('replyModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeReplyModal();
            }
        });
    </script>
@endsection

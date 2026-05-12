@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-5xl mx-auto px-4">
        <div class="mb-6 flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Services I Requested</h1>
                <p class="text-gray-600 mt-1">Services you booked from other users</p>
            </div>

            <a href="{{ route('dashboard') }}" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300">
                Back
            </a>
        </div>

        @if($myBookings->count() > 0)
            <div class="space-y-4">
                @foreach($myBookings as $booking)
                    <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm">
                        <div class="flex justify-between gap-4">
                            <div>
                                <h3 class="font-semibold text-gray-900">
                                    {{ $booking->skill->title ?? 'Skill Deleted' }}
                                </h3>

                                <p class="text-sm text-gray-500 mt-1">
                                    Provider:
                                    {{ trim(($booking->provider->first_name ?? '') . ' ' . ($booking->provider->last_name ?? '')) ?: 'N/A' }}
                                </p>

                                @if($booking->message)
                                    <div class="mt-3 bg-gray-50 border border-gray-200 rounded-lg p-3">
                                        <p class="text-xs font-semibold text-gray-500 mb-1">Your Message:</p>
                                        <p class="text-sm text-gray-700">{{ $booking->message }}</p>
                                    </div>
                                @endif
                            </div>

                            <span class="h-fit text-xs px-3 py-1 rounded-full font-medium
                                @if($booking->status === 'interested') bg-amber-50 text-amber-700
                                @elseif($booking->status === 'confirmed') bg-emerald-50 text-emerald-700
                                @elseif($booking->status === 'done') bg-blue-50 text-blue-700
                                @else bg-gray-50 text-gray-700 @endif">
                                {{ $booking->statusLabel() }}
                            </span>
                        </div>

                        <div class="mt-4 border-t pt-4 flex justify-between items-center">
                            <span class="text-xs text-gray-400">
                                Requested {{ $booking->created_at->diffForHumans() }}
                            </span>

                            @if($booking->status === 'interested')
                                <span class="text-sm text-gray-500">Waiting for provider confirmation</span>
                            @elseif($booking->status === 'confirmed')
                                <button onclick="clientConfirmBooking({{ $booking->id }})"
                                        class="px-4 py-2 bg-blue-500 text-white text-sm rounded-lg hover:bg-blue-600">
                                    Mark as Done
                                </button>
                            @elseif($booking->status === 'done')
                                <span class="text-sm text-green-600">✓ Service Completed</span>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="bg-white rounded-xl border border-gray-200 p-10 text-center">
                <p class="text-gray-500">You have not requested any services yet.</p>
                <a href="{{ route('skills.index') }}" class="inline-block mt-4 px-5 py-2 bg-[#1e3a8a] text-white rounded-lg">
                    Browse Skills
                </a>
            </div>
        @endif
    </div>
</div>

<script>
function clientConfirmBooking(bookingId) {
    if (!confirm('Mark this service as received and done?')) return;

    fetch(`/bookings/${bookingId}/client-confirm`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) location.reload();
        else alert(data.message || 'Error confirming completion');
    })
    .catch(() => alert('Error confirming completion'));
}
</script>
@endsection
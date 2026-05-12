@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        <div class="mb-8 flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">My Bookings</h1>
                <p class="text-gray-600 mt-2">Manage services you requested and bookings for your own skills</p>
            </div>

            <a href="{{ route('skills.index') }}"
               class="px-4 py-2 bg-[#1e3a8a] text-white rounded-lg hover:bg-[#0f2b5e] transition-colors">
                <i class="fa-solid fa-search mr-2"></i>Browse Skills
            </a>
        </div>

        <!-- Services I Requested -->
        <div class="mb-10">
            <h2 class="text-xl font-bold text-gray-900 mb-4">
                <i class="fa-solid fa-hand-point-up text-blue-500 mr-2"></i>
                Services I Requested
            </h2>

            @if($myBookings->count() > 0)
                <div class="space-y-4">
                    @foreach($myBookings as $booking)
                        <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm">
                            <div class="flex flex-col sm:flex-row justify-between gap-4">
                                <div class="flex-1">
                                    <h3 class="font-semibold text-gray-900">
                                        {{ $booking->skill->title }}
                                    </h3>

                                    <p class="text-sm text-gray-500 mt-1">
                                        Provider: {{ $booking->provider->fullName() }}
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

                            <div class="mt-4 border-t pt-4">
                                <div class="flex flex-col sm:flex-row justify-between gap-3">
                                    <span class="text-xs text-gray-400">
                                        Requested {{ $booking->created_at->diffForHumans() }}
                                    </span>

                                    @if($booking->status === 'interested')
                                        <span class="text-sm text-gray-500">Waiting for provider confirmation</span>

                                    @elseif($booking->status === 'confirmed')
                                        @if($booking->client_confirmed_at)
                                            <span class="text-sm text-amber-600">You confirmed — waiting for provider</span>
                                        @else
                                            <button onclick="clientConfirmBooking({{ $booking->id }})"
                                                    class="px-4 py-2 bg-blue-500 text-white text-sm rounded-lg hover:bg-blue-600">
                                                Mark as Done
                                            </button>
                                        @endif

                                    @elseif($booking->status === 'done')
                                        @php
                                            $hasRated = $booking->ratings
                                                ->where('reviewer_id', auth()->id())
                                                ->count() > 0;
                                        @endphp

                                        @if(!$hasRated)
                                            <div class="w-full">
                                                <p class="mb-3 text-sm text-green-700 font-medium">
                                                    ✓ Service Completed
                                                </p>

                                                <div class="p-4 bg-white border border-gray-200 rounded-xl">
                                                    <h6 class="font-semibold text-gray-800 mb-3">
                                                        Rate Your Experience
                                                    </h6>

                                                    <div class="flex gap-1 mb-3" id="booking-rating-stars-{{ $booking->id }}">
                                                        @for($i = 1; $i <= 5; $i++)
                                                            <button type="button"
                                                                    onclick="setBookingInlineRating({{ $booking->id }}, {{ $i }})"
                                                                    class="text-3xl text-gray-300 hover:text-yellow-400 transition-colors">
                                                                ★
                                                            </button>
                                                        @endfor
                                                    </div>

                                                    <textarea id="booking-review-{{ $booking->id }}"
                                                              placeholder="Share your experience (optional)"
                                                              class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm mb-3"
                                                              rows="3"
                                                              maxlength="500"></textarea>

                                                    <button onclick="submitBookingInlineRating({{ $booking->id }})"
                                                            class="w-full px-4 py-2 bg-[#1e3a8a] text-white rounded-lg hover:bg-[#0f2b5e]">
                                                        <i class="fa-solid fa-star mr-2"></i>
                                                        Submit Rating
                                                    </button>
                                                </div>
                                            </div>
                                        @else
                                            <span class="text-sm text-green-600">✓ Service Completed & Rated</span>
                                        @endif
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="bg-white rounded-xl border border-gray-200 p-8 text-center">
                    <p class="text-gray-500">You have not requested any services yet.</p>
                </div>
            @endif
        </div>

        <!-- Bookings for My Skills -->
        <div>
            <h2 class="text-xl font-bold text-gray-900 mb-4">
                <i class="fa-solid fa-calendar-check text-emerald-500 mr-2"></i>
                Bookings for My Skills
            </h2>

            @if($myServiceBookings->count() > 0)
                <div class="space-y-4">
                    @foreach($myServiceBookings as $booking)
                        <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm">
                            <div class="flex flex-col sm:flex-row justify-between gap-4">
                                <div class="flex-1">
                                    <h3 class="font-semibold text-gray-900">
                                        {{ $booking->skill->title }}
                                    </h3>

                                    <p class="text-sm text-gray-500 mt-1">
                                        Client: {{ $booking->client->fullName() }}
                                    </p>

                                    @if($booking->message)
                                        <div class="mt-3 bg-blue-50 border border-blue-200 rounded-lg p-3">
                                            <p class="text-xs font-semibold text-blue-700 mb-1">Client Message:</p>
                                            <p class="text-sm text-blue-800">{{ $booking->message }}</p>
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

                            <div class="mt-4 border-t pt-4">
                                <div class="flex flex-col sm:flex-row justify-between gap-3">
                                    <span class="text-xs text-gray-400">
                                        Requested {{ $booking->created_at->diffForHumans() }}
                                    </span>

                                    <div class="flex flex-col sm:flex-row gap-2">
                                        @if($booking->status === 'interested')
                                            <button onclick="confirmBooking({{ $booking->id }})"
                                                    class="px-4 py-2 bg-emerald-500 text-white text-sm rounded-lg hover:bg-emerald-600">
                                                Confirm
                                            </button>

                                            <button onclick="declineBooking({{ $booking->id }})"
                                                    class="px-4 py-2 bg-red-500 text-white text-sm rounded-lg hover:bg-red-600">
                                                Decline
                                            </button>

                                        @elseif($booking->status === 'confirmed')
                                            @if($booking->provider_confirmed_at)
                                                <span class="text-sm text-amber-600">You confirmed — waiting for client</span>
                                            @else
                                                <button onclick="providerConfirmBooking({{ $booking->id }})"
                                                        class="px-4 py-2 bg-blue-500 text-white text-sm rounded-lg hover:bg-blue-600">
                                                    Mark as Done
                                                </button>
                                            @endif

                                        @elseif($booking->status === 'done')
                                            @php
                                                $hasRated = $booking->ratings
                                                    ->where('reviewer_id', auth()->id())
                                                    ->count() > 0;
                                            @endphp

                                            @if(!$hasRated)
                                                <div class="w-full">
                                                    <p class="mb-3 text-sm text-green-700 font-medium">
                                                        ✓ Service Completed
                                                    </p>

                                                    <div class="p-4 bg-white border border-gray-200 rounded-xl">
                                                        <h6 class="font-semibold text-gray-800 mb-3">
                                                            Rate Client
                                                        </h6>

                                                        <div class="flex gap-1 mb-3" id="booking-rating-stars-{{ $booking->id }}">
                                                            @for($i = 1; $i <= 5; $i++)
                                                                <button type="button"
                                                                        onclick="setBookingInlineRating({{ $booking->id }}, {{ $i }})"
                                                                        class="text-3xl text-gray-300 hover:text-yellow-400 transition-colors">
                                                                    ★
                                                                </button>
                                                            @endfor
                                                        </div>

                                                        <textarea id="booking-review-{{ $booking->id }}"
                                                                  placeholder="Share your experience (optional)"
                                                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm mb-3"
                                                                  rows="3"
                                                                  maxlength="500"></textarea>

                                                        <button onclick="submitBookingInlineRating({{ $booking->id }})"
                                                                class="w-full px-4 py-2 bg-[#1e3a8a] text-white rounded-lg hover:bg-[#0f2b5e]">
                                                            <i class="fa-solid fa-star mr-2"></i>
                                                            Submit Rating
                                                        </button>
                                                    </div>
                                                </div>
                                            @else
                                                <span class="text-sm text-green-600">✓ Service Completed & Rated</span>
                                            @endif
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="bg-white rounded-xl border border-gray-200 p-8 text-center">
                    <p class="text-gray-500">No one has booked your skills yet.</p>
                </div>
            @endif
        </div>
    </div>
</div>

<script>
function confirmBooking(bookingId) {
    if (!confirm('Confirm this booking?')) return;

    fetch(`/bookings/${bookingId}/confirm`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) location.reload();
        else alert(data.message || 'Error confirming booking');
    })
    .catch(() => alert('Error confirming booking'));
}

function declineBooking(bookingId) {
    if (!confirm('Decline this booking?')) return;

    fetch(`/bookings/${bookingId}/decline`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) location.reload();
        else alert(data.message || 'Error declining booking');
    })
    .catch(() => alert('Error declining booking'));
}

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

function providerConfirmBooking(bookingId) {
    if (!confirm('Mark this service as delivered and done?')) return;

    fetch(`/bookings/${bookingId}/provider-confirm`, {
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

function setBookingInlineRating(bookingId, rating) {
    const container = document.getElementById(`booking-rating-stars-${bookingId}`);

    if (!container) return;

    container.setAttribute('data-selected', rating);

    const stars = container.querySelectorAll('button');

    stars.forEach((star, index) => {
        star.classList.remove('text-yellow-400', 'text-gray-300');

        if (index < rating) {
            star.classList.add('text-yellow-400');
        } else {
            star.classList.add('text-gray-300');
        }
    });
}

function submitBookingInlineRating(bookingId) {
    const container = document.getElementById(`booking-rating-stars-${bookingId}`);
    const rating = container ? container.getAttribute('data-selected') : null;

    if (!rating) {
        alert('Please select a star rating before submitting.');
        return;
    }

    const review = document.getElementById(`booking-review-${bookingId}`)?.value || '';

    const button = document.querySelector(`[onclick="submitBookingInlineRating(${bookingId})"]`);

    if (button) {
        button.disabled = true;
        button.innerHTML = '<i class="fa-solid fa-spinner fa-spin mr-2"></i>Submitting...';
    }

    fetch(`/bookings/${bookingId}/rate`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            rating: parseInt(rating),
            review: review
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.message || 'Rating submitted successfully!');
            location.reload();
        } else {
            if (button) {
                button.disabled = false;
                button.innerHTML = '<i class="fa-solid fa-star mr-2"></i>Submit Rating';
            }

            alert(data.message || 'Error submitting rating');
        }
    })
    .catch(error => {
        console.error('Error:', error);

        if (button) {
            button.disabled = false;
            button.innerHTML = '<i class="fa-solid fa-star mr-2"></i>Submit Rating';
        }

        alert('Error submitting rating');
    });
}
</script>
@endsection
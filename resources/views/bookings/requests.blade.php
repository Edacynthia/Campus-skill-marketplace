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

            @if ($myBookings->count() > 0)
                <div class="space-y-4">
                    @foreach ($myBookings as $booking)
                        <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm">

                            <div class="flex flex-col sm:flex-row justify-between gap-4">
                                <div class="flex-1">
                                    <h3 class="font-semibold text-gray-900">
                                        {{ $booking->skill->title ?? 'Skill Deleted' }}
                                    </h3>

                                    <p class="text-sm text-gray-500 mt-1">
                                        Provider:
                                        {{ trim(($booking->provider->first_name ?? '') . ' ' . ($booking->provider->last_name ?? '')) ?: 'N/A' }}
                                    </p>

                                    @if ($booking->message)
                                        <div class="mt-3 bg-gray-50 border border-gray-200 rounded-lg p-3">
                                            <p class="text-xs font-semibold text-gray-500 mb-1">Your Message:</p>
                                            <p class="text-sm text-gray-700">{{ $booking->message }}</p>
                                        </div>
                                    @endif
                                </div>

                                <span
                                    class="h-fit text-xs px-3 py-1 rounded-full font-medium
                                @if ($booking->status === 'interested') bg-amber-50 text-amber-700
                                @elseif($booking->status === 'confirmed') bg-blue-50 text-blue-700
                                @elseif($booking->status === 'in_progress') bg-indigo-50 text-indigo-700
                                @elseif($booking->status === 'completed_waiting_payment') bg-emerald-50 text-emerald-700
                                @elseif($booking->status === 'done') bg-green-50 text-green-700
                                @else bg-red-50 text-red-700 @endif">
                                    {{ $booking->statusLabel() }}
                                </span>
                            </div>

                            <div class="mt-4 border-t pt-4 space-y-5">

                                <div>
                                    <span class="text-xs text-gray-400">
                                        Requested {{ $booking->created_at->diffForHumans() }}
                                    </span>
                                </div>

                                @if ($booking->status === 'interested')
                                    <span class="text-sm text-gray-500">
                                        Waiting for provider confirmation
                                    </span>
                                @elseif($booking->status === 'declined')
                                    <div class="p-4 bg-red-50 border border-red-200 rounded-lg">
                                        <p class="font-semibold text-red-700">
                                            Booking Declined
                                        </p>

                                        <p class="text-sm text-red-600 mt-1">
                                            The provider declined this booking request.
                                            You can submit a new booking request for this skill if you still need the
                                            service.
                                        </p>

                                        <a href="{{ route('skills.show', $booking->skill_id) }}"
                                            class="inline-block mt-3 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                                            Book Again
                                        </a>
                                    </div>
                                @else
                                    <div class="w-full">
                                        <div class="flex items-center gap-2 flex-wrap">

                                            <div
                                                class="px-3 py-2 rounded-full text-xs font-semibold
                {{ in_array($booking->status, ['confirmed', 'in_progress', 'completed_waiting_payment', 'done']) ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-600' }}">
                                                Confirmed
                                            </div>

                                            <div class="w-8 h-1 bg-gray-300"></div>

                                            <div
                                                class="px-3 py-2 rounded-full text-xs font-semibold
                {{ in_array($booking->status, ['in_progress', 'completed_waiting_payment', 'done']) ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-600' }}">
                                                In Progress
                                            </div>

                                            <div class="w-8 h-1 bg-gray-300"></div>

                                            <div
                                                class="px-3 py-2 rounded-full text-xs font-semibold
                {{ in_array($booking->status, ['completed_waiting_payment', 'done']) ? 'bg-emerald-600 text-white' : 'bg-gray-200 text-gray-600' }}">
                                                Completed
                                            </div>

                                            <div class="w-8 h-1 bg-gray-300"></div>

                                            <div
                                                class="px-3 py-2 rounded-full text-xs font-semibold
                {{ $booking->status === 'completed_waiting_payment' && $booking->payment_status !== 'provider_confirmed_received' ? 'bg-amber-500 text-white' : 'bg-gray-200 text-gray-600' }}">
                                                Waiting Payment
                                            </div>

                                            <div class="w-8 h-1 bg-gray-300"></div>

                                            <div
                                                class="px-3 py-2 rounded-full text-xs font-semibold
                {{ $booking->status === 'done' && $booking->payment_status === 'provider_confirmed_received' ? 'bg-green-600 text-white' : 'bg-gray-200 text-gray-600' }}">
                                                Paid
                                            </div>

                                        </div>
                                    </div>
                                @endif

                                @if ($booking->status === 'completed_waiting_payment' && $booking->payment_status === 'unpaid')
                                    <button onclick="clientMarkedPaid({{ $booking->id }})"
                                        class="px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700">
                                        I Have Paid
                                    </button>
                                @elseif($booking->payment_status === 'client_marked_paid')
                                    <div
                                        class="px-4 py-2 bg-amber-50 border border-amber-200 text-amber-700 rounded-lg text-sm">
                                        Waiting for provider to confirm payment.
                                    </div>
                                @elseif($booking->payment_status === 'payment_disputed')
                                    <div class="p-4 bg-red-50 border border-red-200 rounded-lg">
                                        <p class="text-sm text-red-700 font-semibold">
                                            Payment dispute opened. Admin reviewing issue.
                                        </p>

                                        @if ($booking->payment_dispute_reason)
                                            <p class="text-sm text-red-600 mt-1">
                                                Provider's message: {{ $booking->payment_dispute_reason }}
                                            </p>
                                        @endif
                                    </div>
                                @endif

                                @if ($booking->status === 'done' && $booking->payment_status === 'provider_confirmed_received')
                                    @php
                                        $hasRated = $booking->ratings->where('reviewer_id', auth()->id())->count() > 0;
                                    @endphp

                                    @if (!$hasRated)
                                        <div class="p-4 bg-white border border-gray-200 rounded-xl max-w-md">
                                            <h6 class="font-semibold text-gray-800 mb-3">
                                                Rate Your Experience
                                            </h6>

                                            <div class="flex gap-1 mb-3" id="booking-rating-stars-{{ $booking->id }}">
                                                @for ($i = 1; $i <= 5; $i++)
                                                    <button type="button"
                                                        onclick="setBookingInlineRating({{ $booking->id }}, {{ $i }})"
                                                        class="text-3xl text-gray-300 hover:text-yellow-400 transition-colors">
                                                        ★
                                                    </button>
                                                @endfor
                                            </div>

                                            <textarea id="booking-review-{{ $booking->id }}" placeholder="Share your experience (optional)"
                                                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm mb-3" rows="3" maxlength="500"></textarea>

                                            <button onclick="submitBookingInlineRating({{ $booking->id }})"
                                                class="w-full px-4 py-2 bg-[#1e3a8a] text-white rounded-lg hover:bg-[#0f2b5e]">
                                                <i class="fa-solid fa-star mr-2"></i>
                                                Submit Rating
                                            </button>
                                        </div>
                                    @else
                                        <div class="p-3 bg-emerald-50 border border-emerald-200 rounded-lg">
                                            <p class="text-sm text-emerald-800">
                                                <i class="fa-solid fa-check-circle mr-2"></i>
                                                Service Completed & Rated
                                            </p>
                                        </div>
                                    @endif
                                @endif

                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="bg-white rounded-xl border border-gray-200 p-10 text-center">
                    <p class="text-gray-500">You have not requested any services yet.</p>

                    <a href="{{ route('skills.index') }}"
                        class="inline-block mt-4 px-5 py-2 bg-[#1e3a8a] text-white rounded-lg">
                        Browse Skills
                    </a>
                </div>
            @endif
        </div>
    </div>

    <script>
        function clientMarkedPaid(bookingId) {
            if (!confirm('Confirm that you have paid the provider?')) return;

            fetch(`/bookings/${bookingId}/client-paid`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) location.reload();
                    else alert(data.message || 'Error confirming payment');
                })
                .catch(() => alert('Error confirming payment'));
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
                        alert(data.message || 'Error submitting rating');
                    }
                })
                .catch(() => alert('Error submitting rating'));
        }
    </script>
@endsection

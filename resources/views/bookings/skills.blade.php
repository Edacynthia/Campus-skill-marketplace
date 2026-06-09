@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-gray-50 py-8">
        <div class="max-w-5xl mx-auto px-4">

            <div class="mb-6 flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Bookings For My Skills</h1>
                    <p class="text-gray-600 mt-1">Manage people who booked your skills</p>
                </div>

                <a href="{{ auth()->user()->hasRole('admin') || auth()->user()->role === 'admin' ? route('admin.dashboard') : route('dashboard') }}"
                    class="px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300">
                    Back
                </a>
            </div>

            @if ($myServiceBookings->count() > 0)
                <div class="space-y-4">
                    @foreach ($myServiceBookings as $booking)
                        <div id="booking-card-{{ $booking->id }}"
                            class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm mb-6">

                            <div class="flex flex-col sm:flex-row justify-between gap-4">
                                <div class="flex-1">
                                    <h3 class="font-semibold text-gray-900">
                                        {{ $booking->skill->title ?? 'Skill Deleted' }}
                                    </h3>

                                    <p class="text-sm text-gray-500 mt-1">
                                        Client:
                                        {{ trim(($booking->client->first_name ?? '') . ' ' . ($booking->client->last_name ?? '')) ?: 'N/A' }}
                                    </p>

                                    @if ($booking->message)
                                        <div class="mt-3 bg-blue-50 border border-blue-200 rounded-lg p-3">
                                            <p class="text-xs font-semibold text-blue-700 mb-1">Client Message:</p>
                                            <p class="text-sm text-blue-800">{{ $booking->message }}</p>
                                        </div>
                                    @endif
                                </div>

                                <div class="flex items-start gap-3">
                                    <span
                                        class="h-fit text-xs px-3 py-1 rounded-full font-medium
        @if ($booking->status === 'interested') bg-amber-50 text-amber-700
        @elseif($booking->status === 'confirmed') bg-emerald-50 text-emerald-700
        @elseif($booking->status === 'in_progress') bg-indigo-50 text-indigo-700
        @elseif($booking->status === 'completed_waiting_payment') bg-blue-50 text-blue-700
        @elseif($booking->status === 'done') bg-green-50 text-green-700
        @elseif($booking->status === 'declined') bg-red-50 text-red-700
        @else bg-gray-50 text-gray-700 @endif">
                                        {{ $booking->statusLabel() }}
                                    </span>

                                    @if (in_array($booking->status, ['done', 'declined']) || in_array($booking->escrow_status, ['released', 'disputed']))
                                        <button onclick="deleteProviderBooking({{ $booking->id }})"
                                            class="text-red-500 hover:text-red-700 transition" title="Delete booking">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    @endif
                                </div>
                            </div>

                            <div class="mt-4 border-t pt-4 space-y-5">
                                <div>
                                    <span class="text-xs text-gray-400">
                                        Requested {{ $booking->created_at->diffForHumans() }}
                                    </span>
                                </div>

                                @if ($booking->status === 'interested')
                                    <div class="flex gap-2 flex-wrap">
                                        <button onclick="confirmBooking({{ $booking->id }})"
                                            class="px-4 py-2 bg-emerald-500 text-white text-sm rounded-lg hover:bg-emerald-600">
                                            Confirm Booking
                                        </button>

                                        <button onclick="declineBooking({{ $booking->id }})"
                                            class="px-4 py-2 bg-red-500 text-white text-sm rounded-lg hover:bg-red-600">
                                            Decline
                                        </button>
                                    </div>
                                @elseif($booking->status === 'declined')
                                    <div class="p-4 bg-red-50 border border-red-200 rounded-lg">
                                        <p class="font-semibold text-red-700">Booking Declined</p>
                                        <p class="text-sm text-red-600 mt-1">
                                            You declined this booking request.
                                        </p>
                                    </div>
                                @else
                                    @if ($booking->status === 'confirmed' && $booking->escrow_status === 'not_funded')
                                        <div class="p-4 bg-amber-50 border border-amber-200 rounded-lg">
                                            <p class="font-semibold text-amber-700">Waiting For Escrow Funding</p>
                                            <p class="text-sm text-amber-600 mt-1">
                                                Do not begin work until payment has been secured.
                                            </p>
                                        </div>
                                    @endif

                                    @if ($booking->escrow_status === 'funded')
                                        <div class="p-4 bg-emerald-50 border border-emerald-200 rounded-lg">
                                            <p class="font-semibold text-emerald-700">Payment Secured</p>
                                            <p class="text-sm text-emerald-600 mt-1">
                                                Escrow payment has been received. You may begin work.
                                            </p>
                                        </div>
                                    @endif

                                    @if ($booking->escrow_status === 'completed')
                                        <div class="p-4 bg-blue-50 border border-blue-200 rounded-lg">
                                            <p class="font-semibold text-blue-700">Service Completed</p>
                                            <p class="text-sm text-blue-600 mt-1">
                                                Waiting for the client to confirm service received or open a dispute.
                                            </p>
                                        </div>
                                    @endif

                                    @if ($booking->escrow_status === 'disputed')
                                        <div class="p-4 bg-red-50 border border-red-200 rounded-lg">
                                            <p class="font-semibold text-red-700">Escrow Dispute Opened</p>
                                            <p class="text-sm text-red-600 mt-1">
                                                Admin is reviewing this booking before payment can be released.
                                            </p>
                                        </div>
                                    @endif

                                    @if ($booking->escrow_status === 'released')
                                        <div class="p-4 bg-green-50 border border-green-200 rounded-lg">

                                            <p class="font-semibold text-green-700">
                                                🎉 Payment Successfully Released To You
                                            </p>

                                            <p class="text-sm text-green-600 mt-1">
                                                The client has confirmed that the service was completed satisfactorily.
                                                Escrow funds have now been released to you.
                                            </p>

                                            <div class="mt-3 p-3 bg-white rounded-lg border border-green-100">

                                                <p class="text-sm">
                                                    Total Service Amount:
                                                    <strong>
                                                        ₦{{ number_format($booking->escrow_amount ?? $booking->skill->price, 2) }}
                                                    </strong>
                                                </p>

                                                <p class="text-sm">
                                                    Campus Connect Fee:
                                                    <strong>
                                                        ₦{{ number_format($booking->platform_fee ?? 0, 2) }}
                                                    </strong>
                                                </p>

                                                <p class="text-sm font-semibold text-green-700">
                                                    Amount Released To You:
                                                    ₦{{ number_format($booking->provider_payout ?? $booking->skill->price, 2) }}
                                                </p>

                                            </div>

                                            <div class="mt-3 p-3 bg-green-100 border border-green-200 rounded-lg">
                                                <p class="text-sm font-medium text-green-800">
                                                    <i class="fa-solid fa-circle-check mr-2"></i>
                                                    Payment has been credited to your Campus Connect escrow balance.
                                                </p>
                                            </div>

                                            <p class="text-xs text-green-600 mt-3">
                                                This booking is now complete and you can leave a rating and review for the
                                                client.
                                            </p>

                                        </div>
                                    @endif

                                    <div class="w-full mt-5 mb-5 pb-5 border-b border-gray-200">
                                        <div class="flex items-center gap-2 flex-wrap">

                                            <div
                                                class="px-3 py-2 rounded-full text-xs font-semibold
                                            {{ in_array($booking->status, ['confirmed', 'in_progress', 'completed_waiting_payment', 'done']) ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-600' }}">
                                                Confirmed
                                            </div>

                                            <div class="w-8 h-1 bg-gray-300"></div>

                                            @if ($booking->escrow_status === 'funded')
                                                <button onclick="updateProgress({{ $booking->id }}, 'in_progress')"
                                                    class="px-3 py-2 rounded-full text-xs font-semibold
                                                    {{ in_array($booking->status, ['in_progress', 'completed_waiting_payment', 'done']) ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-600' }}">
                                                    In Progress
                                                </button>
                                            @else
                                                <div
                                                    class="px-3 py-2 rounded-full text-xs font-semibold
                                                {{ in_array($booking->status, ['in_progress', 'completed_waiting_payment', 'done']) ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-600' }}">
                                                    In Progress
                                                </div>
                                            @endif

                                            <div class="w-8 h-1 bg-gray-300"></div>

                                            @if ($booking->escrow_status === 'funded')
                                                <button onclick="providerMarkCompleted({{ $booking->id }})"
                                                    class="px-3 py-2 rounded-full text-xs font-semibold
                                                    {{ in_array($booking->status, ['completed_waiting_payment', 'done']) ? 'bg-emerald-600 text-white' : 'bg-gray-200 text-gray-600' }}">
                                                    Completed
                                                </button>
                                            @else
                                                <div
                                                    class="px-3 py-2 rounded-full text-xs font-semibold
                                                {{ in_array($booking->status, ['completed_waiting_payment', 'done']) ? 'bg-emerald-600 text-white' : 'bg-gray-200 text-gray-600' }}">
                                                    Completed
                                                </div>
                                            @endif

                                            <div class="w-8 h-1 bg-gray-300"></div>

                                            <div
                                                class="px-3 py-2 rounded-full text-xs font-semibold
                                            {{ $booking->escrow_status === 'released' ? 'bg-green-600 text-white' : 'bg-gray-200 text-gray-600' }}">
                                                Released
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                @if ($booking->status === 'done' && $booking->payment_status === 'provider_confirmed_received')
                                    @php
                                        $hasRated = $booking->ratings->where('reviewer_id', auth()->id())->count() > 0;
                                    @endphp

                                    @if (!$hasRated)
                                        <div class="p-4 bg-white border border-gray-200 rounded-xl max-w-md">
                                            <h6 class="font-semibold text-gray-800 mb-3">Rate Client</h6>

                                            <div class="flex gap-1 mb-3" id="booking-rating-stars-{{ $booking->id }}">
                                                @for ($i = 1; $i <= 5; $i++)
                                                    <button type="button"
                                                        onclick="setBookingInlineRating({{ $booking->id }}, {{ $i }})"
                                                        class="text-3xl text-gray-300 hover:text-yellow-400 transition-colors">
                                                        ★
                                                    </button>
                                                @endfor
                                            </div>

                                            <textarea id="booking-review-{{ $booking->id }}" placeholder="Share your experience with this client (optional)"
                                                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm mb-3" rows="3" maxlength="500"></textarea>

                                            <button onclick="submitBookingInlineRating({{ $booking->id }})"
                                                class="w-full px-4 py-2 bg-[#1e3a8a] text-white rounded-lg hover:bg-[#0f2b5e]">
                                                <i class="fa-solid fa-star mr-2"></i>
                                                Submit Rating
                                            </button>
                                        </div>
                                    @else
                                        <div class="mt-5 p-4 bg-emerald-50 border border-emerald-200 rounded-lg">
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
                    <p class="text-gray-500">No one has booked your skills yet.</p>
                </div>
            @endif
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
                    if (data.success) {
                        alert(data.message || 'Booking confirmed successfully.');
                        location.reload();
                    } else {
                        alert(data.message || 'Error confirming booking');
                    }
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
                    if (data.success) {
                        alert(data.message || 'Booking declined successfully.');
                        location.reload();
                    } else {
                        alert(data.message || 'Error declining booking');
                    }
                })
                .catch(() => alert('Error declining booking'));
        }

        function updateProgress(bookingId, status) {
            if (!confirm('Update booking progress?')) return;

            fetch(`/bookings/${bookingId}/update-progress`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        status: status
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert(data.message || 'Progress updated successfully.');
                        location.reload();
                    } else {
                        alert(data.message || 'Error updating progress');
                    }
                })
                .catch(() => alert('Error updating progress'));
        }

        function providerMarkCompleted(bookingId) {
            if (!confirm('Mark this service as completed?')) return;

            fetch(`/bookings/${bookingId}/provider-completed`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    }
                })
                .then(r => r.json())
                .then(data => {
                    if (data.success) {
                        alert(data.message);
                        location.reload();
                    } else {
                        alert(data.message);
                    }
                });
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

        function deleteProviderBooking(id) {

            if (!confirm('Delete this booking from your list?')) {
                return;
            }

            fetch(`/bookings/${id}/provider-delete`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    }
                })
                .then(r => r.json())
                .then(data => {

                    if (data.success) {

                        const card = document.getElementById(`booking-card-${id}`);

                        if (card) {

                            card.style.transition = 'all 0.3s ease';
                            card.style.opacity = '0';

                            setTimeout(() => {
                                card.remove();
                            }, 300);
                        }

                        const toast = document.createElement('div');

                        toast.className =
                            'fixed top-5 right-5 bg-emerald-600 text-white px-5 py-3 rounded-xl shadow-lg z-50';

                        toast.innerHTML =
                            '<i class="fa-solid fa-check-circle mr-2"></i>' +
                            (data.message || 'Booking deleted successfully.');

                        document.body.appendChild(toast);

                        setTimeout(() => {
                            toast.remove();
                        }, 3000);

                    } else {

                        alert(data.message || 'Could not delete booking.');

                    }

                })
                .catch(() => {
                    alert('Error deleting booking.');
                });
        }
    </script>
@endsection

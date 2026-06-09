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
                        <div id="booking-card-{{ $booking->id }}"
                            class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm mb-6">

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

                                <div class="flex items-start gap-3">
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

                                    @if (in_array($booking->status, ['done', 'declined']) || in_array($booking->escrow_status, ['released', 'disputed']))
                                        <button onclick="deleteClientBooking({{ $booking->id }})"
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

                                @if ($booking->status === 'confirmed' && $booking->escrow_status === 'not_funded')
                                    <form action="{{ route('bookings.payEscrow', $booking->id) }}"method="POST"
                                        onsubmit="showEscrowLoading({{ $booking->id }})">
                                        @csrf

                                        <button type="submit" id="escrowBtn-{{ $booking->id }}"
                                            class="px-4 py-2 bg-[#1e3a8a] text-white rounded-lg hover:bg-[#0f2b5e] transition-all">

                                            <span class="escrow-btn-text">
                                                Pay Into Escrow
                                            </span>

                                            <span class="escrow-btn-loading hidden">
                                                <i class="fa-solid fa-spinner fa-spin mr-2"></i>
                                                Redirecting...
                                            </span>

                                        </button>
                                    </form>
                                @elseif($booking->escrow_status === 'funded')
                                    <div class="p-4 bg-blue-50 border border-blue-200 rounded-lg">
                                        <p class="font-semibold text-blue-700">
                                            Payment Secured In Escrow
                                        </p>

                                        <p class="text-sm text-blue-600 mt-1">
                                            The provider has been notified and can begin work.
                                        </p>
                                    </div>
                                @elseif($booking->escrow_status === 'completed')
                                    <div class="flex flex-wrap gap-3">

                                        <button onclick="showReleaseConfirmation({{ $booking->id }})"
                                            class="px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700">
                                            Confirm Service Received
                                        </button>

                                        <button onclick="showDisputeBox({{ $booking->id }})"
                                            class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
                                            Open Dispute
                                        </button>

                                    </div>

                                    <div id="escrow-dispute-box-{{ $booking->id }}" class="hidden mt-3">

                                        <textarea id="escrow-dispute-reason-{{ $booking->id }}" rows="3" class="w-full border rounded-lg p-3"
                                            placeholder="Explain the issue with the completed work"></textarea>

                                        <button onclick="openEscrowDispute({{ $booking->id }})"
                                            class="mt-2 px-4 py-2 bg-red-700 text-white rounded-lg">
                                            Submit Dispute
                                        </button>

                                    </div>
                                @elseif($booking->escrow_status === 'disputed')
                                    <div class="p-4 bg-red-50 border border-red-200 rounded-lg">
                                        <p class="font-semibold text-red-700">
                                            Escrow Dispute Opened
                                        </p>

                                        <p class="text-sm text-red-600 mt-1">
                                            Admin is reviewing the dispute.
                                        </p>
                                    </div>
                                @elseif($booking->escrow_status === 'released')
                                    <div class="p-4 bg-green-50 border border-green-200 rounded-lg">
                                        <p class="font-semibold text-green-700">
                                            ✅ Service Confirmed & Payment Released
                                        </p>

                                        <p class="text-sm text-green-600 mt-1">
                                            You confirmed that the service was completed satisfactorily.
                                            The escrow payment has been released to the provider.
                                        </p>

                                        <div class="mt-3 p-3 bg-white rounded-lg border border-green-100">
                                            <p class="text-sm">
                                                Service Amount:
                                                <strong>₦{{ number_format($booking->escrow_amount ?? $booking->skill->price, 2) }}</strong>
                                            </p>

                                            <p class="text-sm text-green-700 font-medium">
                                                Provider has been paid successfully.
                                            </p>
                                        </div>

                                        <p class="text-xs text-green-600 mt-3">
                                            You can now leave a rating and review for this provider.
                                        </p>
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

    <div id="releasePaymentModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50">

        <div class="bg-white rounded-2xl shadow-2xl max-w-lg w-full mx-4">

            <div class="p-6">

                <div class="flex items-center gap-3 mb-4">
                    <div class="w-12 h-12 rounded-full bg-amber-100 flex items-center justify-center">
                        <i class="fa-solid fa-triangle-exclamation text-amber-600 text-xl"></i>
                    </div>

                    <div>
                        <h3 class="text-lg font-bold text-gray-900">
                            Confirm Service Completion
                        </h3>
                    </div>
                </div>

                <div class="space-y-3 text-sm text-gray-700">

                    <p>
                        You are about to release the escrow payment to the provider.
                    </p>

                    <ul class="space-y-2">
                        <li>✓ The service was completed satisfactorily</li>
                        <li>✓ The work delivered matches what was agreed</li>
                        <li>✓ You do not have any unresolved issues</li>
                        <li>✓ You do not wish to open a dispute</li>
                    </ul>

                    <div class="p-3 rounded-lg bg-red-50 border border-red-200">
                        <p class="text-red-700 font-medium">
                            Once payment is released, it may not be possible to recover the funds through the escrow system.
                        </p>
                    </div>

                    <p>
                        If there is any issue with the service, close this window and open a dispute instead.
                    </p>
                </div>

                <div class="flex flex-wrap gap-3 mt-6">

                    <button onclick="closeReleaseConfirmation()"
                        class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">

                        Cancel

                    </button>

                    <button onclick="goToDispute()" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">

                        Open Dispute Instead

                    </button>

                    <button id="confirmReleaseBtn"
                        class="ml-auto px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">

                        Yes, Release Payment

                    </button>

                </div>

            </div>

        </div>

    </div>

    <script>
        function confirmServiceReceived(bookingId) {
            if (!confirm('Confirm that the service was completed successfully?')) return;

            fetch(`/bookings/${bookingId}/confirm-received`, {
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

        function showDisputeBox(bookingId) {
            const box = document.getElementById(`escrow-dispute-box-${bookingId}`);

            if (box) {
                box.classList.toggle('hidden');
            }
        }

        function openEscrowDispute(bookingId) {
            const reason =
                document.getElementById(`escrow-dispute-reason-${bookingId}`).value;

            if (!reason.trim()) {
                alert('Please explain the issue.');
                return;
            }

            fetch(`/bookings/${bookingId}/escrow-dispute`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        reason: reason
                    })
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

        function deleteClientBooking(id) {

            if (!confirm('Delete this booking from your list?')) {
                return;
            }

            fetch(`/bookings/${id}/client-delete`, {
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

        let currentBookingId = null;

        function showReleaseConfirmation(bookingId) {
            currentBookingId = bookingId;

            const modal = document.getElementById('releasePaymentModal');

            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeReleaseConfirmation() {
            const modal = document.getElementById('releasePaymentModal');

            modal.classList.remove('flex');
            modal.classList.add('hidden');
        }

        function goToDispute() {
            closeReleaseConfirmation();

            alert(
                'Please use the dispute button on this booking if there is an issue with the service.'
            );
        }

        const confirmBtn = document.getElementById('confirmReleaseBtn');

        if (confirmBtn) {
            confirmBtn.addEventListener('click', function() {

                closeReleaseConfirmation();

                confirmServiceReceived(currentBookingId);

            });
        }

        function showEscrowLoading(bookingId) {
            const button =
                document.getElementById(`escrowBtn-${bookingId}`);

            if (!button) return true;

            button.disabled = true;

            button.classList.add(
                'opacity-75',
                'cursor-not-allowed'
            );

            button.querySelector('.escrow-btn-text')
                ?.classList.add('hidden');

            button.querySelector('.escrow-btn-loading')
                ?.classList.remove('hidden');

            return true;
        }
    </script>
@endsection

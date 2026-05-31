@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-8">
    <div class="mb-6">
        <a href="{{ route('bookings.requests') }}" class="text-blue-600 hover:underline text-sm">
            ← Back to My Bookings
        </a>
    </div>

    <div class="bg-white rounded-xl shadow border p-6">
        <h1 class="text-2xl font-bold text-gray-800 mb-4">
            Payment Dispute
        </h1>

        {{-- @if(session('success'))
            <div class="mb-4 p-4 bg-green-100 border border-green-200 text-green-700 rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mb-4 p-4 bg-red-100 border border-red-200 text-red-700 rounded-lg">
                {{ session('error') }}
            </div>
        @endif --}}

        <div class="space-y-2 text-sm text-gray-700">
            <p>
                <strong>Skill:</strong>
                {{ $booking->skill->title ?? 'Skill Deleted' }}
            </p>

            <p>
                <strong>Client:</strong>
                {{ $booking->client->fullName() }}
            </p>

            <p>
                <strong>Provider:</strong>
                {{ $booking->provider->fullName() }}
            </p>

            <p>
                <strong>Dispute opened by:</strong>
                {{ $booking->paymentDisputeOpenedBy?->fullName() ?? 'Unknown User' }}

                <span class="ml-2 px-2 py-1 rounded-full text-xs bg-blue-100 text-blue-700 font-semibold">
                    {{ ucfirst($booking->payment_dispute_opened_by_role ?? 'Unknown') }}
                </span>
            </p>

            <p>
                <strong>Status:</strong>
                <span class="px-2 py-1 rounded-full text-xs bg-yellow-100 text-yellow-700 font-semibold">
                    {{ ucfirst(str_replace('_', ' ', $booking->dispute_status ?? 'open')) }}
                </span>
            </p>

            @if($booking->admin_payment_deadline_at)
                <p class="text-red-600 font-semibold">
                    <strong>Payment Deadline:</strong>
                    {{ $booking->admin_payment_deadline_at->format('d M Y, h:i A') }}
                </p>
            @endif
        </div>

        <div class="mt-5 p-4 bg-red-50 border border-red-200 rounded-lg">
            <p class="font-semibold text-red-700">
                Dispute Message from {{ ucfirst($booking->payment_dispute_opened_by_role ?? 'User') }}:
            </p>

            <p class="text-sm text-red-600 mt-1">
                {{ $booking->payment_dispute_reason }}
            </p>
        </div>

        @if($booking->admin_dispute_note)
            <div class="mt-5 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                <p class="font-semibold text-yellow-700">Admin Note:</p>
                <p class="text-sm text-yellow-700 mt-1">
                    {{ $booking->admin_dispute_note }}
                </p>
            </div>
        @endif

        @if($booking->client_payment_response)
    <div class="mt-5 p-4 bg-green-50 border border-green-200 rounded-lg">
        <p class="font-semibold text-green-700">Your Submitted Response:</p>

        <p class="text-sm text-green-700 mt-1">
            {{ $booking->client_payment_response }}
        </p>

        @if($booking->client_payment_proof)
            <div class="mt-3">
                <p class="text-sm font-semibold text-gray-700">Uploaded Proof:</p>

                <a href="{{ asset('storage/' . $booking->client_payment_proof) }}"
                   target="_blank"
                   class="text-blue-600 underline text-sm">
                    View uploaded proof
                </a>
            </div>
        @endif
    </div>
@endif

        @if(auth()->id() === $booking->client_id)
            <form method="POST"
                  action="{{ route('bookings.dispute.response', $booking->id) }}"
                  enctype="multipart/form-data"
                  class="mt-6 border-t pt-6">
                @csrf

                <h2 class="text-lg font-bold text-gray-800 mb-4">
                    Respond to Dispute
                </h2>

                <div class="mb-4">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Your Response
                    </label>

                    <textarea name="client_payment_response"
                              rows="5"
                              required
                              class="w-full border rounded-lg p-3 focus:ring focus:ring-blue-200"
                              placeholder="Explain your side. For example: I have paid, payment was made on..., through...">{{ old('client_payment_response') }}</textarea>

                    @error('client_payment_response')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Upload Proof of Payment
                    </label>

                    <input type="file"
                           name="client_payment_proof"
                           accept="image/*"
                           class="w-full border rounded-lg p-3">

                    <p class="text-xs text-gray-500 mt-1">
                        Accepted: JPG, PNG, WEBP. Max size: 2MB.
                    </p>

                    @error('client_payment_proof')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                @if($booking->client_payment_proof)
                    <div class="mb-4 p-3 bg-green-50 border border-green-200 rounded-lg">
                        <p class="text-sm font-semibold text-green-700">
                            Existing proof uploaded:
                        </p>

                        <a href="{{ asset('storage/' . $booking->client_payment_proof) }}"
                           target="_blank"
                           class="text-blue-600 underline text-sm">
                            View proof
                        </a>
                    </div>
                @endif

                <button type="submit"
                        class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg">
                    Submit Response
                </button>
            </form>
        @else
            @if($booking->client_payment_response)
                <div class="mt-6 p-4 bg-green-50 border border-green-200 rounded-lg">
                    <p class="font-semibold text-green-700">Client Response:</p>
                    <p class="text-sm text-green-700 mt-1">
                        {{ $booking->client_payment_response }}
                    </p>

                    @if($booking->client_payment_proof)
                        <a href="{{ asset('storage/' . $booking->client_payment_proof) }}"
                           target="_blank"
                           class="text-blue-600 underline text-sm mt-2 inline-block">
                            View uploaded proof
                        </a>
                    @endif
                </div>
            @else
                <div class="mt-6 p-4 bg-gray-50 border rounded-lg text-sm text-gray-600">
                    Client has not submitted any response yet.
                </div>
            @endif
        @endif
    </div>
</div>
@endsection
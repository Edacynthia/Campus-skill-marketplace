@extends('layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto px-4 py-8">
        <h1 class="text-2xl font-bold text-gray-800 mb-6">Payment Disputes</h1>

        {{-- @if (session('success'))
        <div class="mb-4 p-4 bg-green-100 border border-green-200 text-green-700 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="mb-4 p-4 bg-red-100 border border-red-200 text-red-700 rounded-lg">
            {{ session('error') }}
        </div>
    @endif --}}

        <div class="space-y-4">
            @forelse($disputes as $booking)
                <div class="bg-white p-5 rounded-xl shadow border">
                    <div class="flex justify-between items-start gap-4">
                        <div>
                            <h3 class="font-bold text-gray-800 text-lg">
                                {{ $booking->skill->title ?? 'Skill Deleted' }}
                            </h3>

                            <p class="text-sm text-gray-600 mt-2">
                                <strong>Client:</strong> {{ $booking->client->fullName() }}
                            </p>

                            <p class="text-sm text-gray-600">
                                <strong>Provider:</strong> {{ $booking->provider->fullName() }}
                            </p>

                            <p class="text-sm text-gray-600 mt-2">
                                <strong>Dispute opened by:</strong>

                                @if ($booking->paymentDisputeOpenedBy)
                                    {{ $booking->paymentDisputeOpenedBy->fullName() }}
                                @else
                                    Unknown User
                                @endif

                                <span class="ml-2 px-2 py-1 rounded-full text-xs bg-blue-100 text-blue-700 font-semibold">
                                    {{ ucfirst($booking->payment_dispute_opened_by_role ?? 'Unknown') }}
                                </span>
                            </p>
                        </div>

                        <div>
                            <span
                                class="px-3 py-1 rounded-full text-xs font-semibold
                            @if (($booking->dispute_status ?? 'open') === 'open') bg-red-100 text-red-700
                            @elseif(($booking->dispute_status ?? '') === 'warned')
                                bg-yellow-100 text-yellow-700
                            @elseif(($booking->dispute_status ?? '') === 'awaiting_proof')
                                bg-blue-100 text-blue-700
                            @elseif(($booking->dispute_status ?? '') === 'proof_submitted')
                                bg-purple-100 text-purple-700
                            @elseif(($booking->dispute_status ?? '') === 'resolved')
                                bg-green-100 text-green-700
                            @elseif(($booking->dispute_status ?? '') === 'dismissed')
                                bg-gray-100 text-gray-700
                            @else
                                bg-gray-100 text-gray-700 @endif
                        ">
                                {{ ucfirst(str_replace('_', ' ', $booking->dispute_status ?? 'open')) }}
                            </span>
                        </div>
                    </div>

                    @if ($booking->payment_disputed_at)
                        <p class="text-xs text-gray-500 mt-3">
                            Disputed on: {{ $booking->payment_disputed_at->format('d M Y, h:i A') }}
                        </p>
                    @endif

                    @if ($booking->admin_payment_deadline_at)
                        <p class="text-sm text-red-600 mt-2 font-semibold">
                            Payment deadline:
                            {{ $booking->admin_payment_deadline_at->format('d M Y, h:i A') }}
                        </p>
                    @endif

                    <div class="mt-4 p-4 bg-red-50 border border-red-200 rounded-lg">
                        <p class="font-semibold text-red-700">
                            Dispute Message from {{ ucfirst($booking->payment_dispute_opened_by_role ?? 'User') }}:
                        </p>

                        <p class="text-sm text-red-600 mt-1">
                            {{ $booking->payment_dispute_reason }}
                        </p>
                    </div>

                    @if ($booking->client_payment_response)
                        <div class="mt-4 p-4 bg-green-50 border border-green-200 rounded-lg">
                            <p class="font-semibold text-green-700">
                                Client Response:
                            </p>

                            <p class="text-sm text-green-700 mt-1">
                                {{ $booking->client_payment_response }}
                            </p>

                            @if ($booking->client_payment_proof)
                                <div class="mt-3">
                                    <p class="text-sm font-semibold text-gray-700">Proof of Payment:</p>

                                    <a href="{{ asset('storage/' . $booking->client_payment_proof) }}" target="_blank"
                                        class="text-blue-600 underline text-sm">
                                        View uploaded proof
                                    </a>
                                </div>
                            @endif
                        </div>
                    @endif

                    @if ($booking->admin_dispute_note)
                        <div class="mt-4 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                            <p class="font-semibold text-yellow-700">
                                Admin Note:
                            </p>

                            <p class="text-sm text-yellow-700 mt-1">
                                {{ $booking->admin_dispute_note }}
                            </p>
                        </div>
                    @endif

                    @if (($booking->dispute_status ?? 'open') === 'resolved')
                        <div class="mt-5 p-4 bg-green-50 border border-green-200 rounded-lg">
                            <p class="text-green-700 font-semibold">
                                This dispute has been resolved.
                            </p>

                            @if ($booking->payment_status === 'provider_confirmed_received')
                                <p class="text-sm text-green-700 mt-1">
                                    Escrow payment has been released to the provider.
                                </p>
                            @endif

                            @if ($booking->payment_resolved_at)
                                <p class="text-xs text-gray-500 mt-1">
                                    Resolved on: {{ $booking->payment_resolved_at->format('d M Y, h:i A') }}
                                </p>
                            @endif
                        </div>
                    @elseif(($booking->dispute_status ?? 'open') === 'dismissed')
                        <div class="mt-5 p-4 bg-gray-50 border border-gray-200 rounded-lg">
                            <p class="text-gray-700 font-semibold">
                                This dispute has been dismissed.
                            </p>

                            @if ($booking->payment_resolved_at)
                                <p class="text-xs text-gray-500 mt-1">
                                    Dismissed on: {{ $booking->payment_resolved_at->format('d M Y, h:i A') }}
                                </p>
                            @endif
                        </div>
                   @else
    <div class="mt-5 border-t pt-5 space-y-5">

        {{-- Dispute Actions --}}
        <div>
            <h4 class="text-sm font-bold text-gray-700 mb-3">
                Dispute Actions
            </h4>

            <div class="flex flex-wrap gap-2">

                <form action="{{ route('admin.disputes.warnClient', $booking->id) }}"
                      method="POST">
                    @csrf
                    <button
                        class="px-3 py-2 bg-orange-500 hover:bg-orange-600 text-white rounded-lg text-sm">
                        Warn Client
                    </button>
                </form>

                <form action="{{ route('admin.disputes.resolve', $booking->id) }}"
                      method="POST"
                      onsubmit="return confirm('Resolve dispute and release escrow payment?');">
                    @csrf
                    <button
                        class="px-3 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg text-sm">
                        Resolve & Release Payment
                    </button>
                </form>

                <form action="{{ route('admin.disputes.dismiss', $booking->id) }}"
                      method="POST"
                      onsubmit="return confirm('Dismiss this dispute?');">
                    @csrf
                    <button
                        class="px-3 py-2 bg-gray-700 hover:bg-gray-800 text-white rounded-lg text-sm">
                        Dismiss Dispute
                    </button>
                </form>

            </div>
        </div>

        {{-- User Moderation --}}
        <div class="grid md:grid-cols-2 gap-4">

            {{-- Client --}}
            <div class="p-4 bg-gray-50 border rounded-lg">

                <h4 class="font-semibold text-gray-800 mb-2">
                    Client Actions
                </h4>

                <p class="text-sm text-gray-600 mb-3">
                    {{ $booking->client->fullName() }}
                </p>

                <div class="flex flex-wrap gap-2">

                    <a href="{{ route('profile.show', $booking->client->id) }}"
                       class="px-3 py-2 bg-blue-700 text-white rounded-lg text-sm">
                        View Profile
                    </a>

                    <form action="{{ route('admin.users.suspend', $booking->client) }}"
                          method="POST"
                          onsubmit="return confirm('Suspend this client?');">
                        @csrf
                        <button
                            class="px-3 py-2 bg-yellow-500 hover:bg-yellow-600 text-white rounded-lg text-sm">
                            Suspend
                        </button>
                    </form>

                    <form action="{{ route('admin.users.ban', $booking->client) }}"
                          method="POST"
                          onsubmit="return confirm('Ban this client?');">
                        @csrf
                        <button
                            class="px-3 py-2 bg-red-700 hover:bg-red-800 text-white rounded-lg text-sm">
                            Ban
                        </button>
                    </form>

                </div>

            </div>

            {{-- Provider --}}
            <div class="p-4 bg-gray-50 border rounded-lg">

                <h4 class="font-semibold text-gray-800 mb-2">
                    Provider Actions
                </h4>

                <p class="text-sm text-gray-600 mb-3">
                    {{ $booking->provider->fullName() }}
                </p>

                <div class="flex flex-wrap gap-2">

                    <a href="{{ route('profile.show', $booking->provider->id) }}"
                       class="px-3 py-2 bg-blue-700 text-white rounded-lg text-sm">
                        View Profile
                    </a>

                    <form action="{{ route('admin.users.suspend', $booking->provider) }}"
                          method="POST"
                          onsubmit="return confirm('Suspend this provider?');">
                        @csrf
                        <button
                            class="px-3 py-2 bg-yellow-500 hover:bg-yellow-600 text-white rounded-lg text-sm">
                            Suspend
                        </button>
                    </form>

                    <form action="{{ route('admin.users.ban', $booking->provider) }}"
                          method="POST"
                          onsubmit="return confirm('Ban this provider?');">
                        @csrf
                        <button
                            class="px-3 py-2 bg-red-700 hover:bg-red-800 text-white rounded-lg text-sm">
                            Ban
                        </button>
                    </form>

                </div>

            </div>

        </div>

    </div>
@endif
                    </div>

                </div>

            @empty
                <div class="bg-white p-10 rounded-xl text-center text-gray-500">
                    No payment disputes yet.
                </div>
            @endforelse

        </div>
    </div>


@endsection

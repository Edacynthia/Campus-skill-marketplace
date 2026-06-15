@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="max-w-7xl mx-auto px-6 py-6">
        <!-- Header -->
        <div class="mb-6">
            <h1 class="text-2xl font-semibold text-gray-800">Payment Disputes</h1>
            <p class="text-sm text-gray-500 mt-1">Review and resolve payment disputes</p>
        </div>

        <!-- Disputes List -->
        <div class="space-y-4">
            @forelse($disputes as $booking)
                <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
                    <!-- Card Header -->
                    <div class="p-5 border-b border-gray-100">
                        <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-3">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">
                                    {{ $booking->skill->title ?? 'Skill Deleted' }}
                                </h3>
                                <div class="flex flex-wrap gap-x-4 gap-y-1 mt-1.5 text-sm text-gray-500">
                                    <span>Client: <strong class="text-gray-700">{{ $booking->client->fullName() }}</strong></span>
                                    <span>Provider: <strong class="text-gray-700">{{ $booking->provider->fullName() }}</strong></span>
                                </div>
                            </div>
                            <div class="flex flex-col items-end gap-2">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium
                                    @if(($booking->dispute_status ?? 'open') === 'open') bg-red-50 text-red-700 border border-red-200
                                    @elseif(($booking->dispute_status ?? '') === 'warned') bg-yellow-50 text-yellow-700 border border-yellow-200
                                    @elseif(($booking->dispute_status ?? '') === 'awaiting_proof') bg-blue-50 text-blue-700 border border-blue-200
                                    @elseif(($booking->dispute_status ?? '') === 'proof_submitted') bg-purple-50 text-purple-700 border border-purple-200
                                    @elseif(($booking->dispute_status ?? '') === 'resolved') bg-green-50 text-green-700 border border-green-200
                                    @elseif(($booking->dispute_status ?? '') === 'dismissed') bg-gray-50 text-gray-600 border border-gray-200
                                    @else bg-gray-50 text-gray-600 border border-gray-200 @endif
                                ">
                                    {{ ucfirst(str_replace('_', ' ', $booking->dispute_status ?? 'open')) }}
                                </span>
                                <div class="flex items-center gap-2 text-xs text-gray-400">
                                    <span>Opened by: {{ $booking->paymentDisputeOpenedBy?->fullName() ?? 'Unknown' }}</span>
                                    <span class="px-1.5 py-0.5 rounded bg-gray-100 text-gray-600 text-xs">
                                        {{ ucfirst($booking->payment_dispute_opened_by_role ?? 'Unknown') }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="flex flex-wrap gap-4 mt-3 text-xs text-gray-400">
                            @if($booking->payment_disputed_at)
                                <span>Disputed: {{ $booking->payment_disputed_at->format('d M Y, h:i A') }}</span>
                            @endif
                            @if($booking->admin_payment_deadline_at)
                                <span class="text-red-500 font-medium">Deadline: {{ $booking->admin_payment_deadline_at->format('d M Y, h:i A') }}</span>
                            @endif
                        </div>
                    </div>
                    
                    <!-- Dispute Message -->
                    <div class="p-5 bg-red-50/30 border-b border-red-100">
                        <div class="flex items-start gap-2">
                            <svg class="w-4 h-4 text-red-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                            </svg>
                            <div>
                                <p class="text-sm font-medium text-red-700">Dispute from {{ ucfirst($booking->payment_dispute_opened_by_role ?? 'User') }}:</p>
                                <p class="text-sm text-red-600 mt-1">{{ $booking->payment_dispute_reason }}</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Client Response -->
                    @if($booking->client_payment_response)
                        <div class="p-5 bg-green-50/30 border-b border-green-100">
                            <div class="flex items-start gap-2">
                                <svg class="w-4 h-4 text-green-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-green-700">Client Response:</p>
                                    <p class="text-sm text-green-700 mt-1">{{ $booking->client_payment_response }}</p>
                                    @if($booking->client_payment_proof)
                                        <div class="mt-2">
                                            <a href="{{ asset('storage/' . $booking->client_payment_proof) }}" target="_blank" 
                                                class="inline-flex items-center gap-1 text-xs text-blue-600 hover:text-blue-800">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                </svg>
                                                View uploaded proof
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif
                    
                    <!-- Admin Note -->
                    @if($booking->admin_dispute_note)
                        <div class="p-5 bg-yellow-50/30 border-b border-yellow-100">
                            <div class="flex items-start gap-2">
                                <svg class="w-4 h-4 text-yellow-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                                <div>
                                    <p class="text-sm font-medium text-yellow-700">Admin Note:</p>
                                    <p class="text-sm text-yellow-700 mt-1">{{ $booking->admin_dispute_note }}</p>
                                </div>
                            </div>
                        </div>
                    @endif
                    
                    <!-- Resolved or Dismissed State -->
                    @if(($booking->dispute_status ?? 'open') === 'resolved')
                        <div class="p-5 bg-green-50">
                            <div class="flex items-center gap-2">
                                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <div>
                                    <p class="text-sm font-medium text-green-700">This dispute has been resolved.</p>
                                    @if($booking->payment_status === 'provider_confirmed_received')
                                        <p class="text-xs text-green-600 mt-1">Escrow payment has been released to the provider.</p>
                                    @endif
                                    @if($booking->payment_resolved_at)
                                        <p class="text-xs text-gray-500 mt-1">Resolved on: {{ $booking->payment_resolved_at->format('d M Y, h:i A') }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @elseif(($booking->dispute_status ?? 'open') === 'dismissed')
                        <div class="p-5 bg-gray-50">
                            <div class="flex items-center gap-2">
                                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                                <div>
                                    <p class="text-sm font-medium text-gray-700">This dispute has been dismissed.</p>
                                    @if($booking->payment_resolved_at)
                                        <p class="text-xs text-gray-500 mt-1">Dismissed on: {{ $booking->payment_resolved_at->format('d M Y, h:i A') }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @else
                        <!-- Active Dispute Actions -->
                        <div class="p-5 space-y-5 bg-gray-50/30">
                            <!-- Dispute Actions -->
                            <div>
                                <h4 class="text-xs font-medium text-gray-400 uppercase tracking-wide mb-3">Dispute Actions</h4>
                                <div class="flex flex-wrap gap-2">
                                    <form action="{{ route('admin.disputes.warnClient', $booking->id) }}" method="POST">
                                        @csrf
                                        <button class="inline-flex items-center px-3 py-1.5 bg-yellow-500 hover:bg-yellow-600 text-white text-xs font-medium rounded-lg transition">
                                            <svg class="w-3 h-3 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                            </svg>
                                            Warn Client
                                        </button>
                                    </form>
                                    
                                    <form action="{{ route('admin.disputes.resolve', $booking->id) }}" method="POST" onsubmit="return confirm('Resolve dispute and release escrow payment?');">
                                        @csrf
                                        <button class="inline-flex items-center px-3 py-1.5 bg-green-600 hover:bg-green-700 text-white text-xs font-medium rounded-lg transition">
                                            <svg class="w-3 h-3 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                            Resolve & Release
                                        </button>
                                    </form>
                                    
                                    <form action="{{ route('admin.disputes.dismiss', $booking->id) }}" method="POST" onsubmit="return confirm('Dismiss this dispute?');">
                                        @csrf
                                        <button class="inline-flex items-center px-3 py-1.5 bg-gray-600 hover:bg-gray-700 text-white text-xs font-medium rounded-lg transition">
                                            <svg class="w-3 h-3 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                            Dismiss
                                        </button>
                                    </form>
                                </div>
                            </div>
                            
                            <!-- User Moderation -->
                            <div class="grid md:grid-cols-2 gap-4">
                                <!-- Client -->
                                <div class="bg-white rounded-xl border border-gray-100 p-4">
                                    <h4 class="text-sm font-medium text-gray-800 mb-2">Client Actions</h4>
                                    <p class="text-xs text-gray-500 mb-3">{{ $booking->client->fullName() }}</p>
                                    <div class="flex flex-wrap gap-2">
                                        <a href="{{ route('profile.show', $booking->client->id) }}" 
                                            class="inline-flex items-center px-2.5 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-medium rounded-lg transition">
                                            <svg class="w-3 h-3 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                            View Profile
                                        </a>
                                        <form action="{{ route('admin.users.suspend', $booking->client) }}" method="POST" onsubmit="return confirm('Suspend this client?');">
                                            @csrf
                                            <button class="inline-flex items-center px-2.5 py-1.5 bg-yellow-500 hover:bg-yellow-600 text-white text-xs font-medium rounded-lg transition">
                                                <svg class="w-3 h-3 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                Suspend
                                            </button>
                                        </form>
                                        <form action="{{ route('admin.users.ban', $booking->client) }}" method="POST" onsubmit="return confirm('Ban this client?');">
                                            @csrf
                                            <button class="inline-flex items-center px-2.5 py-1.5 bg-red-600 hover:bg-red-700 text-white text-xs font-medium rounded-lg transition">
                                                <svg class="w-3 h-3 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path>
                                                </svg>
                                                Ban
                                            </button>
                                        </form>
                                    </div>
                                </div>
                                
                                <!-- Provider -->
                                <div class="bg-white rounded-xl border border-gray-100 p-4">
                                    <h4 class="text-sm font-medium text-gray-800 mb-2">Provider Actions</h4>
                                    <p class="text-xs text-gray-500 mb-3">{{ $booking->provider->fullName() }}</p>
                                    <div class="flex flex-wrap gap-2">
                                        <a href="{{ route('profile.show', $booking->provider->id) }}" 
                                            class="inline-flex items-center px-2.5 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-medium rounded-lg transition">
                                            <svg class="w-3 h-3 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                            View Profile
                                        </a>
                                        <form action="{{ route('admin.users.suspend', $booking->provider) }}" method="POST" onsubmit="return confirm('Suspend this provider?');">
                                            @csrf
                                            <button class="inline-flex items-center px-2.5 py-1.5 bg-yellow-500 hover:bg-yellow-600 text-white text-xs font-medium rounded-lg transition">
                                                <svg class="w-3 h-3 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                Suspend
                                            </button>
                                        </form>
                                        <form action="{{ route('admin.users.ban', $booking->provider) }}" method="POST" onsubmit="return confirm('Ban this provider?');">
                                            @csrf
                                            <button class="inline-flex items-center px-2.5 py-1.5 bg-red-600 hover:bg-red-700 text-white text-xs font-medium rounded-lg transition">
                                                <svg class="w-3 h-3 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path>
                                                </svg>
                                                Ban
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            @empty
                <div class="bg-white rounded-xl border border-gray-100 text-center py-12">
                    <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <p class="text-gray-500 text-sm">No payment disputes yet.</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
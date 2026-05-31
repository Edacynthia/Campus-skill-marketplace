<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Skill;
use App\Models\Rating;
use App\Models\Message;
use App\Models\Notification;

class BookingController extends Controller
{
    public function store(Request $request, $skillId)
    {
        if (!auth()->check()) {
            return response()->json(['success' => false, 'message' => 'Please sign in'], 401);
        }

        $skill = Skill::findOrFail($skillId);

        // Verify authenticated user is not the skill owner
        if ($skill->user_id === auth()->id()) {
            return response()->json(['success' => false, 'message' => 'You cannot book your own skill'], 403);
        }

        // Check no existing active booking exists between this client and skill
        $existingBooking = Booking::where('skill_id', $skillId)
            ->where('client_id', auth()->id())
            ->whereNotIn('status', ['done', 'declined'])
            ->first();

        if ($existingBooking) {
            return response()->json([
                'success' => false,
                'message' => 'You already have an active booking for this skill.'
            ]);
        }

        // Validate optional message field
        $request->validate([
            'message' => 'required|string|max:500'
        ]);

        // Create booking
        $booking = Booking::create([
            'skill_id' => $skillId,
            'client_id' => auth()->id(),
            'provider_id' => $skill->user_id,
            'status' => 'interested',
            'message' => $request->message
        ]);

        Notification::createNotification(
            $skill->user_id,
            'booking_request',
            'New Booking Request',
            auth()->user()->first_name . ' requested your skill: ' . $skill->title,
            '/my-skill-bookings'
        );

        // Create message for skill provider
        Message::create([
            'sender_id' => auth()->id(),
            'receiver_id' => $skill->user_id,
            'skill_id' => $skillId,
            'message' => $request->message,
            'status' => 'sent'
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Booking request sent successfully!'
        ]);
    }

    public function confirm($bookingId)
    {
        if (!auth()->check()) {
            return response()->json(['success' => false, 'message' => 'Please sign in'], 401);
        }

        $booking = Booking::findOrFail($bookingId);

        // Verify authenticated user is the provider
        if ($booking->provider_id !== auth()->id()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized action'], 403);
        }

        // Verify booking status is interested
        if ($booking->status !== 'interested') {
            return response()->json(['success' => false, 'message' => 'Can only confirm interested bookings'], 400);
        }

        $booking->update(['status' => 'confirmed']);

        return response()->json([
            'success' => true,
            'message' => 'Booking confirmed successfully!'
        ]);
    }

    public function confirmPaymentSent($bookingId)
    {
        if (!auth()->check()) {
            return response()->json([
                'success' => false,
                'message' => 'Please sign in'
            ], 401);
        }

        $booking = Booking::findOrFail($bookingId);

        // Only client/employer can confirm payment sent
        if ($booking->client_id !== auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized action'
            ], 403);
        }

        // Booking must be completed first
        if ($booking->status !== 'done') {
            return response()->json([
                'success' => false,
                'message' => 'Booking must be completed first'
            ], 400);
        }

        // Prevent duplicate confirmation
        if ($booking->payment_status !== 'unpaid') {
            return response()->json([
                'success' => false,
                'message' => 'Payment already processed'
            ], 400);
        }

        $booking->update([
            'payment_status' => 'paid_by_employer',
            'payment_confirmed_at' => now(),
            'payment_confirmed_by' => auth()->id(),
        ]);

        // Notify provider
        Notification::createNotification(
            $booking->provider_id,
            'payment_sent',
            'Payment Marked As Sent',
            auth()->user()->first_name . ' marked payment as sent.',
            route('bookings.skills')
        );

        return response()->json([
            'success' => true,
            'message' => 'Payment marked as sent successfully!'
        ]);
    }

    public function confirmPaymentReceived($bookingId)
    {
        if (!auth()->check()) {
            return response()->json([
                'success' => false,
                'message' => 'Please sign in'
            ], 401);
        }

        $booking = Booking::findOrFail($bookingId);

        // Only provider can confirm payment received
        if ($booking->provider_id !== auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized action'
            ], 403);
        }

        // Employer must first confirm payment sent
        if ($booking->payment_status !== 'paid_by_employer') {
            return response()->json([
                'success' => false,
                'message' => 'Employer has not confirmed payment yet'
            ], 400);
        }

        $booking->update([
            'payment_status' => 'received_by_provider',
            'payment_confirmed_at' => now(),
            'payment_confirmed_by' => auth()->id(),
        ]);

        // Notify client/employer
        Notification::createNotification(
            $booking->client_id,
            'payment_received',
            'Payment Confirmed',
            auth()->user()->first_name . ' confirmed payment receipt.',
            route('bookings.requests')
        );

        return response()->json([
            'success' => true,
            'message' => 'Payment confirmed successfully!'
        ]);
    }

    public function decline($bookingId)
    {
        if (!auth()->check()) {
            return response()->json(['success' => false, 'message' => 'Please sign in'], 401);
        }

        $booking = Booking::with(['skill', 'client', 'provider'])->findOrFail($bookingId);

        if ($booking->provider_id !== auth()->id()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized action'], 403);
        }

        if ($booking->status !== 'interested') {
            return response()->json(['success' => false, 'message' => 'Can only decline interested bookings'], 400);
        }

        $booking->update([
            'status' => 'declined',
        ]);

        Notification::createNotification(
            $booking->client_id,
            'booking_declined',
            'Booking Declined',
            ($booking->provider->fullName() ?? 'The provider') . ' declined your booking request for "' . ($booking->skill->title ?? 'a skill') . '".',
            route('bookings.requests')
        );

        return response()->json([
            'success' => true,
            'message' => 'Booking declined successfully.'
        ]);
    }

    public function submitRating(Request $request, $bookingId)
    {
        if (!auth()->check()) {
            return response()->json(['success' => false, 'message' => 'Please sign in'], 401);
        }

        $booking = Booking::findOrFail($bookingId);

        // Verify booking status is done
        // Booking must be completed
        if ($booking->status !== 'done' || $booking->payment_status !== 'provider_confirmed_received') {
            return response()->json([
                'success' => false,
                'message' => 'Rating is only available after payment has been confirmed by the provider.'
            ], 400);
        }


        // Determine type
        $reviewerId = auth()->id();
        if ($reviewerId === $booking->provider_id) {
            $type = 'provider_to_client';
            $revieweeId = $booking->client_id;
        } else {
            $type = 'client_to_provider';
            $revieweeId = $booking->provider_id;
        }

        // Check this reviewer has not already rated this booking
        $existingRating = Rating::where('booking_id', $bookingId)
            ->where('reviewer_id', $reviewerId)
            ->first();

        if ($existingRating) {
            return response()->json(['success' => false, 'message' => 'You have already rated this booking'], 400);
        }

        // Validate rating and review
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'nullable|string|max:500'
        ]);

        // Create rating
        Rating::create([
            'booking_id' => $bookingId,
            'application_id' => null,
            'job_id' => null,
            'reviewer_id' => $reviewerId,
            'reviewee_id' => $revieweeId,
            'rating' => $request->rating,
            'review' => $request->review,
            'type' => $type
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Rating submitted successfully!'
        ]);
    }

    /**
     * Display user's bookings
     */
    public function myBookings()
    {
        $user = auth()->user();

        $myBookings = Booking::with(['skill.user', 'client', 'provider', 'ratings'])
            ->where('client_id', $user->id)
            ->latest()
            ->get();

        $myServiceBookings = Booking::with(['skill.user', 'client', 'provider', 'ratings'])
            ->where('provider_id', $user->id)
            ->latest()
            ->get();

        return view('bookings.index', compact('myBookings', 'myServiceBookings'));
    }

    public function myServiceRequests()
    {
        auth()->user()->notifications()
            ->whereIn('type', [
                'booking_confirmed',
                'booking_declined',
                'booking_completed',
                'rating_received'
            ])
            ->where('is_read', false)
            ->update(['is_read' => true]);

        $myBookings = Booking::with(['skill', 'provider', 'ratings'])
            ->where('client_id', auth()->id())
            ->latest()
            ->get();

        return view('bookings.requests', compact('myBookings'));
    }

    public function mySkillBookings()
    {
        auth()->user()->notifications()
            ->whereIn('type', [
                'booking_request',
                'booking_completed',
                'rating_received'
            ])
            ->where('is_read', false)
            ->update(['is_read' => true]);

        $myServiceBookings = Booking::with(['skill', 'client', 'ratings'])
            ->where('provider_id', auth()->id())
            ->latest()
            ->get();

        return view('bookings.skills', compact('myServiceBookings'));
    }

    public function updateProgress(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:in_progress,completed_waiting_payment',
        ]);

        $booking = Booking::findOrFail($id);

        if ($booking->provider_id !== auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized action'
            ], 403);
        }

        $booking->update([
            'status' => $request->status,
            'completed_at' => $request->status === 'completed_waiting_payment'
                ? now()
                : $booking->completed_at,
        ]);

        Notification::createNotification(
            $booking->client_id,
            'booking_progress_updated',
            'Booking Progress Updated',
            'The provider updated your booking progress.',
            route('bookings.requests')
        );

        return response()->json([
            'success' => true
        ]);
    }

    public function clientMarkedPaid($id)
    {
        $booking = Booking::findOrFail($id);

        if ($booking->client_id !== auth()->id()) {
            return response()->json([
                'success' => false
            ], 403);
        }

        $booking->update([
            'payment_status' => 'client_marked_paid',
            'client_paid_at' => now(),
        ]);

        Notification::createNotification(
            $booking->provider_id,
            'client_marked_paid',
            'Client Marked Payment As Sent',
            auth()->user()->first_name . ' marked payment as sent.',
            route('bookings.skills')
        );

        return response()->json([
            'success' => true
        ]);
    }

    public function providerReceivedPayment($id)
    {
        $booking = Booking::with(['client', 'provider', 'skill'])->findOrFail($id);

        if ($booking->provider_id !== auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized action'
            ], 403);
        }

        if (!in_array($booking->payment_status, ['client_marked_paid', 'payment_disputed'])) {
            return response()->json([
                'success' => false,
                'message' => 'Payment has not been marked as sent by the client yet.'
            ], 400);
        }

        $booking->update([
            'status' => 'done',
            'payment_status' => 'provider_confirmed_received',
            'provider_payment_confirmed_at' => now(),

            'dispute_status' => $booking->payment_status === 'payment_disputed'
                ? 'resolved'
                : $booking->dispute_status,

            'payment_resolved_at' => $booking->payment_status === 'payment_disputed'
                ? now()
                : $booking->payment_resolved_at,

            'admin_dispute_note' => $booking->payment_status === 'payment_disputed'
                ? 'Provider later confirmed that payment has been received.'
                : $booking->admin_dispute_note,
        ]);

        Notification::createNotification(
            $booking->client_id,
            'payment_confirmed',
            'Payment Confirmed',
            'The provider confirmed that payment was received for "' . ($booking->skill->title ?? 'your booking') . '". You can now rate and review the service.',
            route('bookings.requests')
        );

        return response()->json([
            'success' => true,
            'message' => 'Payment confirmed successfully. Rating and review are now unlocked.'
        ]);
    }

    public function providerPaymentNotReceived(Request $request, $id)
    {
        $request->validate([
            'reason' => 'required|string|max:1000',
        ]);

        $booking = Booking::with(['client', 'provider', 'skill'])->findOrFail($id);

        if ($booking->provider_id !== auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized action'
            ], 403);
        }

        if ($booking->payment_status === 'payment_disputed') {
            return response()->json([
                'success' => true,
                'message' => 'Payment dispute has already been submitted.'
            ]);
        }

        if ($booking->payment_status !== 'client_marked_paid') {
            return response()->json([
                'success' => false,
                'message' => 'Client has not marked payment as sent yet.'
            ], 400);
        }

        $booking->update([
            'status' => 'completed_waiting_payment',
            'payment_status' => 'payment_disputed',
            'payment_dispute_reason' => $request->reason,
            'payment_disputed_at' => now(),
            'payment_dispute_opened_by' => auth()->id(),
            'payment_dispute_opened_by_role' => 'provider',
            'dispute_status' => 'open',
        ]);

        Notification::notifyAdmins(
            'payment_dispute',
            'Payment Dispute',
            ($booking->provider->fullName() ?? 'A provider') . ' reported that payment was not received for "' . ($booking->skill->title ?? 'a skill') . '".',
            route('admin.disputes')
        );

        return response()->json([
            'success' => true,
            'message' => 'Payment dispute submitted. Admin has been notified.'
        ]);
    }

    public function showDispute(Booking $booking)
    {
        if (auth()->id() !== $booking->client_id && auth()->id() !== $booking->provider_id) {
            abort(403);
        }

        if ($booking->payment_status !== 'payment_disputed') {
            return redirect()->back()->with('error', 'This booking does not have an active payment dispute.');
        }

        $booking->load(['client', 'provider', 'skill', 'paymentDisputeOpenedBy']);

        return view('bookings.dispute', compact('booking'));
    }

    public function submitDisputeResponse(Request $request, Booking $booking)
    {
        if (auth()->id() !== $booking->client_id) {
            abort(403);
        }

        $request->validate([
            'client_payment_response' => 'required|string|max:2000',
            'client_payment_proof' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $proofPath = $booking->client_payment_proof;

        if ($request->hasFile('client_payment_proof')) {
            $proofPath = $request->file('client_payment_proof')->store('payment-proofs', 'public');
        }

        $booking->update([
            'client_payment_response' => $request->client_payment_response,
            'client_payment_proof' => $proofPath,
            'dispute_status' => 'proof_submitted',
        ]);

        Notification::notifyAdmins(
            'payment_proof_submitted',
            'Payment Proof Submitted',
            'A client has submitted proof of payment for a dispute.',
            route('admin.disputes')
        );

        return back()->with('success', 'Your response and proof of payment have been submitted.');
    }
}

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
            ->where('status', '!=', 'done')
            ->first();

        if ($existingBooking) {
            return response()->json(['success' => false, 'message' => 'You already have an active booking for this skill'], 400);
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

    public function clientConfirm($bookingId)
    {
        if (!auth()->check()) {
            return response()->json(['success' => false, 'message' => 'Please sign in'], 401);
        }

        $booking = Booking::findOrFail($bookingId);

        // Verify authenticated user is the client
        if ($booking->client_id !== auth()->id()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized action'], 403);
        }

        // Verify booking status is confirmed
        if ($booking->status !== 'confirmed') {
            return response()->json(['success' => false, 'message' => 'Can only confirm confirmed bookings'], 400);
        }

        // Set client_confirmed_at
        $booking->update(['client_confirmed_at' => now()]);

        // Check if both sides have confirmed
        $bothConfirmed = $booking->bothConfirmed();
        if ($bothConfirmed) {
            $booking->update([
                'status' => 'done',
                'completed_at' => now()
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => $bothConfirmed ? 'Service marked as completed!' : 'Service confirmed, waiting for provider confirmation',
            'bothConfirmed' => $bothConfirmed
        ]);
    }

    public function providerConfirm($bookingId)
    {
        if (!auth()->check()) {
            return response()->json(['success' => false, 'message' => 'Please sign in'], 401);
        }

        $booking = Booking::findOrFail($bookingId);

        // Verify authenticated user is the provider
        if ($booking->provider_id !== auth()->id()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized action'], 403);
        }

        // Verify booking status is confirmed
        if ($booking->status !== 'confirmed') {
            return response()->json(['success' => false, 'message' => 'Can only confirm confirmed bookings'], 400);
        }

        // Set provider_confirmed_at
        $booking->update(['provider_confirmed_at' => now()]);

        // Check if both sides have confirmed
        $bothConfirmed = $booking->bothConfirmed();
        if ($bothConfirmed) {
            $booking->update([
                'status' => 'done',
                'completed_at' => now()
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => $bothConfirmed ? 'Service marked as completed!' : 'Service confirmed, waiting for client confirmation',
            'bothConfirmed' => $bothConfirmed
        ]);
    }

    public function decline($bookingId)
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
            return response()->json(['success' => false, 'message' => 'Can only decline interested bookings'], 400);
        }

        $booking->delete();

        return response()->json([
            'success' => true,
            'message' => 'Booking declined successfully'
        ]);
    }

    public function submitRating(Request $request, $bookingId)
    {
        if (!auth()->check()) {
            return response()->json(['success' => false, 'message' => 'Please sign in'], 401);
        }

        $booking = Booking::findOrFail($bookingId);

        // Verify booking status is done
        if ($booking->status !== 'done') {
            return response()->json(['success' => false, 'message' => 'Can only rate completed bookings'], 400);
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
        $myBookings = Booking::with(['skill', 'provider', 'ratings'])
            ->where('client_id', auth()->id())
            ->latest()
            ->get();

        return view('bookings.requests', compact('myBookings'));
    }

    public function mySkillBookings()
    {
        $myServiceBookings = Booking::with(['skill', 'client', 'ratings'])
            ->where('provider_id', auth()->id())
            ->latest()
            ->get();

        return view('bookings.skills', compact('myServiceBookings'));
    }
}

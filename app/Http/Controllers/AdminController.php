<?php

namespace App\Http\Controllers;

use App\Mail\AccountActivatedMail;
use App\Mail\AccountBannedMail;
use App\Mail\AccountSuspendedMail;
use App\Models\Booking;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class AdminController extends Controller
{
    public function users()
    {
        $users = \App\Models\User::latest()->get();

        return view('admin.users.index', compact('users'));
    }

    public function dashboard()
    {
        $totalUsers = \App\Models\User::count();

        $pendingApprovals = \App\Models\User::where('is_approved', false)->count();

        return view('admin.dashboard', compact(
            'totalUsers',
            'pendingApprovals'
        ));
    }

  public function disputes()
    {
        $disputes = Booking::with(['client', 'provider', 'skill', 'paymentDisputeOpenedBy'])
        ->whereNotNull('payment_disputed_at')
        ->latest('payment_disputed_at')
        ->get();

        return view('admin.disputes.index', compact('disputes'));
    }

    public function suspendUser(User $user)
    {
        if ($user->role === 'admin') {
            return back()->with('error', 'You cannot suspend an admin.');
        }

        $user->update([
            'status' => 'suspended',
            'suspended_until' => now()->addDays(7),
        ]);

        Mail::to($user->email)->queue(new AccountSuspendedMail($user));

        return back()->with('success', 'User suspended successfully.');
    }


    public function banUser(Request $request, User $user)
    {
        if ($user->role === 'admin') {
            return back()->with('error', 'You cannot ban an admin.');
        }

        $request->validate([
            'ban_reason' => 'nullable|string|max:500',
        ]);

        $user->update([
            'status' => 'banned',
            'ban_reason' => $request->ban_reason,
        ]);

        Mail::to($user->email)->queue(new AccountBannedMail($user));

        return back()->with('success', 'User banned successfully.');
    }

    public function activateUser(User $user)
    {
       $user->update([
        'status' => 'active',
        'ban_reason' => null,
        'suspended_until' => null,
    ]);

    Mail::to($user->email)->queue(new AccountActivatedMail($user));

    return back()->with('success', 'User activated successfully.');
    }

    public function deleteUser(User $user)
    {
        if ($user->role === 'admin') {
            return back()->with('error', 'You cannot delete an admin.');
        }

        $user->delete();

        return back()->with('success', 'User deleted successfully.');
    }

   public function warnClient(Booking $booking)
{
    $booking->update([
        'dispute_status' => 'warned',
        'admin_dispute_note' => 'Client has been warned to settle payment within 24 hours or upload proof if payment has already been made.',
        'admin_payment_deadline_at' => now()->addHours(24),
    ]);

    Notification::create([
        'user_id' => $booking->client_id,
        'type' => 'payment_warning',
        'title' => 'Payment Warning / Proof Required',
        'message' => 'A payment dispute has been raised against you. Please settle payment within 24 hours. If you have already paid, upload your proof of payment for admin review.',
        'url' => route('bookings.dispute.show', $booking->id),
        'is_read' => false,
    ]);

    return back()->with('success', 'Client has been warned and given option to upload proof.');
}

public function requestPaymentProof(Booking $booking)
{
    $booking->update([
        'dispute_status' => 'awaiting_proof',
        'admin_dispute_note' => 'Client has been asked to upload proof of payment.',
    ]);

    Notification::create([
        'user_id' => $booking->client_id,
        'type' => 'payment_proof_requested',
        'title' => 'Proof of Payment Requested',
        'message' => 'Admin has requested proof of payment for a disputed booking.',
        'url' => route('bookings.dispute.show', $booking->id),
        'is_read' => false,
    ]);

    return back()->with('success', 'Client has been asked to upload proof of payment.');
}

public function resolveDispute(Booking $booking)
{
    $booking->update([
        'dispute_status' => 'resolved',
        'payment_status' => 'provider_confirmed_received',
        'payment_resolved_at' => now(),
        'admin_dispute_note' => 'Dispute resolved by admin.',
    ]);

    return back()->with('success', 'Dispute resolved successfully.');
}

public function dismissDispute(Booking $booking)
{
    $booking->update([
        'dispute_status' => 'dismissed',
        'payment_resolved_at' => now(),
        'admin_dispute_note' => 'Dispute dismissed by admin.',
    ]);

    return back()->with('success', 'Dispute dismissed successfully.');
}
}

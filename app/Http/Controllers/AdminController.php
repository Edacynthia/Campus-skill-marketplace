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
use App\Mail\EscrowReleasedMail;
use App\Models\JobApplication;

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

        $totalRevenue =
            Booking::sum('platform_fee') +
            JobApplication::sum('platform_fee');

        $totalEscrow =
            Booking::sum('escrow_amount') +
            JobApplication::sum('escrow_amount');

        $totalProviderPayouts =
            Booking::sum('provider_payout') +
            JobApplication::sum('worker_payout');

        $totalReleasedTransactions =
            Booking::where('escrow_status', 'released')->count() +
            JobApplication::where('escrow_status', 'released')->count();

        return view(
            'admin.dashboard',
            compact(
                'totalUsers',
                'pendingApprovals',
                'totalRevenue',
                'totalEscrow',
                'totalProviderPayouts',
                'totalReleasedTransactions'
            )
        );
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
            'status' => 'done',
            'escrow_status' => 'released',
            'payment_status' => 'provider_confirmed_received',
            'dispute_status' => 'resolved',
            'payment_resolved_at' => now(),
            'escrow_released_at' => now(),
            'admin_hold' => false,
            'admin_dispute_note' => 'Dispute resolved. Escrow payment released to provider.',
        ]);

        Mail::to($booking->provider->email)->queue(new EscrowReleasedMail($booking));

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

    public function transactions()
    {
        $skillTransactions = Booking::with(['client', 'provider', 'skill'])
            ->where('escrow_status', 'released')
            ->get()
            ->map(function ($booking) {
                return [
                    'type' => 'Skill',
                    'title' => $booking->skill->title ?? 'Skill Deleted',
                    'payer' => $booking->client->fullName() ?? 'N/A',
                    'receiver' => $booking->provider->fullName() ?? 'N/A',
                    'amount' => $booking->escrow_amount,
                    'fee_percent' => $booking->platform_fee_percent,
                    'platform_fee' => $booking->platform_fee,
                    'payout' => $booking->provider_payout,
                    'released_at' => $booking->escrow_released_at,
                ];
            });

        $jobTransactions = JobApplication::with(['job', 'applicant', 'job.employer'])
            ->where('escrow_status', 'released')
            ->get()
            ->map(function ($application) {
                return [
                    'type' => 'Job',
                    'title' => $application->job->title ?? 'Job Deleted',
                    'payer' => $application->job->employer->fullName() ?? 'N/A',
                    'receiver' => $application->applicant->fullName() ?? 'N/A',
                    'amount' => $application->escrow_amount,
                    'fee_percent' => $application->platform_fee_percent,
                    'platform_fee' => $application->platform_fee,
                    'payout' => $application->worker_payout,
                    'released_at' => $application->escrow_released_at,
                ];
            });

        $transactions = $skillTransactions
            ->merge($jobTransactions)
            ->sortByDesc('released_at');

        return view('admin.transactions.index', compact('transactions'));
    }

    public function jobDisputes()
{
    $disputes = JobApplication::with(['job', 'applicant', 'job.employer'])
        ->where('escrow_status', 'disputed')
        ->latest('disputed_at')
        ->get();

    return view('admin.job-disputes.index', compact('disputes'));
}

public function releaseJobPayment(JobApplication $application)
{
    $application->update([
        'progress'          => 'confirmed',
        'escrow_status'     => 'released',
        'escrow_released_at' => now(),
        'confirmed_at'      => now(),
        'admin_hold'        => false,
        'admin_hold_reason' => null,
    ]);

    $application->job->update(['status' => 'completed']);

    // Notify worker
    Notification::create([
        'user_id'  => $application->applicant_id,
        'type'     => 'dispute_resolved',
        'title'    => 'Dispute Resolved — Payment Released',
        'message'  => 'Admin reviewed the dispute for "' . $application->job->title . '" and released the payment to you.',
        'url'      => route('applications.show', $application->id),
        'is_read'  => false,
    ]);

    // Notify employer
    Notification::create([
        'user_id'  => $application->job->employer_id,
        'type'     => 'dispute_resolved',
        'title'    => 'Dispute Resolved — Payment Released to Worker',
        'message'  => 'Admin reviewed the dispute for "' . $application->job->title . '" and released payment to the worker.',
        'url'      => route('applications.show', $application->id),
        'is_read'  => false,
    ]);

    return back()->with('success', 'Job payment released to worker.');
}

public function refundJobPayment(Request $request, JobApplication $application)
{
    $request->validate([
        'refund_reason' => 'required|string|max:1000',
    ]);

    $application->update([
        'progress'      => 'confirmed', // mark as closed
        'escrow_status' => 'refunded',
        'refund_reason' => $request->refund_reason,
        'refunded_at'   => now(),
        'admin_hold'    => false,
    ]);

    $application->job->update(['status' => 'completed']);

    // Notify employer
    Notification::create([
        'user_id'  => $application->job->employer_id,
        'type'     => 'dispute_resolved',
        'title'    => 'Dispute Resolved — Refund Approved',
        'message'  => 'Admin reviewed the dispute for "' . $application->job->title . '" and approved a refund to you.',
        'url'      => route('applications.show', $application->id),
        'is_read'  => false,
    ]);

    // Notify worker
    Notification::create([
        'user_id'  => $application->applicant_id,
        'type'     => 'dispute_resolved',
        'title'    => 'Dispute Resolved — Refunded to Employer',
        'message'  => 'Admin reviewed the dispute for "' . $application->job->title . '" and issued a refund to the employer.',
        'url'      => route('applications.show', $application->id),
        'is_read'  => false,
    ]);

    return back()->with('success', 'Job escrow marked as refunded to employer.');
}
}

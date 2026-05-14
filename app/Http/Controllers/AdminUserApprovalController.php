<?php

namespace App\Http\Controllers;

use App\Mail\AccountApprovedMail;
use App\Mail\AccountRejectedMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class AdminUserApprovalController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        // Admin middleware is applied via routes
    }

    /**
     * Show pending approval requests.
     */
    public function pending()
    {
        $pendingUsers = User::where('approval_status', 'pending')
            ->where('is_approved', false)
            ->where('otp_verified', true)
            ->where(function ($query) {
                $query->whereNull('email')
                    ->orWhere('email', 'not like', '%@edouniversity.edu.ng');
            })
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('admin.users.pending-approvals', compact('pendingUsers'));
    }

    /**
     * Approve a non-university user.
     */
    public function approve(User $user)
    {
        if ($user->hasUniversityEmail()) {
            return back()->with('error', 'University email users are automatically approved.');
        }

        if (!$user->otp_verified) {
            return back()->with('error', 'This user has not verified their OTP yet and cannot be approved.');
        }

        $user->update([
            'approval_status' => 'approved',
            'is_approved' => true,
            'approved_at' => now(),
            'approved_by' => auth()->id(),
        ]);

        Mail::to($user->email)->queue(new AccountApprovedMail($user));

        return back()->with(
            'success',
            "User '{$user->fullName()}' has been approved successfully. Approval email has been queued."
        );
    }

    /**
     * Reject a non-university user.
     */
    public function reject(User $user)
    {
        if ($user->hasUniversityEmail()) {
            return back()->with('error', 'University email users cannot be rejected.');
        }

        $user->update([
            'approval_status' => 'rejected',
            'is_approved' => false,
            'approved_at' => null,
            'approved_by' => auth()->id(),
        ]);

        Mail::to($user->email)->queue(new AccountRejectedMail($user));

        return back()->with(
            'success',
            "User '{$user->fullName()}' has been rejected. Rejection email has been queued."
        );
    }

    /**
     * Show all approval records.
     */
    public function index()
    {
        $query = User::query()
            ->where(function ($query) {
                $query->whereNull('email')
                    ->orWhere('email', 'not like', '%@edouniversity.edu.ng');
            });

        if (request('status')) {
            $query->where('approval_status', request('status'));
        }

        if (request('search')) {
            $search = request('search');

            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if (request('sort') === 'oldest') {
            $query->orderBy('created_at', 'asc');
        } elseif (request('sort') === 'name') {
            $query->orderBy('first_name', 'asc')
                ->orderBy('last_name', 'asc');
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $allUsers = $query->paginate(20)->withQueryString();

        return view('admin.users.all-approvals', compact('allUsers'));
    }
}
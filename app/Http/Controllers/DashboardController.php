<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Job;
use App\Models\JobApplication;
use App\Models\Message;
use App\Models\Skill;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $userJobs = Job::where('employer_id', $user->id)
            ->withCount('applications')
            ->latest()
            ->take(5)
            ->get();

        $jobApplications = JobApplication::whereHas('job', function ($query) use ($user) {
            $query->where('employer_id', $user->id);
        })
            ->with(['job', 'applicant'])
            ->latest()
            ->take(10)
            ->get();

        $recentApplications = $user->jobapplications()
            ->with(['job.employer'])
            ->latest()
            ->take(3)
            ->get();

        $recentBookings = $user->myBookings()
            ->with(['skill.provider', 'client'])
            ->latest()
            ->take(3)
            ->get();

        $recentMessages = $user->receivedMessages()
            ->with(['sender'])
            ->latest()
            ->take(3)
            ->get();

        $pendingReceivedApplications = JobApplication::whereHas('job', function ($query) use ($user) {
            $query->where('employer_id', $user->id);
        })
            ->where('status', 'pending')
            ->count();

        $pendingMyApplicationActions = JobApplication::where('applicant_id', $user->id)
            ->where(function ($query) use ($user) {
                $query->where(function ($q) {
                    $q->where('status', 'accepted')
                        ->whereIn('progress', ['pending', 'in_progress']);
                })
                ->orWhereNotNull('revision_note')
                ->orWhere(function ($q) use ($user) {
                    $q->where('progress', 'confirmed')
                        ->whereDoesntHave('ratings', function ($ratingQuery) use ($user) {
                            $ratingQuery->where('reviewer_id', $user->id);
                        });
                });
            })
            ->count();

        $pendingSkillBookings = Booking::where('provider_id', $user->id)
            ->where('status', 'interested')
            ->count();

        $pendingServiceActions = Booking::where('client_id', $user->id)
            ->where(function ($query) use ($user) {
                $query->where(function ($q) {
                    $q->where('status', 'completed_waiting_payment')
                        ->where('payment_status', 'unpaid');
                })
                ->orWhere(function ($q) use ($user) {
                    $q->where('status', 'done')
                        ->whereDoesntHave('ratings', function ($ratingQuery) use ($user) {
                            $ratingQuery->where('reviewer_id', $user->id);
                        });
                })
                ->orWhere('payment_status', 'payment_disputed');
            })
            ->count();

        $totalPendingBookingActions = $pendingSkillBookings + $pendingServiceActions;

        $stats = [
            'active_skills' => Skill::where('user_id', $user->id)
                ->where('status', 'active')
                ->count(),

            'job_applications' => JobApplication::where('applicant_id', $user->id)
                ->count(),

            'posted_jobs' => Job::where('employer_id', $user->id)
                ->where('status', 'active')
                ->count(),

            'received_applications' => JobApplication::whereHas('job', function ($query) use ($user) {
                $query->where('employer_id', $user->id);
            })
                ->count(),
        ];

        return view('dashboard', compact(
            'userJobs',
            'jobApplications',
            'recentApplications',
            'recentBookings',
            'recentMessages',
            'stats',
            'pendingReceivedApplications',
            'pendingMyApplicationActions',
            'pendingSkillBookings',
            'pendingServiceActions',
            'totalPendingBookingActions'
        ));
    }
}
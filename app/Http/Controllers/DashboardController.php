<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Skill;
use App\Models\Job;
use App\Models\JobApplication;
use App\Models\Booking;
use App\Models\Message;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        // Get user's skills
        $userSkills = Skill::where('user_id', $user->id)
            ->withCount(['reviews', 'bookings'])
            ->latest()
            ->take(5)
            ->get();
        
        // Get user's posted jobs
        $userJobs = Job::where('employer_id', $user->id)
            ->withCount(['applications'])
            ->latest()
            ->take(5)
            ->get();
        
        // Get job applications for user's posted jobs
        $jobApplications = JobApplication::whereIn('job_id', $userJobs->pluck('id'))
            ->with(['job', 'applicant'])
            ->latest()
            ->take(10)
            ->get();
        
        // Get user's job applications (when they applied to jobs) - Recent 3
        $recentApplications = auth()->user()->jobapplications()
            ->with(['job.employer'])
            ->latest()
            ->take(3)
            ->get();
        
        // Get user's bookings - Recent 3
        $recentBookings = auth()->user()->myBookings()
            ->with(['skill.provider', 'client'])
            ->latest()
            ->take(3)
            ->get();
        
        // Get user's received messages - Recent 3
        $recentMessages = auth()->user()->receivedMessages()
            ->with(['sender'])
            ->latest()
            ->take(3)
            ->get();
        
        // Calculate stats
        $stats = [
            'active_skills' => Skill::where('user_id', $user->id)->where('status', 'active')->count(),
            'job_applications' => JobApplication::where('applicant_id', $user->id)->count(),
            'posted_jobs' => Job::where('employer_id', $user->id)->where('status', 'active')->count(),
            'received_applications' => JobApplication::whereIn('job_id', $userJobs->pluck('id'))->count(),
        ];
        
        return view('dashboard', compact(
            'recentApplications',
            'recentBookings',
            'recentMessages',
            'stats'
        ));
    }
}
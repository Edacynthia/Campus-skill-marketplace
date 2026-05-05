<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Skill;
use App\Models\Job;
use App\Models\JobApplication;
use App\Models\Order;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        // Get user's skills
        $userSkills = Skill::where('user_id', $user->id)
            ->withCount(['reviews', 'orders'])
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
        
        // Get user's job applications (when they applied to jobs)
        $myApplications = JobApplication::where('applicant_id', $user->id)
            ->with(['job'])
            ->latest()
            ->take(5)
            ->get();
        
        // Get user's orders (when they purchased skills)
        $myOrders = Order::where('client_id', $user->id)
            ->with(['skill', 'vendor'])
            ->latest()
            ->take(5)
            ->get();
        
        // Calculate stats
        $stats = [
            'active_skills' => Skill::where('user_id', $user->id)->where('status', 'active')->count(),
            'job_applications' => $myApplications->count(),
            'posted_jobs' => Job::where('employer_id', $user->id)->where('status', 'active')->count(),
            'received_applications' => $jobApplications->count(),
            'total_earnings' => Order::where('vendor_id', $user->id)->sum('total_amount'),
        ];
        
        return view('dashboard', compact(
            'userSkills',
            'userJobs', 
            'jobApplications',
            'myApplications',
            'myOrders',
            'stats'
        ));
    }
}
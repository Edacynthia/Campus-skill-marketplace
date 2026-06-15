<?php

namespace App\Http\Controllers;

use App\Models\JobApplication;
use App\Models\Job;
use Illuminate\Http\Request;

class ApplicationController extends Controller
{
    /**
     * Display user's job applications
     */
    public function myApplications()
    {
        $user = auth()->user();
        
        $applications = $user->applications()
            ->with(['job.employer', 'ratings'])
            ->latest()
            ->paginate(10);

        return view('applications.mine', compact('applications'));
    }

    /**
     * Display applications received for user's jobs
     */
    public function receivedApplications()
    {
        $user = auth()->user();
        
        // Get user's job IDs
        $userJobIds = $user->jobs()->pluck('id');
        
        $applications = JobApplication::whereIn('job_id', $userJobIds)
            ->with(['job', 'applicant', 'ratings'])
            ->latest()
            ->paginate(10);

        return view('applications.received', compact('applications'));
    }

    /**
     * Show application details
     */
    public function show($id)
    {
        $application = JobApplication::with(['job.employer', 'applicant', 'ratings'])
            ->findOrFail($id);

        // Check if user can view this application
        $user = auth()->user();
        if ($application->applicant_id !== $user->id && $application->job->employer_id !== $user->id) {
            abort(403, 'Unauthorized');
        }

        if ($application->applicant_id === auth()->id() && $application->status !== 'pending') {
            return redirect()
                ->route('applications.mine')
                ->with('error', 'You can only edit applications that are still pending.');
        }

        return view('applications.show', compact('application'));
    }

    public function viewReceived($id)
{
    $application = JobApplication::with(['job.employer', 'applicant', 'ratings'])
        ->findOrFail($id);

    if ($application->job->employer_id !== auth()->id()) {
        abort(403, 'Unauthorized');
    }

    return view('applications.received-view', compact('application'));
}
}
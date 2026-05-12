<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\JobApplication;
use App\Models\Rating;
use Illuminate\Http\Request;

class JobProgressController extends Controller
{
    public function accept($applicationId)
    {
        if (!auth()->check()) {
            return response()->json(['success' => false, 'message' => 'Please sign in'], 401);
        }

        $application = JobApplication::findOrFail($applicationId);
        
        // Verify authenticated user is the employer of the job
        if ($application->job->employer_id !== auth()->id()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized action'], 403);
        }

        // Set application status to accepted and progress to pending
        $application->update([
            'status' => 'accepted',
            'progress' => 'pending'
        ]);

        // Set all other applications for the same job to rejected
        JobApplication::where('job_id', $application->job_id)
            ->where('id', '!=', $applicationId)
            ->update(['status' => 'rejected']);

        // Update job status to in_progress
        $application->job->update(['status' => 'in_progress']);

        return response()->json([
            'success' => true,
            'message' => 'Application accepted successfully!'
        ]);
    }

    public function reject($applicationId)
{
    $application = JobApplication::findOrFail($applicationId);

    if ($application->job->employer_id !== auth()->id()) {
        return response()->json(['success' => false, 'message' => 'Unauthorized action'], 403);
    }

    if ($application->status !== 'pending') {
        return response()->json(['success' => false, 'message' => 'Can only reject pending applications'], 400);
    }

    $application->update(['status' => 'rejected']);

    return response()->json([
        'success' => true,
        'message' => 'Application rejected.'
    ]);
}

    public function startWork($applicationId)
    {
        if (!auth()->check()) {
            return response()->json(['success' => false, 'message' => 'Please sign in'], 401);
        }

        $application = JobApplication::findOrFail($applicationId);
        
        // Verify authenticated user is the applicant
        if ($application->applicant_id !== auth()->id()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized action'], 403);
        }

        // Verify application status is accepted
        if ($application->status !== 'accepted') {
            return response()->json(['success' => false, 'message' => 'Application must be accepted first'], 400);
        }

        // Set progress to in_progress and record started_at
        $application->update([
            'progress' => 'in_progress',
            'started_at' => now()
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Work started successfully!'
        ]);
    }

    public function markComplete($applicationId)
    {
        if (!auth()->check()) {
            return response()->json(['success' => false, 'message' => 'Please sign in'], 401);
        }

        $application = JobApplication::findOrFail($applicationId);
        
        // Verify authenticated user is the applicant
        if ($application->applicant_id !== auth()->id()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized action'], 403);
        }

        // Verify progress is in_progress
        if ($application->progress !== 'in_progress') {
            return response()->json(['success' => false, 'message' => 'Work must be in progress first'], 400);
        }

        // Set progress to completed and record completed_at
        $application->update([
            'progress' => 'completed',
            'completed_at' => now(),
            'revision_note' => null 
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Work marked as complete!'
        ]);
    }

    public function requestRevision(Request $request, $applicationId)
    {
        if (!auth()->check()) {
            return response()->json(['success' => false, 'message' => 'Please sign in'], 401);
        }

        $application = JobApplication::findOrFail($applicationId);
        
        // Verify authenticated user is the employer
        if ($application->job->employer_id !== auth()->id()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized action'], 403);
        }

        // Verify progress is completed
        if ($application->progress !== 'completed') {
            return response()->json(['success' => false, 'message' => 'Work must be completed first'], 400);
        }

        $request->validate([
            'revision_note' => 'required|string|max:1000'
        ]);

        // Set progress back to in_progress and save revision note
        $application->update([
            'progress' => 'in_progress',
            'revision_note' => $request->revision_note
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Revision requested successfully!'
        ]);
    }

    public function confirmComplete($applicationId)
    {
        if (!auth()->check()) {
            return response()->json(['success' => false, 'message' => 'Please sign in'], 401);
        }

        $application = JobApplication::findOrFail($applicationId);
        
        // Verify authenticated user is the employer
        if ($application->job->employer_id !== auth()->id()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized action'], 403);
        }

        // Verify progress is completed
        if ($application->progress !== 'completed') {
            return response()->json(['success' => false, 'message' => 'Work must be completed first'], 400);
        }

        // Set progress to confirmed and record confirmed_at
        $application->update([
            'progress' => 'confirmed',
            'confirmed_at' => now()
        ]);

        // Update job status to completed
        $application->job->update(['status' => 'completed']);

        return response()->json([
            'success' => true,
            'message' => 'Job confirmed as complete!',
            'ratingsUnlocked' => true
        ]);
    }

    public function submitRating(Request $request, $applicationId)
    {
        if (!auth()->check()) {
            return response()->json(['success' => false, 'message' => 'Please sign in'], 401);
        }

        $application = JobApplication::findOrFail($applicationId);
        
        // Verify progress is confirmed
        if ($application->progress !== 'confirmed') {
            return response()->json(['success' => false, 'message' => 'Job must be confirmed first'], 400);
        }

        // Determine type automatically
        $type = null;
        if ($application->job->employer_id === auth()->id()) {
            $type = 'employer_to_worker';
        } elseif ($application->applicant_id === auth()->id()) {
            $type = 'worker_to_employer';
        } else {
            return response()->json(['success' => false, 'message' => 'Unauthorized action'], 403);
        }

        // Validate that this reviewer has not already submitted a rating for this application
        $existingRating = Rating::where('application_id', $applicationId)
            ->where('reviewer_id', auth()->id())
            ->first();

        if ($existingRating) {
            return response()->json(['success' => false, 'message' => 'You have already submitted a rating for this application'], 400);
        }

        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'nullable|string|max:500'
        ]);

        // Create rating record
        Rating::create([
            'job_id' => $application->job_id,
            'application_id' => $applicationId,
            'reviewer_id' => auth()->id(),
            'reviewee_id' => $type === 'employer_to_worker' ? $application->applicant_id : $application->job->employer_id,
            'rating' => $request->rating,
            'review' => $request->review,
            'type' => $type
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Rating submitted successfully!'
        ]);
    }
}

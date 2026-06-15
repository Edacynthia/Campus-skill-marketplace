<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\JobApplication;
use App\Models\Rating;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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

        // Keep job visible on jobs index.
        // The accepted application controls the job progress instead.
        $application->job->update(['status' => 'active']);

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

        if ($application->escrow_status !== 'funded') {
            return response()->json([
                'success' => false,
                'message' => 'Employer must fund escrow before you can start work.'
            ], 400);
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

    public function markComplete(Request $request, $applicationId)
    {
        if (!auth()->check()) {
            return response()->json(['success' => false, 'message' => 'Please sign in'], 401);
        }

        $application = JobApplication::findOrFail($applicationId);

        if ($application->applicant_id !== auth()->id()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized action'], 403);
        }

        if ($application->progress !== 'in_progress') {
            return response()->json(['success' => false, 'message' => 'Work must be in progress first'], 400);
        }

        $validated = $request->validate([
            'delivery_note'    => 'required|string|max:2000',
            'delivery_link'    => 'nullable|url|max:500',
            'delivery_file'    => 'nullable|file|mimes:zip,pdf,doc,docx,rar|max:10240',
            'screenshots.*'    => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
        ]);

        // Scan zip contents for malicious files
        if (
            $request->hasFile('delivery_file') &&
            in_array($request->file('delivery_file')->getClientOriginalExtension(), ['zip', 'rar'])
        ) {

            $dangerousExtensions = ['php', 'exe', 'sh', 'bat', 'js', 'py', 'rb', 'pl', 'phtml', 'htaccess'];
            $zip = new \ZipArchive();

            if ($zip->open($request->file('delivery_file')->getRealPath()) === true) {
                for ($i = 0; $i < $zip->numFiles; $i++) {
                    $filename = $zip->getNameIndex($i);
                    $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

                    // Block dangerous extensions inside zip
                    if (in_array($ext, $dangerousExtensions)) {
                        $zip->close();
                        return response()->json([
                            'success' => false,
                            'message' => 'ZIP file contains disallowed file types (.' . $ext . ').'
                        ], 422);
                    }

                    // Block zip bombs
                    $stat = $zip->statIndex($i);
                    if ($stat['size'] > 50 * 1024 * 1024) { // 50MB uncompressed limit
                        $zip->close();
                        return response()->json([
                            'success' => false,
                            'message' => 'ZIP contains files that are too large when extracted.'
                        ], 422);
                    }
                }
                $zip->close();
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Could not open ZIP file. It may be corrupted.'
                ], 422);
            }
        }

        try {
            $filePath = $application->delivery_file;
            $screenshotPaths = $application->delivery_screenshots ?? [];

            if ($request->hasFile('delivery_file')) {
                if ($filePath) {
                    Storage::disk('public')->delete($filePath);
                }
                $filePath = $request->file('delivery_file')->storeAs(
                    'job-deliveries',
                    \Str::uuid() . '.' . $request->file('delivery_file')->getClientOriginalExtension(),
                    'public'
                );
            }

            if ($request->hasFile('screenshots')) {
                $screenshotPaths = [];
                foreach ($request->file('screenshots') as $image) {
                    $screenshotPaths[] = $image->store('job-deliveries/screenshots', 'public');
                }
            }

            $application->update([
                'progress'           => 'completed',
                'escrow_status'      => 'completed',
                'completed_at'       => now(),
                'worker_completed_at' => now(),
                'auto_release_at'    => now()->addDays(3),
                'delivery_note'      => $request->delivery_note,
                'delivery_link'      => $request->delivery_link,
                'delivery_file'      => $filePath,
                'revision_note'      => null,
                'delivery_screenshots' => $screenshotPaths,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Work submitted successfully! Employer can now review it.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong while submitting your work. Please try again.',
                'debug'   => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
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
        if ($application->revision_count >= 5) {
            return response()->json([
                'success' => false,
                'message' => 'Revision limit reached. You can only request revision 5 times. Please confirm the job or open a dispute.'
            ], 400);
        }

        $application->update([
            'progress' => 'in_progress',
            'revision_note' => $request->revision_note,
            'revision_count' => $application->revision_count + 1,
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
        if ($application->admin_hold) {
            return response()->json([
                'success' => false,
                'message' => 'Payment is currently on admin hold.'
            ], 400);
        }

        $application->update([
            'progress' => 'confirmed',
            'escrow_status' => 'released',
            'confirmed_at' => now(),
            'escrow_released_at' => now(),
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

    public function openDispute(Request $request, $applicationId)
    {
        if (!auth()->check()) {
            return response()->json(['success' => false, 'message' => 'Please sign in'], 401);
        }

        $application = JobApplication::with('job')->findOrFail($applicationId);

        if ($application->job->employer_id !== auth()->id()) {
            return response()->json(['success' => false, 'message' => 'Only the employer can open this dispute.'], 403);
        }

        if (!in_array($application->progress, ['completed', 'confirmed'])) {
            return response()->json([
                'success' => false,
                'message' => 'You can only dispute after work has been submitted.'
            ], 400);
        }

        $request->validate([
            'reason' => 'required|string|max:1000',
        ]);

        $application->update([
            'escrow_status'    => 'disputed',
            'dispute_reason'   => $request->reason,
            'disputed_at'      => now(),
            'admin_hold'       => true,
            'admin_hold_reason' => 'Employer opened a dispute after job delivery.',
        ]);

        // Notify the worker
        \App\Models\Notification::create([
            'user_id'  => $application->applicant_id,
            'type'     => 'dispute_opened',
            'title'    => 'Dispute Opened on Your Delivery',
            'message'  => 'The employer has raised a dispute on "' . $application->job->title . '". An admin will review and resolve it.',
            'url'      => route('applications.show', $applicationId),
            'is_read'  => false,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Dispute opened. Admin will review the job delivery and complaint.'
        ]);
    }
}

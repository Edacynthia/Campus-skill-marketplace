<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Job;
use App\Models\JobApplication;
use App\Models\User;
use App\Models\Notification;

class JobController extends Controller
{
    public function index(Request $request)
    {
        // Debug: Log all request parameters
        \Log::info('JobController index called with params: ' . json_encode($request->all()));

        $query = Job::with('employer')->where('status', 'active');

        // Search functionality
        if ($request->has('search') && !empty($request->search)) {
            $searchTerm = $request->search;
            // Split search term into individual words for better matching
            $searchWords = explode(' ', $searchTerm);

            $query->where(function ($q) use ($searchWords) {
                foreach ($searchWords as $word) {
                    if (!empty(trim($word))) {
                        $q->orWhere('title', 'LIKE', '%' . trim($word) . '%')
                            ->orWhere('description', 'LIKE', '%' . trim($word) . '%')
                            ->orWhere('category', 'LIKE', '%' . trim($word) . '%')
                            ->orWhere('location', 'LIKE', '%' . trim($word) . '%');
                    }
                }
            });
        }

        // Filter by category
        if ($request->has('category') && !empty($request->category)) {
            $query->where('category', $request->category);
        }

        // Filter by type
        if ($request->has('type') && !empty($request->type)) {
            $query->where('type', $request->type);
        }

        // Filter by urgency
        if ($request->has('urgency') && !empty($request->urgency)) {
            $query->where('urgency', $request->urgency);
        }

        // Filter by salary range
        if ($request->has('min_salary') && $request->min_salary) {
            $query->where('salary', '>=', $request->min_salary);
        }

        if ($request->has('max_salary') && $request->max_salary) {
            $query->where('salary', '<=', $request->max_salary);
        }

        // Filter by deadline
        if ($request->has('deadline')) {
            if ($request->deadline === 'urgent') {
                $query->where('deadline', '<=', now()->addDays(3));
            } elseif ($request->deadline === 'week') {
                $query->where('deadline', '<=', now()->addDays(7));
            }
        }

        // Sort functionality
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');

        if ($sortBy === 'salary_low') {
            $query->orderBy('salary', 'asc');
        } elseif ($sortBy === 'salary_high') {
            $query->orderBy('salary', 'desc');
        } elseif ($sortBy === 'deadline') {
            $query->orderBy('deadline', 'asc');
        } elseif ($sortBy === 'urgency') {
            $query->orderByRaw('urgency DESC, deadline ASC');
        } else {
            $query->orderBy($sortBy, $sortOrder);
        }

        $jobs = $query->paginate(12);

        // Get categories for filter
        $categories = Job::where('status', 'active')
            ->distinct()
            ->pluck('category')
            ->filter()
            ->sort();

        return view('jobs.index', compact('jobs', 'categories'));
    }

    public function show($id)
    {
        $job = Job::with(['employer', 'applications.applicant'])->findOrFail($id);

        // Only count views from non-owners and only once per session
        $sessionKey = 'viewed_job_' . $id;
        if (
            !session()->has($sessionKey) &&
            (!auth()->check() || auth()->id() !== $job->employer_id)
        ) {
            $job->increment('views_count');
            session()->put($sessionKey, true);
        }

        // Get related jobs
        $relatedJobs = Job::where('category', $job->category)
            ->where('id', '!=', $job->id)
            ->where('status', 'active')
            ->take(4)
            ->get();

        // Check if current user has already applied
        $userApplication = null;
        if (auth()->check()) {
            $userApplication = $job->applications()
                ->where('applicant_id', auth()->id())
                ->whereIn('status', ['pending', 'accepted'])
                ->first();
        }

        // If user is not authenticated, show limited view
        if (!auth()->check()) {
            session()->flash('info', 'Sign in to apply for jobs and save opportunities');
        }

        return view('jobs.show', compact('job', 'relatedJobs', 'userApplication'));
    }

    public function apply(Request $request, $id)
    {
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Please sign in to apply for jobs');
        }

        $job = Job::findOrFail($id);

        // Check if user is trying to apply for their own job
        if ($job->employer_id === auth()->id()) {
            return back()->with('error', 'You cannot apply for your own job posting');
        }

        // Check if job is still active
        if ($job->status !== 'active') {
            return back()->with('error', 'This job is no longer accepting applications');
        }

        // Check if deadline has passed
        if ($job->deadline && $job->deadline->isPast()) {
            return back()->with('error', 'The application deadline for this job has passed');
        }

        // Check if user has already applied
        $existingApplication = $job->applications()
            ->where('applicant_id', auth()->id())
            ->whereIn('status', ['pending', 'accepted'])
            ->first();

        if ($existingApplication) {
            return back()->with('error', 'You have already applied for this job');
        }

        // Validate request
        $request->validate([
            'cover_letter' => 'required|string|min:50|max:1000'
        ]);

        // Create application
        $application = $job->applications()->create([
            'applicant_id' => auth()->id(),
            'cover_letter' => $request->cover_letter,
            'status' => 'pending'
        ]);

        // Increment applications count
        $job->increment('applications_count');

        Notification::createNotification(
            $job->employer_id,
            'job_application',
            'New Job Application',
            auth()->user()->first_name . ' applied to your job: ' . $job->title,
            '/received-applications'
        );

        // In JobController.php → apply() method
        return redirect()->route('jobs.show', $job->id)
            ->with('success', 'Your application has been submitted successfully!')
            ->with('flash_success', true);   // Extra force
    }

    public function create()
    {
        return view('jobs.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|string',
            'type' => 'required|in:on_campus,off_campus,remote',
            'urgency' => 'required|in:normal,urgent',
            'salary' => 'required|numeric|min:0',
            'salary_type' => 'required|in:hourly,fixed',
            'location' => 'required|string|max:255',
            'deadline' => 'nullable|date|after_or_equal:today',
            'requirements' => 'nullable|array',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $imagePath = null;

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('jobs', 'public');
        }

        $job = Job::create([
            'employer_id' => auth()->id(),
            'title' => ucwords(strtolower(trim($request->title))),
            'description' => $request->description,
            'category' => $request->category,
            'type' => $request->type,
            'urgency' => $request->urgency,
            'salary' => $request->salary,
            'salary_type' => $request->salary_type,
            'location' => $request->location,
            'deadline' => $request->deadline,
            'requirements' => $request->requirements,
            'image' => $imagePath,
            'status' => 'active',
        ]);

        $users = User::where('id', '!=', auth()->id())->get();

        foreach ($users as $user) {
            Notification::createNotification(
                $user->id,
                'new_job',
                'New Job Posted',
                auth()->user()->first_name . ' posted a new job: ' . $job->title,
                '/jobs/' . $job->id
            );
        }

        return redirect()->route('dashboard')
            ->with('success', 'Job posted successfully!');
    }

    public function edit($id)
    {
        $job = Job::where('employer_id', auth()->id())->findOrFail($id);
        return view('jobs.edit', compact('job'));
    }

    public function update(Request $request, $id)
    {
        $job = Job::where('employer_id', auth()->id())->findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|string',
            'type' => 'required|in:on_campus,off_campus,remote',
            'urgency' => 'required|in:normal,urgent',
            'salary' => 'required|numeric|min:0',
            'salary_type' => 'required|in:hourly,fixed',
            'location' => 'required|string|max:255',
            'deadline' => 'nullable|date|after_or_equal:today',
            'requirements' => 'nullable|array',
        ]);

        $job->update([
            'title' => ucwords(strtolower(trim($request->title))),
            'description' => $request->description,
            'category' => $request->category,
            'type' => $request->type,
            'urgency' => $request->urgency,
            'salary' => $request->salary,
            'salary_type' => $request->salary_type,
            'location' => $request->location,
            'deadline' => $request->deadline,
            'requirements' => $request->requirements,
        ]);

        return redirect()->route('jobs.mine')
            ->with('success', 'Job updated successfully!');
    }

    public function destroy($id)
    {
        $job = Job::where('employer_id', auth()->id())->findOrFail($id);

        // Check for pending applications
        $pendingApplicationsCount = JobApplication::where('job_id', $job->id)
            ->where('status', 'pending')
            ->count();

        if ($pendingApplicationsCount > 0) {
            return redirect()->route('dashboard')
                ->with('error', "Cannot delete job. You have {$pendingApplicationsCount} unreviewed application(s). Please review all applications before deleting the job.");
        }

        $job->delete();

        return redirect()->route('dashboard')
            ->with('success', 'Job deleted successfully!');
    }

    /**
     * Close a job
     */
    public function close($id)
    {
        $job = Job::where('employer_id', auth()->id())->findOrFail($id);
        $job->update(['status' => 'closed']);

        return redirect()->back()
            ->with('success', 'Job closed successfully!');
    }

    /**
     * Reopen a job
     */
    public function reopen($id)
    {
        $job = Job::where('employer_id', auth()->id())->findOrFail($id);
        $job->update(['status' => 'active']);

        return redirect()->back()
            ->with('success', 'Job reopened successfully!');
    }

    /**
     * Display user's job postings
     */
    public function myJobs()
    {
        $user = auth()->user();

        $jobs = $user->jobs()
            ->withCount(['applications'])
            ->with(['applications' => function ($query) {
                $query->latest()->take(3);
            }])
            ->latest()
            ->paginate(10);

        return view('jobs.mine', compact('jobs'));
    }

    public function editApplication(Request $request, $id)
    {
        if (!auth()->check()) {
            return response()->json(['success' => false, 'message' => 'Please sign in to edit applications'], 401);
        }

        $application = \App\Models\JobApplication::findOrFail($id);

        // Check if user owns this application
        if ($application->applicant_id !== auth()->id()) {
            return response()->json(['success' => false, 'message' => 'You can only edit your own applications'], 403);
        }

        // Check if application is still pending
        if ($application->status !== 'pending') {
            return response()->json(['success' => false, 'message' => 'You can only edit pending applications'], 403);
        }

        // Check if job deadline has passed
        $job = $application->job;
        if ($job->deadline && $job->deadline->isPast()) {
            return response()->json(['success' => false, 'message' => 'The application deadline has passed'], 403);
        }

        // Validate request
        $request->validate([
            'cover_letter' => 'required|string|min:50|max:1000'
        ]);

        if ($application->status !== 'pending') {
            return redirect()
                ->route('applications.mine')
                ->with('error', 'You can only update applications that are still pending.');
        }

        // Update application
        $application->update([
            'cover_letter' => $request->cover_letter,
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Application updated successfully!'
            ]);
        }

        return redirect()
            ->route('applications.mine')
            ->with('success', 'Application updated successfully!');
    }

    public function withdrawApplication(Request $request, $id)
    {
        if (!auth()->check()) {
            return response()->json(['success' => false, 'message' => 'Please sign in to withdraw applications'], 401);
        }

        $application = \App\Models\JobApplication::findOrFail($id);

        // Check if user owns this application
        if ($application->applicant_id !== auth()->id()) {
            return response()->json(['success' => false, 'message' => 'You can only withdraw your own applications'], 403);
        }

        // Check if application is still pending
        if ($application->status !== 'pending') {
            return response()->json(['success' => false, 'message' => 'You can only withdraw pending applications'], 403);
        }

        // Check if job deadline has passed
        $job = $application->job;
        if ($job->deadline && $job->deadline->isPast()) {
            return response()->json(['success' => false, 'message' => 'The application deadline has passed'], 403);
        }

        // Delete the application
        $application->delete();

        // Decrement applications count
        $job->decrement('applications_count');

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Application withdrawn successfully!'
            ]);
        }

        return redirect()
            ->route('applications.mine')
            ->with('success', 'Application withdrawn successfully!');
    }
}

<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminUserApprovalController;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\OTPController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\JobController;
use App\Http\Controllers\JobProgressController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PaymentController;


use App\Http\Controllers\SkillController;
use Illuminate\Http\Request;

Route::get('/test-mail', function () {
    Mail::raw('Laravel email test is working.', function ($message) {
        $message->to('cynthiaeda70@gmail.com')
            ->subject('Test Email from Laravel');
    });

    return 'Test email sent';
});

// ====================== PUBLIC ROUTES ======================
Route::get('/', function () {
    if (auth()->check()) {
        $user = auth()->user();

        // Pending users should see the guest home, not the dashboard
        if ($user->isPendingApproval()) {
            return view('home');
        }

        // Admin goes to admin panel
        if ($user->hasRole('admin') || $user->role === 'admin') {
            return redirect()->route('admin.users.pending');
        }

        return redirect()->route('dashboard');
    }
    return view('home');
})->name('home');


Route::get('/skills', [SkillController::class, 'index'])->name('skills.index');
Route::get('/jobs', [JobController::class, 'index'])->name('jobs.index');
// Route::get('/search', fn() => 'Search results coming...')->name('search');
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;


Route::get('/search', function (Request $request) {
    $type = $request->get('type', 'skills');
    $query = $request->get('q');

    if ($type === 'jobs') {
        return redirect()->route('jobs.index', [
            'search' => $query,
        ]);
    }

    return redirect()->route('skills.index', [
        'search' => $query,
    ]);
})->name('search');

// ====================== AUTHENTICATION ROUTES ======================
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);

Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// ====================== OTP ROUTES ======================
Route::get('/otp/request', [OTPController::class, 'showRequestForm'])->name('otp.request.form');
Route::post('/otp/send', [OTPController::class, 'sendOTP'])->name('otp.send');
Route::get('/otp/verify', [OTPController::class, 'showVerificationForm'])->name('otp.verification.form');
Route::post('/otp/verify', [OTPController::class, 'verifyOTP'])->name('otp.verify');

// ====================== APPROVAL ROUTES ======================
Route::get('/approval-pending', function () {
    return view('auth.approval-pending');
})->name('approval.pending');

// ====================== PROTECTED ROUTES ======================
Route::middleware(['auth', 'approved', 'check.status'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::post('/applications/{id}/pay-escrow', [PaymentController::class, 'payJobEscrow'])->name('applications.payEscrow');
    Route::get('/applications/escrow/callback', [PaymentController::class, 'jobEscrowCallback'])->name('jobs.escrow.callback');

    Route::post('/bookings/{id}/pay-escrow', [PaymentController::class, 'payEscrow'])->name('bookings.payEscrow');
    Route::get('/bookings/escrow/callback', [PaymentController::class, 'escrowCallback'])->name('bookings.escrow.callback');

    // Profile Routes
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');

    // User Management Routes
    Route::get('/my-applications', [ApplicationController::class, 'myApplications'])->name('applications.mine');
    Route::get('/received-applications', [ApplicationController::class, 'receivedApplications'])->name('applications.received');
    Route::get('/applications/{id}', [ApplicationController::class, 'show'])->name('applications.show');
    Route::get('/bookings', [BookingController::class, 'myBookings'])->name('bookings.index');
    Route::get('/my-service-requests', [BookingController::class, 'myServiceRequests'])->name('bookings.requests');
    Route::get('/my-skill-bookings', [BookingController::class, 'mySkillBookings'])->name('bookings.skills');
    Route::get('/my-skills', [SkillController::class, 'mySkills'])->name('skills.mine');
    Route::get('/my-jobs', [JobController::class, 'myJobs'])->name('jobs.mine');

    // Job Routes — create must be before {id}
    Route::get('/jobs/create', [JobController::class, 'create'])->name('jobs.create');
    Route::post('/jobs', [JobController::class, 'store'])->name('jobs.store');
    Route::get('/jobs/{id}/edit', [JobController::class, 'edit'])->name('jobs.edit');
    Route::put('/jobs/{id}', [JobController::class, 'update'])->name('jobs.update');
    Route::delete('/jobs/{id}', [JobController::class, 'destroy'])->name('jobs.destroy');
    Route::post('/jobs/{id}/apply', [JobController::class, 'apply'])->name('jobs.apply');
    Route::patch('/jobs/{id}/close', [JobController::class, 'close'])->name('jobs.close');
    Route::patch('/jobs/{id}/reopen', [JobController::class, 'reopen'])->name('jobs.reopen');

    Route::get('/received-applications/{id}/view', [ApplicationController::class, 'viewReceived'])
    ->name('applications.received.view');

    // Application editing routes
    Route::patch('/applications/{id}', [JobController::class, 'editApplication'])->name('applications.edit');
    Route::delete('/applications/{id}', [JobController::class, 'withdrawApplication'])->name('applications.withdraw');

    Route::post('/applications/{id}/dispute', [JobProgressController::class, 'openDispute'])->name('applications.dispute');

    // Application progress routes
    Route::post('/applications/{id}/accept', [JobProgressController::class, 'accept'])->name('applications.accept');
    Route::post('/applications/{id}/reject', [JobProgressController::class, 'reject'])->name('applications.reject');
    Route::post('/applications/{id}/start', [JobProgressController::class, 'startWork'])->name('applications.start');
    Route::post('/applications/{id}/complete', [JobProgressController::class, 'markComplete'])->name('applications.complete');
    Route::post('/applications/{id}/revision', [JobProgressController::class, 'requestRevision'])->name('applications.revision');
    Route::post('/applications/{id}/confirm', [JobProgressController::class, 'confirmComplete'])->name('applications.confirm');
    Route::post('/applications/{id}/rate', [JobProgressController::class, 'submitRating'])->name('applications.rate');

    // Skill Routes — create must be before {id}
    Route::get('/skills/create', [SkillController::class, 'create'])->name('skills.create');
    Route::post('/skills', [SkillController::class, 'store'])->name('skills.store');
    Route::get('/skills/{id}/edit', [SkillController::class, 'edit'])->name('skills.edit');
    Route::put('/skills/{id}', [SkillController::class, 'update'])->name('skills.update');
    Route::delete('/skills/{id}', [SkillController::class, 'destroy'])->name('skills.destroy');
    Route::post('/skills/{id}/apply', [SkillController::class, 'apply'])->name('skills.apply');
    Route::patch('/skills/{id}/activate', [SkillController::class, 'activate'])->name('skills.activate');
    Route::patch('/skills/{id}/deactivate', [SkillController::class, 'deactivate'])->name('skills.deactivate');

    // Booking Routes
    Route::post('/skills/{skillId}/book', [BookingController::class, 'store'])->name('bookings.store');
    Route::post('/bookings/{id}/confirm', [BookingController::class, 'confirm'])->name('bookings.confirm');
    Route::post('/bookings/{id}/payment-sent', [BookingController::class, 'confirmPaymentSent'])->name('bookings.payment.sent');
    Route::post('/bookings/{id}/decline', [BookingController::class, 'decline'])->name('bookings.decline');
    Route::post('/bookings/{id}/rate', [BookingController::class, 'submitRating'])->name('bookings.rate');
    Route::post('/bookings/{id}/update-progress', [BookingController::class, 'updateProgress'])->name('bookings.updateProgress');
    Route::post('/bookings/{id}/client-paid', [BookingController::class, 'clientMarkedPaid'])->name('bookings.clientPaid');
    Route::post('/bookings/{id}/payment-received', [BookingController::class, 'providerReceivedPayment'])->name('bookings.paymentReceived');
    Route::post('/bookings/{id}/payment-not-received', [BookingController::class, 'providerPaymentNotReceived'])->name('bookings.paymentNotReceived');
    Route::post('/bookings/{id}/provider-completed', [BookingController::class, 'providerMarkCompleted'])->name('bookings.providerCompleted');
    Route::post('/bookings/{id}/confirm-received', [BookingController::class, 'clientConfirmServiceReceived'])->name('bookings.confirmReceived');
    Route::post('/bookings/{id}/escrow-dispute', [BookingController::class, 'clientOpenEscrowDispute'])->name('bookings.escrowDispute');
    Route::delete('/bookings/{id}/client-delete',[BookingController::class, 'clientDeleteBooking'])->name('bookings.clientDelete');
    Route::delete('/bookings/{id}/provider-delete',[BookingController::class, 'providerDeleteBooking'])->name('bookings.providerDelete');
    // Payment Dispute Routes
    Route::get('/bookings/{booking}/dispute', [BookingController::class, 'showDispute'])->name('bookings.dispute.show');
    Route::post('/bookings/{booking}/dispute-response', [BookingController::class, 'submitDisputeResponse'])->name('bookings.dispute.response');
    // Job Route::middleware(['auth'])->group(function () {
});

// ====================== MESSAGING ROUTES ======================
Route::middleware(['auth', 'approved', 'check.status'])->group(function () {
    Route::get('/messages', [MessageController::class, 'index'])->name('messages.index');
    Route::post('/messages', [MessageController::class, 'store'])->name('messages.store');
    Route::post('/messages/{id}/read', [MessageController::class, 'markAsRead'])->name('messages.read');
    Route::post('/messages/{id}/reply', [MessageController::class, 'reply'])->name('messages.reply');
    Route::post('/messages/{id}/archive', [MessageController::class, 'archive'])->name('messages.archive');
    Route::delete('/messages/{id}', [MessageController::class, 'destroy'])->name('messages.destroy');
    Route::get('/messages/archived', [MessageController::class, 'archived'])
        ->name('messages.archived');
    Route::post('/messages/{id}/unarchive', [MessageController::class, 'unarchive'])
        ->name('messages.unarchive');
});

// ====================== NOTIFICATION ROUTES ======================
Route::middleware(['auth', 'approved', 'check.status'])->group(function () {
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllAsRead'])->name('notifications.readAll');
    Route::get('/api/notifications/count', [NotificationController::class, 'getUnreadCount']);
    Route::get('/api/notifications/recent', [NotificationController::class, 'getRecentNotifications']);
    Route::get('/notifications/{id}/open', [NotificationController::class, 'open'])->name('notifications.open');
});

// ====================== ADMIN ROUTES ======================
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/users/pending-approvals', [AdminUserApprovalController::class, 'pending'])->name('users.pending');
    Route::post('/users/{user}/approve', [AdminUserApprovalController::class, 'approve'])->name('users.approve');
    Route::post('/users/{user}/reject', [AdminUserApprovalController::class, 'reject'])->name('users.reject');
    Route::get('/users/all-approvals', [AdminUserApprovalController::class, 'index'])->name('users.all');
    Route::get('/admin/users', [AdminController::class, 'users'])->name('users');
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/disputes', [AdminController::class, 'disputes'])->name('disputes');
    Route::post('/users/{user}/suspend', [AdminController::class, 'suspendUser'])->name('users.suspend');
    Route::post('/users/{user}/ban', [AdminController::class, 'banUser'])->name('users.ban');
    Route::post('/users/{user}/activate', [AdminController::class, 'activateUser'])->name('users.activate');
    Route::delete('/users/{user}', [AdminController::class, 'deleteUser'])->name('users.delete');
    Route::get('/disputes', [AdminController::class, 'disputes'])->name('disputes');
    Route::post('/disputes/{booking}/warn-client', [AdminController::class, 'warnClient'])->name('disputes.warnClient');
    Route::post('/disputes/{booking}/request-proof', [AdminController::class, 'requestPaymentProof'])->name('disputes.requestProof');
    Route::post('/disputes/{booking}/resolve', [AdminController::class, 'resolveDispute'])->name('disputes.resolve');
    Route::post('/disputes/{booking}/dismiss', [AdminController::class, 'dismissDispute'])->name('disputes.dismiss');
    Route::get('/transactions', [AdminController::class, 'transactions'])->name('transactions');
    Route::get('/job-disputes', [AdminController::class, 'jobDisputes'])
    ->name('jobDisputes');

Route::post('/job-disputes/{application}/release', [AdminController::class, 'releaseJobPayment'])
    ->name('jobDisputes.release');

Route::post('/job-disputes/{application}/refund', [AdminController::class, 'refundJobPayment'])
    ->name('jobDisputes.refund');
});

// ====================== WILDCARD ROUTES LAST ======================
Route::get('/jobs/{id}', [JobController::class, 'show'])->name('jobs.show');
Route::get('/skills/{id}', [SkillController::class, 'show'])->name('skills.show');
Route::get('/profile/{id}', [ProfileController::class, 'show'])->name('profile.show');


use App\Http\Controllers\PaystackTestController;

Route::get('/paystack-test', [PaystackTestController::class, 'index'])
    ->name('paystack.test.index');

Route::post('/paystack-test/pay', [PaystackTestController::class, 'initialize'])
    ->name('paystack.test.pay');

Route::get('/paystack-test/callback', [PaystackTestController::class, 'callback'])
    ->name('paystack.test.callback');
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SkillController;
use App\Http\Controllers\JobController;
use App\Http\Controllers\MessageController; 
use App\Http\Controllers\NotificationController; 

// ====================== PUBLIC ROUTES ======================
Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }
    return view('home');
})->name('home');

Route::get('/skills', [SkillController::class, 'index'])->name('skills.index');
Route::get('/jobs', [JobController::class, 'index'])->name('jobs.index');
Route::get('/search', fn() => 'Search results coming...')->name('search');

// ====================== AUTHENTICATION ROUTES ======================
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);

Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// ====================== PROTECTED ROUTES ======================
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profile Routes
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');

    // Job Routes — create must be before {id}
    Route::get('/jobs/create', [JobController::class, 'create'])->name('jobs.create');
    Route::post('/jobs', [JobController::class, 'store'])->name('jobs.store');
    Route::get('/jobs/{id}/edit', [JobController::class, 'edit'])->name('jobs.edit');
    Route::put('/jobs/{id}', [JobController::class, 'update'])->name('jobs.update');
    Route::delete('/jobs/{id}', [JobController::class, 'destroy'])->name('jobs.destroy');
    Route::post('/jobs/{id}/apply', [JobController::class, 'apply'])->name('jobs.apply');

    // Skill Routes — create must be before {id}
    Route::get('/skills/create', [SkillController::class, 'create'])->name('skills.create');
    Route::post('/skills', [SkillController::class, 'store'])->name('skills.store');
    Route::get('/skills/{id}/edit', [SkillController::class, 'edit'])->name('skills.edit');
    Route::put('/skills/{id}', [SkillController::class, 'update'])->name('skills.update');
    Route::delete('/skills/{id}', [SkillController::class, 'destroy'])->name('skills.destroy');
});

// ====================== MESSAGING ROUTES ======================
Route::middleware(['auth'])->group(function () {
    Route::get('/messages', [MessageController::class, 'index'])->name('messages.index');
    Route::post('/messages', [MessageController::class, 'store'])->name('messages.store');
    Route::post('/messages/{id}/read', [MessageController::class, 'markAsRead'])->name('messages.read');
    Route::post('/messages/{id}/reply', [MessageController::class, 'reply'])->name('messages.reply');
    Route::post('/messages/{id}/archive', [MessageController::class, 'archive'])->name('messages.archive');
    Route::delete('/messages/{id}', [MessageController::class, 'destroy'])->name('messages.destroy');
});

// ====================== NOTIFICATION ROUTES ======================
Route::middleware(['auth'])->group(function () {
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllAsRead'])->name('notifications.readAll');
    Route::get('/api/notifications/count', [NotificationController::class, 'getUnreadCount']);
    Route::get('/api/notifications/recent', [NotificationController::class, 'getRecentNotifications']);
});

// ====================== WILDCARD ROUTES LAST ======================
Route::get('/jobs/{id}', [JobController::class, 'show'])->name('jobs.show');
Route::get('/skills/{id}', [SkillController::class, 'show'])->name('skills.show');
Route::get('/profile/{id}', [ProfileController::class, 'show'])->name('profile.show');
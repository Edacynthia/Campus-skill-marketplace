<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'passport_photo',     // for non-university users
        'is_approved',        // important for external users
        'department',
        'matric_number',
        'staff_id',
        'otp_code',          // for OTP authentication
        'otp_expires_at',    // for OTP expiration
        'otp_verified',       // for OTP verification status
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
            'is_approved'       => 'boolean',
            'otp_expires_at'    => 'datetime',
            'otp_verified'       => 'boolean',
        ];
    }

    public function fullName(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }

    // Check if user has university email
    public function hasUniversityEmail(): bool
    {
        $universityDomains = [
            '@edouniversity.edu.ng'
        ];
        
        foreach ($universityDomains as $domain) {
            if (str_ends_with($this->email, $domain)) {
                return true;
            }
        }
        
        return false;
    }

    public function skills()
    {
        return $this->hasMany(Skill::class);
    }

    public function jobsPosted()
    {
        return $this->hasMany(Job::class, 'employer_id');
    }

    public function jobApplications()
    {
        return $this->hasMany(JobApplication::class, 'applicant_id');
    }

    public function applications()
    {
        return $this->hasMany(JobApplication::class, 'applicant_id');
    }

    public function jobs()
    {
        return $this->hasMany(\App\Models\Job::class, 'employer_id');
    }

    public function myBookings()
    {
        return $this->hasMany(Booking::class, 'client_id');
    }

    public function myServiceBookings()
    {
        return $this->hasMany(Booking::class, 'provider_id');
    }

    public function sentMessages()
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    public function receivedMessages()
    {
        return $this->hasMany(Message::class, 'receiver_id');
    }

    public function unreadMessages()
    {
        return $this->receivedMessages()->unread()->notArchived();
    }

    public function unreadCount()
    {
        return $this->unreadMessages()->count();
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    public function unreadNotifications()
    {
        return $this->notifications()->unread();
    }

    public function unreadNotificationCount()
    {
        return $this->unreadNotifications()->count();
    }

    public function markNotificationsAsRead()
    {
        $this->unreadNotifications()->update(['is_read' => true]);
    }

    public function ratingsReceived()
    {
        return $this->hasMany(Rating::class, 'reviewee_id');
    }

    public function ratingsGiven()
    {
        return $this->hasMany(Rating::class, 'reviewer_id');
    }

    public function averageRatingAsWorker()
    {
        return $this->ratingsReceived()
            ->where('type', 'employer_to_worker')
            ->avg('rating') ?? 0;
    }

    public function averageRatingAsEmployer()
    {
        return $this->ratingsReceived()
            ->where('type', 'worker_to_employer')
            ->avg('rating') ?? 0;
    }

    public function totalReviewsAsWorker()
    {
        return $this->ratingsReceived()
            ->where('type', 'employer_to_worker')
            ->count();
    }

    public function totalReviewsAsEmployer()
    {
        return $this->ratingsReceived()
            ->where('type', 'worker_to_employer')
            ->count();
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobApplication extends Model
{
    use HasFactory;

    protected $fillable = [
        'job_id',
        'applicant_id',
        'cover_letter',
        'status',
        'applied_at',
        'progress',
        'started_at',
        'completed_at',
        'confirmed_at',
        'revision_note'
    ];

    protected $casts = [
        'applied_at' => 'datetime',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
        'confirmed_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    public function job()
    {
        return $this->belongsTo(Job::class);
    }

    public function applicant()
    {
        return $this->belongsTo(User::class, 'applicant_id');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeAccepted($query)
    {
        return $query->where('status', 'accepted');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    public function ratings()
    {
        return $this->hasMany(Rating::class, 'application_id');
    }

    public function progressPercentage()
    {
        return match($this->progress) {
            'pending' => 0,
            'in_progress' => 50,
            'completed' => 75,
            'confirmed' => 100,
            default => 0
        };
    }

    public function progressLabel()
    {
        return match($this->progress) {
            'pending' => 'Pending',
            'in_progress' => 'In Progress',
            'completed' => 'Completed',
            'confirmed' => 'Confirmed',
            default => 'Unknown'
        };
    }
}

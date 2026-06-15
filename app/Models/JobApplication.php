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
        'revision_note',
        'paystack_reference',
        'escrow_status',
        'escrow_paid_at',
        'worker_completed_at',
        'auto_release_at',
        'escrow_released_at',
        'escrow_amount',
        'platform_fee_percent',
        'platform_fee',
        'worker_payout',
        'admin_hold',
        'admin_hold_reason',
        'admin_hold_at',
        'admin_hold_by',
        'delivery_note',
        'delivery_file',
        'delivery_screenshots',
        'delivery_link',
        'revision_count',
        'dispute_reason',
        'disputed_at',
        'refund_reason',
        'refunded_at',
    ];

    protected $casts = [
        'applied_at' => 'datetime',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
        'confirmed_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'escrow_paid_at' => 'datetime',
        'worker_completed_at' => 'datetime',
        'auto_release_at' => 'datetime',
        'escrow_released_at' => 'datetime',
        'admin_hold_at' => 'datetime',
        'admin_hold' => 'boolean',
        'disputed_at' => 'datetime',
        'delivery_screenshots' => 'array',
        'refunded_at' => 'datetime',
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
    // Disputed state overrides progress-based percentage
    if ($this->escrow_status === 'disputed') {
        return 75; // stays at "completed" visually
    }

    return match($this->progress) {
        'pending'     => 0,
        'in_progress' => 50,
        'completed'   => 75,
        'confirmed'   => 100,
        default       => 0
    };
}

public function progressLabel()
{
    if ($this->escrow_status === 'disputed') {
        return 'Under Dispute';
    }

    return match($this->progress) {
        'pending'     => 'Pending',
        'in_progress' => 'In Progress',
        'completed'   => 'Completed',
        'confirmed'   => 'Confirmed',
        default       => 'Unknown'
    };
}
}

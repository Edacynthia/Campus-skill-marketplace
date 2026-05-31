<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'skill_id',
        'client_id',
        'provider_id',
        'status',
        'message',
        'client_confirmed_at',
        'provider_confirmed_at',
        'completed_at',
        'payment_status',
        'payment_confirmed_at',
        'payment_confirmed_by',
        'status',
        'payment_status',
        'client_paid_at',
        'provider_payment_confirmed_at',
        'payment_dispute_reason',
        'payment_disputed_at',
        'payment_resolved_at',
        'completed_at',
        'payment_dispute_opened_by',
        'payment_dispute_opened_by_role',
        'client_payment_response',
        'client_payment_proof',
        'admin_dispute_note',
        'admin_payment_deadline_at',
        'dispute_status',
    ];

    protected $casts = [
        'client_confirmed_at' => 'datetime',
        'provider_confirmed_at' => 'datetime',
        'completed_at' => 'datetime',
        'payment_disputed_at' => 'datetime',
        'payment_resolved_at' => 'datetime',
        'admin_payment_deadline_at' => 'datetime',
    ];

    public function skill()
    {
        return $this->belongsTo(Skill::class);
    }

    public function client()
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    public function provider()
    {
        return $this->belongsTo(User::class, 'provider_id');
    }

    public function ratings()
    {
        return $this->hasMany(Rating::class, 'booking_id');
    }

    public function bothConfirmed()
    {
        return $this->client_confirmed_at && $this->provider_confirmed_at;
    }

    public function statusLabel()
    {
        return match($this->status) {
           'interested' => 'Interested',
            'confirmed' => 'Confirmed',
            'in_progress' => 'In Progress',
            'completed_waiting_payment' => 'Waiting Payment',
            'done' => 'Completed',
            'declined' => 'Declined',
            default => ucfirst(str_replace('_', ' ', $this->status)),
        };
    }

    public function paymentConfirmedBy()
    {
        return $this->belongsTo(User::class, 'payment_confirmed_by');
    }

    public function paymentDisputeOpenedBy()
    {
        return $this->belongsTo(User::class, 'payment_dispute_opened_by');
    }
}

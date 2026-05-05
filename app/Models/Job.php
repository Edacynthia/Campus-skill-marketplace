<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
    use HasFactory;

    protected $fillable = [
        'employer_id',
        'title',
        'description',
        'category',
        'type',
        'urgency',
        'salary',
        'salary_type',
        'location',
        'deadline',
        'requirements',
        'image',
        'status',
        'views_count',
        'applications_count'
    ];

    protected $casts = [
        'salary' => 'decimal:2',
        'requirements' => 'array',
        'deadline' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    public function employer()
    {
        return $this->belongsTo(User::class, 'employer_id');
    }

    public function applications()
    {
        return $this->hasMany(JobApplication::class);
    }

    public function getFormattedSalaryAttribute()
    {
        return '₦' . number_format($this->salary, 0);
    }

    public function getDeadlineDaysAttribute()
    {
        if (!$this->deadline) return null;
        
        $now = now();
        $deadline = $this->deadline;
        
        // Ensure both are Carbon instances and in the same timezone
        if (!$deadline instanceof \Carbon\Carbon) {
            $deadline = \Carbon\Carbon::parse($deadline);
        }
        
        $days = $deadline->diffInDays($now, false); // false = absolute value
        
        // Check if deadline is in the past
        if ($deadline->isPast()) {
            return 'Expired';
        } elseif ($deadline->isToday()) {
            return 'Today';
        } elseif ($deadline->isTomorrow()) {
            return 'Tomorrow';
        } elseif ($days <= 7) {
            return $days . ' days';
        } else {
            return $deadline->format('M j, Y');
        }
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeByUrgency($query, $urgency)
    {
        return $query->where('urgency', $urgency);
    }

    public function scopeBySalaryRange($query, $min, $max)
    {
        if ($min) {
            $query->where('salary', '>=', $min);
        }
        if ($max) {
            $query->where('salary', '<=', $max);
        }
        return $query;
    }

    public function scopeWithDeadline($query)
    {
        return $query->where('deadline', '>=', now());
    }
}

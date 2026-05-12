<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Skill extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'category',
        'price',
        'price_type',
        'image',
        'status',
        'rating',
        'views_count',
        'bookings_count'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'rating' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function provider()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function ratings()
    {
        return $this->hasManyThrough(
            \App\Models\Rating::class,
            \App\Models\Booking::class,
            'skill_id',
            'booking_id',
            'id',
            'id'
        );
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function getFormattedPriceAttribute()
    {
        return '₦' . number_format($this->price, 0);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    public function scopeByPriceRange($query, $min, $max)
    {
        if ($min) {
            $query->where('price', '>=', $min);
        }
        if ($max) {
            $query->where('price', '<=', $max);
        }
        return $query;
    }
}

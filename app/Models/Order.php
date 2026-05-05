<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'skill_id',
        'client_id',
        'vendor_id',
        'status',
        'total_amount',
        'requirements',
        'delivery_date',
        'completed_at'
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
        'delivery_date' => 'date',
        'completed_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    public function skill()
    {
        return $this->belongsTo(Skill::class);
    }

    public function client()
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    public function vendor()
    {
        return $this->belongsTo(User::class, 'vendor_id');
    }

    public function scopeByClient($query, $clientId)
    {
        return $query->where('client_id', $clientId);
    }

    public function scopeByVendor($query, $vendorId)
    {
        return $query->where('vendor_id', $vendorId);
    }
}

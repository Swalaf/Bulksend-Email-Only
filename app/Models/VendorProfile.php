<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VendorProfile extends Model
{
    protected $fillable = [
        'user_id',
        'business_name',
        'business_type',
        'business_description',
        'website',
        'country',
        'status',
        'approved_at',
        'rejected_at',
        'rejection_reason',
    ];

    protected $casts = [
        'approved_at' => 'datetime',
        'rejected_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function listings()
    {
        return $this->hasMany(MarketplaceListing::class);
    }
}

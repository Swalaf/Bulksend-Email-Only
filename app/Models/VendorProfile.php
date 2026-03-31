<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VendorProfile extends Model
{
    protected $fillable = [
        'user_id',
        'shop_name',
        'description',
        'logo',
        'website',
        'is_verified',
        'status',
        'commission_rate',
        'total_earnings',
        'pending_earnings',
    ];

    protected $casts = [
        'is_verified' => 'boolean',
        'commission_rate' => 'decimal:2',
        'total_earnings' => 'decimal:2',
        'pending_earnings' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function listings()
    {
        return $this->hasMany(MarketplaceListing::class);
    }

    public function purchases()
    {
        return $this->hasMany(MarketplacePurchase::class);
    }

    public function transactions()
    {
        return $this->hasMany(MarketplaceTransaction::class);
    }

    public function payouts()
    {
        return $this->hasMany(VendorPayout::class);
    }

    public function addEarnings(float $amount): void
    {
        $this->increment('total_earnings', $amount);
        $this->increment('pending_earnings', $amount);
    }

    public function processPayout(float $amount): void
    {
        $this->decrement('pending_earnings', $amount);
    }

    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    public function isVerified(): bool
    {
        return $this->is_verified;
    }
}

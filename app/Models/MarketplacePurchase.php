<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class MarketplacePurchase extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'listing_id',
        'vendor_id',
        'type',
        'amount',
        'vendor_amount',
        'commission_amount',
        'commission_rate',
        'emails_credit',
        'emails_used',
        'is_subscription',
        'subscription_start',
        'subscription_end',
        'subscription_active',
        'stripe_subscription_id',
        'stripe_payment_id',
        'payment_status',
        'is_active',
        'purchased_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'vendor_amount' => 'decimal:2',
        'commission_amount' => 'decimal:2',
        'commission_rate' => 'decimal:2',
        'emails_credit' => 'integer',
        'emails_used' => 'integer',
        'is_subscription' => 'boolean',
        'subscription_start' => 'date',
        'subscription_end' => 'date',
        'subscription_active' => 'boolean',
        'is_active' => 'boolean',
        'purchased_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function listing()
    {
        return $this->belongsTo(MarketplaceListing::class);
    }

    public function vendor()
    {
        return $this->belongsTo(VendorProfile::class);
    }

    public function smtpAccount()
    {
        return $this->hasOne(MarketplaceSmtpAccount::class);
    }

    public function transactions()
    {
        return $this->hasMany(MarketplaceTransaction::class);
    }

    public function getRemainingCredits(): int
    {
        return $this->emails_credit - $this->emails_used;
    }

    public function hasCreditsLeft(): bool
    {
        if (!$this->is_subscription) {
            return $this->getRemainingCredits() > 0;
        }
        return $this->subscription_active;
    }

    public function useCredit(): bool
    {
        if (!$this->hasCreditsLeft()) {
            return false;
        }

        if (!$this->is_subscription) {
            $this->increment('emails_used');
            return true;
        }

        return true;
    }

    public function activateSubscription(): void
    {
        $this->update([
            'subscription_active' => true,
            'subscription_start' => now(),
            'subscription_end' => now()->addMonth(),
        ]);
    }

    public function deactivateSubscription(): void
    {
        $this->update(['subscription_active' => false]);
    }

    public function isExpired(): bool
    {
        if ($this->subscription_end) {
            return $this->subscription_end->isPast();
        }
        return $this->getRemainingCredits() <= 0;
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeWithCredits($query)
    {
        return $query->active()->where(function ($q) {
            $q->where('is_subscription', true)
                ->where('subscription_active', true)
                ->orWhereRaw('emails_credit > emails_used');
        });
    }
}

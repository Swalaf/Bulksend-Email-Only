<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    protected $fillable = [
        'user_id',
        'plan_id',
        'status',
        'stripe_subscription_id',
        'stripe_customer_id',
        'trial_ends_at',
        'starts_at',
        'ends_at',
        'cancels_at',
    ];

    protected $casts = [
        'trial_ends_at' => 'datetime',
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
        'cancels_at' => 'datetime',
    ];

    const STATUS_ACTIVE = 'active';
    const STATUS_PAUSED = 'paused';
    const STATUS_CANCELLED = 'cancelled';
    const STATUS_PAST_DUE = 'past_due';

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    public function isActive(): bool
    {
        return $this->status === self::STATUS_ACTIVE;
    }

    public function isOnTrial(): bool
    {
        return $this->trial_ends_at && $this->trial_ends_at->isFuture();
    }

    public function isCancelled(): bool
    {
        return $this->status === self::STATUS_CANCELLED;
    }

    public function isPastDue(): bool
    {
        return $this->status === self::STATUS_PAST_DUE;
    }

    public function onGracePeriod(): bool
    {
        return $this->cancels_at && $this->cancels_at->isFuture();
    }

    public function cancel(bool $immediately = false): void
    {
        if ($immediately) {
            $this->update([
                'status' => self::STATUS_CANCELLED,
                'ends_at' => now(),
            ]);
        } else {
            $this->update([
                'cancels_at' => $this->ends_at,
            ]);
        }
    }

    public function resume(): void
    {
        $this->update([
            'status' => self::STATUS_ACTIVE,
            'cancels_at' => null,
        ]);
    }

    public function changePlan(Plan $newPlan): void
    {
        $this->update([
            'plan_id' => $newPlan->id,
            'ends_at' => now()->addDays($newPlan->billing_period),
        ]);
    }

    public function scopeActive($query)
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }

    public function scopeOnTrial($query)
    {
        return $query->whereNotNull('trial_ends_at')
            ->where('trial_ends_at', '>', now());
    }
}

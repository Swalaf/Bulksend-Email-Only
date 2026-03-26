<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'price',
        'billing_period',
        'max_emails',
        'max_subscribers',
        'max_smtp_accounts',
        'max_campaigns',
        'has_analytics',
        'has_ai',
        'has_api',
        'has_white_label',
        'has_priority_support',
        'sort_order',
        'is_active',
        'is_featured',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'has_analytics' => 'boolean',
        'has_ai' => 'boolean',
        'has_api' => 'boolean',
        'has_white_label' => 'boolean',
        'has_priority_support' => 'boolean',
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
    ];

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true)->orderBy('sort_order');
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order');
    }

    public function isFree(): bool
    {
        return $this->price == 0;
    }

    public function hasFeature(string $feature): bool
    {
        return match($feature) {
            'analytics' => $this->has_analytics,
            'ai' => $this->has_ai,
            'api' => $this->has_api,
            'white_label' => $this->has_white_label,
            'priority_support' => $this->has_priority_support,
            default => false,
        };
    }

    public function canSendEmails(int $count): bool
    {
        return $this->max_emails === 0 || $this->max_emails >= $count;
    }

    public function canAddSubscribers(int $count): bool
    {
        return $this->max_subscribers === 0 || $this->max_subscribers >= $count;
    }
}

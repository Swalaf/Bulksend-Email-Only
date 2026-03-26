<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class MarketplaceListing extends Model
{
    use HasFactory;

    protected $fillable = [
        'vendor_id',
        'name',
        'description',
        'host',
        'port',
        'encryption',
        'from_address',
        'from_name',
        'pricing_type',
        'price_per_email',
        'monthly_subscription',
        'free_emails',
        'included_emails',
        'daily_limit',
        'monthly_limit',
        'features',
        'thumbnail',
        'status',
        'rejection_reason',
        'view_count',
        'purchase_count',
    ];

    protected $casts = [
        'price_per_email' => 'decimal:4',
        'monthly_subscription' => 'decimal:2',
        'free_emails' => 'integer',
        'included_emails' => 'integer',
        'daily_limit' => 'integer',
        'monthly_limit' => 'integer',
        'features' => 'array',
        'view_count' => 'integer',
        'purchase_count' => 'integer',
    ];

    public function vendor()
    {
        return $this->belongsTo(VendorProfile::class);
    }

    public function purchases()
    {
        return $this->hasMany(MarketplacePurchase::class);
    }

    public function activePurchases()
    {
        return $this->purchases()->where('is_active', true);
    }

    public function favorites()
    {
        return $this->hasMany(MarketplaceFavorite::class);
    }

    public function incrementViewCount(): void
    {
        $this->increment('view_count');
    }

    public function incrementPurchaseCount(): void
    {
        $this->increment('purchase_count');
    }

    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    public function getFormattedPrice(): string
    {
        if ($this->pricing_type === 'subscription') {
            return '$' . number_format($this->monthly_subscription, 2) . '/month';
        }
        
        if ($this->free_emails > 0) {
            return 'Free (' . $this->free_emails . ' emails)';
        }
        
        return '$' . number_format($this->price_per_email, 4) . '/email';
    }

    public function getPricePerEmail(): float
    {
        if ($this->pricing_type === 'subscription' && $this->included_emails) {
            return $this->monthly_subscription / $this->included_emails;
        }
        
        return (float) $this->price_per_email;
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeFeatured($query)
    {
        return $query->active()->orderBy('purchase_count', 'desc')->limit(6);
    }
}

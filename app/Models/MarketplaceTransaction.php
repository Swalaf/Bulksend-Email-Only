<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MarketplaceTransaction extends Model
{
    protected $fillable = [
        'vendor_id',
        'purchase_id',
        'type',
        'amount',
        'commission_amount',
        'net_amount',
        'stripe_transaction_id',
        'status',
        'description',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'commission_amount' => 'decimal:2',
        'net_amount' => 'decimal:2',
    ];

    public function vendor()
    {
        return $this->belongsTo(VendorProfile::class);
    }

    public function purchase()
    {
        return $this->belongsTo(MarketplacePurchase::class);
    }
}
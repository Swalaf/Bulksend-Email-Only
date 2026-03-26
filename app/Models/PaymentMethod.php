<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    protected $fillable = [
        'user_id',
        'type',
        'stripe_payment_method_id',
        'last4',
        'brand',
        'exp_month',
        'exp_year',
        'is_default',
        'is_valid',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function isExpired(): bool
    {
        if (!$this->exp_month || !$this->exp_year) return false;
        
        $now = now();
        return $this->exp_year < $now->year || 
               ($this->exp_year == $now->year && $this->exp_month < $now->month);
    }

    public function getDisplayName(): string
    {
        return ucfirst($this->type) . ' •••• ' . ($this->last4 ?? '****');
    }
}

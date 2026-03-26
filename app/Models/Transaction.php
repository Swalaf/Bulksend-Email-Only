<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'user_id',
        'invoice_id',
        'stripe_payment_id',
        'type',
        'amount',
        'fee',
        'currency',
        'status',
        'payment_method',
        'description',
        'metadata',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'fee' => 'decimal:2',
        'metadata' => 'array',
    ];

    const TYPE_PAYMENT = 'payment';
    const TYPE_REFUND = 'refund';
    const TYPE_SUBSCRIPTION = 'subscription';
    const TYPE_CREDIT = 'credit';

    const STATUS_PENDING = 'pending';
    const STATUS_COMPLETED = 'completed';
    const STATUS_FAILED = 'failed';
    const STATUS_REFUNDED = 'refunded';

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    public function isSuccessful(): bool
    {
        return $this->status === self::STATUS_COMPLETED;
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $fillable = [
        'user_id',
        'subscription_id',
        'stripe_invoice_id',
        'number',
        'subtotal',
        'tax',
        'total',
        'currency',
        'status',
        'due_date',
        'paid_at',
        'invoice_date',
        'notes',
        'line_items',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'tax' => 'decimal:2',
        'total' => 'decimal:2',
        'due_date' => 'datetime',
        'paid_at' => 'datetime',
        'invoice_date' => 'datetime',
        'line_items' => 'array',
    ];

    const STATUS_DRAFT = 'draft';
    const STATUS_OPEN = 'open';
    const STATUS_PAID = 'paid';
    const STATUS_VOID = 'void';
    const STATUS_FAILED = 'failed';

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function subscription()
    {
        return $this->belongsTo(Subscription::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function isPaid(): bool
    {
        return $this->status === self::STATUS_PAID;
    }

    public function isOpen(): bool
    {
        return $this->status === self::STATUS_OPEN;
    }

    public function markAsPaid(): void
    {
        $this->update([
            'status' => self::STATUS_PAID,
            'paid_at' => now(),
        ]);
    }

    public function getFormattedTotal(): string
    {
        return number_format($this->total, 2) . ' ' . strtoupper($this->currency);
    }
}
